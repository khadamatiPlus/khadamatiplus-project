@extends('backend.layouts.app')
@section('title', __('Edit Service'))
@section('content')
    <style>
        .form-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-section h5 {
            margin-bottom: 15px;
        }
        .dynamic-input {
            position: relative;
        }
        .remove-input {
            position: absolute;
            top: 50%;
            right: -30px;
            transform: translateY(-50%);
            cursor: pointer;
            color: red;
        }
    </style>

    <x-forms.post :action="route('admin.service.update', $service)" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PATCH" />
        <input type="hidden" name="id" value="{{$service->id}}" />
        <x-backend.card>
            <x-slot name="header">
                @lang('Edit Service')
            </x-slot>
            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.service.index')" :text="__('Cancel')" />
            </x-slot>
            <x-slot name="body">
                <div class="form-group mb-3">
                    <label for="merchant_id" class="form-label">Merchant</label>
                    <select name="merchant_id" id="merchant_id" class="form-control" required>
                        <option value="" disabled>@lang('-- Select --')</option>
                        @foreach ($merchants as $value)
                            <option value="{{$value->id}}" @selected($service->merchant_id == $value->id)>{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sub Category -->
                <div class="form-group mb-3">
                    <label for="sub_category_id" class="form-label">Sub Category</label>
                    <select class="form-control" id="sub_category_id" name="sub_category_id" required>
                        @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach($category->children as $subCategory)
                                    <option value="{{ $subCategory->id }}" @selected($service->sub_category_id == $subCategory->id)>- {{ $subCategory->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <!-- Title -->
                <div class="form-group mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Enter service title" value="{{ old('title', $service->title) }}" required>
                </div>

                <!-- Description -->
                <div class="form-group mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Enter service description">{{ old('description', $service->description) }}</textarea>
                </div>

                <!-- Price -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" id="price" name="price" class="form-control" placeholder="Enter price" value="{{ old('price', $service->price) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="new_price" class="form-label">New Price</label>
                        <input type="number" id="new_price" name="new_price" class="form-control" placeholder="Enter discounted price" value="{{ old('new_price', $service->new_price) }}">
                    </div>
                </div>

                <!-- Duration -->
                <div class="form-group mb-3">
                    <label for="duration" class="form-label">Duration</label>
                    <input type="text" id="duration" name="duration" class="form-control" placeholder="Enter duration" value="{{ old('duration', $service->duration) }}">
                </div>

                <!-- Tags -->
                <div class="form-group mb-3">
                    <label for="tags" class="form-label">Tags</label>
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

                <div class="form-section">
                    <h5>Service Images</h5>
                    <div id="service-images">
                        @foreach($service->images as $index => $image)
                            <div class="row align-items-end mb-2 dynamic-input" id="image-row-{{ $image->id }}">
                                <div class="col-md-8">
                                    <input disabled type="file" name="images[{{ $index }}][image]" class="form-control" placeholder="Image">
                                    @if($image->image)
                                        <img src="{{ asset($image->image) }}" alt="Service Image" class="mt-2" width="100">
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <select disabled readonly name="images[{{ $index }}][is_main]" class="form-control">
                                        <option value="1" @selected($image->is_main == 1)>Yes</option>
                                        <option value="0" @selected($image->is_main == 0)>No</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm delete-image" data-id="{{ $image->id }}">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="add-service-image">Add Image</button>
                </div>


                <div class="form-section">
                    <h5>Products</h5>
                    <div id="products">
                        @foreach($service->products as $index => $product)
                            <div class="product-item mb-4" id="product-{{ $product->id }}">
                                <h6>Product {{ $index + 1 }}</h6>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <input disabled type="text" name="products[{{ $index }}][title]" class="form-control" placeholder="Product Title" value="{{ old('products.' . $index . '.title', $product->title) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input  disabled type="number" name="products[{{ $index }}][price]" class="form-control" placeholder="Product Price" value="{{ old('products.' . $index . '.price', $product->price) }}" required>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <textarea readonly disabled name="products[{{ $index }}][description]" class="form-control" rows="2" placeholder="Product Description">{{ old('products.' . $index . '.description', $product->description) }}</textarea>
                                </div>
                                <div class="mb-2">
                                    <h6>Product Images</h6>
                                    <div id="product-images-{{ $index }}">
                                        @foreach($product->images as $imgIndex => $image)
                                            <div class="row align-items-end mb-2 dynamic-input">
                                                <div class="col-md-8">
                                                    <input disabled type="file" name="products[{{ $index }}][images][{{ $imgIndex }}][image]" class="form-control" placeholder="Image">
                                                    @if($image->image)
                                                        <img src="{{ asset($image->image) }}" alt="Product Image" class="mt-2" width="100">
                                                    @endif
                                                </div>
                                                <div class="col-md-4">
                                                    <select disabled name="products[{{ $index }}][images][{{ $imgIndex }}][is_main]" class="form-control">
                                                        <option value="1" @selected($image->is_main == 1)>Yes</option>
                                                        <option value="0" @selected($image->is_main == 0)>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm delete-product" data-id="{{ $product->id }}">Delete Product</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="add-product">Add Product</button>
                </div>

            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update Service')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
@endsection
@push('after-scripts')
    <script>
        let serviceImageCount = {{ count($service->images) }};
        let productCount = {{ count($service->products) }};

        document.getElementById('add-service-image').addEventListener('click', () => {
            const serviceImages = document.getElementById('service-images');
            const newImage = `<div class="row align-items-end mb-2 dynamic-input">
            <div class="col-md-8">
                <input type="file" name="images[${serviceImageCount}][image]" class="form-control" placeholder="Image">
            </div>
            <div class="col-md-4">
                <select name="images[${serviceImageCount}][is_main]" class="form-control">
                    <option value="" disabled selected>Main Image?</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>`;
            serviceImages.insertAdjacentHTML('beforeend', newImage);
            serviceImageCount++;
        });

        document.getElementById('add-product').addEventListener('click', () => {
            const products = document.getElementById('products');
            const newProduct = `<div class="product-item mb-4">
            <h6>Product ${productCount + 1}</h6>
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="text" name="products[${productCount}][title]" class="form-control" placeholder="Product Title" required>
                </div>
                <div class="col-md-6">
                    <input type="number" name="products[${productCount}][price]" class="form-control" placeholder="Product Price" required>
                </div>
            </div>
            <div class="mb-2">
                <textarea name="products[${productCount}][description]" class="form-control" rows="2" placeholder="Product Description"></textarea>
            </div>
            <div class="mb-2">
                <h6>Product Images</h6>
                <div id="product-images-${productCount}"></div>
                <button type="button" class="btn btn-secondary btn-sm add-product-image" data-index="${productCount}">Add Product Image</button>
            </div>
        </div>`;
            products.insertAdjacentHTML('beforeend', newProduct);
            productCount++;
        });

        document.addEventListener('click', function(event) {
            if (event.target && event.target.matches('.add-product-image')) {
                const productIndex = event.target.getAttribute('data-index');
                const productImages = document.getElementById('product-images-' + productIndex);
                const newProductImage = `<div class="row align-items-end mb-2 dynamic-input">
                <div class="col-md-8">
                    <input type="file" name="products[${productIndex}][images][${productImages.children.length}][image]" class="form-control" placeholder="Image">
                </div>
                <div class="col-md-4">
                    <select name="products[${productIndex}][images][${productImages.children.length}][is_main]" class="form-control">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>`;
                productImages.insertAdjacentHTML('beforeend', newProductImage);
            }
        });



        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".delete-image").forEach((button) => {
                button.addEventListener("click", function () {
                    const imageId = this.getAttribute("data-id");

                    // Confirmation dialog
                    if (!confirm("Are you sure you want to delete this image?")) {
                        return;
                    }

                    // Send an AJAX request to delete the image
                    fetch(`/service-images/${imageId}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                            "Content-Type": "application/json",
                        },
                    })
                        .then((response) => {
                            if (response.ok) {
                                // Remove the image row from the DOM
                                document.getElementById(`image-row-${imageId}`).remove();
                                alert("Image deleted successfully!");
                            } else {
                                alert("Failed to delete the image. Please try again.");
                            }
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                            alert("An error occurred. Please try again.");
                        });
                });
            });
        });



        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-product').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.dataset.id;
                    if (confirm('Are you sure you want to delete this product?')) {
                        fetch(`/products/${productId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById(`product-${productId}`).remove();
                                    alert(data.message);
                                } else {
                                    alert('Failed to delete the product.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred while deleting the product.');
                            });
                    }
                });
            });
        });

    </script>
@endpush
