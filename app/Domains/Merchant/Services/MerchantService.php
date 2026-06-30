<?php

namespace App\Domains\Merchant\Services;
use App\Domains\Auth\Events\User\UserCreated;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\UserService;
use App\Domains\Merchant\Models\Merchant;
use App\Domains\Wallet\Services\WalletService;
use App\Exceptions\GeneralException;
use App\Services\BaseService;
use App\Services\StorageManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;



class MerchantService extends BaseService
{

    /**
     * @var string $entityName
     */
    protected $entityName = 'Merchant';

    /**
     * @var UserService $userService
     */
    protected $userService;

    /**
     * @var StorageManagerService $storageManagerService
     */
    protected $storageManagerService;

    protected WalletService $walletService;

    /**
     * @param Merchant $merchant
     * @param UserService $userService
     * @param StorageManagerService $storageManagerService
     */
    public function __construct(Merchant $merchant, UserService $userService, StorageManagerService $storageManagerService, WalletService $walletService)
    {
        $this->model = $merchant;
        $this->userService = $userService;
        $this->storageManagerService = $storageManagerService;
        $this->walletService = $walletService;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws GeneralException
     * @throws \Throwable
     */
    public function register(array $data = [])
    {
        DB::beginTransaction();

        try {
            // Register the user (merchant admin)
            $merchantAdmin = $this->userService->registerUser([
                'type' => User::TYPE_USER,
                'name' => $data['name'] ?? null,
                'password' => $data['password'],
                'email' => $data['email'] ?? null,
                'mobile_number' => $data['mobile_number'],
                'merchant_id' => $data['merchant_id'] ?? null,
                'fcm_token' => $data['fcm_token'] ?? null,
                'timezone' => $data['timezone'] ?? env('DEFAULT_TIMEZONE', 'Asia/Amman'),
            ]);

            // Assign a role to the merchantAdmin
            $merchantAdmin->syncRoles([2]);

            // Log in the user if they are not authenticated
            if (!auth()->check()) {
                auth()->loginUsingId($merchantAdmin->id);
            }

            // Handle the id_image as a URL
            $idImage = null;
            if (isset($data['id_image'])) {
                $idImage = $data['id_image'];
            }

            // Store the merchant detailssss
            $data['profile_id'] = $merchantAdmin->id;
            $merchant = $this->store($data);
            $this->walletService->ensureWallet(Merchant::class, $merchant->id, 'default');

            // Save the merchant id_image
            $merchant->id_image = $idImage;
            $merchant->save();

            // Sync app services if provided
            if (isset($data['app_services']) && is_array($data['app_services'])) {
                $merchant->appServices()->sync($data['app_services']);
            }

            // Associate the merchant id with the merchantAdmin
            $merchantAdmin->merchant_id = $merchant->id;
            $merchantAdmin->save();

            // Refresh the merchantAdmin model
            $merchantAdmin = $merchantAdmin->refresh();

        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            throw new GeneralException(__('There was a problem registering the merchant. Please try again.'));
        }

        // Trigger an event after the user is created
        event(new UserCreated($merchantAdmin));

        DB::commit();
        return $merchant;
    }


    /**
     * @param array $data
     * @return mixed
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store(array $data = [])
    {
        $data['is_verified']='1';
        if(!empty($data['profile_pic']) && request()->hasFile('profile_pic')){
            try {
                $this->upload($data,'profile_pic');
            } catch (\Exception $e) {
                throw $e;
            }
        }
        if(!empty($data['id_image']) && request()->hasFile('id_image')){

            try {
                $this->upload($data,'id_image','uploads');

            } catch (\Exception $e) {
                throw $e;
            }
        }
        if(!empty($data['id_image']) && request()->hasFile('id_image')){
            $data['id_image']='uploads/'.$data['id_image'];
        }

        $merchant = parent::store($data);


        return $merchant;
    }

    /**
     * @param $entity
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws GeneralException
     * @throws \Throwable
     */
    public function update($entity, array $data = [])
    {
        $syncAppServices = array_key_exists('app_services', $data);
        $appServices = $data['app_services'] ?? null;
        $data = array_filter($data);
        if ($syncAppServices) {
            $data['app_services'] = is_array($appServices) ? $appServices : [];
        }
        $merchant = $this->getById($entity);
        $user = User::query()->where('id', $merchant->profile_id)->firstOrFail();

        // Update user fields
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        if (isset($data['mobile_number'])) {
            $user->mobile_number = $data['mobile_number'];
        }
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        // Handle profile picture
        if (!empty($data['profile_pic']) && request()->hasFile('profile_pic')) {
            try {
                $this->storageManagerService->deletePublicFile($merchant->profile_pic, 'merchants/profile_pics');
                $this->upload($data, 'profile_pic', 'merchants/profile_pic', $merchant->logo);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        // Handle ID image
        if (!empty($data['id_image']) && request()->hasFile('id_image')) {
            try {
                $this->storageManagerService->deletePublicFile($merchant->id_image, 'uploads');
                $this->upload($data, 'id_image', 'uploads', $merchant->id_image);
            } catch (\Exception $e) {
                throw $e;
            }
            $data['id_image'] = 'uploads/'.$data['id_image'];
        }

        // Handle verification status
        $data['is_verified'] = isset($data['is_verified']) ? 1 : $merchant->is_verified;
        $data['profile_id'] = $merchant->profile_id;

        $updatedMerchant = parent::update($entity, $data);

        // Sync app services if provided
        if (isset($data['app_services']) && is_array($data['app_services'])) {
            $updatedMerchant->appServices()->sync($data['app_services']);
        }

        return $updatedMerchant->load(['appServices.category', 'appServices.subCategory']);
    }

    /**
     * @param array $data
     * @param $fileColumn
     * @param string $directory
     * @return array
     * @throws \Exception
     */
    private function upload(array &$data, $fileColumn, string $directory = 'merchants/profile_pic',$old_file_name = null): array
    {
        try{
            $data[$fileColumn] = $this->storageManagerService->uploadPublicFile($data[$fileColumn],$directory,$old_file_name);
            return $data;
        }
        catch (\Exception $exception){
            throw $exception;
        }
    }

    public function updateStatus(Request $request)
    {
        $merchant = Merchant::query()->findOrFail($request->input('merchantId'));
        if($merchant->is_verified){
            $merchant->is_verified = 0;
        }
        else{
            $merchant->is_verified = 1;
        }
        $merchant->update();

    }


    public function getMerchants($service_id = null,$is_featured = null, $category_id = null, $status = null, $free_delivery = null, $new = null, $is_near = null,$is_top_rated = null)
    {
        $query = $this->model::query()->where('is_verified','1')
            ->when($service_id, fn ($query, $service_id) => $query->where('service_id', $service_id))
            ->when($is_featured, fn ($query, $is_featured) => $query->where('is_featured', $is_featured))
            ->when($category_id, fn ($query, $category_id) => $query->where('category_id', $category_id))
            ->when($new, fn($query,$new) => $query->newOnPlatform())
            ->when($free_delivery, function ($query) use($free_delivery){
                $query->whereHas('merchantBranches', function ($qq) use ($free_delivery){
                    $qq->freeDelivery();
                });
            })
            ->when($status, function ($query) use($status){
                $query->whereHas('merchantBranches', function ($qq) use ($status){
                    $qq->byStatus($status);
                });
            })
            ->whereHas('merchantBranches', function ($qq) {
                if (\request()->header('latitude') != 0 &&\request()->header('longitude')!=0) {
                    $qq->isWithinMaxDistance(['latitude' => \request()->header('latitude'), 'longitude' => \request()->header('longitude')]);

                }
            });


//            ->whereHas('merchantBranches',function ($qq){
//                $qq->isWithinMaxDistance(['latitude' => \request()->header('latitude') ?? '0','longitude' => \request()->header('longitude') ?? '0']);
//            });

        if(!empty(request()->input('is_top_rated')) && request()->input('is_top_rated') == 1){
            $query->withAvg('reviews','rating_stars')->having('reviews_avg_rating_stars','>=',env('TOP_RATE_VALUE',4.5));
        }
        return $query->orderByDesc('id');
    }
    public function getMerchantsHaveCoupons($service_id = null,$is_featured = null, $category_id = null, $status = null, $free_delivery = null, $new = null, $is_near = null,$is_top_rated = null)
    {
        $query = $this->model::query()->where('is_verified','1')
            ->when($service_id, fn ($query, $service_id) => $query->where('service_id', $service_id))
            ->when($is_featured, fn ($query, $is_featured) => $query->where('is_featured', $is_featured))
            ->when($category_id, fn ($query, $category_id) => $query->where('category_id', $category_id))
            ->when($new, fn($query,$new) => $query->newOnPlatform())
            ->when($free_delivery, function ($query) use($free_delivery){
                $query->whereHas('merchantBranches', function ($qq) use ($free_delivery){
                    $qq->freeDelivery();
                });
            })
            ->when($status, function ($query) use($status){
                $query->whereHas('merchantBranches', function ($qq) use ($status){
                    $qq->byStatus($status);
                });
            })
            ->whereHas('merchantBranches', function ($query) {
                $query->whereHas('items', function ($query) {
                    $query->where('is_offer', '2')->where('status','!=','0')->whereHas('itemVariations', function ($query) {
                        $query->where('in_stock', '!=', 0);
                    });
                });
            })
            ->whereHas('merchantBranches',function ($qq){
                $qq->isWithinMaxDistance(['latitude' => \request()->header('latitude') ?? '0','longitude' => \request()->header('longitude') ?? '0']);
            });


        if(!empty(request()->input('is_top_rated')) && request()->input('is_top_rated') == 1){
            $query->withAvg('reviews','rating_stars')->having('reviews_avg_rating_stars','>=',env('TOP_RATE_VALUE',4.5));
        }
        return $query->orderByDesc('id');
    }
}
