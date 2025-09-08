@extends('backend.layouts.app')
@section('title', __('Create Highlight'))
@section('content')
    <x-forms.post :action="route('admin.highlight.store')" enctype="multipart/form-data">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Highlight')
            </x-slot>
            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.highlight.index')" :text="__('Cancel')" />
            </x-slot>
            <x-slot name="body">
                <div class="form-group row">
                    <label for="image" class="col-md-2 col-form-label">@lang('Image')</label>
                    <div class="col-md-10">
                        <input type="file" name="image" id="image" class="form-control" required />
                        <img class="mt-2 d-none" id="blah" height="100px" width="100px" alt="{{ old('image') }}" />
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="title" class="col-md-2 col-form-label">@lang('Title (English)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{ old('title') }}" name="title" id="title" class="form-control" required />
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="title_ar" class="col-md-2 col-form-label">@lang('Title (Arabic)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{ old('title_ar') }}" name="title_ar" id="title_ar" class="form-control" required />
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="description" class="col-md-2 col-form-label">@lang('Description (English)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{old('description')}}" name="description" id="description" class="form-control"  />
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="description_ar" class="col-md-2 col-form-label">@lang('Description (Arabic)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{old('description_ar')}}" name="description_ar" id="description_ar" class="form-control"  />
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="type" class="col-md-2 col-form-label">@lang('Type')</label>
                    <div class="col-md-10">
                        <select name="type" id="type" class="form-control" required>
                            <option value="">{{ __('Select Type') }}</option>
                            <option value="none">None</option>
                            <option value="category">Category</option>
                            <option value="service">Service</option>
                            <option value="merchant">Merchant</option>
                            <option value="link">Custom Link</option>
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row d-none" id="category-field">
                    <label for="category_id" class="col-md-2 col-form-label">@lang('Category')</label>
                    <div class="col-md-10">
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
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
                                <option value="{{ $merchant->id }}">{{ $merchant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row d-none" id="link-field">
                    <label for="link" class="col-md-2 col-form-label">@lang('Link')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{ old('link') }}" name="link" id="link" class="form-control" />
                    </div>
                </div><!--form-group-->

            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create Highlight')</button>
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

        // Show/hide fields based on type selection
        $("#type").change(function() {
            let type = $(this).val();
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
        });
    </script>
@endpush
