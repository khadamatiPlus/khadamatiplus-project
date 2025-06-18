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
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
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

            if(app()->environment(['local', 'testing'])){
                $verifyResult = true;
            }

            if($verifyResult){
                $this->customerService->register($request->validated());
                $login = $this->userService->authenticateUserMobile($country_code,$request->input('mobile_number'),$request->header('App-Version-Name'),$password,$fcm_token);
                return $this->successResponse($login);
            }

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
            return response()->json(['message' => 'Invalid App-Version-Name'], 400);
        }

        // If the email already exists for the respective role, return an error
        if ($existingUser) {
            return response()->json(['message' => 'Email already exists for this role'], 400);
        }

        // Combine name and email into a single field (optional)
        $nameWithEmail = $request->name;

        // Create the user and associated records in a transaction
        $resp = new \stdClass();
        DB::transaction(function () use ($request, $appVersionName, $nameWithEmail, &$resp) {
            // Create the user
            $user = User::create([
                'name' => $nameWithEmail,
                'email' => $request->email,
                'password' => $request->email, // Kept original password logic
            ]);

            // Assign role using syncRoles
            if ($appVersionName === 'khadamati_merchant_app') {
                $user->syncRoles(2); // Assign merchant_admin role (ID 2)
            } elseif ($appVersionName === 'khadamati_customer_app') {
                $user->syncRoles(3); // Assign customer role (ID 3)
            }

            // Check the App-Version-Name and create merchant or customer
            if ($appVersionName === 'khadamati_merchant_app') {
                // Create a merchant
                $merchant = Merchant::create([
                    'profile_id' => $user->id,
                    'created_by_id' => $user->id,
                    'name' => $nameWithEmail,
                    'updated_by_id' => $user->id,
                ]);

                // Save the merchant_id in the user record
                $user->merchant_id = $merchant->id;
                $user->save();

                // Verify user is a merchant admin
                if (!$user->isMerchantAdmin()) {
                    throw new \Exception('User is not a merchant admin');
                }

                // Transform merchant data for response
                $resp->merchant = (new MerchantTransformer())->transform($merchant);
            } elseif ($appVersionName === 'khadamati_customer_app') {
                // Create a customer
                $customer = Customer::create([
                    'name' => $nameWithEmail,
                    'profile_id' => $user->id,
                    'created_by_id' => $user->id,
                    'updated_by_id' => $user->id,
                ]);

                // Save the customer_id in the user record
                $user->customer_id = $customer->id;
                $user->save();

                // Verify user is a customer
                if (!$user->isCustomer()) {
                    throw new \Exception('User is not a customer');
                }

                // Transform customer data for response
                $resp->customer = (new CustomerTransformer())->transform($customer);
            }

            // Automatically log in the user after registration
            event(new UserLoggedIn($user));

            // Delete any existing tokens
            $user->tokens()->each(function ($token) {
                $token->delete();
            });

            // Create a new access token for the user
            $resp->access_token = $user->createToken('mobile')->plainTextToken;
            $resp->user = (new UserTransformer)->transform($user);
            $resp->active = true;
            $resp->completed = true;
        });

        return response()->json($resp, 201);
    }




    public function handleFacebookCallback(Request $request)
    {

        $request->validate([
            'access_token' => 'required|string', // Frontend sends Facebook token
        ]);

        $appVersionName = $request->header('App-Version-Name');

        try {
            // Fetch user data from Facebook
            $facebookUser = Socialite::driver('facebook')
                ->stateless()
                ->userFromToken($request->access_token);

            // Check if user exists by Facebook ID
            $existingUser = User::where('facebook_id', $facebookUser->id)->first();


            if ($existingUser) {

                return $this->handleFacebookLogin($existingUser, $appVersionName);
            }


            // Check if email exists for merchant/customer role
            if ($appVersionName === 'khadamati_merchant_app') {
                $emailUser = User::where('email', $facebookUser->email)
                    ->whereNotNull('merchant_id')
                    ->first();
            } elseif ($appVersionName === 'khadamati_customer_app') {

                $emailUser = User::where('email', $facebookUser->email)
                    ->whereNotNull('customer_id')
                    ->first();
            } else {
                return response()->json(['message' => 'Invalid App-Version-Name'], 400);
            }

            if ($emailUser) {

                return response()->json(['message' => 'Email already exists for this role'], 400);
            }

            // Create new user with Facebook data
            $user = User::create([
                'name' => $facebookUser->name ?? $facebookUser->email,
                'email' => $facebookUser->email,
                'facebook_id' => $facebookUser->id,
                'password' => bcrypt(Str::random(16)), // Random password since login is via Facebook
            ]);

            // Create merchant/customer based on app type
            if ($appVersionName === 'khadamati_merchant_app') {
                $merchant = Merchant::create([
                    'profile_id' => $user->id,
                    'created_by_id' => $user->id,
                    'name' => $facebookUser->name,
                    'updated_by_id' => $user->id,
                ]);
                $user->merchant_id = $merchant->id;
                $user->save();
                $merchantData = (new MerchantTransformer())->transform($merchant);
            } elseif ($appVersionName === 'khadamati_customer_app') {
                $customer = Customer::create([
                    'name' => $facebookUser->name,
                    'profile_id' => $user->id,
                    'created_by_id' => $user->id,
                    'updated_by_id' => $user->id,
                ]);
                $user->customer_id = $customer->id;
                $user->save();
                $customerData = (new CustomerTransformer())->transform($customer);
            }

            // Log in the user
            event(new UserLoggedIn($user));
            $user->tokens()->delete();
            $accessToken = $user->createToken('api')->plainTextToken;

            // Prepare response
            $resp = new \stdClass();
            $resp->access_token = $accessToken;
            $resp->user = (new UserTransformer)->transform($user);
            $resp->active = true;
            $resp->completed = true;

            if ($appVersionName === 'khadamati_merchant_app') {
                $resp->merchant = $merchantData;
            } elseif ($appVersionName === 'khadamati_customer_app') {
                $resp->customer = $customerData;
            }

            return response()->json($resp, 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Facebook authentication failed',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    private function handleFacebookLogin(User $user, $appVersionName)
    {
        // Verify role
        if ($appVersionName === 'khadamati_merchant_app' && !$user->merchant_id) {
            return response()->json(['message' => 'Account not registered as merchant'], 400);
        }

        if ($appVersionName === 'khadamati_customer_app' && !$user->customer_id) {
            return response()->json(['message' => 'Account not registered as customer'], 400);
        }

        // Log in
        event(new UserLoggedIn($user));
        $user->tokens()->delete();
        $accessToken = $user->createToken('api')->plainTextToken;

        $resp = new \stdClass();
        $resp->access_token = $accessToken;
        $resp->user = (new UserTransformer)->transform($user);
        $resp->active = true;
        $resp->completed = true;

        if ($appVersionName === 'khadamati_merchant_app') {
            $resp->merchant = (new MerchantTransformer())->transform($user->merchant);
        } elseif ($appVersionName === 'khadamati_customer_app') {
            $resp->customer = (new CustomerTransformer())->transform($user->customer);
        }

        return response()->json($resp, 200);
    }


    public function handleGoogleCallback(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string', // Frontend sends Google token
        ]);

        $appVersionName = $request->header('App-Version-Name');

        try {
            // Fetch user data from Google
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->access_token);

            // Check if user exists by Google ID
            $existingUser = User::where('google_id', $googleUser->id)->first();

            if ($existingUser) {
                return $this->handleGoogleLogin($existingUser, $appVersionName);
            }

            // Check if email exists for merchant/customer role
            if ($appVersionName === 'khadamati_merchant_app') {
                $emailUser = User::where('email', $googleUser->email)
                    ->whereNotNull('merchant_id')
                    ->first();
            } elseif ($appVersionName === 'khadamati_customer_app') {
                $emailUser = User::where('email', $googleUser->email)
                    ->whereNotNull('customer_id')
                    ->first();
            } else {
                return response()->json(['message' => 'Invalid App-Version-Name'], 400);
            }

            if ($emailUser) {
                return response()->json(['message' => 'Email already exists for this role'], 400);
            }

            // Create new user with Google data
            $user = User::create([
                'name' => $googleUser->name ?? $googleUser->email,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => bcrypt(Str::random(16)), // Random password since login is via Google
            ]);

            // Create merchant/customer based on app type
            if ($appVersionName === 'khadamati_merchant_app') {
                $merchant = Merchant::create([
                    'profile_id' => $user->id,
                    'created_by_id' => $user->id,
                    'name' => $googleUser->name,
                    'updated_by_id' => $user->id,
                ]);
                $user->merchant_id = $merchant->id;
                $user->save();
                $merchantData = (new MerchantTransformer())->transform($merchant);
            } elseif ($appVersionName === 'khadamati_customer_app') {
                $customer = Customer::create([
                    'name' => $googleUser->name,
                    'profile_id' => $user->id,
                    'created_by_id' => $user->id,
                    'updated_by_id' => $user->id,
                ]);
                $user->customer_id = $customer->id;
                $user->save();
                $customerData = (new CustomerTransformer())->transform($customer);
            }

            // Log in the user
            event(new UserLoggedIn($user));
            $user->tokens()->delete();
            $accessToken = $user->createToken('api')->plainTextToken;

            // Prepare response
            $resp = new \stdClass();
            $resp->access_token = $accessToken;
            $resp->user = (new UserTransformer)->transform($user);
            $resp->active = true;
            $resp->completed = true;

            if ($appVersionName === 'khadamati_merchant_app') {
                $resp->merchant = $merchantData;
            } elseif ($appVersionName === 'khadamati_customer_app') {
                $resp->customer = $customerData;
            }

            return response()->json($resp, 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Google authentication failed',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    private function handleGoogleLogin(User $user, $appVersionName)
    {
        // Verify role
        if ($appVersionName === 'khadamati_merchant_app' && !$user->merchant_id) {
            return response()->json(['message' => 'Account not registered as merchant'], 400);
        }

        if ($appVersionName === 'khadamati_customer_app' && !$user->customer_id) {
            return response()->json(['message' => 'Account not registered as customer'], 400);
        }

        // Log in
        event(new UserLoggedIn($user));
        $user->tokens()->delete();
        $accessToken = $user->createToken('api')->plainTextToken;

        $resp = new \stdClass();
        $resp->access_token = $accessToken;
        $resp->user = (new UserTransformer)->transform($user);
        $resp->active = true;
        $resp->completed = true;

        if ($appVersionName === 'khadamati_merchant_app') {
            $resp->merchant = (new MerchantTransformer())->transform($user->merchant);
        } elseif ($appVersionName === 'khadamati_customer_app') {
            $resp->customer = (new CustomerTransformer())->transform($user->customer);
        }

        return response()->json($resp, 200);
    }
}
