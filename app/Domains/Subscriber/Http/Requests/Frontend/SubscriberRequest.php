<?php
namespace App\Domains\Subscriber\Http\Requests\Frontend;
use anhskohbo\NoCaptcha\Facades\NoCaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class SubscriberRequest.
 */
class SubscriberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        switch ($request->method()) {
            case self::METHOD_POST:
                return [
                    'email' => ['required','email','unique:subscribers'],
                ];
        }
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
        ];
    }
}
