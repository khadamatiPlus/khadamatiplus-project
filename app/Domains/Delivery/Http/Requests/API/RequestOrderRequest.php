<?php

namespace App\Domains\Delivery\Http\Requests\API;

use App\Domains\AppService\Models\AppService;
use App\Domains\Delivery\Services\AppServiceVariantResolver;
use App\Enums\Core\ErrorTypes;
use App\Http\Requests\JsonRequest;
use InvalidArgumentException;

class RequestOrderRequest extends JsonRequest
{
    protected int $errorType = ErrorTypes::ORDER;

    public function authorize(): bool
    {
        return (bool) $this->user()?->customer_id;
    }

    public function rules(): array
    {
        return [
            'app_service_id' => ['required', 'exists:app_services,id'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.name' => ['required', 'string'],
            'variants.*.selected_options' => ['required', 'array', 'min:1'],
            'variants.*.selected_options.*' => ['required', 'string'],
            'payment_method' => ['nullable', 'string', 'in:cash,card,wallet'],
            'coupon_code' => ['nullable', 'string', 'exists:coupons,code'],
            'day' => ['nullable', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'time' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $appService = AppService::query()
                ->where('id', $this->input('app_service_id'))
                ->where('status', 'active')
                ->first();

            if (!$appService) {
                $validator->errors()->add('app_service_id', __('The selected app service is not available.'));

                return;
            }

            try {
                $resolved = app(AppServiceVariantResolver::class)->resolve($appService, $this->input('variants', []));
            } catch (InvalidArgumentException $exception) {
                $validator->errors()->add('variants', $exception->getMessage());
                return;
            }

            // Validate coupon if provided
            if ($this->filled('coupon_code')) {
                $coupon = \App\Domains\Coupon\Models\Coupon::query()
                    ->where('code', $this->input('coupon_code'))
                    ->active()
                    ->valid()
                    ->notExpired()
                    ->first();

                if (!$coupon) {
                    $validator->errors()->add('coupon_code', __('The coupon code is invalid or expired.'));
                    return;
                }

                // Check minimum order amount
                if ($coupon->minimum_order_amount && $resolved['total_price'] < $coupon->minimum_order_amount) {
                    $validator->errors()->add('coupon_code', __('This coupon requires a minimum order amount of :amount.', ['amount' => $coupon->minimum_order_amount]));
                    return;
                }
            }
        });
    }
}
