<?php

namespace App\Domains\Delivery\Http\Transformers;

use App\Domains\Delivery\Models\Order;
use App\Domains\Service\Http\Transformers\ServiceTransformer;
use App\Enums\Core\OrderStatuses;

class OrderTransformer
{

    /**
     * @param Order $order
     * @return array
     */
    public function transform(Order $order): array
    {
        return [
            'id' => $order->id,
            'customer_phone' => $order->customer_phone??"",
            'price' => numberFormatPrecision($order->price)??"",
            'total_price' => numberFormatPrecision($order->total_price)??"",
            'merchant_name' => $order->merchant->name??"",
            'merchant_mobile_number' => $order->merchant->profile->mobile_number??"",
            'service' => !empty($order->service_id)?(new ServiceTransformer())->transform($order->service):null,
            'customer_id' => $order->customer_id,
            'customer_name' => !empty($order->customer_id)?$order->customer->name??"":'',
            'status' => $order->status,
            'created_at' => $order->created_at,
            'customer_requested_at' => $order->customer_requested_at,
            'merchant_accepted_at' => $order->merchant_accepted_at,
            'merchant_arrived_at' => $order->merchant_arrived_at,
            'merchant_started_trip_at' => $order->merchant_started_trip_at,
            'merchant_on_the_way_at' => $order->merchant_on_the_way_at,
            'merchant_at' => $order->delivered_at,
            'notes' => $order->notes,
            'last_update' => $order->updated_at,
            'latitude' => $order->latitude,
            'longitude' => $order->longitude,
        ];
    }

//    /**
//     * @param Order $order
//     * @return array
//     */
//    public function transformForMerchantReport(Order $order): array
//    {
//        return [
//            'id' => $order->id,
//            'order_amount' => numberFormatPrecision($order->order_amount),
//            'delivery_amount' => numberFormatPrecision($order->delivery_amount),
//            'merchant_percentage' => numberFormatPrecision($order->merchant_percentage),
//            'merchant_revenue' => numberFormatPrecision($order->merchant_revenue),
//            'total_amount_after_deduct' => numberFormatPrecision($order->total_amount_after_deduct),
//            'total_amount' => numberFormatPrecision($order->total_amount),
//            'order_reference' => $order->order_reference,
//            'payment_type' => $order->payment_type,
//            'delivered_at' => $order->delivered_at
//        ];
//    }


}

