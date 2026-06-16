<?php

namespace App\Domains\Information\Http\Controllers\API;

use App\Domains\Information\Services\InformationService;
use App\Domains\Information\Http\Transformers\InformationTransformer;
use App\Domains\Lookups\Services\BusinessTypeService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;


class InformationApiController extends APIBaseController
{

    /**
     * @var BusinessTypeService $businessTypeService
     */
    private $informationService;

    /**
     * @param InformationService $informationService
     */
    public function __construct(InformationService $informationService)
    {
        $this->informationService = $informationService;
    }


    public function getInformation(Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            return $this->successResponse($this->informationService
                ->get()
                ->transform(function ($information){
                    return (new InformationTransformer())->transform($information);
                }));
        }
        catch (\Exception $exception){
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }
}
