<?php

namespace App\Domains\AppService\Http\Transformers;

use App\Domains\AppService\Models\AppService;
use App\Enums\Core\StoragePaths;

class AppServiceTransformer
{
    public function transform(AppService $appService): array
    {
        return [
            'id' => $appService->id,
            'name' => $appService->name,
            'description' => $appService->description,
            'category_id' => $appService->category_id,
            'category_name' => $appService->category?->name,
            'sub_category_id' => $appService->sub_category_id,
            'sub_category_name' => $appService->subCategory?->name,
            'images' => $this->transformImages($appService->images),
            'video_url' => $appService->video_url,
            'base_price' => $appService->base_price,
            'currency' => $appService->currency,
            'price_type' => $appService->price_type,
            'discount' => $appService->discount,
            'variants' => $this->transformVariants($appService->variants),
//            'delivery_time' => $appService->delivery_time,
//            'delivery_time_unit' => $appService->delivery_time_unit,
//            'free_revisions' => $appService->free_revisions,
//            'customer_requirements' => $appService->customer_requirements,
//            'requirements_mandatory' => $appService->requirements_mandatory,
//            'tags' => $appService->tags,
//            'seo_description' => $appService->seo_description,
//            'language' => $appService->language,
//            'scope' => $appService->scope,
            'availability_days' => $appService->availability_days,
            'max_concurrent_orders' => $appService->max_concurrent_orders,
//            'expiry_date' => $appService->expiry_date,
            'is_featured' => $appService->is_featured,
//            'is_urgent' => $appService->is_urgent,
            'is_online' => $appService->is_online,
            'status' => $appService->status,
//            'visibility' => $appService->visibility,
//            'created_by_id' => $appService->created_by_id,
//            'created_by_name' => $appService->createdBy?->name,
//            'updated_by_id' => $appService->updated_by_id,
//            'updated_by_name' => $appService->updatedBy?->name,
//            'created_at' => $appService->created_at,
//            'updated_at' => $appService->updated_at,
        ];
    }

    /**
     * Transform images to URLs
     *
     * @param array|null $images
     * @return array
     */
    private function transformImages($images): array
    {
        if (empty($images)) {
            return [];
        }

        if (is_string($images)) {
            $images = json_decode($images, true);
        }

        return array_map(function ($image) {
            return storageBaseLink($image);
        }, $images);
    }

    /**
     * Transform variants with options
     *
     * @param array|null $variants
     * @return array
     */
    private function transformVariants($variants): array
    {
        if (empty($variants)) {
            return [];
        }

        if (is_string($variants)) {
            $decoded = json_decode($variants, true);

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($variants) ? $variants : [];
    }
}
