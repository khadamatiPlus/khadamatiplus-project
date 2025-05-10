@extends('backend.layouts.app')
@section('title', __('Create Service'))
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

    <x-forms.post :action="route('admin.service.store')" enctype="multipart/form-data" class="needs-validation" id="service-form">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create New Service')
                <small class="text-muted d-block mt-1">Fill in the details to create a new service offering</small>
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
                                <label for="merchant_id" class="form-label">Merchant *</label>
                                <select name="merchant_id" id="merchant_id" class="form-control select2" >
                                    <option value="" selected disabled>Select Merchant</option>
                                    @foreach ($merchants as $merchant)
                                        <option value="{{ $merchant->id }}" {{ old('merchant_id') == $merchant->id ? 'selected' : '' }}>
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
                                <label for="sub_category_id" class="form-label">Category *</label>
                                <select class="form-control select2" id="sub_category_id" name="sub_category_id" >
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            @foreach($category->children as $subCategory)
                                                <option value="{{ $subCategory->id }}" {{ old('sub_category_id') == $subCategory->id ? 'selected' : '' }}>
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
                            <label for="title" class="form-label">Service Title *</label>
                            <input type="text" id="title" name="title" class="form-control"
                                   placeholder="e.g. Premium Haircut & Styling" value="{{ old('title') }}" >
                            @error('title')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Service Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea id="description" name="description" class="form-control" rows="4"
                                      placeholder="Describe the service in detail..." >{{ old('description') }}</textarea>
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
                            @if(old('prices') && count(old('prices')) > 0)
                                @foreach(old('prices') as $index => $price)
                                    <div class="price-option mb-3" data-index="{{ $index }}">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label class="form-label">Price Title *</label>
                                                <input type="text" name="prices[{{ $index }}][title]"
                                                       class="form-control price-title"
                                                       placeholder="e.g., Standard, Premium, VIP"
                                                       value="{{ $price['title'] ?? '' }}" >
                                                @error("prices.$index.title")
                                                <div class="error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Price *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" name="prices[{{ $index }}][amount]"
                                                           class="form-control price-amount"
                                                           placeholder="0.00" min="0.01" step="0.01"
                                                           value="{{ $price['amount'] ?? '' }}" >
                                                </div>
                                                @error("prices.$index.amount")
                                                <div class="error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-price-btn"
                                                    {{ $loop->first && count(old('prices')) == 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Default first price option -->
                                <div class="price-option mb-3" data-index="0">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="form-label">Price Title *</label>
                                            <input type="text" name="prices[0][title]"
                                                   class="form-control price-title"
                                                   placeholder="e.g., Standard, Premium, VIP" >
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Price *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" name="prices[0][amount]"
                                                       class="form-control price-amount"
                                                       placeholder="0.00" min="0.01" step="0.01" >
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
                                <label for="duration" class="form-label">Duration (in minutes)</label>
                                <div class="input-group">
                                    <input type="number" id="duration" name="duration" class="form-control"
                                           placeholder="e.g., 30, 60, 90" min="1" value="{{ old('duration') }}">
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
                                    {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
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
                <div class="card card-section mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Service Images</h5>
                        <small class="text-muted">At least one image </small>
                    </div>
                    <div class="card-body">
                        <div class="file-upload-wrapper">
                            <input type="file" id="service-images-input" name="service_images[]"
                                   class="d-none" multiple accept="image/*">
                            <label for="service-images-input" class="file-upload-label">
                                <i class="cil-cloud-upload file-upload-icon"></i>
                                <span>Click to upload or drag and drop</span>
                                <small class="text-muted">PNG, JPG, JPEG up to 5MB</small>
                            </label>
                        </div>
                        @error('service_images')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        @error('service_images.*')
                        <div class="error-message">{{ $message }}</div>
                        @enderror

                        <div id="service-images-preview" class="image-preview-container mt-3">
                            @if(old('service_images_previews'))
                                @foreach(old('service_images_previews') as $index => $preview)
                                    <div class="image-preview-wrapper">
                                        <img src="{{ $preview }}" class="image-preview" data-index="{{ $index }}">
                                        <button type="button" class="btn btn-sm btn-danger remove-image-btn"
                                                style="position: absolute; top: 0; right: 0;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Main Image Selection *</label>
                            <small class="text-muted d-block">Select which image should be the primary display image</small>
                            <div id="main-image-selection" class="mt-2">
                                @if(old('service_images_previews'))
                                    @foreach(old('service_images_previews') as $index => $preview)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="main_service_image"
                                                   id="main-image-{{ $index }}" value="{{ $index }}"
                                                {{ old('main_service_image') == $index ? 'checked' : ($loop->first ? 'checked' : '') }}>
                                            <label class="form-check-label" for="main-image-{{ $index }}">
                                                Image {{ $index + 1 }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            @error('main_service_image')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
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
                            @if(old('products') && count(old('products')) > 0)
                                @foreach(old('products') as $index => $product)
                                    <div class="dynamic-item product-item" data-index="{{ $index }}">
                                        <button type="button" class="remove-btn remove-product">
                                            <i class="cil-x"></i>
                                        </button>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Product Name *</label>
                                                <input type="text" name="products[{{ $index }}][title]"
                                                       class="form-control" placeholder="e.g. Shampoo, Hair Mask"
                                                       value="{{ $product['title'] ?? '' }}" >
                                                @error("products.$index.title")
                                                <div class="error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Product Price *</label>
                                                <input type="number" name="products[{{ $index }}][price]"
                                                       class="form-control" placeholder="0.00" min="0" step="0.01"
                                                       value="{{ $product['price'] ?? '' }}" >
                                                @error("products.$index.price")
                                                <div class="error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Product Description</label>
                                            <textarea name="products[{{ $index }}][description]" class="form-control"
                                                      rows="2" placeholder="Describe this product...">{{ $product['description'] ?? '' }}</textarea>
                                        </div>

                                        <div class="product-images-section">
                                            <label class="form-label">Product Images</label>
                                            <div class="file-upload-wrapper mb-2">
                                                <input type="file" id="product-images-{{ $index }}"
                                                       name="products[{{ $index }}][images][]" class="d-none" multiple accept="image/*">
                                                <label for="product-images-{{ $index }}" class="file-upload-label py-1">
                                                    <i class="cil-image"></i>
                                                    <span>Upload Product Images</span>
                                                </label>
                                            </div>

                                            <div class="image-preview-container product-images-preview"
                                                 id="product-images-preview-{{ $index }}">
                                                @if(isset($product['images_previews']))
                                                    @foreach($product['images_previews'] as $imgIndex => $preview)
                                                        <div class="image-preview-wrapper">
                                                            <img src="{{ $preview }}" class="image-preview" data-index="{{ $imgIndex }}">
                                                            <button type="button" class="btn btn-sm btn-danger remove-image-btn"
                                                                    style="position: absolute; top: 0; right: 0;">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>

                                            <div class="mt-2">
                                                <label class="form-label">Main Product Image</label>
                                                <div class="main-product-image-selection" id="main-product-image-selection-{{ $index }}">
                                                    @if(isset($product['images_previews']))
                                                        @foreach($product['images_previews'] as $imgIndex => $preview)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                       name="products[{{ $index }}][main_image]"
                                                                       id="main-product-image-{{ $index }}-{{ $imgIndex }}"
                                                                       value="{{ $imgIndex }}"
                                                                    {{ $product['main_image'] == $imgIndex ? 'checked' : ($loop->first ? 'checked' : '') }}>
                                                                <label class="form-check-label" for="main-product-image-{{ $index }}-{{ $imgIndex }}">
                                                                    Image {{ $imgIndex + 1 }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info" id="no-products-alert">
                                    No products added yet. Click "Add Product" to include products in this service.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create Service')</button>
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
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="products[__INDEX__][title]" class="form-control" placeholder="e.g. Shampoo, Hair Mask" >
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Product Price *</label>
                    <input type="number" name="products[__INDEX__][price]" class="form-control" placeholder="0.00" min="0" step="0.01" >
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Description</label>
                <textarea name="products[__INDEX__][description]" class="form-control" rows="2" placeholder="Describe this product..."></textarea>
            </div>

            <div class="product-images-section">
                <label class="form-label">Product Images</label>
                <div class="file-upload-wrapper mb-2">
                    <input type="file" id="product-images-__INDEX__" name="products[__INDEX__][images][]" class="d-none" multiple accept="image/*">
                    <label for="product-images-__INDEX__" class="file-upload-label py-1">
                        <i class="cil-image"></i>
                        <span>Upload Product Images</span>
                    </label>
                </div>

                <div class="image-preview-container product-images-preview" id="product-images-preview-__INDEX__"></div>

                <div class="mt-2">
                    <label class="form-label">Main Product Image</label>
                    <div class="main-product-image-selection" id="main-product-image-selection-__INDEX__"></div>
                </div>
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

                // Validate  fields
                $(this).find('[]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Validate at least one price option
                if ($('.price-option').length === 0) {
                    $('#price-options-container').append('<div class="error-message">At least one price option is .</div>');
                    isValid = false;
                }

                // Validate at least one service image
                if ($('#service-images-preview img').length === 0) {
                    $('#service-images-preview').before('<div class="error-message">At least one service image is .</div>');
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
            let priceOptionCount = {{ old('prices') ? count(old('prices')) : 1 }};

            $('#add-price-btn').click(function() {
                const newOption = $('.price-option').first().clone();
                const newIndex = priceOptionCount++;

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
                    $(this).closest('.price-option').remove();

                    // Disable remove button if only one option left
                    if ($('.price-option').length === 1) {
                        $('.remove-price-btn').prop('disabled', true);
                    }
                }
            });

            // Service Images Handling
            const serviceImagesInput = $('#service-images-input');
            const serviceImagesPreview = $('#service-images-preview');
            const mainImageSelection = $('#main-image-selection');

            serviceImagesInput.on('change', function(e) {
                serviceImagesPreview.empty();
                mainImageSelection.empty();

                if (this.files && this.files.length > 0) {
                    for (let i = 0; i < this.files.length; i++) {
                        const file = this.files[i];
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            // Create image preview
                            const previewWrapper = $('<div class="image-preview-wrapper position-relative"></div>');
                            const imgPreview = $(`<img src="${e.target.result}" class="image-preview" data-index="${i}">`);

                            // Add remove button
                            const removeBtn = $(`
                                <button type="button" class="btn btn-sm btn-danger remove-image-btn" style="position: absolute; top: 0; right: 0;">
                                    <i class="fas fa-times"></i>
                                </button>
                            `);

                            previewWrapper.append(imgPreview).append(removeBtn);
                            serviceImagesPreview.append(previewWrapper);

                            // Create radio button for main image selection
                            const radioId = `main-image-${i}`;
                            const radioDiv = $(`
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="main_service_image"
                                           id="${radioId}" value="${i}" ${i === 0 ? 'checked' : ''}>
                                    <label class="form-check-label" for="${radioId}">
                                        Image ${i + 1}
                                    </label>
                                </div>
                            `);

                            mainImageSelection.append(radioDiv);
                        };

                        reader.readAsDataURL(file);
                    }
                }
            });

            // Remove service image
            $(document).on('click', '.remove-image-btn', function() {
                const wrapper = $(this).closest('.image-preview-wrapper');
                const index = wrapper.find('img').data('index');

                // Remove the file from the input
                const dt = new DataTransfer();
                const files = serviceImagesInput[0].files;

                for (let i = 0; i < files.length; i++) {
                    if (i !== parseInt(index)) {
                        dt.items.add(files[i]);
                    }
                }

                serviceImagesInput[0].files = dt.files;

                // Trigger change event to rebuild previews and radio buttons
                serviceImagesInput.trigger('change');
            });

            // Drag and drop functionality for service images
            const fileUploadLabel = $('.file-upload-label');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                fileUploadLabel.on(eventName, preventDefaults);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                fileUploadLabel.on(eventName, function() {
                    $(this).addClass('bg-light');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                fileUploadLabel.on(eventName, function() {
                    $(this).removeClass('bg-light');
                });
            });

            fileUploadLabel.on('drop', function(e) {
                const dt = e.originalEvent.dataTransfer;
                const files = dt.files;
                serviceImagesInput[0].files = files;
                serviceImagesInput.trigger('change');
            });















            let productIndex = {{ old('products') ? count(old('products')) : 0 }};
            const productsContainer = $('#products-container');
            const noProductsAlert = $('#no-products-alert');
            const productTemplate = $('#product-template').html();

            // Modify the add product function to ensure proper data structure
            $('#add-product-btn').click(function() {

                if (noProductsAlert.length) noProductsAlert.addClass('d-none');

                const newProduct = productTemplate.replace(/__INDEX__/g, productIndex);
                productsContainer.append(newProduct);
                productIndex++;
                // Initialize file upload
                const productImageInput = $(`#product-images-${productIndex}`);
                const productImagePreview = $(`#product-images-preview-${productIndex}`);
                const mainProductImageSelection = $(`#main-product-image-selection-${productIndex}`);

                productImageInput.on('change', function(e) {
                    productImagePreview.empty();
                    mainProductImageSelection.empty();

                    if (this.files && this.files.length > 0) {
                        Array.from(this.files).forEach((file, i) => {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                // Image preview
                                const previewWrapper = $(`<div class="image-preview-wrapper position-relative" data-index="${i}"></div>`);
                                const imgPreview = $(`<img src="${e.target.result}" class="image-preview">`);

                                // Remove button
                                const removeBtn = $(`<button type="button" class="btn btn-sm btn-danger remove-image-btn">×</button>`);
                                previewWrapper.append(imgPreview).append(removeBtn);
                                productImagePreview.append(previewWrapper);

                                // Main image selection
                                const radioId = `main-product-image-${productIndex}-${i}`;
                                const radioDiv = $(`
                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                   name="products[${productIndex}][main_image_index]"
                                   id="${radioId}" value="${i}" ${i === 0 ? 'checked' : ''}>
                            <label class="form-check-label" for="${radioId}">
                                Image ${i + 1}
                            </label>
                        </div>
                    `);
                                mainProductImageSelection.append(radioDiv);
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                });

            });

// Improve image removal
            productsContainer.on('click', '.remove-image-btn', function() {
                const wrapper = $(this).closest('.image-preview-wrapper');
                const previewContainer = wrapper.closest('.product-images-preview');
                const productItem = wrapper.closest('.product-item');
                const productIndex = productItem.data('index');
                const imgIndex = wrapper.data('index');

                // Update file input
                const input = $(`#product-images-${productIndex}`)[0];
                const dt = new DataTransfer();

                Array.from(input.files).forEach((file, i) => {
                    if (i !== parseInt(imgIndex)) {
                        dt.items.add(file);
                    }
                });

                input.files = dt.files;

                // Rebuild previews
                $(input).trigger('change');

                // If main image was removed, set first remaining image as main
                const mainImageRadio = $(`input[name="products[${productIndex}][main_image_index]"]:checked`);
                if (mainImageRadio.length === 0 && dt.files.length > 0) {
                    $(`input[name="products[${productIndex}][main_image_index]"][value="0"]`).prop('checked', true);
                }
            });
            // Remove product - make sure this is properly set up
            productsContainer.on('click', '.remove-product', function(e) {
                e.preventDefault();

                // Find the closest product item and remove it
                $(this).closest('.product-item').remove();

                // Show no products message if none left
                if (productsContainer.find('.product-item').length === 0) {
                    noProductsAlert.removeClass('d-none');
                }
            });
        });
    </script>
@endpush
