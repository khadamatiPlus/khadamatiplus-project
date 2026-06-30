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
    </style>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{__("Orders Management")}}</h6>
            <div class="d-flex gap-2">
                <input type="text" wire:model="search" class="form-control form-control-sm" placeholder="{{__('Search...')}}" style="width: 200px;">
                <select wire:model="statusFilter" class="form-control form-control-sm" style="width: 150px;">
                    <option value="">{{__('All Status')}}</option>
                    <option value="pending">{{__('Pending')}}</option>
                    <option value="accepted">{{__('Accepted')}}</option>
                    <option value="completed">{{__('Completed')}}</option>
                    <option value="cancelled">{{__('Cancelled')}}</option>
                </select>
                <select wire:model="perPage" class="form-control form-control-sm" style="width: 120px;">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{__("Order ID")}}</th>
                        <th>{{__("Order Reference")}}</th>
                        <th>{{__("Customer")}}</th>
                        <th>{{__("Service")}}</th>
                        <th>{{__("Merchant")}}</th>
                        <th>{{__("Status")}}</th>
                        <th>{{__("Price")}}</th>
                        <th>{{__("Created At")}}</th>
                        <th>{{__("Actions")}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{$order->id}}</td>
                            <td>{{$order->order_reference}}</td>
                            <td>{{$order->customer->name ?? 'N/A'}}</td>
                            <td>
                                @if($order->appService)
                                    <strong>{{$order->appService->name}}</strong>
                                    @if($order->selected_variants && count($order->selected_variants) > 0)
                                        <br>
                                        <small class="text-muted">
                                            @foreach($order->selected_variants as $variant)
                                                @if(is_array($variant))
                                                    <span class="badge badge-secondary">{{ $variant['name'] ?? 'Variant' }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $variant }}</span>
                                                @endif
                                            @endforeach
                                        </small>
                                    @endif
                                @elseif($order->service)
                                    {{$order->service->title}}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $order->merchant->name ?? __('Unassigned') }}</td>
                            <td>
                                <span class="badge badge-{{getStatusBadgeClass($order->status)}}">
                                    {{ucfirst($order->status)}}
                                </span>
                            </td>
                            <td>{{number_format($order->total_price ?? $order->price, 2)}} JD</td>
                            <td>{{$order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i:s') : 'N/A'}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{route('admin.order.show', $order->id)}}" class="btn btn-sm btn-info" title="{{__('View Details')}}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">{{__("No orders found")}}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-between align-items-center my-3">
                <div>
                    @if ($orders->total() > 0)
                        <p class="text-muted mb-0">
                            {{ __("Showing") }} {{ $orders->firstItem() }} - {{ $orders->lastItem() }}
                            {{ __("of") }} {{ $orders->total() }} {{ __("results") }}
                        </p>
                    @endif
                </div>
                <div>
                    <nav>
                        {{ $orders->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
