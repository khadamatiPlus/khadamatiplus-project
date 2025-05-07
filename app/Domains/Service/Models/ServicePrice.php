<?php

namespace App\Domains\Service\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePrice extends Model
{
    protected $fillable = ['title', 'amount','service_id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
