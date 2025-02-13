<?php

namespace App\Domains\Banner\Http\Transformers;

use App\Domains\Banner\Models\Banner;
use App\Enums\Core\StoragePaths;
use Illuminate\Support\Facades\Log;

class BannerTransformer
{
    /**
     * @param Banner $banner
     * @return array
     */
    public function transform(Banner $banner): array
    {
        return [
            'id' => $banner->id,
            'title' => $banner->title,
            'link' => $banner->link ?? "",
            'banner' => !empty($banner->image) ? storageBaseLink(StoragePaths::BANNER_IMAGE . $banner->image) : '',
            'type' => $banner->type,  // Transforming the type to capitalize the first letter
            'category' => $this->getCategoryName($banner),
            'service' => $this->getServiceName($banner),
            'merchant' => $this->getMerchantName($banner),
        ];
    }

    /**
     * Get the category name if type is category.
     *
     * @param Banner $banner
     * @return string|null
     */
    private function getCategoryName(Banner $banner)
    {
        if ($banner->type === 'category' && isset($banner->category_id)) {
            return $banner->category->name ?? null;
        }
        return null;
    }

    /**
     * Get the service name if type is service.
     *
     * @param Banner $banner
     * @return string|null
     */
    private function getServiceName(Banner $banner)
    {
        if ($banner->type === 'service' && isset($banner->service_id)) {
            return $banner->service->name ?? null;
        }
        return null;
    }

    /**
     * Get the merchant name if type is merchant.
     *
     * @param Banner $banner
     * @return string|null
     */
    private function getMerchantName(Banner $banner)
    {
        if ($banner->type === 'merchant' && isset($banner->merchant)) {
            return $banner->merchant->name ?? null;
        }
        return null;
    }
}
