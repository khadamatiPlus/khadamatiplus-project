<?php

namespace App\Domains\Customer\Http\Transformers;

use App\Domains\Customer\Models\Customer;
use App\Domains\Customer\Models\CustomerAddress;


/**
 * Created by Omar
 * Author: Vibes Solutions
 * On: 6/8/2022
 * Class: CustomerTransformer.php
 */
class CustomerAddressTransformer
{

    /**
     * @param CustomerAddress $customerAddress
     * @return array
     */
    public function transform(CustomerAddress $customerAddress): array
    {
        return [
            'id' => $customerAddress->id,
            'name' => $customerAddress->name,
            'phone_number' => $customerAddress->phone_number,
            'email' => $customerAddress->email,
            'latitude' => $customerAddress->latitude,
            'longitude' => $customerAddress->longitude,
            'building_number' => $customerAddress->building_number,
            'floor' => $customerAddress->floor,
            'apartment_number' => $customerAddress->apartment_number,
            'street_name' => $customerAddress->street_name,
        ];
    }
    public function transformCollection($items)
    {
        return $items->map(function ($item) {
            return $this->transform($item);
        })->toArray();
    }

}
