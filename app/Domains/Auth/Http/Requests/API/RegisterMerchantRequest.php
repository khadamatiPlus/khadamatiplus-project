<?php

namespace App\Domains\Auth\Http\Requests\API;
use App\Domains\Lookups\Models\Area;
use App\Domains\Lookups\Models\City;
use App\Enums\Core\ErrorTypes;
use App\Http\Requests\JsonRequest;
use App\Services\StorageManagerService;
use Illuminate\Validation\Rule;

class RegisterMerchantRequest extends JsonRequest
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
                    // Normalize the number by removing the '00' prefix if it exists
                    $normalizedValue = preg_replace('/^00/', '', $value);

                    // Check for uniqueness in the 'users' table
                    $exists = \DB::table('users')
                        ->whereNotNull('merchant_id')
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
            'country_id' => ['required', 'exists:countries,id'],
            'city_id' => [
                'required',
                'exists:cities,id',
                function ($attribute, $value, $fail) {
                    // Check if the city belongs to the selected country
                    if (!City::where('id', $value)->where('country_id', request('country_id'))->exists()) {
                        $fail(__('The selected city does not belong to the chosen country.'));
                    }
                },
            ],
            'area_id' => [
                'required',
                'exists:areas,id',
                function ($attribute, $value, $fail) {
                    // Check if the area belongs to the selected city
                    if (!Area::where('id', $value)->where('city_id', request('city_id'))->exists()) {
                        $fail(__('The selected area does not belong to the chosen city.'));
                    }
                },
            ],

            'is_verified' => ['sometimes','in:0,1'],
            'profile_pic' => ['nullable', 'mimes:'.implode(',',StorageManagerService::$allowedImages)],
            'latitude' => ['required', 'max:350'],
            'longitude' => ['required', 'max:350'],
            'password' => ['required'],
            'id_image' => ['nullable'],
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
            'country_code.unique' => __('Mobile number is already registered'),
            'name.unique' => __('The store name is not available'),
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
