<?php

namespace App\Domains\Delivery\Models;

use App\Domains\Customer\Models\Customer;
use App\Domains\Delivery\Models\Traits\Method\OrderMethod;
use App\Domains\Lookups\Models\City;
use App\Domains\Service\Models\Service;
use App\Domains\Service\Models\ServiceOption;
use App\Models\BaseModel;
use App\Domains\Merchant\Models\Merchant;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends BaseModel
{
    use OrderMethod,SoftDeletes;
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = [
        'customer_phone',
        'merchant_id',
        'customer_id',
        'service_id',
        'created_by_id',
        'updated_by_id',
        'price',
        'total_price',
        'order_reference',
        'status',
        'day',
        'time',
        'customer_requested_at',
        'merchant_accepted_at',
        'merchant_arrived_at',
        'merchant_started_trip_at',
        'merchant_on_the_way_at',
        'delivered_at',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
        'deleted_at',
        'cancel_reason',
        'cancelled_at',
        'cancelled_by_id',
        'notes',


    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Service::class,'service_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cancelledBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'cancelled_by_id');
    }
    public function options()
    {
        return $this->belongsToMany(ServiceOption::class, 'order_service_option', 'order_id', 'service_option_id');
    }
}
