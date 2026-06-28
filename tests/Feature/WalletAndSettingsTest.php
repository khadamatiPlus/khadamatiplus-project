<?php

namespace Tests\Feature;

use App\Domains\Setting\Services\SettingService;
use App\Domains\Wallet\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletAndSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_app_profit_percentage_can_be_saved_and_retrieved(): void
    {
        $settingService = app(SettingService::class);

        $settingService->set('app_profit_percentage', 12.5);

        $this->assertSame('12.5', getSettingByKey('app_profit_percentage'));
        $this->assertDatabaseHas('settings', [
            'key' => 'app_profit_percentage',
            'value' => '12.5',
        ]);
    }

    public function test_wallet_credit_and_debit_update_balance_and_transactions(): void
    {
        $walletService = app(WalletService::class);

        $wallet = $walletService->ensureWallet('app', 'app', 1);

        $walletService->credit($wallet, 100.50, ['description' => 'Initial top-up']);
        $walletService->debit($wallet, 25.25, ['description' => 'Service fee']);

        $wallet->refresh();

        $this->assertSame('75.25', (string) $wallet->balance);
        $this->assertDatabaseHas('wallet_transactions', [
            'wallet_id' => $wallet->id,
            'direction' => 'credit',
            'amount' => '100.50',
        ]);
        $this->assertDatabaseHas('wallet_transactions', [
            'wallet_id' => $wallet->id,
            'direction' => 'debit',
            'amount' => '25.25',
        ]);
    }
}
