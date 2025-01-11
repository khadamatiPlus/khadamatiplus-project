<?php

namespace App\Domains\Merchant\Http\Controllers\API;

use App\Domains\Auth\Http\Transformers\UserTransformer;
use App\Domains\Auth\Models\User;
use App\Domains\Merchant\Http\Requests\API\ListMerchantBranchRequest;
use App\Domains\Merchant\Http\Requests\API\UpdateMerchantRequest;
use App\Domains\Merchant\Http\Transformers\MerchantTransformer;
use App\Domains\Merchant\Models\Merchant;
use App\Domains\Merchant\Models\MerchantAvailability;
use App\Domains\Merchant\Services\MerchantService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MerchantApiController extends APIBaseController
{

    private MerchantService $merchantService;

    /**
     * @param MerchantService $merchantService
     */
    public function __construct(MerchantService $merchantService)
    {
        $this->merchantService = $merchantService;
    }


    public function update(UpdateMerchantRequest $request): \Illuminate\Http\JsonResponse
    {
       ;
        return $this->successResponse(
            (new MerchantTransformer)->transform($this->merchantService->update($request->user()->merchant_id,$request->validated()))
        );
    }


    public function profile()
    {
        $merchant = auth()->user()->merchant->where('profile_id',auth()->id())->firstOrFail();
        return $this->successResponse(
            (new MerchantTransformer)->transform($merchant)
        );
    }
    public function updatePassword(Request $request)
    {
        // Validate the request data
        $request->validate([
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6',
        ]);

        $user = $request->user();

        // Check if the current password matches the one in the database
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'The current password is incorrect.'], 401);
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Revoke all previous tokens
        $user->tokens()->delete();

        // Create a new token for the user
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'message' => 'Password updated successfully.',
            'token' => $token,
            'user' => (new UserTransformer)->transform($user),
        ], 200);
    }
    public function deleteMerchantAccount(Request $request)
    {
        // Authenticate the user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        // Delete the user account
        $user->delete();

        return response()->json(['message' => 'User account deleted successfully.'], 200);
    }

    public function requestResetOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
        ]);
        $mobile_number = preg_replace('/^0{1,2}/', '', $request->mobile_number);
        $user = User::where('mobile_number', $mobile_number)->first();
        if (!$user) {
            return response()->json(['error' => 'User with this phone number does not exist.'], 404);
        }
//        $otp = Str::random(6);
        $otp = '0000';
        DB::table('password_resets')->updateOrInsert(
            ['phone_number' => $mobile_number],
            ['otp' => $otp, 'created_at' => now()]
        );
        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent successfully.',
            'timer'=>60

        ], 200);
    }
    public function requestMobileNumberOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
        ]);
        $mobile_number = preg_replace('/^0{1,2}/', '', $request->mobile_number);
//        $user = User::where('mobile_number', $mobile_number)->first();
//        if (!$user) {
//            return response()->json(['error' => 'User with this phone number does not exist.'], 404);
//        }
//        $otp = Str::random(6);
        $otp = '0000';
        DB::table('password_resets')->updateOrInsert(
            ['phone_number' => $mobile_number],
            ['otp' => $otp, 'created_at' => now()]
        );
        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent successfully.',
            'timer'=>60

        ], 200);
    }


    public function confirmOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'otp' => 'required|string',
        ]);

        $mobile_number = preg_replace('/^0{1,2}/', '', $request->mobile_number);

        $otp = $request->otp;

        // Check if OTP is valid
        $resetRecord = DB::table('password_resets')
            ->where('phone_number', $mobile_number)
            ->where('otp', $otp)
            ->first();

        if (!$resetRecord) {
            return response()->json(['error' => 'Invalid OTP.'], 400);
        }

        // OTP is valid
        return response()->json(['message' => 'OTP confirmed successfully.'], 200);
    }




    public function resetPassword(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        $mobile_number = preg_replace('/^0{1,2}/', '', $request->mobile_number);

        $new_password = $request->new_password;

        // Find the user
        $user = User::where('mobile_number', $mobile_number)->where('merchant_id','!=',null)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Update the user's password
        $user->password = Hash::make($new_password);
        $user->save();

        // Delete OTP record (if you want to clear OTPs after password reset)
        DB::table('password_resets')->where('phone_number', $mobile_number)->delete();

        return response()->json(['message' => 'Password reset successfully.'], 200);
    }


    public function getAllMerchants(Request $request)
    {


        // Base query to get services for the authenticated merchant
        $query = Merchant::query();

        // Apply optional search filters if provided
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc'); // Replace 'created_at' with the field you want to sort by
        $merchants = $query->paginate(10); // Adjust the items per page as needed

        // Transform the paginated data
        $transformedMerchants = $merchants->getCollection()->map(function ($merchants) {
            return (new MerchantTransformer())->transform($merchants);
        });

        // Return the response using successResponse
        return $this->successResponse([
            'data' => $transformedMerchants,
            'pagination' => [
                'total' => $merchants->total(),
                'count' => $merchants->count(),
                'per_page' => $merchants->perPage(),
                'current_page' => $merchants->currentPage(),
                'total_pages' => $merchants->lastPage(),
            ],
        ]);
    }

    public function getMerchantById($id)
    {
        // Fetch the merchant by ID
        $merchant = Merchant::find($id);

        // Check if the merchant exists
        if (!$merchant) {
            return $this->errorResponse('Merchant not found', 404);
        }

        // Transform the merchant data
        $transformedMerchant = (new MerchantTransformer())->transform($merchant);

        // Return the response
        return $this->successResponse(['data' => $transformedMerchant]);
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|array',
            'days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'times' => 'required|array',
            'times.*' => 'required',
        ]);

        $merchantId = Auth::user()->merchant_id; // Assuming merchants are authenticated.
        MerchantAvailability::where('merchant_id', $merchantId)->delete();

        // Iterate over days and times to update or create records.
        foreach ($validated['days'] as $day) {
            foreach ($validated['times'] as $time) {
                MerchantAvailability::updateOrCreate(
                    [
                        'merchant_id' => $merchantId,
                        'day' => $day,
                        'time' => $time,
                    ],
                    [] // No additional fields to update in this case.
                );
            }
        }

        return response()->json(['message' => 'Availability updated successfully.'], 200);
    }

    public function hasAvailability()
    {
        $merchant = Merchant::findOrFail( Auth::user()->merchant_id);

        $hasAvailability = $merchant->availability()->exists();

        return response()->json([
            'merchant_id' =>  Auth::user()->merchant_id,
            'has_availability' => $hasAvailability,
        ]);
    }

    public function getAvailability(Request $request)
    {
        $merchant_id = auth()->user()->merchant_id;

        $merchant=Merchant::query()->where('id',$merchant_id)->first();

        if (!$merchant) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Merchant not found',
            ], 401);
        }

        // Extract availability data
        $availability = [
            'days' => $merchant->availability->pluck('day')->unique()->values(),
            'times' => $merchant->availability->pluck('time')->unique()->values(),
        ];

        return response()->json([
            'availability' => $availability,
        ]);
    }
}
