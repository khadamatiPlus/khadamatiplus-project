<?php

namespace App\Domains\Offer\Services;

use App\Services\BaseService;
use App\Domains\Offer\Models\Offer;

class OfferService extends BaseService
{
    /**
     * @var string $entityName
     */
    protected $entityName = 'Offer';

    /**
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        $this->model = $offer;
    }

    /**
     * Get active offers
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getActiveOffers()
    {
        return $this->model->active()->valid()->with(['coupon', 'category', 'appService']);
    }

    /**
     * Get featured offers
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getFeaturedOffers()
    {
        return $this->model->active()->valid()->featured()->with(['coupon', 'category', 'appService']);
    }

    /**
     * Get offers by category
     *
     * @param int $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getOffersByCategory($categoryId)
    {
        return $this->model->active()->valid()->where('category_id', $categoryId)->with(['coupon', 'category', 'appService']);
    }

    /**
     * Get offers by app service
     *
     * @param int $appServiceId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getOffersByAppService($appServiceId)
    {
        return $this->model->active()->valid()->where('app_service_id', $appServiceId)->with(['coupon', 'category', 'appService']);
    }
}
