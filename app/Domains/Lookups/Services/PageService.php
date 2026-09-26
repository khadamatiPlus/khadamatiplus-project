<?php

namespace App\Domains\Lookups\Services;

use App\Domains\Lookups\Models\Page;
use App\Services\BaseService;

/**
 * Class PageService
 */
class PageService extends BaseService
{

    /**
     * @var $entityName
     */
    protected $entityName = 'Page';

    /**
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->model = $page;
    }

    public function store(array $data = [])
    {
        if (empty($data['slug']) && ! empty($data['title'])) {
            $data['slug'] = Page::generateUniqueSlug($data['title']);
        }

        return parent::store($data);
    }

    public function update($entity, array $data = [])
    {
        if (empty($data['slug']) && ! empty($data['title'])) {
            $data['slug'] = Page::generateUniqueSlug($data['title'], $entity->id ?? null);
        }

        return parent::update($entity, $data);
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function bySlug($slug)
    {
        return $this->model::bySlug($slug);
    }
}
