<?php
namespace App\Domains\Service\Http\Requests\Backend;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\StorageManagerService;

class ServiceRequest extends FormRequest
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
        switch ($request->method()) {
            case self::METHOD_POST:
                return [
                    'title' => ['required', 'max:350'],
                    'description' => ['required'],

//                    'prices' => 'required|array|min:1',
//                    'prices.*.title' => 'required|string|max:255',
//                    'prices.*.amount' => 'required|numeric|min:0',
                    'order' => ['nullable'],
                    'duration' => ['nullable'],
                    'sub_category_id' => ['required','exists:categories,id'],
                    'merchant_id' => ['required','exists:merchants,id'],
                    'video' => ['nullable', 'mimes:'.implode(',',StorageManagerService::$allowedVideos)],
                    'tags' => 'required|array',
                    'images' => 'required|array',
                    'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
                    'main_image' => 'required|integer',  // Main image index
                    'products' => 'nullable|array', // Validate products as an array
                    'products.*.title' => 'required|string|max:255',
                    'products.*.price' => 'required|numeric',
                    'products.*.duration' => 'required|string|max:100',
                    'products.*.description' => 'nullable|string',
                    'products.*.order' => 'required|integer',
                    'products.*.image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                ];
            case self::METHOD_PATCH:
                return [
                    'id' => ['required', 'exists:services,id'],
                    'title' => ['required', 'max:350'],
                    'description' => ['required'],
                    'prices' => 'required|array|min:1',
                    'prices.*.title' => 'required|string|max:255',
                    'prices.*.amount' => 'required|numeric|min:0',
                    'order' => ['nullable'],
                    'duration' => ['nullable'],
                    'sub_category_id' => ['required','exists:categories,id'],
                    'merchant_id' => ['required','exists:merchants,id'],
                    'video' => ['nullable', 'mimes:'.implode(',',StorageManagerService::$allowedVideos)],
                    'tags' => 'required|array',
                    'images' => 'nullable|array',
                    'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
                    'main_image' => 'nullable',
                    'products' => 'nullable|array',
                    'products.*.title' => 'required|string|max:255',
                    'products.*.price' => 'required|numeric',
                    'products.*.duration' => 'required|string|max:100',
                    'products.*.description' => 'nullable|string',
                    'products.*.order' => 'required|integer',
                    'products.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                ];
            case self::METHOD_DELETE:
            default:
                return [
                    'id' => ['required', 'exists:services,id']
                ];
        }
    }
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'prices.required' => 'At least one price option is required.',
            'prices.*.title.required' => 'Each price option must have a title.',
            'prices.*.amount.required' => 'Each price option must have an amount.',

        ];
    }

}
