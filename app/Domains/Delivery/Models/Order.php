<?php

namespace App\Domains\Delivery\Models;

use App\Domains\Customer\Models\Customer;
use App\Domains\Delivery\Events\OrderCreated;
use App\Domains\Delivery\Events\OrderUpdated;
use App\Domains\Delivery\Models\Traits\Method\OrderMethod;
use App\Domains\AppService\Models\AppService;
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
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => OrderCreated::class,
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'customer_phone',
        'merchant_id',
        'customer_id',
        'service_id',
        'app_service_id',
        'selected_variants',
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

    protected $casts = [
        'selected_variants' => 'array',
    ];

    /**
     * Boot the model and add event listeners
     */
    public static function boot()
    {
        parent::boot();

        static::updating(function ($order) {
            if ($order->isDirty('status')) {
                event(new OrderUpdated($order, $order->getOriginal('status'), $order->status));
            }
        });
    }

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

    public function appService(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AppService::class, 'app_service_id');
    }

    public function scopeVisibleToMerchant($query, int $merchantId)
    {
        return $query->where(function ($query) use ($merchantId) {
            $query->where('merchant_id', $merchantId)
                ->orWhere(function ($query) use ($merchantId) {
                    $query->whereNull('merchant_id')
                        ->where('status', 'pending')
                        ->whereHas('appService.merchants', function ($query) use ($merchantId) {
                            $query->where('merchants.id', $merchantId);
                        });
                });
        });
    }

    public function getDisplayName(): string
    {
        return $this->appService?->name
            ?? $this->service?->title
            ?? __('Order #:id', ['id' => $this->id]);
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
