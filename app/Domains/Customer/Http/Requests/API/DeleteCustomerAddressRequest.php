<?php

namespace App\Domains\Customer\Http\Requests\API;

use App\Domains\Customer\Models\CustomerAddress;
use App\Enums\Core\ErrorTypes;
use App\Http\Requests\JsonRequest;
use App\Services\StorageManagerService;
use Illuminate\Validation\Rule;

/**
 * Created by Omar
 * Author: Vibes Solutions
 * On: 6/12/2022
 * Class: UpdateCustomerAddressRequest.php
 */
class DeleteCustomerAddressRequest extends JsonRequest
{
    /**
     * @var int $errorType
     */
    protected int $errorType = ErrorTypes::CUSTOMER;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->isCustomer();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
       return [
           'customer_address_id' => ['required', 'integer', Rule::in(CustomerAddress::query()->where('customer_id',$this->user()->customer_id)->pluck('id'))],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }
}
