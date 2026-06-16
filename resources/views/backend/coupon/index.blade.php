@extends('backend.layouts.app')

@section('title', __('Coupons Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Coupons Management')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('admin.coupon.create')"
                :text="__('Create Coupon')"
            />
        </x-slot>

        <x-slot name="body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Discount Type</th>
                            <th>Discount Value</th>
                            <th>Usage</th>
                            <th>Status</th>
                            <th>Valid Until</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td><strong>{{ $coupon->code }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $coupon->discount_type == 'percentage' ? 'primary' : 'info' }}">
                                        {{ $coupon->discount_type == 'percentage' ? '%' : 'Fixed' }}
                                    </span>
                                </td>
                                <td>{{ $coupon->discount_value }}{{ $coupon->discount_type == 'percentage' ? '%' : '' }}</td>
                                <td>{{ $coupon->used_count }}{{ $coupon->usage_limit ? '/' . $coupon->usage_limit : '/∞' }}</td>
                                <td>
                                    <span class="badge bg-{{ $coupon->is_active ? 'success' : 'danger' }}">
                                        {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $coupon->end_date ? $coupon->end_date->format('Y-m-d') : 'No expiry' }}</td>
                                <td>
                                    <a href="{{ route('admin.coupon.show', $coupon) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.coupon.edit', $coupon) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.coupon.delete', $coupon) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No coupons found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $coupons->links() }}
            </div>
        </x-slot>
    </x-backend.card>
@endsection
