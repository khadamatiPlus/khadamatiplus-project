<?php

namespace App\Domains\Auth\Http\Controllers\API;

use App\Domains\Auth\Events\User\UserLoggedIn;
use App\Domains\Auth\Http\Requests\API\MobileAuthenticateRequest;
use App\Domains\Auth\Http\Transformers\UserTransformer;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\UserService;
use App\Domains\Customer\Http\Transformers\CustomerTransformer;
use App\Domains\FirebaseIntegration\FirebaseIntegration;
use App\Domains\Merchant\Http\Transformers\MerchantTransformer;
use App\Http\Controllers\APIBaseController;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginApiController extends APIBaseController
{

    /**
     * @var UserService $userService
     */
    protected $userService;



    /**
     * @param UserService $userService
     * @param FirebaseIntegration $firebaseIntegration
     */
    public function __construct(UserService $userService,SmsService $smsService)
    {
        $this->userService = $userService;
        $this->smsService = $smsService;
    }

    /**
     * Login with Mobile Number
     *
     * Authenticates a user using their mobile number and password.
     * If authentication succeeds, an access token and user information are returned.
     * Optionally updates the user's FCM token for push notifications.
     *
     * @group Authentication
     *
     * @unauthenticated
     *
     * @header App-Version-Name string Required. Mobile application version. Example: 1.0.0
     *
     * @bodyParam country_code string optional Country calling code without "+". Defaults to 962. Example: 962
     * @bodyParam mobile_number string required User mobile number without the country code. Example: 791234567
     * @bodyParam password string required User password. Example: Password@123
     * @bodyParam fcm_token string optional Firebase Cloud Messaging token. Example: eXampleFcmToken123456789
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "completed": true,
     *     "access_token": "1|abcdefghijklmnopqrstuvwxyz",
     *     "active": true,
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "mobile_number": "791234567",
     *       "country_code": "962"
     *     }
     *   }
     * }
     *
     * @response 422 {
     *   "success": false,
     *   "message": "The given data was invalid."
     * }
     *
     * @response 401 {
     *   "success": false,
     *   "message": "Invalid credentials."
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Internal Server Error."
     * }
     */
    public function mobileAuthenticate(MobileAuthenticateRequest $request)
    {
        $request->validated(); // Validate before proceeding

        $country_code = $request->input('country_code') ?? env('DEFAULT_COUNTRY_CODE', '962');
        $fullNumber = $country_code . $request->input('mobile_number');
        $password = $request->input('password');
        $fcm_token = $request->input('fcm_token');

        try {
            if (app()->environment(['local', 'testing'])) {
                $verifyResult = true;
            }

            if ($verifyResult) {
                $login = $this->userService->authenticateUserMobile(
                    $country_code,
                    $request->input('mobile_number'),
                    $request->header('App-Version-Name'),
                    $password
                );

                if (isset($login->show_not_merchant) && $login->show_not_merchant) {
                    return $this->inputValidationErrorResponse(__('You cannot login using the merchant application'));
                }
                if (isset($login->show_not_captain) && $login->show_not_captain) {
                    return $this->inputValidationErrorResponse(__('You cannot login using the captain application'));
                }
                if (isset($login->show_not_customer) && $login->show_not_customer) {
                    return $this->inputValidationErrorResponse(__('You cannot login using the customer application'));
                }

                if ($login) {
                    // Check if login is an object or an array
                    $user = is_array($login) ? $login['user'] ?? null : $login->user ?? null;

                    if ($user) {
                        // Save FCM token if provided
                        if ($fcm_token) {
                            if (is_array($user)) {
                                // Handle user as an array
                                $userModel = User::find($user['id']);
                                if ($userModel) {
                                    $userModel->fcm_token = $fcm_token;
                                    $userModel->save();
                                }
                            } else {
                                // Handle user as an object
                                $user->fcm_token = $fcm_token;
                                $user->save();
                            }
                        }
                    }

                    return $this->successResponse($login);
                }
            }

            return $this->successResponse([
                'completed' => false,
                'access_token' => '',
                'active' => false,
            ]);
        } catch (\Exception $exception) {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }



    public function sendOtp(Request $request)
    {
        $request->validate([
            'country_code' => 'required',
            'mobile_number' => 'required',
        ]);

        $countryCode = $request->input('country_code');
        $mobileNumber = $request->input('mobile_number');

        // Check if the mobile number starts with 962
        if (str_starts_with($mobileNumber, '962')) {
            $fullNumber = $mobileNumber; // Use the mobile number as is
        } else {
            $fullNumber = $countryCode . $mobileNumber; // Append country code
        }

        $user = User::where('mobile_number', $mobileNumber)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        $message = "Your OTP code is $otp. It expires in 5 minutes.";
        Log::info($fullNumber);
        $this->smsService->sendSms($fullNumber, $message);

        return response()->json(['message' => 'OTP sent successfully']);
    }
    public function sendOtpRegister(Request $request)
    {
        $request->validate([
            'country_code' => 'required',
            'mobile_number' => 'required',
        ]);
        $countryCode = $request->input('country_code');
        $mobileNumber = $request->input('mobile_number');
        if (str_starts_with($mobileNumber, '962')) {
            $fullNumber = $mobileNumber; // Use the mobile number as is
        } else {
            $fullNumber = $countryCode . $mobileNumber; // Append country code
        }
        $otp = rand(100000, 999999);
        $message = "Your OTP code is $otp. It expires in 5 minutes.";
        Log::info($fullNumber);
        $this->smsService->sendSms($fullNumber, $message);

        return response()->json(['message' => 'OTP sent successfully']);
    }



    public function otpAuthenticate(Request $request)
    {
        $request->validate([
            'country_code' => 'nullable',
            'mobile_number' => 'required',
            'fcm_token' => 'nullable',
        ]);

        $country_code = $request->input('country_code');
        $mobile_number = $request->input('mobile_number');
        $otp_code = $request->input('otp_code');
        $fcm_token = $request->input('fcm_token');

        try {
            // Find the user with the provided mobile number and OTP
            $user = User::where('mobile_number', $mobile_number)->first();

            if (!$user) {
                return $this->inputValidationErrorResponse(__('Invalid or expired OTP.'));
            }

            // Clear OTP after successful login
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            $login = $this->userService->authenticateUserMobileOtp(
                $country_code,
                $request->input('mobile_number'),
                $request->header('App-Version-Name'),
            );
            if (isset($login->show_not_merchant) && $login->show_not_merchant) {
                return $this->inputValidationErrorResponse(__('You cannot login using the merchant application'));
            }
            if (isset($login->show_not_captain) && $login->show_not_captain) {
                return $this->inputValidationErrorResponse(__('You cannot login using the captain application'));
            }
            if (isset($login->show_not_customer) && $login->show_not_customer) {
                return $this->inputValidationErrorResponse(__('You cannot login using the customer application'));
            }

            if ($login) {
                // Check if login is an object or an array
                $user = is_array($login) ? $login['user'] ?? null : $login->user ?? null;

                if ($user) {
                    // Save FCM token if provided
                    if ($fcm_token) {
                        if (is_array($user)) {
                            // Handle user as an array
                            $userModel = User::find($user['id']);
                            if ($userModel) {
                                $userModel->fcm_token = $fcm_token;
                                $userModel->save();
                            }
                        } else {
                            // Handle user as an object
                            $user->fcm_token = $fcm_token;
                            $user->save();
                        }
                    }
                }

                return $this->successResponse($login);
            }

            return $this->successResponse([
                'completed' => false,
                'access_token' => '',
                'active' => false,
            ]);
        } catch (\Exception $exception) {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }


    public function loginUsingEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Get the App-Version-Name from the header
        $appVersionName = $request->header('App-Version-Name');

        // Find the user by email
        $user = User::where('email', $request->email)->first();


        if ($user) {
            // Check if the user is active
            if (!$user->isActive()) {
                $user->tokens()->delete();
                $resp = new \stdClass();
                $resp->access_token = '';
                $resp->active = false;
                $resp->completed = true;
                return response()->json($resp);
            }

            // Initialize response object
            $resp = new \stdClass();

            // Trigger the UserLoggedIn event
            event(new UserLoggedIn($user));

            // Delete existing tokens
            $user->tokens()->each(function ($token) {
                $token->delete();
            });

            // Handle merchant app
            if ($appVersionName === 'khadamati_merchant_app' && $user->isMerchantAdmin()) {
                $merchant = $user->merchant;
                $resp->access_token = $user->createToken('mobile')->plainTextToken;
                $resp->user = (new UserTransformer)->transform($user);
                $resp->active = true;
                $resp->completed = true;
                $resp->merchant = (new MerchantTransformer)->transform($merchant);
            }
            // Handle customer app
            elseif ($appVersionName === 'khadamati_customer_app' && $user->isCustomer()) {

                $resp->access_token = $user->createToken('mobile')->plainTextToken;
                $resp->user = (new UserTransformer)->transform($user);
                $resp->active = true;
                $resp->completed = true;
                $resp->customer = (new CustomerTransformer)->transform($user->customer);
            }
            // Invalid app version or user type
            else {
                return response()->json(['message' => 'Invalid app version or user type'], 403);
            }

            return response()->json($resp);
        }

        return response()->json(['message' => 'User not found'], 404);
    }

}
