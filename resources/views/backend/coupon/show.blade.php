@extends('backend.layouts.app')

@section('title', __('View Coupon'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View Coupon')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-arrow-left"
                class="card-header-action"
                :href="route('admin.coupon.index')"
                :text="__('Back')"
            />
        </x-slot>

        <x-slot name="body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $coupon->id }}</td>
                        </tr>
                        <tr>
                            <th>Code</th>
                            <td><strong>{{ $coupon->code }}</strong></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $coupon->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Discount Type</th>
                            <td>
                                <span class="badge bg-{{ $coupon->discount_type == 'percentage' ? 'primary' : 'info' }}">
                                    {{ $coupon->discount_type == 'percentage' ? 'Percentage (%)' : 'Fixed Amount' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Discount Value</th>
                            <td>{{ $coupon->discount_value }}{{ $coupon->discount_type == 'percentage' ? '%' : '' }}</td>
                        </tr>
                        <tr>
                            <th>Minimum Order Amount</th>
                            <td>{{ $coupon->minimum_order_amount ?? '0.00' }}</td>
                        </tr>
                        <tr>
                            <th>Maximum Discount Amount</th>
                            <td>{{ $coupon->maximum_discount_amount ?? 'No limit' }}</td>
                        </tr>
                        <tr>
                            <th>Usage Limit</th>
                            <td>{{ $coupon->usage_limit ?? 'Unlimited' }}</td>
                        </tr>
                        <tr>
                            <th>Used Count</th>
                            <td>{{ $coupon->used_count }}</td>
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <td>{{ $coupon->start_date ? $coupon->start_date->format('Y-m-d H:i:s') : 'Not set' }}</td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <td>{{ $coupon->end_date ? $coupon->end_date->format('Y-m-d H:i:s') : 'No expiry' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $coupon->is_active ? 'success' : 'danger' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{ $coupon->createdBy->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td>{{ $coupon->updatedBy->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $coupon->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $coupon->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Coupon Status</h5>
                        </div>
                        <div class="card-body">
                            @if($coupon->isValid())
                                <div class="alert alert-success">
                                    <strong>✓ Valid</strong> - This coupon is currently active and can be used.
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <strong>✗ Invalid</strong> - This coupon cannot be used.
                                    @if(!$coupon->is_active)
                                        <br>• Coupon is inactive
                                    @endif
                                    @if($coupon->start_date && $coupon->start_date > now())
                                        <br>• Coupon has not started yet
                                    @endif
                                    @if($coupon->end_date && $coupon->end_date < now())
                                        <br>• Coupon has expired
                                    @endif
                                    @if($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit)
                                        <br>• Usage limit reached
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Usage Statistics</h5>
                        </div>
                        <div class="card-body">
                            @if($coupon->usage_limit)
                                <div class="progress mb-2">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ ($coupon->used_count / $coupon->usage_limit) * 100 }}%"
                                         aria-valuenow="{{ $coupon->used_count }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="{{ $coupon->usage_limit }}">
                                        {{ $coupon->used_count }} / {{ $coupon->usage_limit }}
                                    </div>
                                </div>
                                <small class="text-muted">{{ round(($coupon->used_count / $coupon->usage_limit) * 100, 1) }}% used</small>
                            @else
                                <p class="text-muted">Unlimited usage - {{ $coupon->used_count }} times used</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.coupon.edit', $coupon) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('admin.coupon.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </x-slot>
    </x-backend.card>
@endsection
