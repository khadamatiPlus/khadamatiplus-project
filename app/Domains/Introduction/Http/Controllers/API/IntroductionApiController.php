<?php

namespace App\Domains\Introduction\Http\Controllers\API;
use App\Domains\Introduction\Http\Transformers\IntroductionTransformer;
use App\Domains\Introduction\Services\IntroductionService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;

class IntroductionApiController extends APIBaseController
{


    private $introductionService;


    public function __construct(IntroductionService $introductionService)
    {
        $this->introductionService = $introductionService;
    }


    public function getIntroductions(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:customers,merchants'
        ]);

        // Fetch the type from the request
        $type = $request->input('type');
        try {
            return $this->successResponse($this->introductionService
                ->where('type',$type)
                ->get()
                ->transform(function ($introductions) {
                    return (new IntroductionTransformer)->transform($introductions);
                }));
        } catch (\Exception $exception) {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }
}
