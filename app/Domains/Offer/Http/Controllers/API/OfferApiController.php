<?php

namespace App\Domains\Offer\Http\Controllers\API;

use App\Domains\Offer\Http\Transformers\OfferTransformer;
use App\Domains\Offer\Services\OfferService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class OfferApiController extends APIBaseController
{
    private $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * Get Offers
     *
     * Retrieve all active offers with optional filters.
     *
     * @group Offers
     *
     * @queryParam featured boolean Return featured offers only. Example: true
     * @queryParam category_id integer Filter offers by category ID. Example: 1
     * @queryParam app_service_id integer Filter offers by app service ID. Example: 5
     *
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Summer Offer",
     *       "discount_percentage": 20
     *     }
     *   ]
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Internal Server Error"
     * }
     */

    public function getOffers(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $query = $this->offerService->getActiveOffers();

            // Filter by featured
            if ($request->has('featured') && $request->boolean('featured')) {
                $query = $this->offerService->getFeaturedOffers();
            }

            // Filter by category
            if ($request->has('category_id')) {
                $query = $this->offerService->getOffersByCategory($request->category_id);
            }

            // Filter by app service
            if ($request->has('app_service_id')) {
                $query = $this->offerService->getOffersByAppService($request->app_service_id);
            }

            $offers = $query->get();

            return $this->successResponse(
                $offers->transform(function ($offer) {
                    return (new OfferTransformer())->transform($offer);
                })
            );
        } catch (\Exception $exception) {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }

    /**
     * Get Offer By ID
     *
     * Retrieve a specific active offer by its ID.
     *
     * @group Offers
     *
     * @urlParam id integer required The ID of the offer. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "id": 1,
     *     "title": "Summer Offer",
     *     "discount_percentage": 20
     *   }
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Offer not found"
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Internal Server Error"
     * }
     */

    public function getOfferById($id): \Illuminate\Http\JsonResponse
    {
        try {
            $offer = $this->offerService->getActiveOffers()->find($id);

            if (!$offer) {
                return $this->inputValidationErrorResponse('Offer not found');
            }

            return $this->successResponse(
                (new OfferTransformer())->transform($offer)
            );
        } catch (\Exception $exception) {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }
}
