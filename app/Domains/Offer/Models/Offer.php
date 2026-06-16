<?php

namespace App\Domains\Offer\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Coupon\Models\Coupon;
use App\Domains\AppService\Models\AppService;
use App\Domains\Lookups\Models\Category;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends BaseModel
{
    use SoftDeletes;

    protected $table = 'offers';

    protected $fillable = [
        'title',
        'description',
        'coupon_id',
        'category_id',
        'app_service_id',
        'image',
        'start_date',
        'end_date',
        'is_active',
        'is_featured',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function appService()
    {
        return $this->belongsTo(AppService::class, 'app_service_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeValid($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
        });
    }

    public function isValid()
    {
        $now = now();
        
        // Check if active
        if (!$this->is_active) {
            return false;
        }

        // Check start date
        if ($this->start_date && $this->start_date > $now) {
            return false;
        }

        // Check end date
        if ($this->end_date && $this->end_date < $now) {
            return false;
        }

        return true;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
