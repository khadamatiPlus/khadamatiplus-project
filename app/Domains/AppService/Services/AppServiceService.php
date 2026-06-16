<?php

namespace App\Domains\AppService\Services;

use App\Services\BaseService;
use App\Domains\AppService\Models\AppService;

class AppServiceService extends BaseService
{
    /**
     * @var string $entityName
     */
    protected $entityName = 'AppService';

    /**
     * @param AppService $appService
     */
    public function __construct(AppService $appService)
    {
        $this->model = $appService;
    }

    /**
     * Get active app services
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getActiveAppServices()
    {
        return $this->model->where('status', 'active')->with(['category', 'subCategory', 'createdBy', 'updatedBy']);
    }

    /**
     * Get featured app services
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getFeaturedAppServices()
    {
        return $this->model->where('status', 'active')->where('is_featured', true)->with(['category', 'subCategory', 'createdBy', 'updatedBy']);
    }

    /**
     * Get app services by category
     *
     * @param int $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getAppServicesByCategory($categoryId)
    {
        return $this->model->where('status', 'active')->where('category_id', $categoryId)->with(['category', 'subCategory', 'createdBy', 'updatedBy']);
    }

    /**
     * Get online app services
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getOnlineAppServices()
    {
        return $this->model->where('status', 'active')->where('is_online', true)->with(['category', 'subCategory', 'createdBy', 'updatedBy']);
    }
}
