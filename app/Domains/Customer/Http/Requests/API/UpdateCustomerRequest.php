<?php

namespace App\Domains\Customer\Http\Requests\API;

use App\Enums\Core\ErrorTypes;
use App\Http\Requests\JsonRequest;
use App\Services\StorageManagerService;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends JsonRequest
{
    /**
     * @var int $errorType
     */
    protected int $errorType = ErrorTypes::CAPTAIN;

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
            'name' => !empty($this->name)?['max:350']:'',
            'profile_pic' => !empty($this->personal_photo)?['sometimes', 'mimes:'.implode(',',StorageManagerService::$allowedImages)]:'',
            'email' => !empty($this->name)?[
                'sometimes',
                'email',
                Rule::unique('users')->ignore($this->owner_id)->where(function ($query) {
                    return $query->whereNotNull('merchant_id');
                })
            ]:'',

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
