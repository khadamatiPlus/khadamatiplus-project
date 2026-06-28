<?php

namespace App\Domains\Wallet\Services;

use App\Domains\Wallet\Models\Wallet;
use App\Domains\Wallet\Models\WalletTransaction;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WalletService
{
    public function __construct(protected Wallet $wallet, protected WalletTransaction $transaction)
    {
    }

    public function ensureWallet(string $ownerType, string|int $ownerId, string|int $type = 'default'): Wallet
    {
        return $this->wallet->newQuery()->firstOrCreate(
            [
                'owner_type' => $ownerType,
                'owner_id' => (string) $ownerId,
                'type' => (string) $type,
            ],
            [
                'balance' => 0.00,
            ]
        );
    }

    public function credit(Wallet $wallet, float|int|string $amount, array $meta = []): WalletTransaction
    {
        return $this->applyMovement($wallet, 'credit', $amount, $meta);
    }

    public function debit(Wallet $wallet, float|int|string $amount, array $meta = []): WalletTransaction
    {
        return $this->applyMovement($wallet, 'debit', $amount, $meta);
    }

    protected function applyMovement(Wallet $wallet, string $direction, float|int|string $amount, array $meta = []): WalletTransaction
    {
        $amount = (float) $amount;

        if ($amount <= 0) {
            throw new InvalidArgumentException(__('The amount must be greater than zero.'));
        }

        return DB::transaction(function () use ($wallet, $direction, $amount, $meta): WalletTransaction {
            $wallet->refresh();

            if ($direction === 'debit' && (float) $wallet->balance < $amount) {
                throw new GeneralException(__('Insufficient wallet balance.'));
            }

            $newBalance = $direction === 'credit'
                ? (float) $wallet->balance + $amount
                : (float) $wallet->balance - $amount;

            $wallet->balance = number_format($newBalance, 2, '.', '');
            $wallet->save();

            return $wallet->transactions()->create([
                'direction' => $direction,
                'amount' => number_format($amount, 2, '.', ''),
                'running_balance' => number_format($wallet->balance, 2, '.', ''),
                'description' => $meta['description'] ?? null,
                'reference_type' => $meta['reference_type'] ?? null,
                'reference_id' => $meta['reference_id'] ?? null,
                'meta' => $meta,
            ]);
        });
    }
}
