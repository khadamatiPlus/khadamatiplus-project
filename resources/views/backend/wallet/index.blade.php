@extends('backend.layouts.app')

@section('title', __('Wallet Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Wallet Management')
        </x-slot>
        <x-slot name="body">
            <div class="alert alert-info mb-0">
                @lang('Wallets and transactions are managed through the application services. Use the wallet API/service layer to credit, debit, or review balances.')
            </div>
        </x-slot>
    </x-backend.card>
@endsection
