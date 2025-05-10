@extends('backend.layouts.app')
@section('title', __('View Service'))
@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View Service') - {{ $service->title }}
        </x-slot>
        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.service.index')" :text="__('Back')" />
        </x-slot>
        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th>@lang('Service Title')</th>
                    <td>{{ $service->title }}</td>
                </tr>
                <tr>
                    <th>@lang('Merchant')</th>
                    <td>{{ $service->merchant->name }}</td>
                </tr>
                <tr>
                    <th>@lang('Category')</th>
                    <td>{{ $service->category->name }} > {{ $service->subCategory->name }}</td>
                </tr>
                <tr>
                    <th>@lang('Description')</th>
                    <td>{!! $service->description !!}</td>
                </tr>
                <tr>
                    <th>@lang('Duration')</th>
                    <td>{{ $service->duration }} minutes</td>
                </tr>
                <tr>
                    <th>@lang('Price Options')</th>
                    <td>
                        <ul class="list-unstyled">
                            @foreach($service->prices as $price)
                                <li>{{ $price->title }}: ${{ number_format($price->amount, 2) }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th>@lang('Tags')</th>
                    <td>
                        @foreach($service->tags as $tag)
                            <span class="badge badge-primary">{{ $tag->name }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>@lang('Service Images')</th>
                    <td>
                        <div class="d-flex flex-wrap">
                            @foreach($service->images as $image)
                                <div class="mr-2 mb-2" style="position: relative;">
                                    <img src="{{ $image->image }}" width="100" height="100" class="img-thumbnail" loading="lazy" onerror="this.src='{{ asset('img/brand/default.png') }}'" />
                                    @if($image->is_main)
                                        <span class="badge badge-success" style="position: absolute; top: 5px; left: 5px;">Main</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>@lang('Included Products')</th>
                    <td>
                        @if($service->products->count() > 0)
                            <div class="accordion" id="productsAccordion">
                                @foreach($service->products as $product)
                                    <div class="card">
                                        <div class="card-header" id="heading{{ $product->id }}">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $product->id }}" aria-expanded="true" aria-controls="collapse{{ $product->id }}">
                                                    {{ $product->title }} - ${{ number_format($product->price, 2) }}
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapse{{ $product->id }}" class="collapse" aria-labelledby="heading{{ $product->id }}" data-parent="#productsAccordion">
                                            <div class="card-body">
                                                <p>{{ $product->description }}</p>
                                                @if($product->images->count() > 0)
                                                    <div class="d-flex flex-wrap">
                                                        @foreach($product->images as $image)
                                                            <div class="mr-2 mb-2" style="position: relative;">
                                                                <img src="{{ Storage::url($image->path) }}" width="80" height="80" class="img-thumbnail" loading="lazy" onerror="this.src='{{ asset('img/brand/default.png') }}'" />
                                                                @if($image->is_main)
                                                                    <span class="badge badge-success" style="position: absolute; top: 5px; left: 5px;">Main</span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @lang('No products included')
                        @endif
                    </td>
                </tr>
            </table>
        </x-slot>
        <x-slot name="footer">
            <small class="float-right text-muted">
                <strong>@lang('Service Created'):</strong> {{ $service->created_at->format('Y-m-d H:i:s') }} ({{ $service->created_at->diffForHumans() }}),
                <strong>@lang('Last Updated'):</strong> {{ $service->updated_at->format('Y-m-d H:i:s') }} ({{ $service->updated_at->diffForHumans() }})
                @if($service->trashed())
                    <strong>@lang('Service Deleted'):</strong> {{ $service->deleted_at->format('Y-m-d H:i:s') }} ({{ $service->deleted_at->diffForHumans() }})
                @endif
            </small>
        </x-slot>
    </x-backend.card>
@endsection
