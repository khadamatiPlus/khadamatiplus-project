<?php

namespace App\Domains\Coupon\Http\Controllers\API;

use App\Domains\Coupon\Http\Requests\API\ValidateCouponRequest;
use App\Domains\Coupon\Models\Coupon;
use App\Http\Controllers\APIBaseController;
use OpenApi\Annotations as OA;

class CouponApiController extends APIBaseController
{
    /**
     * Validate Coupon
     *
     * Validate whether a coupon code is active, not expired, and eligible for the provided order amount.
     *
     * @group Coupon Validation
     *
     * @bodyParam code string required The coupon code to validate. Example: SAVE10
     * @bodyParam order_amount numeric required The subtotal/order amount to validate against. Example: 100.00
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Coupon validation successful",
     *   "data": {
     *     "is_valid": true,
     *     "code": "SAVE10",
     *     "discount_type": "percentage",
     *     "discount_value": 10,
     *     "minimum_order_amount": 50,
     *     "maximum_discount_amount": 20,
     *     "reason": null
     *   }
     * }
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Coupon validation successful",
     *   "data": {
     *     "is_valid": false,
     *     "code": "SAVE10",
     *     "discount_type": "percentage",
     *     "discount_value": 10,
     *     "minimum_order_amount": 100,
     *     "maximum_discount_amount": 20,
     *     "reason": "minimum_order_amount_not_met"
     *   }
     * }
     *
     * @response 400 {
     *   "error_type": "general",
     *   "errors": [
     *     {"key": "code", "error": "The coupon code is required."}
     *   ]
     * }
     */
    public function validateCoupon(ValidateCouponRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $coupon = Coupon::query()
                ->active()
                ->valid()
                ->notExpired()
                ->where('code', $request->input('code'))
                ->first();

            $orderAmount = (float) $request->input('order_amount');

            if (!$coupon) {
                return $this->successResponse([
                    'is_valid' => false,
                    'code' => $request->input('code'),
                    'discount_type' => null,
                    'discount_value' => null,
                    'minimum_order_amount' => null,
                    'maximum_discount_amount' => null,
                    'reason' => 'coupon_not_found',
                ], 'Coupon validation successful');
            }

            $reason = null;

            if ($coupon->minimum_order_amount && $orderAmount < (float) $coupon->minimum_order_amount) {
                $reason = 'minimum_order_amount_not_met';
            }

            $isValid = $reason === null;

            return $this->successResponse([
                'is_valid' => $isValid,
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'discount_value' => (float) $coupon->discount_value,
                'minimum_order_amount' => (float) $coupon->minimum_order_amount,
                'maximum_discount_amount' => (float) $coupon->maximum_discount_amount,
                'reason' => $reason,
            ], 'Coupon validation successful');
        } catch (\Throwable $exception) {
            report($exception);

            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }
}
