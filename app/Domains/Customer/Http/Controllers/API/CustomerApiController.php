<?php

namespace App\Domains\Customer\Http\Controllers\API;

use App\Domains\Auth\Models\User;
use App\Domains\Customer\Http\Requests\API\UpdateCustomerRequest;
use App\Domains\Customer\Http\Transformers\CustomerTransformer;
use App\Domains\Customer\Models\Customer;
use App\Domains\Customer\Services\CustomerService;
use App\Domains\Service\Http\Transformers\ServiceTransformer;
use App\Domains\Service\Models\Service;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerApiController extends APIBaseController
{

    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }


    public function update(UpdateCustomerRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse(
            (new CustomerTransformer)->transform($this->customerService->update($request->user()->customer_id,$request->validated()))
        );
    }

    public function profile()
    {

        $customer = auth()->user()->customer->where('profile_id',auth()->id())->firstOrFail();
        return $this->successResponse(
            (new CustomerTransformer())->transform($customer)
        );
    }

    public function deleteCustomerAccount(Request $request)
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
        $user = User::where('mobile_number', $mobile_number)->where('customer_id','!=',null)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $user->password = Hash::make($new_password);
        $user->save();

        // Delete OTP record (if you want to clear OTPs after password reset)
        DB::table('password_resets')->where('phone_number', $mobile_number)->delete();

        return response()->json(['message' => 'Password reset successfully.'], 200);
    }


    public function toggleFavorite(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        $customer_id = Auth::user()->customer_id; // Get the authenticated user (assuming they're a customer)

        $customer=Customer::query()->where('id',$customer_id)->first();
        $service = $customer->favoriteServices()->where('service_id', $validated['service_id'])->first();

        if ($service) {
            // Unfavorite the service
            $customer->favoriteServices()->detach($validated['service_id']);
            return response()->json([
                'success' => true,
                'message' => 'Service removed from favorites',
                'action' => 'unfavorite',
            ]);
        } else {
            // Favorite the service
            $customer->favoriteServices()->attach($validated['service_id']);
            return response()->json([
                'success' => true,
                'message' => 'Service added to favorites',
                'action' => 'favorite',
            ]);
        }
    }


    public function getFavoriteServices(Request $request)
    {
        $customer_id = Auth::user()->customer_id;
        $customer=Customer::query()->where('id',$customer_id)->first();

        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $query = $customer->favoriteServices();

        // Apply optional search filters if provided
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply pagination
        $services = $query->paginate(10); // Adjust the items per page as needed

        // Transform the paginated data
        $transformedServices = $services->getCollection()->map(function ($service) {
            return (new ServiceTransformer())->transform($service);
        });

        // Return the response using successResponse
        return $this->successResponse([
            'data' => $transformedServices,
            'pagination' => [
                'total' => $services->total(),
                'count' => $services->count(),
                'per_page' => $services->perPage(),
                'current_page' => $services->currentPage(),
                'total_pages' => $services->lastPage(),
            ],
        ]);
    }


    public function setDefaultAddress(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'required|exists:customer_addresses,id',
        ]);

        $customer_id = Auth::user()->customer_id;
        $customer=Customer::query()->where('id',$customer_id)->first();
        $customer->customer_address_id = $validated['address_id'];
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Default address set successfully.',
        ]);
    }


    public function storeReview(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $service = Service::findOrFail($request->service_id);

        // Check if the user already reviewed this service
        if ($service->reviews()->where('customer_id',  Auth::user()->customer_id)->exists()) {
            return response()->json(['message' => 'You have already reviewed this service.'], 400);
        }

        // Create the review
        $review = $service->reviews()->create([
            'customer_id' =>  Auth::user()->customer_id,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review added successfully.',
            'data' => $review,
        ], 201);
    }

}
