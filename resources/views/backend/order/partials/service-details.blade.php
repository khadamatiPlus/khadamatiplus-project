{{-- resources/views/backend/order/partials/service-details.blade.php --}}

@if($order->appService)
    <strong>{{ $order->appService->name }}</strong>

    @if(!empty($order->selected_variants))
        <div class="mt-2">
            @foreach($order->selected_variants as $variant)
                @if(is_array($variant))
                    <div class="card mb-2 small">
                        <div class="card-header py-1 px-2 bg-light">
                            <strong>{{ $variant['name'] ?? __('Variant') }}</strong>
                        </div>
                        <div class="card-body py-1 px-2">
                            @if(!empty($variant['options']) && is_array($variant['options']))
                                <ul class="list-unstyled mb-0">
                                    @foreach($variant['options'] as $option)
                                        <li class="d-flex justify-content-between">
                                            <span>{{ $option['name'] ?? __('Option') }}</span>
                                            <span class="text-muted">
                                                @if(!empty($option['price']) && $option['price'] > 0)
                                                    +{{ number_format($option['price'], 2) }} {{ __('JD') }}
                                                @endif
                                                @if(!empty($option['discount_price']) && $option['discount_price'] > 0)
                                                    <span class="text-success ms-1">{{ number_format($option['discount_price'], 2) }} {{ __('JD') }}</span>
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif(!empty($variant['selected_options']) && is_array($variant['selected_options']))
                                <ul class="list-unstyled mb-0">
                                    @foreach($variant['selected_options'] as $option)
                                        <li class="d-flex justify-content-between">
                                            <span>{{ $option['name'] ?? __('Option') }}</span>
                                            <span class="text-muted">
                                                @if(!empty($option['price']) && $option['price'] > 0)
                                                    +{{ number_format($option['price'], 2) }} {{ __('JD') }}
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">{{ __('No options selected') }}</span>
                            @endif
                        </div>
                    </div>
                @else
                    <span class="badge bg-secondary">{{ $variant }}</span>
                @endif
            @endforeach
        </div>
    @endif
@elseif($order->service)
    {{ $order->service->title }}
@else
    --
@endif
