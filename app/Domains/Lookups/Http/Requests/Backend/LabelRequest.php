<?php
namespace App\Domains\Lookups\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class LabelRequest extends FormRequest
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
                    'key' => ['required', 'max:255','unique:labels'],
                    'value' => ['required', 'max:255'],
                    'value_ar' => ['required', 'max:255'],
                ];
            case self::METHOD_PATCH:
                return [
                    'id' => ['required', 'exists:labels,id'],
                    'value' => ['required', 'max:255'],
                    'value_ar' => ['required', 'max:255'],
                ];
            case self::METHOD_DELETE:
            default:
                return [
                    'id' => ['required', 'exists:labels,id']
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


