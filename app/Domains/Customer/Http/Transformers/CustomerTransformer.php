<?php

namespace App\Domains\Customer\Http\Transformers;

use App\Domains\Customer\Models\Customer;
use App\Enums\Core\StoragePaths;

class CustomerTransformer
{


    public function transform(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'mobile_number' => $customer->profile->mobile_number,
            'email' => $customer->profile->email,
            'name' => $customer->name,
            'profile_pic' => !empty($customer->profile_pic)?storageBaseLink(StoragePaths::CUSTOMER_PROFILE_PIC.$customer->profile_pic):'',

        ];
    }
}
