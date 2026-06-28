<?php

namespace App\Domains\Wallet\Models;

use App\Models\BaseModel;

class WalletTransaction extends BaseModel
{
    protected $table = 'wallet_transactions';

    protected $fillable = [
        'wallet_id',
        'direction',
        'amount',
        'running_balance',
        'description',
        'reference_type',
        'reference_id',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'running_balance' => 'decimal:2',
        'meta' => 'array',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
