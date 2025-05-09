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
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MerchantApiController extends APIBaseController
{

    private MerchantService $merchantService;

    /**
     * @param MerchantService $merchantService
     */
    public function __construct(MerchantService $merchantService,SmsService $smsService)
    {
        $this->merchantService = $merchantService;
        $this->smsService = $smsService;
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
        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        $message = "Your OTP code is $otp. It expires in 5 minutes.";
        Log::info($mobile_number);
        $this->smsService->sendSms($mobile_number, $message);
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
        $user = User::where('mobile_number', $mobile_number)
            ->where('otp_code', $otp)
            ->where('otp_expires_at', '>', now())
            ->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid OTP.'], 400);
        }

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
        // Validate the incoming request
        $validated = $request->validate([
            'days' => 'required|array',
            'days.*' => 'required',
            'times' => 'required|array',
            'times.*' => 'required',
        ]);

        // Conversion for Arabic numerals to English numerals (for times)
        $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $englishNumerals = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        // Conversion for Arabic day names to English day names
        $arabicDays = [
            'الإثنين' => 'Monday',
            'الثلاثاء' => 'Tuesday',
            'الأربعاء' => 'Wednesday',
            'الخميس' => 'Thursday',
            'الجمعة' => 'Friday',
            'السبت' => 'Saturday',
            'الأحد' => 'Sunday'
        ];

        // Normalize days (convert Arabic day names to English)
        $normalizedDays = array_map(function ($day) use ($arabicDays) {
            return $arabicDays[$day] ?? $day; // If not found in $arabicDays, keep original (assumes it's already English)
        }, $validated['days']);

        // Normalize times (convert Arabic numerals to English numerals)
        $normalizedTimes = array_map(function ($time) use ($arabicNumerals, $englishNumerals) {
            return str_replace($arabicNumerals, $englishNumerals, $time);
        }, $validated['times']);

        $merchantId = Auth::user()->merchant_id; // Assuming merchants are authenticated.
        MerchantAvailability::where('merchant_id', $merchantId)->delete();

        // Iterate over normalized days and times to update or create records
        foreach ($normalizedDays as $day) {
            foreach ($normalizedTimes as $time) {
                MerchantAvailability::updateOrCreate(
                    [
                        'merchant_id' => $merchantId,
                        'day' => $day,
                        'time' => $time,
                    ],
                    [] // No additional fields to update in this case
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
    public function getStatus()
    {
        $merchant = Auth::user()->merchant;

        return response()->json([
            'status' => $merchant->status
        ]);
    }

    // Update merchant status
    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $merchant = Auth::user()->merchant;
        $merchant->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status updated successfully',
            'status' => $merchant->status
        ]);
    }

    // Update merchant location
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $merchant = Auth::user()->merchant;
        $merchant->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return response()->json([
            'message' => 'Location updated successfully',
            'location' => [
                'latitude' => $merchant->latitude,
                'longitude' => $merchant->longitude
            ]
        ]);
    }
}
