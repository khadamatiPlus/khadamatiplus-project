<?php

namespace App\Domains\AppVersion\Http\Transformers;
use App\Domains\AppVersion\Models\AppVersion;


class AppVersionTransformer
{

    /**
     * @param AppVersion $appVersion
     * @return array
     */
    public function transform(AppVersion $appVersion): array
    {
        return [
            'current_version_android' => $appVersion->current_version_android,
            'current_version_ios' => $appVersion->current_version_ios,
            'current_version_huawei' => $appVersion->current_version_huawei,
            'customer_version_android' => $appVersion->customer_version_android,
            'customer_version_ios' => $appVersion->customer_version_ios,
            'customer_version_huawei' => $appVersion->customer_version_huawei,

        ];
    }
}
