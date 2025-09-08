<?php

namespace App\Domains\Highlight\Http\Controllers\API;
use App\Domains\Highlight\Http\Transformers\HighlightTransformer;
use App\Domains\Highlight\Services\HighlightService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;

class HighlightApiController extends APIBaseController
{
    private $highlightService;

    public function __construct(HighlightService $highlightService)
    {
        $this->highlightService = $highlightService;
    }

    public function getHighlights(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->successResponse(
                $this->highlightService
                    ->get()
                    ->where('status', 1)
                    ->transform(function ($highlight) {
                        return (new HighlightTransformer)->transform($highlight);
                    })
            );
        } catch (\Exception $exception) {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }

}
