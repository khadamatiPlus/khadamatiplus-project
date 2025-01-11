<?php

namespace App\Domains\Auth\Http\Requests\API;

use App\Enums\Core\ErrorTypes;
use App\Http\Requests\JsonRequest;
use App\Services\StorageManagerService;
use Illuminate\Validation\Rule;

/**
 * Created by Omar
 * Author: Vibes Solutions
 * On: 6/8/2022
 * Class: RegisterCustomerRequest.php
 */
class RegisterCustomerRequest extends JsonRequest
{

    /**
     * @var int $errorType
     */
    protected int $errorType = ErrorTypes::AUTH;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobile_number' => [
                'required',
                function ($attribute, $value, $fail) {
                    $normalizedValue = preg_replace('/^00/', '', $value);
                    $exists = \DB::table('users')
                        ->whereNotNull('customer_id')
                        ->whereRaw('REPLACE(mobile_number, "00", "") = ?', [$normalizedValue])
                        ->exists();
                    if ($exists) {
                        $fail('The mobile number has already been taken.');
                    }
                },
            ],
            'email' => [
                'nullable','email',
            ],
            'name' => ['required', 'max:350'],
            'password' => ['required'],
            'fcm_token' => ['nullable'],
//            'firebase_auth_token' => ['required', 'string'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'firebase_auth_token.required' => __('It seems you didn\'t complete the OTP verification steps')
        ];
    }
    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance(): \Illuminate\Contracts\Validation\Validator
    {
        $this->mobileNumberFormat();

        return parent::getValidatorInstance();
    }

    /**
     * @return void
     */
    protected function mobileNumberFormat()
    {
        $this->request->set('mobile_number',(int)$this->request->get('mobile_number'));
    }
}
