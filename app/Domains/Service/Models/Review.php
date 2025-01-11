<?php

namespace App\Domains\Service\Models;

use App\Domains\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['service_id', 'customer_id', 'comment', 'rating'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
