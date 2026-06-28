@extends('backend.layouts.app')

@section('title', __('View App Service'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View App Service')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-arrow-left"
                class="card-header-action"
                :href="route('admin.app-service.index')"
                :text="__('Back')"
            />
        </x-slot>

        <x-slot name="body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">{{ __('ID') }}</th>
                            <td>{{ $appService->id }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <td>{{ $appService->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Description') }}</th>
                            <td>{!! $appService->description ?? '-'!!}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Category') }}</th>
                            <td>{{ $appService->category->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Sub Category') }}</th>
                            <td>{{ $appService->subCategory->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Base Price') }}</th>
                            <td>{{ $appService->base_price }} {{ $appService->currency }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Price Type') }}</th>
                            <td>{{ $appService->price_type }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Discount') }}</th>
                            <td>{{ $appService->discount }}%</td>
                        </tr>
                        <tr>
                            <th>{{ __('Delivery Time') }}</th>
                            <td>{{ $appService->delivery_time }} {{ $appService->delivery_time_unit }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Free Revisions') }}</th>
                            <td>{{ $appService->free_revisions }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Status') }}</th>
                            <td>
                                <span class="badge bg-{{ $appService->status == 'active' ? 'success' : ($appService->status == 'draft' ? 'warning' : 'secondary') }}">
                                    {{ $appService->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Visibility') }}</th>
                            <td>{{ $appService->visibility }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Language') }}</th>
                            <td>{{ $appService->language }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Scope') }}</th>
                            <td>{{ $appService->scope }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Featured') }}</th>
                            <td>{{ $appService->is_featured ? __('Yes') : __('No') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Urgent') }}</th>
                            <td>{{ $appService->is_urgent ? __('Yes') : __('No') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Online') }}</th>
                            <td>{{ $appService->is_online ? __('Yes') : __('No') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Created By') }}</th>
                            <td>{{ $appService->createdBy->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Updated By') }}</th>
                            <td>{{ $appService->updatedBy->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Created At') }}</th>
                            <td>{{ $appService->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Updated At') }}</th>
                            <td>{{ $appService->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>{{ __('Images') }}</h5>
                    @if($appService->images && is_array($appService->images))
                        <div class="row">
                            @foreach($appService->images as $image)
                                <div class="col-md-4 mb-2">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" style="width: 100%; height: 150px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __('No images') }}</p>
                    @endif

                    <h5 class="mt-3">{{ __('Video URL') }}</h5>
                    @if($appService->video_url)
                        <a href="{{ $appService->video_url }}" target="_blank" class="btn btn-info">{{ __('Watch Video') }}</a>
                    @else
                        <p>{{ __('No video') }}</p>
                    @endif

                    <h5 class="mt-3">{{ __('Customer Requirements') }}</h5>
                    <p>{{ $appService->customer_requirements ?? '-' }}</p>

                    <h5 class="mt-3">{{ __('Tags') }}</h5>
                    @if($appService->tags && is_array($appService->tags))
                        <div>
                            @foreach($appService->tags as $tag)
                                <span class="badge bg-primary">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __('No tags') }}</p>
                    @endif

                    <h5 class="mt-3">{{ __('Availability Days') }}</h5>
                    @if($appService->availability_days && is_array($appService->availability_days))
                        <div>
                            @foreach($appService->availability_days as $day)
                                <span class="badge bg-success">{{ $day }}</span>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __('No availability days set') }}</p>
                    @endif

                    <h5 class="mt-3">{{ __('Variants') }}</h5>
                    @if($appService->variants && is_array($appService->variants))
                        @foreach($appService->variants as $variant)
                            <div class="card mb-2">
                                <div class="card-header bg-light">
                                    <strong>{{ $variant['name'] ?? __('Unnamed Variant') }}</strong>
                                    <span class="badge bg-info ms-2">{{ $variant['type'] ?? 'single' }}</span>
                                    <span class="badge {{ ($variant['required'] ?? 'required') == 'required' ? 'bg-warning' : 'bg-secondary' }} ms-1">
                                        {{ ($variant['required'] ?? 'required') == 'required' ? __('Required') : __('Optional') }}
                                    </span>
                                </div>
                                <div class="card-body p-2">
                                    @if(isset($variant['options']) && is_array($variant['options']))
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Option Name') }}</th>
                                                    <th>{{ __('Additional Price') }}</th>
                                                    <th>{{ __('Discount Price') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($variant['options'] as $option)
                                                    <tr>
                                                        <td>{{ $option['name'] ?? '-' }}</td>
                                                        <td>{{ $option['price'] ?? 0 }}</td>
                                                        <td>
                                                            {{ isset($option['discount_price']) && $option['discount_price'] != 0 ? $option['discount_price'] : '--' }}
                                                        </td>                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="mb-0 text-muted">{{ __('No options defined') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>{{ __('No variants defined') }}</p>
                    @endif
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.app-service.edit', $appService) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                <a href="{{ route('admin.app-service.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
            </div>
        </x-slot>
    </x-backend.card>
@endsection
