@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <link href="{{asset('backend/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <x-backend.card>
        <x-slot name="header">
            @lang('Welcome :Name', ['name' => $logged_in_user->name])
        </x-slot>

        <x-slot name="body">
            @livewire('backend.real-time-orders')
        </x-slot>

    </x-backend.card>

@endsection
