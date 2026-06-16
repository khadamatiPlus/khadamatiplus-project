@extends('backend.layouts.app')

@section('title', __('Edit Coupon'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Edit Coupon')
        </x-slot>

        <x-slot name="body">
            <form action="{{ route('admin.coupon.update', $coupon) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Code *</label>
                        <input type="text" name="code" class="form-control" value="{{ $coupon->code }}" required placeholder="e.g., SUMMER2024">
                        @error('code') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Discount Type *</label>
                        <select name="discount_type" class="form-select" required>
                            <option value="percentage" {{ $coupon->discount_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ $coupon->discount_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        </select>
                        @error('discount_type') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Discount Value *</label>
                        <input type="number" name="discount_value" class="form-control" value="{{ $coupon->discount_value }}" step="0.01" min="0" required>
                        @error('discount_value') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Minimum Order Amount</label>
                        <input type="number" name="minimum_order_amount" class="form-control" value="{{ $coupon->minimum_order_amount }}" step="0.01" min="0" placeholder="0.00">
                        @error('minimum_order_amount') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Maximum Discount Amount</label>
                        <input type="number" name="maximum_discount_amount" class="form-control" value="{{ $coupon->maximum_discount_amount }}" step="0.01" min="0" placeholder="No limit">
                        @error('maximum_discount_amount') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Usage Limit</label>
                        <input type="number" name="usage_limit" class="form-control" value="{{ $coupon->usage_limit }}" min="1" placeholder="Unlimited">
                        @error('usage_limit') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ $coupon->start_date ? $coupon->start_date->format('Y-m-d\TH:i') : '' }}">
                        @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ $coupon->end_date ? $coupon->end_date->format('Y-m-d\TH:i') : '' }}">
                        @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ $coupon->description }}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ $coupon->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update Coupon</button>
                    <a href="{{ route('admin.coupon.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </x-slot>
    </x-backend.card>
@endsection
