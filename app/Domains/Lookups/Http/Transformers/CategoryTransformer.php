<?php

namespace App\Domains\Lookups\Http\Transformers;

use App\Domains\Lookups\Models\Category;
use App\Enums\Core\StoragePaths;

class CategoryTransformer
{

    public function transform(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'image' => !empty($category->image)
                ? storageBaseLink(StoragePaths::CATEGORY_IMAGE . $category->image)
                : '',
            'sub_categories' => $category->children()->get()->transform(function ($subCategory) {
                return [
                    'id' => $subCategory->id,
                    'name' => $subCategory->name,
                    'image' => !empty($subCategory->image)
                        ? storageBaseLink(StoragePaths::CATEGORY_IMAGE . $subCategory->image)
                        : '',
                ];
            }),
            'tags' => $category->tags()->get()->transform(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ];
            }),
        ];
    }


}
