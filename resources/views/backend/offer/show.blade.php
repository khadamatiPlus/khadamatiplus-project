@extends('backend.layouts.app')

@section('title', __('View Offer'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View Offer')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-arrow-left"
                class="card-header-action"
                :href="route('admin.offer.index')"
                :text="__('Back')"
            />
        </x-slot>

        <x-slot name="body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $offer->id }}</td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td><strong>{{ $offer->title }}</strong></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $offer->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Coupon</th>
                            <td>{{ $offer->coupon ? $offer->coupon->code . ' - ' . $offer->coupon->discount_value . ($offer->coupon->discount_type == 'percentage' ? '%' : '') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $offer->category->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>App Service</th>
                            <td>{{ $offer->appService->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <td>{{ $offer->start_date ? $offer->start_date->format('Y-m-d H:i:s') : 'Not set' }}</td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <td>{{ $offer->end_date ? $offer->end_date->format('Y-m-d H:i:s') : 'No expiry' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $offer->is_active ? 'success' : 'danger' }}">
                                    {{ $offer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Featured</th>
                            <td>{{ $offer->is_featured ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{ $offer->createdBy->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td>{{ $offer->updatedBy->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $offer->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $offer->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Offer Image</h5>
                        </div>
                        <div class="card-body">
                            @if($offer->image)
                                <img src="{{ asset('storage/' . $offer->image) }}" class="img-fluid rounded" style="max-width: 100%; height: auto;">
                            @else
                                <p class="text-muted">No image uploaded</p>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Offer Status</h5>
                        </div>
                        <div class="card-body">
                            @if($offer->isValid())
                                <div class="alert alert-success">
                                    <strong>✓ Valid</strong> - This offer is currently active and can be used.
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <strong>✗ Invalid</strong> - This offer cannot be used.
                                    @if(!$offer->is_active)
                                        <br>• Offer is inactive
                                    @endif
                                    @if($offer->start_date && $offer->start_date > now())
                                        <br>• Offer has not started yet
                                    @endif
                                    @if($offer->end_date && $offer->end_date < now())
                                        <br>• Offer has expired
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.offer.edit', $offer) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('admin.offer.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </x-slot>
    </x-backend.card>
@endsection
