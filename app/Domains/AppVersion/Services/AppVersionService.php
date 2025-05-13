<?php

namespace App\Domains\AppVersion\Services;
use App\Domains\AppVersion\Models\AppVersion;
use App\Services\BaseService;
use Illuminate\Http\Request;

/**
 * Class AppVersionService
 */
class AppVersionService extends BaseService
{

    /**
     * @var $entityName
     */
    protected $entityName = 'AppVersion';
    /**
     * @param AppVersion $appVersion
     */
    public function __construct(AppVersion $appVersion)
    {
        $this->model = $appVersion;
    }
}
