@extends('backend.layouts.app')
@section('title', __('Create Notification'))
@section('content')
    <x-forms.post :action="route('admin.notification.store')" enctype="multipart/form-data">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Notification')
            </x-slot>
            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.notification.index')" :text="__('Cancel')" />
            </x-slot>
            <x-slot name="body">
                <div class="form-group row">
                    <label for="slider_image" class="col-md-2 col-form-label">@lang('Notification Icon')</label>
                    <div class="col-md-10">
                        <input type="file" name="notification_icon" id="notification_icon" class="form-control" required />
                        <img class="mt-2 d-none" id="blah" height="100px" width="100px"  alt="{{old('notification_icon')}}" />
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="title" class="col-md-2 col-form-label">@lang('Title (English)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{old('title')}}" name="title" id="title" class="form-control" required />
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="title_ar" class="col-md-2 col-form-label">@lang('Title (Arabic)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{old('title_ar')}}" name="title_ar" id="title_ar" class="form-control" required />
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="description" class="col-md-2 col-form-label">@lang('Description (English)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{old('description')}}" name="description" id="description" class="form-control" required />
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="description_ar" class="col-md-2 col-form-label">@lang('Description (Arabic)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{old('description_ar')}}" name="description_ar" id="description_ar" class="form-control" required />
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="type" class="col-md-2 col-form-label">@lang('Recipient Type')</label>
                    <div class="col-md-10">
                        <select name="type" id="type" class="form-control" required>
                            <option value="" selected disabled>@lang('-- Select --')</option>
                            <option value="merchant" {{ old('type') == 'merchant' ? 'selected' : '' }}>{{__("Merchants")}}</option>
                            <option value="user" {{ old('type') == 'user' ? 'selected' : '' }}>{{__("Customers")}}</option>
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="notification_type" class="col-md-2 col-form-label">@lang('Notification Type')</label>
                    <div class="col-md-10">
                        <select name="notification_type" id="notification_type" class="form-control" required>
                            <option value="" selected disabled>@lang('-- Select --')</option>
                            <option value="category" {{ old('notification_type') == 'category' ? 'selected' : '' }}>{{__("Category")}}</option>
                            <option value="service" {{ old('notification_type') == 'service' ? 'selected' : '' }}>{{__("Service")}}</option>
                            <option value="informative" {{ old('notification_type') == 'informative' ? 'selected' : '' }}>{{__("Informative")}}</option>
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row category-select" style="display: none;">
                    <label for="category_id" class="col-md-2 col-form-label">@lang('Category')</label>
                    <div class="col-md-10">
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="" selected disabled>@lang('-- Select Category --')</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} @if($category->name_ar) ({{ $category->name_ar }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row service-select" style="display: none;">
                    <label for="service_id" class="col-md-2 col-form-label">@lang('Service')</label>
                    <div class="col-md-10">
                        <select name="service_id" id="service_id" class="form-control">
                            <option value="" selected disabled>@lang('-- Select Service --')</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->title }} @if($service->title_ar) ({{ $service->title_ar }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div><!--form-group-->

            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create Notification')</button>
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
        $("#notification_icon").change(function(){
            $('#blah').removeClass('d-none');
            readURL(this);
        });

        // Handle notification type change
        $('#notification_type').change(function() {
            const type = $(this).val();

            // Hide all select boxes first
            $('.category-select, .service-select').hide();
            $('#category_id, #service_id').prop('required', false);

            // Show the relevant select box based on type
            if (type === 'category') {
                $('.category-select').show();
                $('#category_id').prop('required', true);
            } else if (type === 'service') {
                $('.service-select').show();
                $('#service_id').prop('required', true);
            }
        });

        // Trigger change on page load if there's an old value
        @if(old('notification_type'))
        $('#notification_type').trigger('change');
        @endif
    </script>
@endpush
