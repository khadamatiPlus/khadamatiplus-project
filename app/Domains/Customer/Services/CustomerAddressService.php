<?php

namespace App\Domains\Customer\Services;
use App\Domains\Customer\Models\CustomerAddress;
use App\Services\BaseService;


class CustomerAddressService extends BaseService
{

    /**
     * @var $entityName
     */
    protected $entityName = 'CustomerAddress';

    /**
     * @param CustomerAddress $customerAddress
     */
    public function __construct(CustomerAddress $customerAddress)
    {
        $this->model = $customerAddress;
    }


    public function store(array $data = [])
    {
        $data['customer_id'] = $data['customer_id'] ?? request()->user()->customer_id;
        return parent::store($data);
    }

    /**
     * @param $entity
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update($entity, array $data = [])
    {
        $data['id'] = $data['customer_address_id'];
        unset($data['customer_address_id']);
        $data = array_filter($data);
        return parent::update($entity, $data);
    }

    public function getAddressesByCustomerId(int $customerId)
    {
        return CustomerAddress::where('customer_id', $customerId)->get();
    }

    public function getAddressDetails(int $customerId, int $addressId)
    {
        return CustomerAddress::where('customer_id', $customerId)
            ->where('id', $addressId)
            ->first();
    }


}
