<?php

namespace App\Domains\AppService\Http\Controllers\API;

use App\Domains\AppService\Http\Transformers\AppServiceTransformer;
use App\Domains\AppService\Services\AppServiceService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;

class AppServiceApiController extends APIBaseController
{
    private $appServiceService;

    public function __construct(AppServiceService $appServiceService)
    {
        $this->appServiceService = $appServiceService;
    }

    /**
     * Get App Services
     *
     * Returns a list of active app services.
     *
     * @group App Services
     *
     * @queryParam featured boolean Filter featured services only. Example: true
     * @queryParam category_id integer Filter services by category ID. Example: 5
     * @queryParam online boolean Filter online services only. Example: true
     *
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Cleaning Service"
     *     }
     *   ]
     * }
     */
 public function getAppServices(Request $request): \Illuminate\Http\JsonResponse
 {
     try {
         $query = $this->appServiceService->getActiveAppServices();

         // Filter by featured
         if ($request->has('featured') && $request->boolean('featured')) {
             $query = $this->appServiceService->getFeaturedAppServices();
         }

         // Filter by category
         if ($request->has('category_id')) {
             $query = $this->appServiceService->getAppServicesByCategory($request->category_id);
         }

         // Filter by online
         if ($request->has('online') && $request->boolean('online')) {
             $query = $this->appServiceService->getOnlineAppServices();
         }

         $appServices = $query->get();

         return $this->successResponse(
             $appServices->transform(function ($appService) {
                 return (new AppServiceTransformer())->transform($appService);
             })
         );
     } catch (\Exception $exception) {
         report($exception);
         return $this->internalServerErrorResponse($exception->getMessage());
     }
 }


    /**
     * Get App Service By ID
     *
     * Retrieve a single active app service by its ID.
     *
     * @group App Services
     *
     * @urlParam id integer required The ID of the app service. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "id": 1,
     *     "name": "Cleaning Service",
     *     "images": [
     *       "http://127.0.0.1:8000/storage/app-services/image.jpg"
     *     ]
     *   }
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "App Service not found"
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Internal Server Error"
     * }
     */
    public function getAppServiceById($id)
    {
        try {
            $appService = $this->appServiceService->getActiveAppServices()->find($id);

            if (!$appService) {
                return $this->inputValidationErrorResponse('App Service not found');
            }

            return $this->successResponse(
                (new AppServiceTransformer())->transform($appService)
            );
        } catch (\Exception $exception) {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }
}
