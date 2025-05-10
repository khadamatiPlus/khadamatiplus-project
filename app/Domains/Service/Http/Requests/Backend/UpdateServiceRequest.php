<?php
namespace App\Domains\Service\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'merchant_id' => 'required|exists:merchants,id',
            'sub_category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'nullable|integer|min:1',

            'prices' => 'required|array|min:1',
            'prices.*.id' => 'nullable|exists:service_prices,id', // For updates
            'prices.*.title' => 'required|string|max:255',
            'prices.*.amount' => 'required|numeric|min:0.01|max:999999.99',

            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',

            'existing_service_images' => 'nullable|array',
            'existing_service_images.*' => 'exists:service_images,id',


            'products' => 'nullable|array',
            'products.*.id' => 'nullable|exists:service_products,id', // For updates
            'products.*.title' => 'required|string|max:255',
            'products.*.price' => 'required|numeric|min:0|max:999999.99',
            'products.*.description' => 'nullable|string',
            'products.*.existing_images' => 'nullable|array',
            'products.*.existing_images.*' => 'exists:service_product_images,id',
            'products.*.images' => 'nullable|array|max:5',
            'products.*.images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'products.*.main_image' => 'nullable|integer|min:0',

            'existing_images' => ['array'],
            'deleted_images' => ['array'],
            'deleted_images.*' => ['sometimes', 'boolean'],
            'service_images' => ['sometimes', 'array', 'max:5'],
            'service_images.*' => ['image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'main_service_image' => ['required'],

        ];
    }

    public function messages()
    {
        return [
            'prices.required' => 'At least one price option is required',
            'prices.*.amount.min' => 'Price must be at least $0.01',
            'products.*.price.min' => 'Product price must be at least $0.00',
            'service_images.*.max' => 'Service images must not be larger than 5MB',
            'products.*.images.*.max' => 'Product images must not be larger than 5MB',
        ];
    }


}
