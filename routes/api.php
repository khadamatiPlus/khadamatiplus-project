<?php

use \App\Domains\Lookups\Http\Controllers\API\LocationApiController;
use App\Domains\Lookups\Http\Controllers\API\PageApiController;
use App\Domains\Auth\Http\Controllers\API\RegisterApiController;
use App\Domains\Auth\Http\Controllers\API\LoginApiController;
use App\Domains\Auth\Http\Controllers\API\UserManagementApiController;
use App\Domains\Lookups\Http\Controllers\API\CategoryApiController;
use App\Domains\Lookups\Http\Controllers\API\LabelApiController;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\ApiLocaleMiddleware;
use App\Http\Middleware\CheckCaptainVerifiedMiddleware;
use App\Domains\Merchant\Http\Controllers\API\MerchantApiController;
use App\Domains\Delivery\Http\Controllers\API\OrderApiController;
use App\Http\Middleware\CheckMerchantVerifiedMiddleware;
use App\Domains\Lookups\Http\Controllers\API\UserTypeApiController;
use App\Domains\Lookups\Http\Controllers\API\TagApiController;
use App\Domains\Rating\Http\Controllers\API\RatingApiController;
use App\Domains\Notification\Http\Controllers\API\NotificationApiController;
use App\Domains\Information\Http\Controllers\API\InformationApiController;
use App\Domains\Social\Http\Controllers\API\SocialApiController;
use App\Domains\Delivery\Http\Controllers\API\CaptainOrdersApiController;
use App\Domains\Banner\Http\Controllers\API\BannerApiController;
use App\Domains\Introduction\Http\Controllers\API\IntroductionApiController;
use App\Domains\Service\Http\Controllers\API\ServiceApiController;
use App\Domains\Customer\Http\Controllers\API\CustomerAddressApiController;
use App\Domains\Customer\Http\Controllers\API\CustomerApiController;
use App\Domains\AppVersion\Http\Controllers\API\AppVersionApiController;
use App\Domains\Highlight\Http\Controllers\API\HighlightApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//force json response middleware
Route::group(['middleware' => ForceJsonResponse::class], function (){

    //localization group middleware
    Route::group(['middleware' => ApiLocaleMiddleware::class], function (){

        Route::group([
            'prefix' => 'lookups',
            'as' => 'lookups.'
        ], function(){

            Route::get('getCountries', [LocationApiController::class, 'getCountries']);
            Route::get('getCities', [LocationApiController::class, 'getCities']);
            Route::get('getAreas', [LocationApiController::class, 'getAreas']);
            Route::get('getCategories', [CategoryApiController::class, 'getCategories']);

            Route::get('getLabels', [LabelApiController::class, 'getLabels']);
            Route::get('getTags', [TagApiController::class, 'getTags']);
            Route::get('getInformation', [InformationApiController::class, 'getInformation']);
            Route::get('getSocial', [SocialApiController::class, 'getSocial']);
            Route::get('getAppVersion', [AppVersionApiController::class, 'getAppVersion']);

            Route::get('getPageBySlug', [PageApiController::class, 'getPageBySlug']);
        });
        Route::get('getBanners', [BannerApiController::class, 'getBanners']);
        Route::get('getIntroductions', [IntroductionApiController::class, 'getIntroductions']);
        Route::get('getHighlights', [HighlightApiController::class, 'getHighlights']);
        //Required Auth token routes
        Route::group(['middleware' => 'auth:sanctum'], function (){
            Route::get('get-all-orders', [OrderApiController::class, 'getAllOrders']);

            Route::group([
                'prefix' => 'notifications',
                'as' => 'notifications.'
            ], function () {
                Route::get('getNotifications', [NotificationApiController::class, 'getNotifications']);
                Route::get('getNotifications', [NotificationApiController::class, 'getCustomerNotifications']);
            });

            Route::group([
                'prefix' => 'delivery',
                'as' => 'delivery.'
            ], function (){

                Route::group([
                    'prefix' => 'order',
                    'as' => 'order.'
                ], function(){

                    //Merchant Order management Routes
                    Route::get('show', [OrderApiController::class, 'show']);
//                    Route::post('merchantAction', [OrderApiController::class, 'merchantAction']);
                    //End Merchant Order management Routes

                });

            });
            Route::group(['middleware' => CheckMerchantVerifiedMiddleware::class], function (){

                //delivery routes
                Route::group([
                    'prefix' => 'delivery',
                    'as' => 'delivery.'
                ], function (){

                    Route::group([
                        'prefix' => 'order',
                        'as' => 'order.'
                    ], function(){

                        //Merchant Order management Routes
                        Route::post('merchantAction', [OrderApiController::class, 'merchantAction']);
                        Route::get('orderList', [OrderApiController::class, 'list']);
                        Route::post('storeOrderAsMerchant', [OrderApiController::class, 'storeOrderAsMerchant']);
                        //End Merchant Order management Routes

                    });

                });
            });
                //end delivery routes
                Route::group([
                    'prefix' => 'merchant',
                    'as' => 'merchant.'
                ], function (){
                    Route::group(['middleware' => CheckMerchantVerifiedMiddleware::class], function () {

                        Route::post('update', [MerchantApiController::class, 'update']);
                        Route::post('/updatePassword', [MerchantApiController::class, 'updatePassword']);
                        Route::delete('/deleteMerchantAccount', [MerchantApiController::class, 'deleteMerchantAccount']);
                        Route::get('profile', [MerchantApiController::class, 'profile']);
                        Route::post('storeService', [ServiceApiController::class, 'storeService']);
                        Route::put('updateService/{id}', [ServiceApiController::class, 'updateService']);
                        Route::get('getServiceDetails/{id}', [ServiceApiController::class, 'getServiceDetails']);
                        Route::delete('deleteService/{id}', [ServiceApiController::class, 'deleteService']);
                        Route::get('getServices', [ServiceApiController::class, 'getServices']);
                        Route::post('availability', [MerchantApiController::class, 'storeOrUpdate']);
                        Route::post('updateOrderStatusByMerchant', [OrderApiController::class, 'updateOrderStatusByMerchant']);
                        Route::get('/has-availability', [MerchantApiController::class, 'hasAvailability']);
                        Route::get('getAvailability', [MerchantApiController::class, 'getAvailability']);
                        Route::get('/status', [MerchantApiController::class, 'getStatus']);
                        Route::put('/status', [MerchantApiController::class, 'updateStatus']);
                        Route::put('/location', [MerchantApiController::class, 'updateLocation']);

                        Route::post('service/options', [ServiceApiController::class, 'store']);

                        // Update an existing option
                        Route::put('service/options/{id}', [ServiceApiController::class, 'update']);

                        // Delete an option
                        Route::delete('service/options/{id}', [ServiceApiController::class, 'destroy']);

                        // Get all options for a specific service
                        Route::get('service/{serviceId}/options', [ServiceApiController::class, 'index']);

                        // Get a specific option
                        Route::get('service/options/{id}', [ServiceApiController::class, 'show']);
                        Route::get('/monthly-orders', [OrderApiController::class, 'getMonthlyOrderCounts']);

                    });
            });

            Route::group([
                'prefix' => 'customer',
                'as' => 'customer.'
            ], function () {

                Route::post('addCustomerAddress', [CustomerAddressApiController::class, 'addCustomerAddress']);
                Route::post('updateCustomerAddress', [CustomerAddressApiController::class, 'updateCustomerAddress']);
                Route::delete('deleteCustomerAddress', [CustomerAddressApiController::class, 'deleteCustomerAddress']);
                Route::get('getCustomerAddresses', [CustomerAddressApiController::class, 'getCustomerAddresses']);
                Route::get('getCustomerAddressDetails', [CustomerAddressApiController::class, 'getCustomerAddressDetails']);



                Route::delete('/deleteCustomerAccount', [CustomerApiController::class, 'deleteCustomerAccount']);
                Route::post('update', [CustomerApiController::class, 'update']);
                Route::get('getCustomerDetails', [CustomerApiController::class, 'getCustomerDetails']);


                Route::post('/toggle-favorite', [CustomerApiController::class, 'toggleFavorite']);
                Route::get('/getFavoriteServices', [CustomerApiController::class, 'getFavoriteServices']);
                Route::post('/set-default-address', [CustomerApiController::class, 'setDefaultAddress']);
                Route::post('/storeReview', [CustomerApiController::class, 'storeReview']);
                Route::post('/requestOrder', [OrderApiController::class, 'requestOrder']);
                Route::post('updateOrderStatusByCustomer', [OrderApiController::class, 'updateOrderStatusByCustomer']);
                Route::put('/location', [CustomerApiController::class, 'updateLocation']);


            });

        });
        //auth process routes
        Route::group([
            'prefix' => 'auth',
            'as' => 'auth.'
        ], function (){

            Route::post('registerMerchant', [RegisterApiController::class, 'registerMerchant']);
            Route::post('authenticate', [LoginApiController::class, 'mobileAuthenticate']);
            Route::post('otp-login', [LoginApiController::class, 'otpAuthenticate']);

            Route::post('/loginUsingEmail', [LoginApiController::class, 'loginUsingEmail']);
            Route::post('/registerUsingEmail', [RegisterApiController::class, 'registerUsingEmail']);

            Route::post('registerCustomer', [RegisterApiController::class,'registerCustomer']);
            Route::post('/facebook', [RegisterApiController::class, 'handleFacebookCallback']);
            Route::post('/google', [RegisterApiController::class, 'handleGoogleCallback']);

//            Route::get('generateOTP', [UserManagementApiController::class, 'generateOTP']);
//            Route::get('checkAuthEnabled', [UserManagementApiController::class, 'checkAuthEnabled']);
        });
        //Optional Auth token for data changes only such as (is_favorite on item returned by public api)
        Route::group(['middleware' => 'optionalAuthSanctum'],function (){
            Route::get('get-all-services', [ServiceApiController::class, 'getAllServices']);
            Route::get('services/{id}', [ServiceApiController::class, 'getServiceById']);


        });
        Route::post('/request-reset-otp', [MerchantApiController::class, 'requestResetOtp']);
        Route::post('/check_mobile_number-otp', [MerchantApiController::class, 'requestMobileNumberOtp']);
        Route::post('/confirm-otp', [MerchantApiController::class, 'confirmOtp']);
        Route::post('/reset-password', [MerchantApiController::class, 'resetPassword']);

        Route::post('/customer/request-reset-otp', [CustomerApiController::class, 'requestResetOtp']);
        Route::post('/customer/check_mobile_number-otp', [CustomerApiController::class, 'requestMobileNumberOtp']);
        Route::post('/customer/confirm-otp', [CustomerApiController::class, 'confirmOtp']);
        Route::post('/customer/reset-password', [CustomerApiController::class, 'resetPassword']);


        Route::get('get-all-merchants', [MerchantApiController::class, 'getAllMerchants']);
        Route::get('merchants/{id}', [MerchantApiController::class, 'getMerchantById']);

        Route::post('/auth/send-otp', [LoginApiController::class, 'sendOtp']);
        Route::post('/auth/send-otp-register', [LoginApiController::class, 'sendOtpRegister']);
        Route::post('merchant/uploadImage', [ServiceApiController::class, 'uploadImage']);
        Route::middleware('auth:sanctum')->post('/update-mobile-number', [CustomerApiController::class, 'updateMobileNumber']);
        Route::middleware('auth:sanctum')->post('/update-fcm-token', [UserManagementApiController::class, 'updateFcmToken']);

    });
   });
