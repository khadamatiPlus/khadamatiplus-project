<?php

namespace App\Domains\Auth\Http\Controllers\API;
use App\Domains\Auth\Events\User\UserLoggedIn;
use App\Domains\Auth\Http\Requests\API\RegisterCustomerRequest;
use App\Domains\Auth\Http\Transformers\UserTransformer;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\UserService;
use App\Domains\Customer\Http\Transformers\CustomerTransformer;
use App\Domains\Customer\Models\Customer;
use App\Domains\FirebaseIntegration\FirebaseIntegration;
use App\Domains\Merchant\Http\Transformers\MerchantTransformer;
use App\Domains\Merchant\Models\Merchant;
use App\Domains\Merchant\Services\MerchantService;
use App\Domains\Customer\Services\CustomerService;
use App\Http\Controllers\APIBaseController;
use App\Domains\Auth\Http\Requests\API\RegisterMerchantRequest;
use Illuminate\Http\Request;
class RegisterApiController extends APIBaseController
{

    /**
     * @var UserService $userService
     */
    protected $userService;

    /**
     * @var MerchantService $merchantService
     */
    protected $merchantService;

    protected $customerService;


    /**
     * @var FirebaseIntegration $firebaseIntegration
     */
    protected $firebaseIntegration;

    /**
     * @param UserService $userService
     * @param MerchantService $merchantService
     * @param FirebaseIntegration $firebaseIntegration
     */
    public function __construct(UserService $userService, MerchantService $merchantService,CustomerService $customerService,
//                                FirebaseIntegration $firebaseIntegration
    )
    {
        $this->userService = $userService;
        $this->merchantService = $merchantService;
        $this->customerService = $customerService;
//        $this->firebaseIntegration = $firebaseIntegration;
    }

    /**
     * @OA\Post(
     * path="/api/auth/registerMerchant",
     * summary="Register Merchant",
     * description="",
     * operationId="registerMerchant",
     * tags={"Auth"},
     *     @OA\Parameter(
     *         name="Accept-Language",
     *         in="header",
     *         description="Set language parameter by RFC2616 <https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4>",
     *         @OA\Schema(
     *             type="string",
     *             default="en"
     *         )
     *     ),
     *       @OA\Parameter(
     *         name="App-Version-Name",
     *         in="header",
     *         description="Set language parameter by RFC2616 <https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4>",
     *         @OA\Schema(
     *             type="string",
     *             default="hayat_delivery_merchant_app"
     *         )
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *    description="pass authetication data in addition to merchant details",
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               required={"mobile_number", "name", "business_type_id", "city_id", "firebase_auth_token"},
     *              @OA\Property(property="mobile_number", type="string"),
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="latitude", type="string"),
     *              @OA\Property(property="longitude", type="string"),
     *              @OA\Property(property="business_type_id", type="integer"),
     *              @OA\Property(property="city_id", type="integer"),
     *              @OA\Property(property="firebase_auth_token", type="string"),
     *              @OA\Property(property="profile_pic", type="file"),
     *           ),
     *       )
     * ),
     * @OA\Response(
     *    response=400,
     *    description="input validation errors"
     * ),
     * @OA\Response(
     *    response=500,
     *    description="internal server error"
     * ),
     *     @OA\Response(
     *    response=200,
     *    description="success"
     * )
     * )
     */
    public function registerMerchant(RegisterMerchantRequest $request): \Illuminate\Http\JsonResponse
    {
        try{
            $country_code = env('DEFAULT_COUNTRY_CODE','962');
            $fullNumber = $country_code.$request->input('mobile_number');
            $password = $request->input('password');
            $fcm_token = $request->input('fcm_token');


//            if($verifyResult = $this->firebaseIntegration->verifyToken($request->input('firebase_auth_token'),$fullNumber)){
            if(app()->environment(['local', 'testing'])){
//                    $verifyResult->verified = true;
                $verifyResult = true;
            }
            if($verifyResult){
                $this->merchantService->register($request->validated());

                $login = $this->userService->authenticateUserMobile($country_code,$request->input('mobile_number'),$request->header('App-Version-Name'),$password, $fcm_token);
                if($login && $login->active === true){
                    return $this->successResponse($login);
                }
            }
//            }
            return $this->successResponse([
                'completed' => false,
                'access_token' => '',
                'active' => false
            ]);
//        }
        }

        catch (\Exception $exception)
        {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }

    public function registerCustomer(RegisterCustomerRequest $request): \Illuminate\Http\JsonResponse
    {
        try{
            $country_code = env('DEFAULT_COUNTRY_CODE','962');
            $fullNumber = $country_code.$request->input('mobile_number');
            $password = $request->input('password');
            $fcm_token = $request->input('fcm_token');
//            if($verifyResult = $this->firebaseIntegration->verifyToken($request->input('firebase_auth_token'),$fullNumber)){

            if(app()->environment(['local', 'testing'])){
//                    $verifyResult->verified = true;
                $verifyResult = true;
            }

            if($verifyResult){
                $this->customerService->register($request->validated());
                $login = $this->userService->authenticateUserMobile($country_code,$request->input('mobile_number'),$request->header('App-Version-Name'),$password,$fcm_token);
                return $this->successResponse($login);
            }
//            }
            return $this->successResponse([
                'completed' => false,
                'access_token' => '',
                'active' => false
            ]);
        }
        catch (\Exception $exception)
        {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }

    public function registerUsingEmail(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        // Get the App-Version-Name from the header
        $appVersionName = $request->header('App-Version-Name');


        // Check if the email already exists for merchants or customers
        if ($appVersionName === 'khadamati_merchant_app') {
            $existingUser = User::where('email', $request->email)
                ->whereNotNull('merchant_id')
                ->first();
        } elseif ($appVersionName === 'khadamati_customer_app') {
            $existingUser = User::where('email', $request->email)
                ->whereNotNull('customer_id')
                ->first();
        } else {
            // Handle invalid or missing App-Version-Name
            return response()->json(['message' => 'Invalid App-Version-Name'], 400);
        }

        // If the email already exists for the respective role, return an error
        if ($existingUser) {
            return response()->json(['message' => 'Email already exists for this role'], 400);
        }

        // Combine name and email into a single field (optional)
        $nameWithEmail = $request->name . ' (' . $request->email . ')';

        // Create the user
        $user = User::create([
            'name' => $nameWithEmail,
            'email' => $request->email,
            'password' => $request->email,
        ]);

        // Check the App-Version-Name and create merchant or customer
        if ($appVersionName === 'khadamati_merchant_app') {
            // Create a merchant
            $merchant = Merchant::create([
                'profile_id' => $user->id,
                'created_by_id' => $user->id,
                'name'=>$nameWithEmail,
                'updated_by_id' => $user->id,
            ]);

            // Save the merchant_id in the user record
            $user->merchant_id = $merchant->id;
            $user->save();

            // Transform merchant data for response
            $merchantData = (new MerchantTransformer())->transform($merchant);
        } elseif ($appVersionName === 'khadamati_customer_app') {
            // Create a customer
            $customer = Customer::create([
                'name'=>$nameWithEmail,
                'profile_id' => $user->id,
                'created_by_id' => $user->id,
                'updated_by_id' => $user->id,
            ]);

            // Save the customer_id in the user record
            $user->customer_id = $customer->id;
            $user->save();

            // Transform customer data for response
            $customerData = (new CustomerTransformer())->transform($customer);
        } else {
            // Handle invalid or missing App-Version-Name
            return response()->json(['message' => 'Invalid App-Version-Name'], 400);
        }

        // Automatically log in the user after registration
        event(new UserLoggedIn($user));
        $user->tokens()->delete(); // Delete any existing tokens

        // Create a new access token for the user
        $accessToken = $user->createToken('api')->plainTextToken;

        // Prepare the response
        $resp = new \stdClass();
        $resp->access_token = $accessToken;
        $resp->user = (new UserTransformer)->transform($user);
        $resp->active = true;
        $resp->completed = true;

        // Add merchant or customer data to the response based on App-Version-Name
        if ($appVersionName === 'khadamati_merchant_app') {
            $resp->merchant = $merchantData;
        } elseif ($appVersionName === 'khadamati_customer_app') {
            $resp->customer = $customerData;
        }

        return response()->json($resp, 201);
    }
}
