<?php

namespace App\Domains\Delivery\Http\Requests\API;

use App\Enums\Core\ErrorTypes;
use App\Http\Requests\JsonRequest;

class UpdateOrderStatusByCustomerRequest extends JsonRequest
{
    protected int $errorType = ErrorTypes::ORDER;

    public function authorize(): bool
    {
        return (bool) $this->user()?->customer_id;
    }

    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'status' => ['required', 'in:accepted,on_the_way,on_progress,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
