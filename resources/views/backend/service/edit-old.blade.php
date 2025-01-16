@inject('model', '\App\Domains\Service\Models\Service')

@extends('backend.layouts.app')

@section('title', __('Update Service'))

@section('content')
    <x-forms.post :action="route('admin.service.update', $service)" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PATCH" />
        <input type="hidden" name="id" value="{{$service->id}}" />

        <x-backend.card>
            <x-slot name="header">
                @lang('Update Service')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.service.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Merchant Selection -->
                        <div class="form-group row">
                            <label for="merchant_id" class="col-md-2 col-form-label">@lang('Merchant')</label>
                            <div class="col-md-10">
                                <select name="merchant_id" id="merchant_id" class="form-control" required>
                                    <option value="" disabled>@lang('-- Select --')</option>
                                    @foreach ($merchants as $value)
                                        <option value="{{ $value->id }}" {{ $service->merchant_id == $value->id ? 'selected' : '' }}>
                                            {{ $value->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Category Selection -->
                        <div class="form-group mt-3 row">
                            <label for="sub_category_id" class="col-md-2 col-form-label">@lang('Category')</label>
                            <div class="col-md-10">
                                <select name="sub_category_id" id="sub_category_id" class="form-control">
                                    @foreach($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            @foreach($category->children as $subCategory)
                                                <option value="{{ $subCategory->id }}"
                                                    {{ $subCategory->id == $service->sub_category_id ? 'selected' : '' }}>
                                                    - {{ $subCategory->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Service Title -->
                        <div class="form-group row">
                            <label for="title" class="col-md-2 col-form-label">@lang('Title')</label>
                            <div class="col-md-10">
                                <input type="text" name="title" id="title" class="form-control"
                                       value="{{ old('title', $service->title) }}" required>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group row">
                            <label for="description" class="col-md-2 col-form-label">@lang('Description')</label>
                            <div class="col-md-10">
                                <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $service->description) }}</textarea>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="form-group row">
                            <label for="tags" class="col-md-2 col-form-label">@lang('Tags')</label>
                            <div class="col-md-10">
                                <select name="tags[]" id="tags" class="form-control" multiple>
                                    @foreach($tags as $tag)
                                        <optgroup label="{{ $tag->name }}">
                                            <option value="{{ $tag->id }}"
                                                {{ in_array($tag->id, $service->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                            @foreach($tag->children as $subTag)
                                                <option value="{{ $subTag->id }}"
                                                    {{ in_array($subTag->id, $service->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    - {{ $subTag->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="form-group row">
                            <label for="images" class="col-md-2 col-form-label">@lang('Images')</label>
                            <div class="col-md-10">
                                <input type="file" name="images[]" id="images" class="form-control" multiple>
                                <div class="mt-3">
                                    @foreach ($service->images as $image)
                                        <img src="{{ asset('storage/' . $image->url) }}" alt="Service Image" class="img-thumbnail" width="150">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Section -->
                    <div class="col-md-4">
                        @foreach ($service->products as $index => $product)
                            <div class="product-item mb-4">
                                <h5>Product {{ $index + 1 }}</h5>
                                <input type="text" name="products[{{ $index }}][title]"
                                       value="{{ $product->title }}" class="form-control mb-2" placeholder="Product Title" required>
                                <input type="number" name="products[{{ $index }}][price]"
                                       value="{{ $product->price }}" class="form-control mb-2" placeholder="Price" required>
                                <textarea name="products[{{ $index }}][description]"
                                          class="form-control mb-2" placeholder="Description" rows="3">{{ $product->description }}</textarea>

                                <!-- Product Images -->
                                <label>@lang('Images')</label>
                                <input type="file" name="products[{{ $index }}][images][]" class="form-control mb-2" multiple>
                                <div class="mt-2">
                                    @foreach ($product->images as $image)
                                        <h1>ww</h1>
                                        <img src="{{ asset('storage/' . $image->url) }}" alt="Product Image" class="img-thumbnail" width="100">
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeProduct(this)">
                                    @lang('Remove Product')
                                </button>
                            </div>
                        @endforeach

                        <div id="newProductsContainer"></div>
                        <button type="button" class="btn btn-success btn-sm mt-3" onclick="addProduct()">
                            @lang('Add Product')
                        </button>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button type="submit" class="btn btn-primary float-right">@lang('Update Service')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection

@push('after-scripts')
    <script>
        let productIndex = {{ count($service->products) }}; // Initialize product index

        function addProduct() {
            const container = document.getElementById('newProductsContainer');
            const productHTML = `
                <div class="product-item mb-4">
                    <h5>Product ${productIndex + 1}</h5>
                    <input type="text" name="products[${productIndex}][title]" class="form-control mb-2" placeholder="Product Title" required>
                    <input type="number" name="products[${productIndex}][price]" class="form-control mb-2" placeholder="Price" required>
                    <textarea name="products[${productIndex}][description]" class="form-control mb-2" placeholder="Description" rows="3"></textarea>
                    <label>@lang('Images')</label>
                    <input type="file" name="products[${productIndex}][images][]" class="form-control mb-2" multiple>
                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeProduct(this)">
                        @lang('Remove Product')
            </button>
        </div>`;
            container.insertAdjacentHTML('beforeend', productHTML);
            productIndex++;
        }

        function removeProduct(button) {
            button.closest('.product-item').remove();
        }
    </script>
@endpush
