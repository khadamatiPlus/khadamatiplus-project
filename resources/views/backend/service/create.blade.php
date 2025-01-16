@extends('backend.layouts.app')
@section('title', __('Create Service'))
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


    <x-forms.post :action="route('admin.service.store')" enctype="multipart/form-data">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Service')
            </x-slot>
            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.service.index')" :text="__('Cancel')" />
            </x-slot>
            <x-slot name="body">
        <div class="form-group mb-3">
            <label for="sub_category_id" class="form-label">Merchant</label>
            <select  name="merchant_id" id="merchant_id" class="form-control " required>
                <option value="" selected disabled>@lang('-- Select --')</option>
                @foreach ($merchants as $value)
                    <option  value="{{$value->id}}">{{$value->name}}</option>
                @endforeach
            </select>
        </div>
        <!-- Sub Category -->
        <div class="form-group mb-3">
            <label for="sub_category_id" class="form-label">Sub Category</label>
            <select class="form-control" id="sub_category_id" name="sub_category_id">
                @foreach($categories as $category)
                    <optgroup label="{{ $category->name }}">
                        @foreach($category->children as $subCategory)
                            <option value="{{ $subCategory->id }}">- {{ $subCategory->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <!-- Title -->
        <div class="form-group mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Enter service title" required>
        </div>

        <!-- Description -->
        <div class="form-group mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Enter service description" ></textarea>
        </div>

        <!-- Price -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="price" class="form-label">Price</label>
                <input type="number" id="price" name="price" class="form-control" placeholder="Enter price" required>
            </div>
            <div class="col-md-6">
                <label for="new_price" class="form-label">New Price</label>
                <input type="number" id="new_price" name="new_price" class="form-control" placeholder="Enter discounted price (optional)">
            </div>
        </div>

        <!-- Duration -->
        <div class="form-group mb-3">
            <label for="duration" class="form-label">Duration</label>
            <input type="text" id="duration" name="duration" class="form-control" placeholder="Enter duration (optional)">
        </div>

        <!-- Tags -->
        <div class="form-group mb-3">
            <label for="tags" class="form-label">Tags</label>
            <select id="tags" name="tags[]" class="form-control" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Service Images -->
        <div class="form-section">
            <h5>Service Images</h5>
            <div id="service-images">
                <div class="row align-items-end mb-2 dynamic-input">
                    <div class="col-md-8">
                        <input type="file" name="images[0][image]" class="form-control" placeholder="Image" required>
                    </div>
                    <div class="col-md-4">
                        <select name="images[0][is_main]" class="form-control" required>
                            <option value="" disabled selected>Main Image?</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary btn-sm" id="add-service-image">Add Image</button>
        </div>

        <!-- Products -->
        <div class="form-section">
            <h5>Products</h5>
            <div id="products">
                <div class="product-item mb-4">
                    <h6>Product 1</h6>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <input type="text" name="products[0][title]" class="form-control" placeholder="Product Title" required>
                        </div>
                        <div class="col-md-6">
                            <input type="number" name="products[0][price]" class="form-control" placeholder="Product Price" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <textarea name="products[0][description]" class="form-control" rows="2" placeholder="Product Description"></textarea>
                    </div>
                    <div class="mb-2">
                        <h6>Product Images</h6>
                        <div id="product-images-0">
                            <div class="row align-items-end mb-2 dynamic-input">
                                <div class="col-md-8">
                                    <input type="file" name="products[0][images][0][image]" class="form-control" placeholder="Image" required>
                                </div>
                                <div class="col-md-4">
                                    <select name="products[0][images][0][is_main]" class="form-control" required>
                                        <option value="" disabled selected>Main Image?</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm add-product-image" data-index="0">Add Product Image</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary btn-sm" id="add-product">Add Product</button>
        </div>
            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create Service')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
@endsection
@push('after-scripts')
<script>
    let serviceImageCount = 1;
    let productCount = 1;

    document.getElementById('add-service-image').addEventListener('click', () => {
        const serviceImages = document.getElementById('service-images');
        const newImage = `<div class="row align-items-end mb-2 dynamic-input">
            <div class="col-md-8">
                <input type="file" name="images[${serviceImageCount}][image]" class="form-control" placeholder="Image" required>
            </div>
            <div class="col-md-4">
                <select name="images[${serviceImageCount}][is_main]" class="form-control" required>
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
                <div id="product-images-${productCount}">
                    <div class="row align-items-end mb-2 dynamic-input">
                        <div class="col-md-8">
                            <input type="file" name="products[${productCount}][images][0][image]" class="form-control" placeholder="Image" required>
                        </div>
                        <div class="col-md-4">
                            <select name="products[${productCount}][images][0][is_main]" class="form-control" required>
                                <option value="" disabled selected>Main Image?</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary btn-sm add-product-image" data-index="${productCount}">Add Product Image</button>
            </div>
        </div>`;
        products.insertAdjacentHTML('beforeend', newProduct);
        productCount++;
    });

    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('add-product-image')) {
            const index = event.target.getAttribute('data-index');
            const productImages = document.getElementById(`product-images-${index}`);
            const newImage = `<div class="row align-items-end mb-2 dynamic-input">
                <div class="col-md-8">
                    <input type="file" name="products[${index}][images][${productImages.childElementCount}][image]" class="form-control" placeholder="Image" required>
                </div>
                <div class="col-md-4">
                    <select name="products[${index}][images][${productImages.childElementCount}][is_main]" class="form-control" required>
                        <option value="" disabled selected>Main Image?</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>`;
            productImages.insertAdjacentHTML('beforeend', newImage);
        }
    });
</script>

    @endpush
