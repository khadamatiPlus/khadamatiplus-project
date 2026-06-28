@extends('backend.layouts.app')
@section('title', __('Application Settings'))
@section('content')
    <x-forms.post :action="route('admin.settings.update')" enctype="multipart/form-data">
        <x-backend.card>
            <x-slot name="header">
                @lang('Application Settings')
            </x-slot>
            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.dashboard')" :text="__('Cancel')" />
            </x-slot>
            <x-slot name="body">
                <div class="form-group row">
                    <label for="app_profit_percentage" class="col-md-3 col-form-label">@lang('App Profit Percentage')</label>
                    <div class="col-md-9">
                        <input type="number" step="0.01" min="0" max="100" name="app_profit_percentage" id="app_profit_percentage" value="{{ old('app_profit_percentage', getSettingByKey('app_profit_percentage', 0)) }}" class="form-control" />
                        <small class="form-text text-muted">@lang('This percentage is applied to platform-level profit calculations and can be used by wallets and commissions later.') </small>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save Settings')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
