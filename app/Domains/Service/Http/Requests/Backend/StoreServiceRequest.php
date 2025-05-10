<?php
namespace App\Domains\Service\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
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
            'prices.*.title' => 'required|string|max:255',
            'prices.*.amount' => 'required|numeric|min:0.01|max:999999.99',

            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',

            'service_images' => 'required|array|min:1|max:10',
            'service_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'main_service_image' => 'required|integer|min:0',

            'products' => 'nullable|array',
            'products.*.title' => 'required|string|max:255',
            'products.*.price' => 'required|numeric|min:0|max:999999.99',
            'products.*.description' => 'nullable|string',
            'products.*.images' => 'nullable|array|max:5',
            'products.*.images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'products.*.main_image' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'prices.required' => 'At least one price option is required',
            'service_images.required' => 'At least one service image is required',
            'main_service_image.required' => 'Please select a main service image',
            'prices.*.amount.min' => 'Price must be at least $0.01',
            'products.*.price.min' => 'Product price must be at least $0.00',
            'service_images.*.max' => 'Service images must not be larger than 5MB',
            'products.*.images.*.max' => 'Product images must not be larger than 5MB',
        ];
    }

    public function prepareForValidation()
    {
        $products = array_filter($this->products ?? [], function($product) {
            return !empty($product['title']) || !empty($product['price']);
        });
        $this->merge(['products' => array_values($products)]);

        // Ensure main_service_image is within bounds if images exist
        if ($this->hasFile('service_images') && $this->main_service_image >= count($this->file('service_images'))) {
            $this->merge(['main_service_image' => 0]);
        }

        // Ensure product main images are within bounds
        $products = $this->products ?? [];
        foreach ($this->products as $index => $product) {
            if (isset($product['main_image']) && isset($product['images'])) {
                if ($product['main_image'] >= count($product['images'])) {
                    $this->products[$index]['main_image'] = 0;
                }
            }
        }
        $this->merge(['products' => $products]);
    }
}
