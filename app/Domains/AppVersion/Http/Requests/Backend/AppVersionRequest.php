<?php

namespace App\Domains\AppVersion\Http\Requests\Backend;

use App\Domains\AppVersion\Models\AppVersion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class AppVersionRequest.
 */
class AppVersionRequest extends FormRequest
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
            case self::METHOD_PATCH:
                return [
                    'id' => ['required', 'exists:app_versions,id'],
                    'current_version_android' => ['required', 'max:255'],
                    'current_version_ios' => ['required', 'max:255'],
                    'current_version_huawei' => ['required', 'max:255'],
                    'customer_version_android' => ['required', 'max:255'],
                    'customer_version_ios' => ['required', 'max:255'],
                    'customer_version_huawei' => ['required', 'max:255'],
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

