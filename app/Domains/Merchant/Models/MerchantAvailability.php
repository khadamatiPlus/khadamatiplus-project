<?php

namespace App\Domains\Merchant\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantAvailability extends Model
{
    protected $table='merchant_availability';
    protected $fillable = ['merchant_id', 'day', 'time'];


    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Merchant::class,'merchant_id');
    }
}
