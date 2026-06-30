<?php

namespace Tests\Feature;

use App\Domains\Coupon\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_validates_an_active_coupon_for_the_given_order_amount(): void
    {
        Coupon::create([
            'code' => 'SAVE10',
            'description' => '10% off',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'minimum_order_amount' => 50,
            'maximum_discount_amount' => 20,
            'usage_limit' => 10,
            'used_count' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/validate-coupon', [
            'code' => 'SAVE10',
            'order_amount' => 100,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.is_valid', true)
            ->assertJsonPath('data.code', 'SAVE10')
            ->assertJsonPath('data.discount_type', 'percentage');
    }

    public function test_it_returns_invalid_when_coupon_does_not_meet_minimum_order_amount(): void
    {
        Coupon::create([
            'code' => 'SAVE20',
            'description' => '20% off',
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'minimum_order_amount' => 100,
            'maximum_discount_amount' => 50,
            'usage_limit' => 10,
            'used_count' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/validate-coupon', [
            'code' => 'SAVE20',
            'order_amount' => 50,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.is_valid', false)
            ->assertJsonPath('data.reason', 'minimum_order_amount_not_met');
    }
}
