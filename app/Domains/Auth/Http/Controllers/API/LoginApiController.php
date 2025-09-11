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
     * @OA\Post(
     * path="/api/auth/authenticate",
     * summary="Authentication - Login Using Mobile",
     * description="",
     * operationId="authenticate",
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
     *     @OA\Parameter(
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
     *    description="pass authetication data",
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               required={"mobile_number", "firebase_auth_token"},
     *              @OA\Property(property="country_code", type="string"),
     *              @OA\Property(property="mobile_number", type="string"),
     *              @OA\Property(property="firebase_auth_token", type="string")
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
            'fcm_token' => 'required',
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
