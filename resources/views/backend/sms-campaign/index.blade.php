@extends('backend.layouts.app')

@section('title', __('SMS Campaigns'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('SMS Campaigns')
            <a href="{{ route('admin.sms-campaign.create') }}" class="btn btn-primary btn-sm float-right">@lang('New Campaign')</a>
        </x-slot>

        <x-slot name="body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>@lang('Title')</th>
                    <th>@lang('Recipients')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Sent')</th>
                    <th>@lang('Failed')</th>
                    <th>@lang('Actions')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->title }}</td>
                        <td>{{ $campaign->recipient_type }}</td>
                        <td>{{ $campaign->status }}</td>
                        <td>{{ $campaign->sent_count }}</td>
                        <td>{{ $campaign->failed_count }}</td>
                        <td>
                            @if($campaign->status !== 'completed')
                                <form method="POST" action="{{ route('admin.sms-campaign.send', $campaign) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">@lang('Send')</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $campaigns->links() }}
        </x-slot>
    </x-backend.card>
@endsection
