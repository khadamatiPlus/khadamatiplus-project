@extends('backend.layouts.app')

@section('title', __('App Services Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('App Services Management')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('admin.app-service.create')"
                :text="__('Create App Service')"
            />
        </x-slot>

        <x-slot name="body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appServices as $appService)
                            <tr>
                                <td>{{ $appService->id }}</td>
                                <td>{{ $appService->name }}</td>
                                <td>{{ $appService->category->name ?? '-' }}</td>
                                <td>{{ $appService->base_price }} {{ $appService->currency }}</td>
                                <td>
                                    <span class="badge bg-{{ $appService->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ $appService->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.app-service.show', $appService) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.app-service.edit', $appService) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.app-service.delete', $appService) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No app services found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $appServices->links() }}
            </div>
        </x-slot>
    </x-backend.card>
@endsection
