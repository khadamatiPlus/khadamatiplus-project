@extends('backend.layouts.app')

@section('title', __('Contact Us Submission Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Contact Us Submission Management')
        </x-slot>
        <x-slot name="body">
            @livewire('contact-us-submission-table')
        </x-slot>
    </x-backend.card>
@endsection
