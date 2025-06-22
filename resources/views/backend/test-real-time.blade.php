@extends('backend.layouts.app')

@section('title', __('Test Real-time Dashboard'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Test Real-time Dashboard Updates')
        </x-slot>

        <x-slot name="body">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h5>Real-time Dashboard Test</h5>
                        <p>This page demonstrates the real-time dashboard functionality. The dashboard will automatically update when:</p>
                        <ul>
                            <li>New orders are created via the <code>requestOrder</code> API</li>
                            <li>Order statuses are updated via the <code>updateOrderStatusByCustomer</code> API</li>
                            <li>Order statuses are updated via the <code>updateOrderStatusByMerchant</code> API</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6>Test Order Creation</h6>
                        </div>
                        <div class="card-body">
                            <p>To test order creation, use the following API endpoint:</p>
                            <code>POST /api/customer/requestOrder</code>
                            <br><br>
                            <p>Required parameters:</p>
                            <ul>
                                <li><code>service_id</code> - ID of the service</li>
                                <li><code>day</code> - Day of the week (optional)</li>
                                <li><code>time</code> - Time (optional)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6>Test Order Status Update</h6>
                        </div>
                        <div class="card-body">
                            <p>To test order status updates, use the following API endpoints:</p>
                            <code>POST /api/customer/updateOrderStatusByCustomer</code><br>
                            <code>POST /api/merchant/updateOrderStatusByMerchant</code>
                            <br><br>
                            <p>Required parameters:</p>
                            <ul>
                                <li><code>order_id</code> - ID of the order</li>
                                <li><code>status</code> - New status (accepted, on_the_way, on_progress, completed, cancelled)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Real-time Features</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Livewire Component</h6>
                                    <p>The dashboard uses Livewire for real-time updates without page refresh.</p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Event Broadcasting</h6>
                                    <p>Order events are broadcasted to trigger real-time updates.</p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Auto-refresh</h6>
                                    <p>Dashboard data refreshes automatically every 30 seconds.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Current Dashboard Status</h6>
                        </div>
                        <div class="card-body">
                            <p>Go to the <a href="{{ route('admin.dashboard') }}">main dashboard</a> to see the real-time updates in action.</p>
                            <p>The dashboard will show:</p>
                            <ul>
                                <li>Total orders count</li>
                                <li>Total revenue</li>
                                <li>Orders by status (pending, accepted, completed, cancelled)</li>
                                <li>Recent orders table</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-backend.card>
@endsection 