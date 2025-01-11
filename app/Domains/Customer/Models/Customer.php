<?php

namespace App\Domains\Customer\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Captain\Models\Traits\Attribute\CaptainAttribute;
use App\Domains\Captain\Models\Traits\Scope\CaptainScope;
use App\Domains\Lookups\Models\VehicleType;
use App\Domains\Service\Models\Service;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Captain\Models\CaptainCity;


class Customer extends BaseModel
{
    use
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
    protected $fillable = [
        'profile_id',
        'created_by_id',
        'updated_by_id',
        'name',
        'profile_pic',
        'is_verified',
        'is_instant_delivery',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
        'deleted_at',
        'customer_address_id',
    ];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function captainCities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CaptainCity::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'profile_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdById(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function favoriteServices()
    {
        return $this->belongsToMany(Service::class, 'service_favorites')->withTimestamps();
    }

    public function defaultAddress()
    {
        return $this->belongsTo(CustomerAddress::class, 'customer_address_id');
    }

}
