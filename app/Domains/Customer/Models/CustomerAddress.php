<?php

namespace App\Domains\Customer\Models;

use App\Domains\Customer\Models\Traits\Method\CustomerAddressMethod;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $customer_id
 * @property integer $created_by_id
 * @property integer $updated_by_id
 * @property string $address_type
 * @property string $address_line_one
 * @property string $address_line_two
 * @property string $country_code
 * @property string $phone_number
 * @property string $building
 * @property string $floor
 * @property string $latitude
 * @property string $longitude
 * @property string $place_id
 * @property string $postal_code
 * @property string $additional_directions
 * @property string $location_name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Customer $customer
 */
class CustomerAddress extends BaseModel
{
    use
//        CustomerAddressMethod,
        SoftDeletes;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'created_by_id', 'updated_by_id', 'name', 'phone_number', 'email', 'latitude', 'longitude', 'building_number', 'apartment_number', 'latitude', 'longitude', 'street_name', 'floor', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

}
