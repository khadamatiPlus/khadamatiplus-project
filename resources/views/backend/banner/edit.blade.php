@inject('model', '\App\Domains\Banner\Models\Banner')

@extends('backend.layouts.app')

@section('title', __('Update Banner'))

@section('content')
    <x-forms.post :action="route('admin.banner.update', $banner)" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PATCH" />
        <x-backend.card>
            <x-slot name="header">
                @lang('Update Banner')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.banner.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <input type="hidden" name="id" value="{{$banner->id}}" />

                <div class="form-group row">
                    <label for="image" class="col-md-2 col-form-label">@lang('Image')</label>
                    <div class="col-md-10">
                        <input type="file" name="image" id="image" class="form-control" />
                        <img src="{{ storageBaseLink(\App\Enums\Core\StoragePaths::BANNER_IMAGE.$banner->image) }}" class="mt-2" id="blah" height="100px" width="100px" alt="Banner Image" />
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="title" class="col-md-2 col-form-label">@lang('Title (English)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{ $banner->title ?? old('title') }}" name="title" id="title" class="form-control" required />
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="title_ar" class="col-md-2 col-form-label">@lang('Title (Arabic)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{ $banner->title_ar ?? old('title_ar') }}" name="title_ar" id="title_ar" class="form-control" required />
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="type" class="col-md-2 col-form-label">@lang('Type')</label>
                    <div class="col-md-10">
                        <select name="type" id="type" class="form-control" required>
                            <option value="">{{ __('Select Type') }}</option>
                            <option value="category" {{ $banner->type == 'category' ? 'selected' : '' }}>Category</option>
                            <option value="service" {{ $banner->type == 'service' ? 'selected' : '' }}>Service</option>
                            <option value="merchant" {{ $banner->type == 'merchant' ? 'selected' : '' }}>Merchant</option>
                            <option value="link" {{ $banner->type == 'link' ? 'selected' : '' }}>Custom Link</option>
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row d-none" id="category-field">
                    <label for="category_id" class="col-md-2 col-form-label">@lang('Category')</label>
                    <div class="col-md-10">
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $banner->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row d-none" id="service-field">
                    <label for="service_id" class="col-md-2 col-form-label">@lang('Service')</label>
                    <div class="col-md-10">
                        <select name="service_id" id="service_id" class="form-control">
                            <option value="">{{ __('Select Service') }}</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ $banner->service_id == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row d-none" id="merchant-field">
                    <label for="merchant_id" class="col-md-2 col-form-label">@lang('Merchant')</label>
                    <div class="col-md-10">
                        <select name="merchant_id" id="merchant_id" class="form-control">
                            <option value="">{{ __('Select Merchant') }}</option>
                            @foreach($merchants as $merchant)
                                <option value="{{ $merchant->id }}" {{ $banner->merchant_id == $merchant->id ? 'selected' : '' }}>{{ $merchant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row d-none" id="link-field">
                    <label for="link" class="col-md-2 col-form-label">@lang('Link')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{ $banner->link ?? old('link') }}" name="link" id="link" class="form-control" />
                    </div>
                </div><!--form-group-->

            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update Banner')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection

@push('after-scripts')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#image").change(function(){
            $('#blah').removeClass('d-none');
            readURL(this);
        });

        // Function to toggle fields based on type selection
        function toggleFields() {
            let type = $("#type").val();
            $("#category-field, #service-field, #merchant-field, #link-field").addClass('d-none');

            if (type === "category") {
                $("#category-field").removeClass('d-none');
            } else if (type === "service") {
                $("#service-field").removeClass('d-none');
            } else if (type === "merchant") {
                $("#merchant-field").removeClass('d-none');
            } else if (type === "link") {
                $("#link-field").removeClass('d-none');
            }
        }

        // Run on page load
        $(document).ready(function() {
            toggleFields(); // Show fields based on the selected type
        });

        // Listen for changes
        $("#type").change(function() {
            toggleFields();
        });

    </script>
@endpush
