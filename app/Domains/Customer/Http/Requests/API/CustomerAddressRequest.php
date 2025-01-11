<?php

namespace App\Domains\Customer\Http\Requests\API;

use App\Enums\Core\ErrorTypes;
use App\Http\Requests\JsonRequest;
use App\Services\StorageManagerService;
use Illuminate\Validation\Rule;


class CustomerAddressRequest extends JsonRequest
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
           'name' => ['required'],
           'phone_number' => ['required', 'max:350'],
           'email' => ['required', 'max:350'],
           'latitude' => ['required'],
           'longitude' => ['required'],
           'building_number' => ['required', 'max:100'],
           'apartment_number' => ['required', 'max:100'],
           'street_name'  =>  'required',
           'floor' => 'required',
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
