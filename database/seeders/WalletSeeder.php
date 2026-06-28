<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\User;
use App\Domains\Customer\Models\Customer;
use App\Domains\Merchant\Models\Merchant;
use App\Domains\Wallet\Models\Wallet;
use App\Domains\Wallet\Services\WalletService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $walletService = app(WalletService::class);

        $this->seedForUsers($walletService);
        $this->seedForCustomers($walletService);
        $this->seedForMerchants($walletService);
    }

    protected function seedForUsers(WalletService $walletService)
    {
        $users = User::query()->get();

        foreach ($users as $user) {
            $wallet = $walletService->ensureWallet(User::class, $user->id, 'default');
            $this->seedTransactions($walletService, $wallet, 'user-'.$user->id);
        }
    }

    protected function seedForCustomers(WalletService $walletService)
    {
        if (! Schema::hasTable('customers')) {
            return;
        }

        $customers = Customer::query()->get();

        foreach ($customers as $customer) {
            $wallet = $walletService->ensureWallet(Customer::class, $customer->id, 'default');
            $this->seedTransactions($walletService, $wallet, 'customer-'.$customer->id);
        }
    }

    protected function seedForMerchants(WalletService $walletService)
    {
        if (! Schema::hasTable('merchants')) {
            return;
        }

        $merchants = Merchant::query()->get();

        foreach ($merchants as $merchant) {
            $wallet = $walletService->ensureWallet(Merchant::class, $merchant->id, 'default');
            $this->seedTransactions($walletService, $wallet, 'merchant-'.$merchant->id);
        }
    }

    protected function seedTransactions(WalletService $walletService, Wallet $wallet, string $label)
    {
        if ($wallet->transactions()->exists()) {
            return;
        }

        $walletService->credit($wallet, 150.00, [
            'description' => 'Seed credit for '.$label,
            'reference_type' => 'seeder',
            'reference_id' => 'wallet-seed',
            'meta' => ['source' => 'seed'],
        ]);

        $walletService->debit($wallet, 35.50, [
            'description' => 'Seed debit for '.$label,
            'reference_type' => 'seeder',
            'reference_id' => 'wallet-seed',
            'meta' => ['source' => 'seed'],
        ]);
    }
}
