<?php

namespace App\Domains\Highlight\Http\Transformers;

use App\Domains\Highlight\Models\Highlight;
use App\Enums\Core\StoragePaths;
use Illuminate\Support\Facades\Log;

class HighlightTransformer
{
    /**
     * @param Highlight $highlight
     * @return array
     */
    public function transform(Highlight $highlight): array
    {
        return [
            'id' => $highlight->id,
            'title' => $highlight->title,
            'description' => $highlight->description,
            'link' => $highlight->link ?? "",
            'highlight' => !empty($highlight->image) ? storageBaseLink(StoragePaths::HIGHLIGHT_IMAGE . $highlight->image) : '',
            'type' => $highlight->type,  // Transforming the type to capitalize the first letter
            'category' => $this->getCategoryName($highlight),
            'service' => $this->getServiceName($highlight),
            'merchant' => $this->getMerchantName($highlight),
        ];
    }

    /**
     * Get the category name if type is category.
     *
     * @param Highlight $highlight
     * @return string|null
     */
    private function getCategoryName(Highlight $highlight)
    {
        if ($highlight->type === 'category' && isset($highlight->category_id)) {
            return $highlight->category->name ?? null;
        }
        return null;
    }

    /**
     * Get the service name if type is service.
     *
     * @param Highlight $highlight
     * @return string|null
     */
    private function getServiceName(Highlight $highlight)
    {
        if ($highlight->type === 'service' && isset($highlight->service_id)) {
            return $highlight->service->name ?? null;
        }
        return null;
    }

    /**
     * Get the merchant name if type is merchant.
     *
     * @param Highlight $highlight
     * @return string|null
     */
    private function getMerchantName(Highlight $highlight)
    {
        if ($highlight->type === 'merchant' && isset($highlight->merchant)) {
            return $highlight->merchant->name ?? null;
        }
        return null;
    }
}
