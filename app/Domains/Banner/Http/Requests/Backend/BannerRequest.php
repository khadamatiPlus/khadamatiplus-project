<?php
namespace App\Domains\Banner\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\StorageManagerService;

class BannerRequest extends FormRequest
{
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
    public function rules(Request $request): array
    {
        $imageRules = ['mimes:' . implode(',', StorageManagerService::$allowedImages), 'max:5120']; // 5MB limit
        $type = $request->input('type'); // Getting the 'type' from request

        $rules = [
            'title' => ['required', 'string', 'max:350'],
            'title_ar' => ['required', 'string', 'max:350'],
            'link' => ['nullable', 'url'],
            'image' => array_merge(['nullable'], $imageRules),
            'type' => ['required', Rule::in(['category', 'service', 'merchant','link'])], // Ensure type is valid
        ];

        // Add conditional rules based on the 'type' field
        if ($type == 'category') {
            $rules['category_id'] = ['required', 'exists:categories,id'];
        } elseif ($type == 'service') {
            $rules['service_id'] = ['required', 'exists:services,id'];
        } elseif ($type == 'merchant') {
            $rules['merchant_id'] = ['required', 'exists:merchants,id'];
        }

        switch ($request->method()) {
            case self::METHOD_POST:
                return $rules;
            case self::METHOD_PATCH:
                return array_merge($rules, [
                    'id' => ['required', 'exists:banners,id'],
                ]);
            case self::METHOD_DELETE:
            default:
                return [
                    'id' => ['required', 'exists:banners,id']
                ];
        }
    }

    /**
     * Custom validation messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => __('The English title is required.'),
            'title_ar.required' => __('The Arabic title is required.'),
            'title.max' => __('The English title may not be greater than 350 characters.'),
            'title_ar.max' => __('The Arabic title may not be greater than 350 characters.'),
            'link.required' => __('The link field is required.'),
            'link.url' => __('Please provide a valid URL.'),
            'image.mimes' => __('The image must be a valid format: ' . implode(', ', StorageManagerService::$allowedImages)),
            'image.max' => __('The image size must not exceed 5MB.'),
            'id.required' => __('Banner ID is required.'),
            'id.exists' => __('The selected banner ID is invalid.'),
            'category_id.required' => __('The category field is required when the type is "category".'),
            'category_id.exists' => __('The selected category ID is invalid.'),
            'service_id.required' => __('The service field is required when the type is "service".'),
            'service_id.exists' => __('The selected service ID is invalid.'),
            'merchant.required' => __('The merchant field is required when the type is "merchant".'),
            'merchant.exists' => __('The selected merchant ID is invalid.'),
        ];
    }
}
