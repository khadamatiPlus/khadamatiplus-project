<?php

namespace App\Domains\Subscriber\Services;
use App\Domains\Subscriber\Models\Subscriber;
use App\Exceptions\GeneralException;
use App\Services\BaseService;

/**
 * Class SubscriberService
 */
class SubscriberService extends BaseService
{

    /**
     * @var $entityName
     */
    protected $entityName = 'UserPost';

    /**
     * @param Subscriber $subscriber
     */
    public function __construct(Subscriber $subscriber)
    {
        $this->model = $subscriber;

    }
    public function store(array $data = [])
    {

        $data = array_filter($data);
//        $data['created_by_id'] = request()->user()->id??1;
        $subscriber=parent::store($data);
        return $subscriber ;
    }
}

