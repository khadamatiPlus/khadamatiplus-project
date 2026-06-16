@extends('backend.layouts.app')

@section('title', __('Create Offer'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Create Offer')
        </x-slot>

        <x-slot name="body">
            <form action="{{ route('admin.offer.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g., Summer Sale">
                        @error('title') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Coupon</label>
                        <select name="coupon_id" class="form-select">
                            <option value="">Select Coupon</option>
                            @foreach($coupons as $coupon)
                                <option value="{{ $coupon->id }}" {{ old('coupon_id') == $coupon->id ? 'selected' : '' }}>{{ $coupon->code }} - {{ $coupon->discount_value }}{{ $coupon->discount_type == 'percentage' ? '%' : '' }}</option>
                            @endforeach
                        </select>
                        @error('coupon_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @if($category->children)
                                    @foreach($category->children as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ old('category_id') == $subCategory->id ? 'selected' : '' }}>&nbsp;&nbsp;{{ $subCategory->name }}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                        @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">App Service</label>
                        <select name="app_service_id" class="form-select">
                            <option value="">Select App Service</option>
                            @foreach($appServices as $appService)
                                <option value="{{ $appService->id }}" {{ old('app_service_id') == $appService->id ? 'selected' : '' }}>{{ $appService->name }}</option>
                            @endforeach
                        </select>
                        @error('app_service_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}">
                        @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}">
                        @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @error('image') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" checked>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" class="form-check-input" id="isFeatured" value="1">
                            <label class="form-check-label" for="isFeatured">Featured</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Create Offer</button>
                    <a href="{{ route('admin.offer.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </x-slot>
    </x-backend.card>
@endsection
