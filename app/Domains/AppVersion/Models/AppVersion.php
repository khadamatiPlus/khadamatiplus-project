<?php

namespace App\Domains\AppVersion\Models;

use App\Models\BaseModel;
use App\Models\Traits\CreatedBy;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppVersion extends BaseModel
{
    use SoftDeletes, CreatedBy;

    protected $keyType = 'integer';

    protected $fillable = [
        'created_by_id',
        'current_version_android',
        'current_version_ios',
        'current_version_huawei',
        'customer_version_huawei',
        'customer_version_ios',
        'customer_version_android',
        'updated_by_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the first AppVersion record or create one if none exists.
     *
     * @return AppVersion
     */
    public static function getOrCreateDefault()
    {
        return static::firstOrCreate(
            ['id' => 1], // Assuming you want to use ID 1 as the default
            [
                'current_version_android' => '1.0.0',
                'current_version_ios' => '1.0.0',
                'current_version_huawei' => '1.0.0',
                'created_by_id' => auth()->id() ?? 1, // Default to user 1 if no auth
                'updated_by_id' => auth()->id() ?? 1, // Default to user 1 if no auth
            ]
        );
    }
}
