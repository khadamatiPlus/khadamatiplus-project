<?php

namespace App\Domains\Lookups\Http\Controllers\API;

use App\Domains\Lookups\Http\Transformers\UserTypeTransformer;
use App\Domains\Lookups\Services\UserTypeService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;


class UserTypeApiController extends APIBaseController
{

    /**
     * @var UserTypeService
     */
    private $userTypeService;

    /**
     * @param UserTypeService $userTypeService
     */
    public function __construct(UserTypeService $userTypeService)
    {
        $this->userTypeService = $userTypeService;
    }


    public function getUserTypes(Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            return $this->successResponse($this->userTypeService
                ->get()
                ->transform(function ($userType){
                    return (new UserTypeTransformer)->transform($userType);
                }));
        }
        catch (\Exception $exception){
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }
}
