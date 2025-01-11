<?php

namespace App\Domains\ContactUsSubmission\Http\Requests\Frontend;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class ContactUsSubmissionRequest.
 */
class ContactUsSubmissionRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @param Request $request
     * @return \string[][]
     */
    public function rules(Request $request): array
    {
        switch ($request->method()) {
            case self::METHOD_POST:
                return [
//                    'name' => ['required', 'max:255'],
//                    'phone_number' => ['required', 'max:255'],
                    'email' => ['required', 'max:255'],
                    'message' => ['required'],
                    'subject' => ['required'],

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

