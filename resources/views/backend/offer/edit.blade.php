@extends('backend.layouts.app')

@section('title', __('Edit Offer'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Edit Offer')
        </x-slot>

        <x-slot name="body">
            <form action="{{ route('admin.offer.update', $offer) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ $offer->title }}" required placeholder="e.g., Summer Sale">
                        @error('title') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ $offer->description }}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Coupon</label>
                        <select name="coupon_id" class="form-select">
                            <option value="">Select Coupon</option>
                            @foreach($coupons as $coupon)
                                <option value="{{ $coupon->id }}" {{ $offer->coupon_id == $coupon->id ? 'selected' : '' }}>{{ $coupon->code }} - {{ $coupon->discount_value }}{{ $coupon->discount_type == 'percentage' ? '%' : '' }}</option>
                            @endforeach
                        </select>
                        @error('coupon_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $offer->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @if($category->children)
                                    @foreach($category->children as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ $offer->category_id == $subCategory->id ? 'selected' : '' }}>&nbsp;&nbsp;{{ $subCategory->name }}</option>
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
                                <option value="{{ $appService->id }}" {{ $offer->app_service_id == $appService->id ? 'selected' : '' }}>{{ $appService->name }}</option>
                            @endforeach
                        </select>
                        @error('app_service_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ $offer->start_date ? $offer->start_date->format('Y-m-d\TH:i') : '' }}">
                        @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ $offer->end_date ? $offer->end_date->format('Y-m-d\TH:i') : '' }}">
                        @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if($offer->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $offer->image) }}" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                    @endif
                    @error('image') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ $offer->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" class="form-check-input" id="isFeatured" value="1" {{ $offer->is_featured ? 'checked' : '' }}>
                            <label class="form-check-label" for="isFeatured">Featured</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update Offer</button>
                    <a href="{{ route('admin.offer.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </x-slot>
    </x-backend.card>
@endsection
