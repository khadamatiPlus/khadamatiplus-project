@extends('backend.layouts.app')

@section('title', __('Highlights Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Highlights Management')
        </x-slot>

        @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.highlight.store'))
            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    :href="route('admin.highlight.create')"
                    :text="__('Create Highlight')"
                />
            </x-slot>
        @endif
        <x-slot name="body">
            @livewire('highlight-table')
        </x-slot>
    </x-backend.card>
@endsection
