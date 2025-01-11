<?php

namespace App\Domains\Service\Models;

use App\Domains\Delivery\Models\Order;
use Illuminate\Database\Eloquent\Model;

class ServiceOption extends Model
{
    protected $fillable = ['service_id', 'title', 'value', 'type', 'value_type','created_by_id','updated_by_id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_service_option', 'service_option_id', 'order_id');
    }
}
