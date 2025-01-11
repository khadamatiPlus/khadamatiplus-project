<?php

namespace App\Domains\Lookups\Models;

use App\Models\BaseModel;
use App\Models\Traits\CreatedBy;
use Illuminate\Database\Eloquent\SoftDeletes;

class Label extends BaseModel
{
    use SoftDeletes,CreatedBy;
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['created_by_id', 'updated_by_id' ,'key', 'value', 'value_ar', 'created_at', 'updated_at', 'deleted_at'];





}
