<?php

namespace App\Domains\AppService\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Lookups\Models\Category;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppService extends BaseModel
{
    use SoftDeletes;

    protected $table = 'app_services';

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'sub_category_id',
        'images',
        'video_url',
        'base_price',
        'currency',
        'price_type',
        'discount',
        'delivery_time',
        'delivery_time_unit',
        'free_revisions',
        'variants',
        'customer_requirements',
        'requirements_mandatory',
        'tags',
        'seo_description',
        'language',
        'scope',
        'availability_days',
        'max_concurrent_orders',
        'expiry_date',
        'is_featured',
        'is_urgent',
        'is_online',
        'status',
        'visibility',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
        'availability_days' => 'array',
        'variants' => 'array',
        'base_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'requirements_mandatory' => 'boolean',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
        'is_online' => 'boolean',
        'expiry_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function merchants()
    {
        return $this->belongsToMany(
            \App\Domains\Merchant\Models\Merchant::class,
            'app_service_merchant',
            'app_service_id',
            'merchant_id'
        );
    }

    public function orders()
    {
        return $this->hasMany(\App\Domains\Delivery\Models\Order::class, 'app_service_id');
    }
}
