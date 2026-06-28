<?php

namespace App\Domains\Wallet\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends BaseModel
{
    use SoftDeletes;

    protected $table = 'wallets';

    protected $fillable = [
        'owner_type',
        'owner_id',
        'type',
        'balance',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function owner()
    {
        return $this->morphTo();
    }
}
