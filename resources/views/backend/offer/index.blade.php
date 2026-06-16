@extends('backend.layouts.app')

@section('title', __('Offers Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Offers Management')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('admin.offer.create')"
                :text="__('Create Offer')"
            />
        </x-slot>

        <x-slot name="body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>App Service</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            <tr>
                                <td>{{ $offer->id }}</td>
                                <td>
                                    <strong>{{ $offer->title }}</strong>
                                    @if($offer->image)
                                        <br><img src="{{ asset('storage/' . $offer->image) }}" style="width: 50px; height: 50px; object-fit: cover; margin-top: 5px;">
                                    @endif
                                </td>
                                <td>{{ $offer->category->name ?? '-' }}</td>
                                <td>{{ $offer->appService->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $offer->is_active ? 'success' : 'danger' }}">
                                        {{ $offer->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    @if($offer->is_featured)
                                        <span class="badge bg-warning">★ Featured</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.offer.show', $offer) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.offer.edit', $offer) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.offer.delete', $offer) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No offers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $offers->links() }}
            </div>
        </x-slot>
    </x-backend.card>
@endsection
