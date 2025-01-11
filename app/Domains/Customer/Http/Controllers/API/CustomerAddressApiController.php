<?php

namespace App\Domains\Customer\Http\Controllers\API;

use App\Domains\Customer\Http\Requests\API\CustomerAddressRequest;
use App\Domains\Customer\Http\Transformers\CustomerAddressTransformer;
use App\Domains\Customer\Services\CustomerAddressService;
use App\Http\Controllers\APIBaseController;
use App\Domains\Customer\Http\Requests\API\UpdateCustomerAddressRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CustomerAddressApiController extends APIBaseController
{

    private CustomerAddressService $customerAddressService;

    /**
     * @param CustomerAddressService $customerAddressService
     */
    public function __construct(CustomerAddressService $customerAddressService)
    {
        $this->customerAddressService = $customerAddressService;
    }



    public function addCustomerAddress(CustomerAddressRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse((new CustomerAddressTransformer)->transform($this->customerAddressService->store($request->validated())));
    }

    public function updateCustomerAddress(UpdateCustomerAddressRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        return $this->successResponse(
            (new CustomerAddressTransformer())->transform($this->customerAddressService->update($request->input('customer_address_id'),$data))
        );
    }

    public function deleteCustomerAddress(Request $request) : \Illuminate\Http\JsonResponse
    {
        return $this->successResponse($this->customerAddressService->destroy($request->input('customer_address_id')));
    }


    public function getCustomerAddresses(Request $request): \Illuminate\Http\JsonResponse
    {
        $customerId = auth()->user()->customer_id;

        if (!$customerId) {
            return $this->errorResponse('Unauthorized', 401);
        }

        $addresses = $this->customerAddressService->getAddressesByCustomerId($customerId);


        return $this->successResponse(
            (new CustomerAddressTransformer())->transformCollection($addresses)
        );
    }

    public function getCustomerAddressDetails(Request $request): \Illuminate\Http\JsonResponse
    {
        $customerId = auth()->user()->customer_id;
        $addressId = $request->input('customer_address_id');

        if (!$customerId) {
            return $this->errorResponse('Unauthorized', 401);
        }

        $address = $this->customerAddressService->getAddressDetails($customerId, $addressId);

        if (!$address) {
            return $this->errorResponse('Address not found', 404);
        }

        return $this->successResponse(
            (new CustomerAddressTransformer())->transform($address)
        );
    }



}
