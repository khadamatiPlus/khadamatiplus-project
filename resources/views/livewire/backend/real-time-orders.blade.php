<div>
    <style>
        .call-button {
            transition: all 0.3s ease;
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        .call-button:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .btn-group-vertical .btn {
            margin-bottom: 0.25rem;
        }
        .btn-group-vertical .btn:last-child {
            margin-bottom: 0;
        }
        .phone-display {
            font-size: 0.7rem;
            color: #666;
            margin-top: 0.25rem;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .load-more-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>

    <!-- Content Row -->
    <div class="row">
        <!-- Order -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{__("Customers")}}</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{getCustomersCount()}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="far fa-id-card fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{__("Merchants")}}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{getMerchantsCount()}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store-alt fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{__("Orders")}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalOfOrders}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Posts-->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{__("Total Price")}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{number_format($totalPriceOfOrders, 2)}} JD</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{__("Requested Order")}}</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$totalOfRequestedOrders}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cart-plus fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Order -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{__("Accepted Order")}}
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$totalOfAcceptedOrders}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Order -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{("Finished Order")}}</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$totalOfDeliveredOrders}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-end fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Order -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{("Cancelled Order")}}</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$totalOfCanceledOrders}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("Recent Orders")}} - {{__("Click buttons to call customers and merchants")}}</h6>
                    <div>
                        <select wire:model="perPage" class="form-control form-control-sm">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Quick Call Summary -->
                    <div class="alert alert-info mb-3">
                        <h6><i class="fas fa-phone"></i> {{__("Quick Call Guide")}}</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <span class="badge badge-success">{{__("Green Button")}}</span> {{__("Call Customer")}}
                            </div>
                            <div class="col-md-4">
                                <span class="badge badge-info">{{__("Blue Button")}}</span> {{__("Call Merchant")}}
                            </div>
                            <div class="col-md-4">
                                <span class="badge badge-warning">{{__("Yellow Button")}}</span> {{__("Call Order Phone")}}
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{__("Order ID")}}</th>
                                <th>{{__("Customer")}}</th>
                                <th>{{__("Service")}}</th>
                                <th>{{__("Merchant")}}</th>
                                <th>{{__("Status")}}</th>
                                <th>{{__("Price")}}</th>
                                <th>{{__("Created At")}}</th>
                                <th>{{__("Call Actions")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>#{{$order->id}}</td>
                                    <td>{{$order->customer->name ?? 'N/A'}}</td>
                                    <td>{{$order->service->title ?? 'N/A'}}</td>
                                    <td>{{$order->merchant->name ?? 'N/A'}}</td>
                                    <td>
                                            <span class="badge badge-{{getStatusBadgeClass($order->status)}}">
                                                {{ucfirst($order->status)}}
                                            </span>
                                    </td>
                                    <td>{{number_format($order->total_price ?? $order->price, 2)}} JD</td>
                                    <td>{{$order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i:s') : 'N/A'}}</td>
                                    <td>
                                        <div class="btn-group-vertical" role="group">
                                            @if(isset($order->customer->profile->mobile_number) && $order->customer->profile->mobile_number)
                                                <a href="tel:{{$order->customer->profile->mobile_number}}"
                                                   class="btn btn-sm btn-success mb-1 call-button"
                                                   title="{{__('Call Customer')}} - {{$order->customer->profile->mobile_number}}"
                                                   onclick="return confirmCall('{{$order->customer->profile->mobile_number}}', 'Customer')">
                                                    <i class="fas fa-phone"></i> {{__('Call Customer')}}
                                                    <div class="phone-display">{{$order->customer->profile->mobile_number}}</div>
                                                </a>
                                            @else
                                                <span class="btn btn-sm btn-secondary mb-1 disabled" title="{{__('No customer phone available')}}">
                                                        <i class="fas fa-phone-slash"></i> {{__('No Customer Phone')}}
                                                    </span>
                                            @endif

                                            @if(isset($order->merchant->profile->mobile_number) && $order->merchant->profile->mobile_number)
                                                <a href="tel:{{$order->merchant->profile->mobile_number}}"
                                                   class="btn btn-sm btn-info mb-1 call-button"
                                                   title="{{__('Call Merchant')}} - {{$order->merchant->profile->mobile_number}}"
                                                   onclick="return confirmCall('{{$order->merchant->profile->mobile_number}}', 'Merchant')">
                                                    <i class="fas fa-phone"></i> {{__('Call Merchant')}}
                                                    <div class="phone-display">{{$order->merchant->profile->mobile_number}}</div>
                                                </a>
                                            @else
                                                <span class="btn btn-sm btn-secondary mb-1 disabled" title="{{__('No merchant phone available')}}">
                                                        <i class="fas fa-phone-slash"></i> {{__('No Merchant Phone')}}
                                                    </span>
                                            @endif

                                            @if(isset($order->customer_phone) && $order->customer_phone)
                                                <a href="tel:{{$order->customer_phone}}"
                                                   class="btn btn-sm btn-warning mb-1 call-button"
                                                   title="{{__('Call Order Phone')}} - {{$order->customer_phone}}"
                                                   onclick="return confirmCall('{{$order->customer_phone}}', 'Order Phone')">
                                                    <i class="fas fa-phone"></i> {{__('Order Phone')}}
                                                    <div class="phone-display">{{$order->customer_phone}}</div>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">{{__("No orders found")}}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-between align-items-center my-3">
                        <div>
                            @if ($recentOrders->total() > 0)
                                <p class="text-muted mb-0">
                                    {{ __("Showing") }} {{ $recentOrders->firstItem() }} - {{ $recentOrders->lastItem() }}
                                    {{ __("of") }} {{ $recentOrders->total() }} {{ __("results") }}
                                </p>
                            @endif
                        </div>
                        <div>
                            <nav>
                                {{ $recentOrders->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds
        setInterval(function() {
        @this.call('refreshData');
        }, 30000);

        function confirmCall(phoneNumber, type) {
            return confirm("{{__('Are you sure you want to call the')}} " + type + ": " + phoneNumber + "?");
        }
    </script>
</div>
