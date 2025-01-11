<?php

namespace App\Domains\Service\Http\Transformers;

use App\Domains\Merchant\Http\Transformers\MerchantTransformer;
use App\Domains\Service\Models\Service;
use App\Enums\Core\StoragePaths;
use Illuminate\Support\Carbon;

class ServiceTransformer
{


    public function transform(Service $service): array
    {
        $isFavorite = auth()->check() && $service->favoritedBy()->where('customer_id', auth()->user()->customer_id)->exists();

        return [
            'id' => $service->id,
            'title' => $service->title,
            'description' => $service->description,
            'price' => $service->price,
            'new_price' => $service->new_price??null,
            'duration' => $service->duration??null,

            'rating' => $service->reviews()->avg('rating'),
            'user_ratings_count' => $service->reviews()->count(),
            'reviews' => $service->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'customer' => [
                        'id' => $review->customer->id,
                        'name' => $review->customer->name,
                    ],
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->toDateTimeString(),
                ];
            }),

            'tags' => $service->tags->pluck('name'),
            'merchant' => $service->merchant
                ? (new MerchantTransformer())->transform($service->merchant)
                : null,
            'is_favorite' => $isFavorite,

            'images' => ($service->images ?? collect())->map(function ($image) {
                return [
                    'image' => $image->image,
                    'is_main' => $image->is_main,
                ];
            }),
            'products' => ($service->products ?? collect())->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'description' => $product->description,
                    'images' => $product->images->map(function ($image) {
                        return [
                            'image' => $image->image,
                            'is_main' => $image->is_main,
                        ];
                    }),
                ];
            }),
        ];
    }



    public function transformMerchant(Service $service): array
    {
        $isFavorite = auth()->check() && $service->favoritedBy()->where('customer_id', auth()->user()->customer_id)->exists();

        return [
            'id' => $service->id,
            'title' => $service->title,
            'description' => $service->description,
            'price' => $service->price,
            'new_price' => $service->new_price ?? null,
            'duration' => $service->duration ?? null,

            'rating' => $service->reviews()->avg('rating'),
            'user_ratings_count' => $service->reviews()->count(),
            'reviews' => $service->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'customer' => [
                        'id' => $review->customer->id,
                        'name' => $review->customer->name,
                    ],
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->toDateTimeString(),
                ];
            }),

            'tags' => $service->tags->pluck('name'),
            'is_favorite' => $isFavorite,

            'images' => ($service->images ?? collect())->map(function ($image) {
                return [
                    'image' => $image->image,
                    'is_main' => $image->is_main,
                ];
            }),

            'products' => ($service->products ?? collect())->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'description' => $product->description,
                    'images' => $product->images->map(function ($image) {
                        return [
                            'image' => $image->image,
                            'is_main' => $image->is_main,
                        ];
                    }),
                ];
            }),

            // Adding the service options here
//            'options' => $service->options->map(function ($option) {
//                return [
//                    'id' => $option->id,
//                    'title' => $option->title,
//                    'value' => $option->value,
//                    'type' => $option->type,
//                    'value_type' => $option->value_type,
//                ];
//            }),
        ];
    }


}
