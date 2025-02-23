<?php

namespace App\Domains\Merchant\Http\Transformers;

use App\Domains\Delivery\Models\Order;
use App\Domains\Merchant\Models\Merchant;
use App\Domains\Service\Http\Transformers\ServiceTransformer;
use App\Enums\Core\StoragePaths;
use Illuminate\Support\Carbon;

class MerchantTransformer
{

    /**
     * @param Merchant $merchant
     * @return array
     */
    public function transform(Merchant $merchant): array
    {
        return [
            'id' => $merchant->id,
            'mobile_number' => $merchant->profile->mobile_number??null,
            'email' => $merchant->profile->email??null,
            'name' => $merchant->name,
            'city_id' => $merchant->city_id,
            'country_id' => $merchant->country_id,
            'area_id' => $merchant->area_id,
            'city_name' => $merchant->city->name??null,
            'country_name' => $merchant->country->name??null,
            'area_name' => $merchant->area->name??null,
            'longitude' => $merchant->longitude??null,
            'latitude' => $merchant->latitude??null,
            'id_image' =>$merchant->id_image??null,
            'rating' => $merchant->services()->with('reviews')->get()->avg(function ($service) {
                return $service->reviews()->avg('rating'); // Average rating for all services
            }),
            'user_ratings_count' => $merchant->services()->with('reviews')->get()->sum(function ($service) {
                return $service->reviews()->count(); // Total number of reviews for all services
            }),
            'profile_pic' => !empty($merchant->profile_pic)?storageBaseLink(StoragePaths::MERCHANT_PROFILE_PIC.$merchant->profile_pic):'',
            'services' => $merchant->services->map(function ($service) {
                return (new ServiceTransformer())->transformMerchant($service);
            }),
            'availability' => [
                'days' => $merchant->availability->pluck('day')->unique()->values(),
                'times' => $merchant->availability->pluck('time')->unique()->values(),
            ],

            ];
    }
}
