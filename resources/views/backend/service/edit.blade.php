@extends('backend.layouts.app')
@section('title', __('Edit Service'))
@section('content')
    <style>
        .card-section {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 24px;
            border: none;
        }
        .card-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }
        .dynamic-item {
            position: relative;
            padding: 16px;
            border: 1px dashed #cbd5e0;
            border-radius: 6px;
            margin-bottom: 16px;
            background: #f8fafc;
        }
        .remove-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            color: #e53e3e;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 1.2rem;
        }
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 12px;
        }
        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        .file-upload-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .file-upload-label:hover {
            border-color: #4299e1;
            background-color: #ebf8ff;
        }
        .file-upload-icon {
            font-size: 2rem;
            color: #4299e1;
            margin-bottom: 0.5rem;
        }
        .tag-item {
            display: inline-flex;
            align-items: center;
            background-color: #e2e8f0;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .tag-remove {
            margin-left: 0.5rem;
            cursor: pointer;
            color: #718096;
        }
        .select2-container--default .select2-selection--multiple {
            min-height: 38px;
            border: 1px solid #ced4da;
        }
        .existing-image {
            position: relative;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .delete-existing-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0,0,0,0.5);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .price-option {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #eee;
        }
        .has-error {
            border-color: #dc3545;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>

    <x-forms.post :action="route('admin.service.update', $service)" enctype="multipart/form-data" class="needs-validation" id="service-form">
        @method('PATCH')
        <input type="hidden" name="id" value="{{ $service->id }}">

        <x-backend.card>
            <x-slot name="header">
                @lang('Edit Service: :name', ['name' => $service->title])
                <small class="text-muted d-block mt-1">Update the details of this service offering</small>
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-arrow-left"
                    class="card-header-action btn btn-light"
                    :href="route('admin.service.index')"
                    :text="__('Back to Services')" />
            </x-slot>

            <x-slot name="body">
                <!-- Basic Information Section -->
                <div class="card card-section mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Merchant Selection -->
                            <div class="col-md-6 mb-3">
                                <label for="merchant_id" class="form-label">Merchant </label>
                                <select name="merchant_id" id="merchant_id" class="form-control select2" required>
                                    <option value="" disabled>Select Merchant</option>
                                    @foreach ($merchants as $merchant)
                                        <option value="{{ $merchant->id }}" {{ old('merchant_id', $service->merchant_id) == $merchant->id ? 'selected' : '' }}>
                                            {{ $merchant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('merchant_id')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sub Category Selection -->
                            <div class="col-md-6 mb-3">
                                <label for="sub_category_id" class="form-label">Category </label>
                                <select class="form-control select2" id="sub_category_id" name="sub_category_id" required>
                                    <option value="" disabled>Select Category</option>
                                    @foreach($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            @foreach($category->children as $subCategory)
                                                <option value="{{ $subCategory->id }}" {{ old('sub_category_id', $service->sub_category_id) == $subCategory->id ? 'selected' : '' }}>
                                                    {{ $subCategory->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('sub_category_id')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Service Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Service Title </label>
                            <input type="text" id="title" name="title" class="form-control"
                                   placeholder="e.g. Premium Haircut & Styling"
                                   value="{{ old('title', $service->title) }}" required>
                            @error('title')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Service Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description </label>
                            <textarea id="description" name="description" class="form-control" rows="4"
                                      placeholder="Describe the service in detail..." required>{{ old('description', $service->description) }}</textarea>
                            @error('description')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing & Details Section -->
                <div class="card card-section mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pricing & Details</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="add-price-btn">
                            <i class="fas fa-plus"></i> Add Price Option
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="price-options-container">
                            @if(old('prices', $service->prices->count()) > 0)
                                @foreach(old('prices', $service->prices) as $index => $price)
                                    <div class="price-option mb-3" data-index="{{ $index }}">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label class="form-label">Price Title </label>
                                                <input type="text" name="prices[{{ $price->id ?? $index }}][title]"
                                                       class="form-control price-title"
                                                       placeholder="e.g., Standard, Premium, VIP"
                                                       value="{{ $price['title'] ?? $price->title }}" required>
                                                @error("prices.$index.title")
                                                <div class="error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Price </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" name="prices[{{ $price->id ?? $index }}][amount]"
                                                           class="form-control price-amount"
                                                           placeholder="0.00" min="0.01" step="0.01"
                                                           value="{{ $price['amount'] ?? $price->amount }}" required>
                                                </div>
                                                @error("prices.$index.amount")
                                                <div class="error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-price-btn"
                                                        data-price-id="{{ $price->id ?? '' }}"
                                                    {{ $loop->first && count(old('prices', $service->prices)) == 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @if(isset($price->id))
                                            <input type="hidden" name="prices[{{ $price->id }}][id]" value="{{ $price->id }}">
                                            <input type="hidden" name="deleted_prices[{{ $price->id }}]" value="0">
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <!-- Fallback to single price if no prices exist -->
                                <div class="price-option mb-3" data-index="0">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="form-label">Price Title </label>
                                            <input type="text" name="prices[0][title]"
                                                   class="form-control price-title"
                                                   placeholder="e.g., Standard, Premium, VIP" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Price </label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" name="prices[0][amount]"
                                                       class="form-control price-amount"
                                                       placeholder="0.00" min="0.01" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-price-btn" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Duration -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="duration" class="form-label">Duration (in minutes) </label>
                                <div class="input-group">
                                    <input type="number" id="duration" name="duration" class="form-control"
                                           placeholder="e.g., 30, 60, 90" min="1"
                                           value="{{ old('duration', $service->duration) }}" required>
                                    <span class="input-group-text">minutes</span>
                                </div>
                                @error('duration')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tags Section -->
                <div class="card card-section mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Tags & Keywords</h5>
                    </div>
                    <div class="card-body">
                        <label for="tags" class="form-label">Service Tags</label>
                        <select id="tags" name="tags[]" class="form-control select2" multiple="multiple">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}"
                                    {{ in_array($tag->id, old('tags', $service->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tags')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Service Images Section -->
                <!-- Service Images Section -->
                <div class="card card-section mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Service Images</h5>
                        <small class="text-muted">At least one image required</small>
                    </div>
                    <div class="card-body">
                        <!-- Existing Images -->
                        @if($service->images->isNotEmpty())
                            <div class="mb-4">
                                <label class="form-label">Current Images</label>
                                <div class="image-preview-container">
                                    @foreach($service->images as $image)
                                        <div class="existing-image" data-image-id="{{ $image->id }}">
                                            <img src="{{ storageBaseLink($image->image) }}" class="image-preview" alt="Service Image">
                                            <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                                            <input type="checkbox" name="deleted_images[{{ $image->id }}]" value="1" class="d-none delete-flag">
                                            <button type="button" class="delete-existing-image" data-id="{{ $image->id }}">
                                                <i class="cil-trash"></i>
                                            </button>
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="radio"
                                                       name="main_service_image"
                                                       id="main-image-{{ $image->id }}"
                                                       value="{{ $image->id }}"
                                                    {{ $image->is_main ? 'checked' : '' }}>
                                                <label class="form-check-label" for="main-image-{{ $image->id }}">
                                                    Main Image {{ $image->id }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- New Images Upload -->
                        <div class="file-upload-wrapper">
                            <input type="file" id="service-images-input" name="service_images[]" class="d-none" multiple accept="image/*">
                            <label for="service-images-input" class="file-upload-label">
                                <i class="cil-cloud-upload file-upload-icon"></i>
                                <span>Click to upload or drag and drop</span>
                                <small class="text-muted">PNG, JPG, JPEG up to 5MB</small>
                            </label>
                        </div>

                        <div id="service-images-preview" class="image-preview-container mt-3"></div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="card card-section mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Included Products</h5>
                        <button type="button" id="add-product-btn" class="btn btn-sm btn-primary">
                            <i class="cil-plus"></i> Add Product
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="products-container">
                            @if($service->products->isEmpty())
                                <div class="alert alert-info" id="no-products-alert">
                                    No products added yet. Click "Add Product" to include products in this service.
                                </div>
                            @else
                                @foreach($service->products as $product)
                                    <div class="dynamic-item product-item" data-product-id="{{ $product->id }}">
                                        <input type="hidden" name="products[{{ $product->id }}][_delete]" value="0">
                                        <button type="button" class="remove-btn delete-product" data-id="{{ $product->id }}">
                                            <i class="cil-x"></i>
                                        </button>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Product Name </label>
                                                <input type="text" name="products[{{ $product->id }}][title]"
                                                       class="form-control"
                                                       value="{{ old("products.$product->id.title", $product->title) }}"
                                                       placeholder="e.g. Shampoo, Hair Mask" required>
                                                @error("products.$product->id.title")
                                                <div class="error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Product Price </label>
                                                <input type="number" name="products[{{ $product->id }}][price]"
                                                       class="form-control"
                                                       value="{{ old("products.$product->id.price", $product->price) }}"
                                                       placeholder="0.00" min="0" step="0.01" required>
                                                @error("products.$product->id.price")
                                                <div class="error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Product Description</label>
                                            <textarea name="products[{{ $product->id }}][description]"
                                                      class="form-control" rows="2"
                                                      placeholder="Describe this product...">{{ old("products.$product->id.description", $product->description) }}</textarea>
                                        </div>

                                        <!-- Existing Product Images -->
                                        @if($product->images->isNotEmpty())
                                            <div class="mb-3">
                                                <label class="form-label">Current Images</label>
                                                <div class="image-preview-container">
                                                    @foreach($product->images as $image)
                                                        <div class="existing-image" data-image-id="{{ $image->id }}">
                                                            <img src="{{ Storage::url($image->path) }}" class="image-preview" alt="Product Image">
                                                            <input type="hidden" name="products[{{ $product->id }}][existing_images][]" value="{{ $image->id }}">
                                                            <button type="button" class="delete-existing-image" data-id="{{ $image->id }}">
                                                                <i class="cil-trash"></i>
                                                            </button>
                                                            <div class="form-check mt-2 text-center">
                                                                <input class="form-check-input" type="radio"
                                                                       name="products[{{ $product->id }}][main_image]"
                                                                       id="product-image-{{ $image->id }}"
                                                                       value="{{ $image->id }}"
                                                                    {{ $image->is_main ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="product-image-{{ $image->id }}">
                                                                    Main Image
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- New Product Images Upload -->
                                        <div class="product-images-section">
                                            <label class="form-label">Add More Images</label>
                                            <div class="file-upload-wrapper mb-2">
                                                <input type="file" id="product-images-{{ $product->id }}"
                                                       name="products[{{ $product->id }}][new_images][]"
                                                       class="d-none" multiple accept="image/*">
                                                <label for="product-images-{{ $product->id }}" class="file-upload-label py-1">
                                                    <i class="cil-image"></i>
                                                    <span>Upload Product Images</span>
                                                </label>
                                            </div>

                                            <div class="image-preview-container product-images-preview"
                                                 id="product-images-preview-{{ $product->id }}"></div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="cil-save"></i> Update Service
                    </button>
                </div>
            </x-slot>
        </x-backend.card>
    </x-forms.post>

    <!-- Product Template (Hidden) -->
    <div id="product-template" class="d-none">
        <div class="dynamic-item product-item" data-index="__INDEX__">
            <button type="button" class="remove-btn remove-product">
                <i class="cil-x"></i>
            </button>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Product Name </label>
                    <input type="text" name="new_products[__INDEX__][title]" class="form-control" placeholder="e.g. Shampoo, Hair Mask" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Product Price </label>
                    <input type="number" name="new_products[__INDEX__][price]" class="form-control" placeholder="0.00" min="0" step="0.01" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Description</label>
                <textarea name="new_products[__INDEX__][description]" class="form-control" rows="2" placeholder="Describe this product..."></textarea>
            </div>

            <div class="product-images-section">
                <label class="form-label">Product Images</label>
                <div class="file-upload-wrapper mb-2">
                    <input type="file" id="new-product-images-__INDEX__" name="new_products[__INDEX__][images][]" class="d-none" multiple accept="image/*">
                    <label for="new-product-images-__INDEX__" class="file-upload-label py-1">
                        <i class="cil-image"></i>
                        <span>Upload Product Images</span>
                    </label>
                </div>

                <div class="image-preview-container product-images-preview" id="new-product-images-preview-__INDEX__"></div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Select an option',
                allowClear: true,
                width: '100%'
            });

            // Form validation
            $('#service-form').on('submit', function(e) {
                let isValid = true;

                // Validate required fields
                $(this).find('[required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Validate at least one price option
                if ($('.price-option').length === 0) {
                    $('#price-options-container').append('<div class="error-message">At least one price option is required.</div>');
                    isValid = false;
                }

                // Validate at least one service image (existing or new)
                if ($('#service-images-preview img').length === 0 && $('input[name^="existing_images"]').length === 0) {
                    $('#service-images-preview').before('<div class="error-message">At least one service image is required.</div>');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: $('.is-invalid').first().offset().top - 100
                    }, 500);
                }
            });

            // Price Options Management
            $('#add-price-btn').click(function() {
                const newOption = $('.price-option').first().clone();
                const newIndex = $('.price-option').length;

                newOption.attr('data-index', newIndex);
                newOption.find('input').val('');
                newOption.find('.price-title').attr('name', `prices[${newIndex}][title]`);
                newOption.find('.price-amount').attr('name', `prices[${newIndex}][amount]`);
                newOption.find('.remove-price-btn').prop('disabled', false);

                $('#price-options-container').append(newOption);

                // Enable all remove buttons if there are multiple options
                if ($('.price-option').length > 1) {
                    $('.remove-price-btn').prop('disabled', false);
                }
            });

            $(document).on('click', '.remove-price-btn', function() {
                if ($('.price-option').length > 1) {
                    const priceId = $(this).data('price-id');
                    if (priceId) {
                        // Mark for deletion
                        $(`input[name="deleted_prices[${priceId}]"]`).val('1');
                    }
                    $(this).closest('.price-option').remove();

                    // Disable remove button if only one option left
                    if ($('.price-option').length === 1) {
                        $('.remove-price-btn').prop('disabled', true);
                    }
                }
            });

            // Image Upload Handling
            function handleImageUpload(input, previewContainer) {
                if (input.files && input.files.length > 0) {
                    Array.from(input.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'image-preview';
                            previewContainer.append(img);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }

            // Service Images Handling
            const serviceImagesInput = $('#service-images-input');
            const serviceImagesPreview = $('#service-images-preview');

            serviceImagesInput.on('change', function() {
                serviceImagesPreview.empty();
                handleImageUpload(this, serviceImagesPreview);
            });

            // Product Images Handling
            $(document).on('change', '[id^="product-images-"], [id^="new-product-images-"]', function() {
                const previewId = $(this).attr('id').replace('product-images-', 'product-images-preview-')
                    .replace('new-product-images-', 'new-product-images-preview-');
                const previewContainer = $(`#${previewId}`);
                previewContainer.empty();
                handleImageUpload(this, previewContainer);
            });

            // Drag and drop functionality
            $('.file-upload-label').on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('bg-light');
            }).on('dragleave drop', function(e) {
                e.preventDefault();
                $(this).removeClass('bg-light');
            }).on('drop', function(e) {
                const input = $(this).prev('input[type="file"]');
                input[0].files = e.originalEvent.dataTransfer.files;
                input.trigger('change');
            });

            // Products Management
            let productIndex = 0;
            const productsContainer = $('#products-container');
            const noProductsAlert = $('#no-products-alert');
            const productTemplate = $('#product-template').html();

            $('#add-product-btn').click(function() {
                if (noProductsAlert.length) noProductsAlert.addClass('d-none');

                const newProduct = productTemplate.replace(/__INDEX__/g, productIndex);
                productsContainer.append(newProduct);
                productIndex++;
            });

            // Remove product
            productsContainer.on('click', '.remove-product', function() {
                $(this).closest('.product-item').remove();

                // Show no products message if none left
                if (productsContainer.find('.product-item').length === 0) {
                    noProductsAlert.removeClass('d-none');
                }
            });

            // Delete existing image or product via AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '.delete-existing-image, .delete-product', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const isProduct = $(this).hasClass('delete-product');
                const itemName = isProduct ? 'product' : 'image';

                if (!confirm(`Are you sure you want to delete this ${itemName}?`)) return;

                const url = isProduct ? `/admin/products/${id}` : `/admin/service-images/${id}`;

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            $(`[data-id="${id}"]`).closest(isProduct ? '.product-item' : '.existing-image').remove();
                            toastr.success(response.message);

                            if (isProduct && $('#products-container .product-item').length === 0) {
                                $('#no-products-alert').removeClass('d-none');
                            }
                        } else {
                            toastr.error(response.message || `Failed to delete ${itemName}`);
                        }
                    },
                    error: function(xhr) {
                        toastr.error(`An error occurred while deleting the ${itemName}`);
                    }
                });
            });
        });

















        $(document).ready(function() {
            // Image deletion handler
            $(document).on('click', '.delete-existing-image', function() {
                const imageId = $(this).data('id');
                const $imageContainer = $(this).closest('.existing-image');

                // Mark for deletion
                $imageContainer.find('.delete-flag').prop('checked', true);
                $imageContainer.hide();

                // If deleting the main image, select another one
                if ($(`#main-image-${imageId}`).is(':checked')) {
                    const $firstRemaining = $('.existing-image:visible').first();
                    if ($firstRemaining.length) {
                        const newMainId = $firstRemaining.data('image-id');
                        $(`#main-image-${newMainId}`).prop('checked', true);
                    }
                }
            });


        });
    </script>
@endpush
