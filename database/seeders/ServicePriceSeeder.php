<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Service\Models\Service;
use App\Domains\Service\Models\ServicePrice;

class ServicePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all services
        $services = Service::all();

        foreach ($services as $service) {
            $amount = $service->sale_price ?? $service->price;

            if (is_string($amount)) {
            $amount = (float) preg_replace('/[^0-9.]/', '', $amount);
        }

            ServicePrice::create([
                'service_id' => $service->id,
                'title' => 'أخرى',
                'amount' => $amount,
            ]);
        }
    }
}
