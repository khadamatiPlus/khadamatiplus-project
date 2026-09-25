@extends('backend.layouts.app')

@section('title', __('SMS Campaign'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Create SMS Campaign')
        </x-slot>

        <x-slot name="body">
            <form method="POST" action="{{ route('admin.sms-campaign.store') }}">
                @csrf

                <div class="form-group">
                    <label for="title">@lang('Title')</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="recipient_type">@lang('Recipients')</label>
                    <select name="recipient_type" id="recipient_type" class="form-control" required>
                        <option value="all">@lang('All users')</option>
                        <option value="merchants">@lang('Merchants only')</option>
                        <option value="customers">@lang('Customers only')</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">@lang('Message')</label>
                    <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">@lang('Create Campaign')</button>
            </form>
        </x-slot>
    </x-backend.card>
@endsection
