@extends('backend.layouts.app')

@section('title', __('Order Details'))

@push('styles')

@endpush

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.2.0/dist/css/coreui.min.css"
          rel="stylesheet"
          integrity="sha384-UkVD+zxJKGsZP3s/JuRzapi4dQrDDuEf/kHphzg8P3v8wuQ6m9RLjTkPGeFcglQU"
          crossorigin="anonymous">
    <x-backend.card>
        <x-slot name="header">
            @lang('Order Details')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.order.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-order-tab" data-coreui-toggle="pill"
                            data-coreui-target="#pills-order" type="button" role="tab"
                            aria-controls="pills-order" aria-selected="true">
                        {{ __('Order Information') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-merchant-tab" data-coreui-toggle="pill"
                            data-coreui-target="#pills-merchant" type="button" role="tab"
                            aria-controls="pills-merchant" aria-selected="false">
                        {{ __('Merchant Information') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-customer-tab" data-coreui-toggle="pill"
                            data-coreui-target="#pills-customer" type="button" role="tab"
                            aria-controls="pills-customer" aria-selected="false">
                        {{ __('Customer Information') }}
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">

                {{-- ===================== ORDER INFO ===================== --}}
                <div class="tab-pane fade show active" id="pills-order" role="tabpanel"
                     aria-labelledby="pills-order-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Order Reference') }}</th>
                                <th scope="col">{{ __('Service') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Price') }}</th>
                                <th scope="col">{{ __('Total Price') }}</th>
                                <th scope="col">{{ __('Notes') }}</th>
                                @if($order->voice_record)
                                    <th scope="col">{{ __('Voice Record') }}</th>
                                @endif
                                <th scope="col">{{ __('Date') }}</th>
                            </tr>
                            </thead>
                            <tbody class="table-group-divider">
                            <tr>
                                <th scope="row">{{ $order->order_reference }}</th>
                                <td>
                                    @include('backend.order.partials.service-details', ['order' => $order])
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending'   => 'warning',
                                            'accepted'  => 'info',
                                            'on_way'    => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                        $statusColor = $statusColors[$order->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ __(ucwords(str_replace('_', ' ', $order->status))) }}
                                    </span>
                                </td>
                                <td>{{ number_format((float) $order->price, 2) }} {{ __('JD') }}</td>
                                <td>{{ number_format((float) ($order->total_price ?? $order->price), 2) }} {{ __('JD') }}</td>
                                <td>{{ $order->notes ?: '--' }}</td>
                                @if($order->voice_record)
                                    <td>
                                        <audio controls class="w-100">
                                            <source src="{{ storageBaseLink(\App\Enums\Core\StoragePaths::ORDER_VOICE_RECORD . $order->voice_record) }}">
                                            {{ __('Your browser does not support the audio element.') }}
                                        </audio>
                                    </td>
                                @endif
                                <td>{{ optional($order->created_at)->format('d M Y, h:i A') ?? '--' }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===================== MERCHANT INFO ===================== --}}
                <div class="tab-pane fade" id="pills-merchant" role="tabpanel"
                     aria-labelledby="pills-merchant-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Merchant Name') }}</th>
                                <th scope="col">{{ __('Merchant Phone Number') }}</th>
                            </tr>
                            </thead>
                            <tbody class="table-group-divider">
                            <tr>
                                <td>
                                    @if($order->merchant_id && $order->merchant)
                                        <a href="{{ route('admin.merchant.show', $order->merchant_id) }}">
                                            {{ $order->merchant->name }}
                                        </a>
                                    @else
                                        --
                                    @endif
                                </td>
                                <td>
                                    @php($merchantPhone = $order->merchant?->profile?->mobile_number)
                                    @if($merchantPhone)
                                        <a href="tel:{{ $merchantPhone }}">{{ $merchantPhone }}</a>
                                    @else
                                        --
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===================== CUSTOMER INFO ===================== --}}
                <div class="tab-pane fade" id="pills-customer" role="tabpanel"
                     aria-labelledby="pills-customer-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Customer Name') }}</th>
                                <th scope="col">{{ __('Customer Phone') }}</th>
                                @if($order->delivery_destination)
                                    <th scope="col">{{ __('Delivery Destination') }}</th>
                                @endif
                                <th scope="col">{{ __('Order Location') }}</th>
                            </tr>
                            </thead>
                            <tbody class="table-group-divider">
                            <tr>
                                <td>
                                    @if($order->customer_id && $order->customer)
                                        {{ $order->customer->name ?? '--' }}
                                    @else
                                        --
                                    @endif
                                </td>
                                <td>
                                    @if($order->customer_phone)
                                        <a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a>
                                    @else
                                        --
                                    @endif
                                </td>
                                @if($order->delivery_destination)
                                    <td>{{ $order->delivery_destination }}</td>
                                @endif
                                <td>
                                    @if($order->latitude && $order->longitude)
                                        <a href="https://maps.google.com/?q={{ $order->latitude }},{{ $order->longitude }}"
                                           target="_blank" rel="noopener noreferrer">
                                            {{ __('View In Map') }}
                                        </a>
                                    @else
                                        --
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </x-slot>
    </x-backend.card>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"
            integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.2.0/dist/js/coreui.bundle.min.js"
            integrity="sha384-n0qOYeB4ohUPebL1M9qb/hfYkTp4lvnZM6U6phkRofqsMzK29IdkBJPegsyfj/r4"
            crossorigin="anonymous"></script>
@endsection

@push('scripts')

@endpush
