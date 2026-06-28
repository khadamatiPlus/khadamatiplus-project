<?php

namespace App\Domains\Offer\Http\Transformers;

use App\Domains\Offer\Models\Offer;

class OfferTransformer
{
    /**
     * @param Offer $offer
     * @return array
     */
    public function transform(Offer $offer): array
    {
        return [
            'id' => $offer->id,
            'title' => $offer->title,
            'description' => $offer->description,
            'image' => $offer->image_url,
            'start_date' => $offer->start_date ? $offer->start_date->format('Y-m-d H:i:s') : null,
            'end_date' => $offer->end_date ? $offer->end_date->format('Y-m-d H:i:s') : null,
            'is_active' => $offer->is_active,
            'is_featured' => $offer->is_featured,
            'category_id' => $offer->category_id??null,
            'app_service_id' => $offer->app_service_id??null,
            'coupon' => $this->getCouponData($offer),

        ];
    }

    /**
     * Get coupon data if exists.
     *
     * @param Offer $offer
     * @return array|null
     */
    private function getCouponData(Offer $offer)
    {
        if ($offer->coupon) {
            return [
                'id' => $offer->coupon->id,
                'code' => $offer->coupon->code,
                'discount_type' => $offer->coupon->discount_type,
                'discount_value' => $offer->coupon->discount_value,
            ];
        }
        return null;
    }

    /**
     * Get category data if exists.
     *
     * @param Offer $offer
     * @return array|null
     */
    private function getCategoryData(Offer $offer)
    {
        if ($offer->category) {
            return [
                'id' => $offer->category->id,
                'name' => $offer->category->name,
                'slug' => $offer->category->slug,
            ];
        }
        return null;
    }

    /**
     * Get app service data if exists.
     *
     * @param Offer $offer
     * @return array|null
     */
    private function getAppServiceData(Offer $offer)
    {
        if ($offer->appService) {
            return [
                'id' => $offer->appService->id,
                'name' => $offer->appService->name,
                'slug' => $offer->appService->slug,
            ];
        }
        return null;
    }
}
