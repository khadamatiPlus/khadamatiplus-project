<?php

namespace App\Domains\AppVersion\Http\Controllers\API;

use App\Domains\AppVersion\Models\AppVersion;
use App\Domains\AppVersion\Services\AppVersionService;
use App\Domains\AppVersion\Http\Transformers\AppVersionTransformer;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;


class AppVersionApiController extends APIBaseController
{


    /**
     * @param AppVersionService $appVersionService
     */
    public function __construct(AppVersionService $appVersionService)
    {
        $this->appVersionService = $appVersionService;
    }


    public function getAppVersion(Request $request): \Illuminate\Http\JsonResponse
    {
        AppVersion::getOrCreateDefault();
        try{
            return $this->successResponse($this->appVersionService
                ->get()
                ->transform(function ($information){
                    return (new AppVersionTransformer())->transform($information);
                }));
        }
        catch (\Exception $exception){
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }
}
