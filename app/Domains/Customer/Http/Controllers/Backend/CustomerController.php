<?php

namespace App\Domains\Customer\Http\Controllers\Backend;

use App\Domains\Lookups\Services\CityService;
use App\Domains\Customer\Http\Requests\Backend\CustomerRequest;
use App\Domains\Customer\Models\Customer;
use App\Domains\Customer\Services\CustomerService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    private CustomerService $customerService;
    private CityService $cityService;



    public function __construct(CustomerService $customerService, CityService $cityService)
    {
        $this->customerService = $customerService;
        $this->cityService = $cityService;

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.customer.index');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $cities = $this->cityService->select((['id','name']))->get();
        return view('backend.customer.create')
            ->withCities($cities);

    }

    /**
     * @param CustomerRequest $request
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(CustomerRequest $request)
    {
        $this->customerService->register($request->validated());

        return redirect()->route('admin.customer.index')->withFlashSuccess(__('The Customer was successfully added'));
    }


    public function edit(Customer $customer)
    {
        $cities = $this->cityService->select((['id','name']))->get();
        return view('backend.customer.edit')
            ->withCustomer($customer);
    }


    public function update(CustomerRequest $request, $customer)
    {
        $this->customerService->update($customer, $request->validated());

        return redirect()->back()->withFlashSuccess(__('The Customer was successfully updated'));
    }


    public function destroy($customer)
    {
        $this->customerService->destroy($customer);
        return redirect()->back()->withFlashSuccess(__('The Customer was successfully deleted.'));
    }

    /**
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request)
    {
        $this->customerService->updateStatus($request);
        $customer = Customer::query()->findOrFail($request->input('customerId'));
        return response()->json(true);
    }
    public function show(Customer $customer)
    {
        return view('backend.customer.show')
            ->withCustomer($customer);
    }


}
