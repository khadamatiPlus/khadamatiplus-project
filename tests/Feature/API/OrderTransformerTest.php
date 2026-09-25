<?php

namespace Tests\Feature\API;

use App\Domains\Delivery\Http\Transformers\OrderTransformer;
use App\Domains\Delivery\Models\Order;
use Tests\TestCase;

class OrderTransformerTest extends TestCase
{
    public function test_transform_returns_flag_when_merchant_has_accepted_order(): void
    {
        $order = new Order([
            'merchant_accepted_at' => now()->toDateTimeString(),
        ]);

        $order->setRelation('merchant', null);
        $order->setRelation('appService', null);
        $order->setRelation('service', null);
        $order->setRelation('customer', null);

        $data = (new OrderTransformer())->transform($order);

        $this->assertArrayHasKey('is_accepted_by_merchant', $data);
        $this->assertTrue($data['is_accepted_by_merchant']);
    }

    public function test_transform_returns_false_when_merchant_has_not_accepted_order(): void
    {
        $order = new Order();

        $order->setRelation('merchant', null);
        $order->setRelation('appService', null);
        $order->setRelation('service', null);
        $order->setRelation('customer', null);

        $data = (new OrderTransformer())->transform($order);

        $this->assertArrayHasKey('is_accepted_by_merchant', $data);
        $this->assertFalse($data['is_accepted_by_merchant']);
    }
}
