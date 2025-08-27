@inject('model', '\App\Domains\Notification\Models\Notification')

@extends('backend.layouts.app')

@section('title', __('Update Notification'))

@section('content')
    <x-forms.post :action="route('admin.notification.update', $notification)" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PATCH" />
        <x-backend.card>
            <x-slot name="header">
                @lang('Update Notification')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.notification.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <input type="hidden" name="id" value="{{$notification->id}}" />
                <div class="form-group row">
                    <label for="notification_icon" class="col-md-2 col-form-label">@lang('Notification Icon')</label>
                    <div class="col-md-10">
                        <input type="file" name="notification_icon" id="notification_icon" class="form-control" />
                        <img src="{{storageBaseLink(\App\Enums\Core\StoragePaths::NOTIFICATION_ICON.$notification->notification_icon)}}" class="mt-2" id="blah" height="100px" width="100px"  alt="{{old('notification_icon')}}" />
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="title" class="col-md-2 col-form-label">@lang('Title (English)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{$notification->title??old('title')}}" name="title" id="title" class="form-control" required />
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="title_ar" class="col-md-2 col-form-label">@lang('Title (Arabic)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{$notification->title_ar??old('title_ar')}}" name="title_ar" id="title_ar" class="form-control" required />
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="description" class="col-md-2 col-form-label">@lang('Description (English)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{$notification->description??old('description')}}" name="description" id="description" class="form-control" required />
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="description_ar" class="col-md-2 col-form-label">@lang('Description (Arabic)')</label>
                    <div class="col-md-10">
                        <input type="text" value="{{$notification->description_ar??old('description_ar')}}" name="description_ar" id="description_ar" class="form-control" required />
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="type" class="col-md-2 col-form-label">@lang('Recipient Type')</label>
                    <div class="col-md-10">
                        <select name="type" id="type" class="form-control" required>
                            <option value="" disabled>@lang('-- Select --')</option>
                            <option value="merchant" {{ $notification->type == 'merchant' ? 'selected' : '' }}>{{__("Merchants")}}</option>
                            <option value="user" {{ $notification->type == 'user' ? 'selected' : '' }}>{{__("Users")}}</option>
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="notification_type" class="col-md-2 col-form-label">@lang('Notification Type')</label>
                    <div class="col-md-10">
                        <select name="notification_type" id="notification_type" class="form-control" required>
                            <option value="" disabled>@lang('-- Select --')</option>
                            <option value="category" {{ $notification->notification_type == 'category' ? 'selected' : '' }}>{{__("Category")}}</option>
                            <option value="service" {{ $notification->notification_type == 'service' ? 'selected' : '' }}>{{__("Service")}}</option>
                            <option value="informative" {{ $notification->notification_type == 'informative' ? 'selected' : '' }}>{{__("Informative")}}</option>
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row category-select" style="display: {{ $notification->notification_type == 'category' ? 'block' : 'none' }};">
                    <label for="category_id" class="col-md-2 col-form-label">@lang('Category')</label>
                    <div class="col-md-10">
                        <select name="category_id" id="category_id" class="form-control" {{ $notification->notification_type == 'category' ? 'required' : '' }}>
                            <option value="" disabled>@lang('-- Select Category --')</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $notification->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} @if($category->name_ar) ({{ $category->name_ar }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row service-select" style="display: {{ $notification->notification_type == 'service' ? 'block' : 'none' }};">
                    <label for="service_id" class="col-md-2 col-form-label">@lang('Service')</label>
                    <div class="col-md-10">
                        <select name="service_id" id="service_id" class="form-control" {{ $notification->notification_type == 'service' ? 'required' : '' }}>
                            <option value="" disabled>@lang('-- Select Service --')</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ $notification->service_id == $service->id ? 'selected' : '' }}>
                                    {{ $service->title }} @if($service->title_ar) ({{ $service->title_ar }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div><!--form-group-->

            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update Notification')</button>
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
    </script>
@endpush
