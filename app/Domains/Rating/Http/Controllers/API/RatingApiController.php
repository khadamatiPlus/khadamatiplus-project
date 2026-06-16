<?php

namespace App\Domains\Rating\Http\Controllers\API;

use App\Domains\Rating\Http\Requests\API\RatingRequest;
use App\Domains\Rating\Services\RatingService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;

class RatingApiController extends APIBaseController
{

    private RatingService $ratingService;

    /**
     * @param RatingService $ratingService
     */
    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }


    public function store(RatingRequest $request)
    {

        $rating = $this->ratingService->store($request->validated());

        return response()->json(['message' => 'Rating stored successfully', 'rating' => $rating], 200);
    }
}
