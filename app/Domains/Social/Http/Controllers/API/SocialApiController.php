<?php

namespace App\Domains\Social\Http\Controllers\API;

use App\Domains\Social\Services\SocialService;
use App\Domains\Social\Http\Transformers\SocialTransformer;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;


class SocialApiController extends APIBaseController
{


    /**
     * @param SocialService $socialService
     */
    public function __construct(SocialService $socialService)
    {
        $this->socialService = $socialService;
    }


    public function getSocial(Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            return $this->successResponse($this->socialService
                ->get()
                ->transform(function ($information){
                    return (new SocialTransformer())->transform($information);
                }));
        }
        catch (\Exception $exception){
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }
}
