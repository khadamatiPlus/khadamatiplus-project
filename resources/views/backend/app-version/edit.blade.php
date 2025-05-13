@inject('model', '\App\Domains\AppVersion\Models\AppVersion')

@extends('backend.layouts.app')

@section('title', __('Update App Version'))

@section('content')
    <x-forms.post :action="route('admin.appVersion.update', $appVersion)" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PATCH" />
        <x-backend.card>
            <x-slot name="header">
                @lang('Update App Version')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.dashboard')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <input type="hidden" name="id" value="{{$appVersion->id}}" />
                <div class="form-group row">
                    <label for="current_version_android" class="col-md-2 col-form-label">@lang('Current Version Android')</label>
                    <div class="col-md-10">
                        <input name="current_version_android" id="current_version_android" value="{{old('current_version_android')??$appVersion->current_version_android}}" class="form-control"/>
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="current_version_ios" class="col-md-2 col-form-label">@lang('Current Version IOS')</label>
                    <div class="col-md-10">
                        <input name="current_version_ios" id="current_version_ios" value="{{old('current_version_ios')??$appVersion->current_version_ios}}" class="form-control"/>
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="current_version_huawei" class="col-md-2 col-form-label">@lang('Current Version Huawei')</label>
                    <div class="col-md-10">
                        <input name="current_version_huawei" id="current_version_huawei" value="{{old('current_version_huawei')??$appVersion->current_version_huawei}}" class="form-control"/>
                    </div>
                </div><!--form-group-->

            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update App Version')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
