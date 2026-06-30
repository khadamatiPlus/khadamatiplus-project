<?php

namespace App\Domains\Coupon\Http\Requests\API;

use App\Enums\Core\ErrorTypes;
use App\Http\Requests\JsonRequest;

class ValidateCouponRequest extends JsonRequest
{
    /**
     * @var int $errorType
     */
    protected int $errorType = ErrorTypes::GENERAL;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'order_amount' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'The coupon code is required.',
            'order_amount.required' => 'The order amount is required.',
            'order_amount.numeric' => 'The order amount must be a numeric value.',
            'order_amount.min' => 'The order amount must be greater than or equal to 0.',
        ];
    }
}
