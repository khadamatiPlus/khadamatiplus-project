@extends('backend.layouts.app')

@section('title', __('Subscribers Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Subscribers Management')
        </x-slot>
        <x-slot name="body">
            @livewire('subscriber-table')
        </x-slot>
    </x-backend.card>
@endsection
