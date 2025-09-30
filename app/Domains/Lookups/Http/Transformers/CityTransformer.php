<?php

namespace App\Domains\Lookups\Http\Transformers;

use App\Domains\Lookups\Models\City;

class CityTransformer
{
    public function transform(City $city): array
    {
        return [
            'id' => $city->id,
            'name' => $city->name,
            'areas' => $city->areas->map(function ($area) {
                return [
                    'id' => $area->id,
                    'name' => $area->name,
                ];
            })->toArray(),
        ];
    }

}
