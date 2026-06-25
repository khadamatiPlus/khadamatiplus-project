
@extends('backend.layouts.app')

@section('title', __('Create App Service'))

@section('content')
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #eef2ff;
            --primary-border: #c7d2fe;
            --danger-light: #fef2f2;
            --surface: #f8fafc;
            --border: #e2e8f0;
            --text-muted: #64748b;
            --text-dark: #1e293b;
            --success-light: #f0fdf4;
            --success-border: #bbf7d0;
        }
        body { background: var(--surface); font-family: 'Segoe UI', Tahoma, sans-serif; color: var(--text-dark); }
        .page-header { background: white; border-bottom: 1px solid var(--border); padding: 1rem 1.5rem; }
        .page-header h5 { font-weight: 600; margin: 0; color: var(--text-dark); }
        .section-card { background: white; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.25rem; }
        .section-title { font-size: 0.85rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px; }
        .section-title i { font-size: 1rem; color: var(--primary); }
        .form-label { font-size: 0.875rem; font-weight: 500; color: var(--text-dark); margin-bottom: 0.4rem; }
        .form-label .req { color: #ef4444; margin-right: 2px; }
        .form-control, .form-select { border: 1px solid var(--border); border-radius: 8px; font-size: 0.9rem; padding: 0.55rem 0.85rem; transition: border-color 0.15s, box-shadow 0.15s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
        .form-text { font-size: 0.78rem; color: var(--text-muted); }
        .variant-block { border: 1px solid var(--border); border-radius: 10px; overflow: hidden; margin-bottom: 1rem; }
        .variant-header { background: var(--primary-light); border-bottom: 1px solid var(--primary-border); padding: 0.75rem 1rem; display: flex; align-items: center; justify-content: space-between; }
        .variant-header .variant-title { font-weight: 600; font-size: 0.9rem; color: var(--primary); display: flex; align-items: center; gap: 6px; }
        .variant-body { padding: 1rem; }
        .option-row { background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 0.65rem 0.85rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 10px; }
        .option-row .form-control { background: white; }
        .option-row .price-input { width: 130px; flex-shrink: 0; }
        .option-row .option-name { flex: 1; }
        .btn-add-option { border: 1.5px dashed var(--primary-border); color: var(--primary); background: var(--primary-light); border-radius: 8px; font-size: 0.83rem; font-weight: 500; padding: 0.45rem 1rem; width: 100%; transition: all 0.15s; }
        .btn-add-option:hover { background: var(--primary); color: white; border-color: var(--primary); }
        .btn-add-variant { border: 1.5px dashed #c7d2fe; color: var(--primary); background: white; border-radius: 10px; font-size: 0.875rem; font-weight: 500; padding: 0.65rem 1.25rem; width: 100%; transition: all 0.15s; }
        .btn-add-variant:hover { background: var(--primary-light); border-color: var(--primary); }
        .btn-danger-soft { background: var(--danger-light); color: #ef4444; border: none; border-radius: 6px; padding: 0.3rem 0.5rem; font-size: 0.78rem; transition: all 0.15s; }
        .btn-danger-soft:hover { background: #fecaca; color: #dc2626; }
        .price-preview { background: var(--success-light); border: 1px solid var(--success-border); border-radius: 10px; padding: 1rem 1.25rem; }
        .price-preview .base { font-size: 0.85rem; color: var(--text-muted); }
        .price-preview .total { font-size: 1.6rem; font-weight: 700; color: #16a34a; }
        .price-preview .currency { font-size: 1rem; font-weight: 500; color: #16a34a; }
        .image-drop { border: 2px dashed var(--border); border-radius: 10px; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.15s; }
        .image-drop:hover, .image-drop.dragover { border-color: var(--primary); background: var(--primary-light); }
        .image-drop i { font-size: 2rem; color: #94a3b8; display: block; margin-bottom: 0.5rem; }
        .image-drop p { font-size: 0.85rem; color: var(--text-muted); margin: 0; }
        .img-thumb-wrap { position: relative; display: inline-block; margin: 4px; }
        .img-thumb-wrap img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); }
        .img-thumb-wrap .remove-img { position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; font-size: 0.7rem; line-height: 1; padding: 0; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .tag-input-wrap { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; border: 1px solid var(--border); border-radius: 8px; padding: 0.4rem 0.6rem; min-height: 44px; cursor: text; }
        .tag-input-wrap:focus-within { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
        .tag-pill { background: var(--primary-light); color: var(--primary); border-radius: 20px; padding: 3px 10px; font-size: 0.8rem; font-weight: 500; display: flex; align-items: center; gap: 5px; }
        .tag-pill button { background: none; border: none; color: var(--primary); padding: 0; cursor: pointer; font-size: 0.75rem; line-height: 1; }
        .tag-input-wrap input { border: none; outline: none; font-size: 0.875rem; background: transparent; min-width: 80px; }
        .status-toggle { display: flex; align-items: center; gap: 10px; }
        .form-switch .form-check-input { width: 2.5em; height: 1.3em; }
        .form-switch .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }
        .availability-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 8px; }
        .day-check label { background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.82rem; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.15s; }
        .day-check input:checked + label { background: var(--primary-light); border-color: var(--primary-border); color: var(--primary); font-weight: 500; }
        .sidebar-sticky { position: sticky; top: 1rem; }
        .btn-publish { background: var(--primary); border: none; color: white; border-radius: 10px; font-size: 0.925rem; font-weight: 600; padding: 0.75rem 1.5rem; width: 100%; transition: all 0.15s; }
        .btn-publish:hover { background: #4f46e5; color: white; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.35); }
        .btn-draft { background: white; border: 1.5px solid var(--border); color: var(--text-dark); border-radius: 10px; font-size: 0.875rem; font-weight: 500; padding: 0.65rem 1.5rem; width: 100%; margin-bottom: 0.75rem; transition: all 0.15s; }
        .btn-draft:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-light); }
        .char-count { font-size: 0.75rem; color: var(--text-muted); text-align: left; }
        .input-group-text { background: var(--surface); border: 1px solid var(--border); border-radius: 8px; font-size: 0.875rem; color: var(--text-muted); }
        .variant-type-badge { font-size: 0.72rem; background: white; border: 1px solid var(--primary-border); color: var(--primary); border-radius: 20px; padding: 2px 10px; }
        .required-badge { font-size: 0.7rem; background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; border-radius: 20px; padding: 2px 8px; }
        .optional-badge { font-size: 0.7rem; background: var(--success-light); border: 1px solid var(--success-border); color: #16a34a; border-radius: 20px; padding: 2px 8px; }
        .summary-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: 0.875rem; border-bottom: 1px dashed var(--border); }
        .summary-row:last-child { border-bottom: none; }
        .completion-bar { height: 6px; background: var(--border); border-radius: 10px; overflow: hidden; margin-bottom: 1rem; }
        .completion-fill { height: 100%; background: var(--primary); border-radius: 10px; transition: width 0.4s; }
    </style>
<div class="container-fluid p-3">
    <div class="row g-3">
        <!-- Main Column -->
        <div class="col-lg-8">
            <form action="{{ route('admin.app-service.store') }}" method="POST" enctype="multipart/form-data" id="appServiceForm">
                @csrf

                <!-- 1. Basic Info -->
                <div class="section-card">
                    <div class="section-title"><i class="bi bi-info-circle-fill"></i> المعلومات الأساسية</div>
                    <div class="mb-3">
                        <label class="form-label"><span class="req">*</span> اسم الخدمة</label>
                        <input type="text" name="name" class="form-control" id="svcName" placeholder="مثال: تصميم شعار احترافي" required oninput="updatePreview(); countChars(this,'nc',60)">
                        <div class="d-flex justify-content-between"><div class="form-text">اسم واضح وجذاب يصف الخدمة</div><div class="char-count" id="nc">0/60</div></div>
                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><span class="req">*</span> وصف الخدمة</label>
                        <textarea name="description" class="form-control" id="svcDesc" rows="4" placeholder="اكتب وصفاً تفصيلياً يوضح ما تقدمه..." oninput="countChars(this,'dc',500)"></textarea>
                        <div class="d-flex justify-content-between"><div class="form-text">وصف شامل يساعد العملاء على فهم الخدمة</div><div class="char-count" id="dc">0/500</div></div>
                        @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label"><span class="req">*</span> التصنيف</label>
                            <select name="category_id" class="form-select" id="svcCat" onchange="updatePreview(); loadSubCategories(this.value)" required>
                                <option value="">اختر تصنيف...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-children="{{ $category->children ? json_encode($category->children) : '' }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">التصنيف الفرعي</label>
                            <select name="sub_category_id" class="form-select" id="subCategorySelect" disabled>
                                <option value="">اختر تصنيف فرعي...</option>
                            </select>
                            @error('sub_category_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. Media -->
                <div class="section-card">
                    <div class="section-title"><i class="bi bi-images"></i> الصور والوسائط</div>
                    <label class="form-label">صور الخدمة <span class="text-muted fw-normal">(حتى 8 صور)</span></label>
                    <div class="image-drop" id="dropzone" onclick="document.getElementById('imgInput').click()" ondragover="event.preventDefault();this.classList.add('dragover')" ondragleave="this.classList.remove('dragover')">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <p><strong>اسحب الصور هنا</strong> أو انقر للاختيار</p>
                        <p class="mt-1" style="font-size:0.75rem">PNG, JPG, WEBP — حد أقصى 5 MB لكل صورة</p>
                    </div>
                    <input type="file" name="images[]" id="imgInput" accept="image/*" multiple style="display:none" onchange="handleImages(this)">
                    <div id="thumbsWrap" class="mt-2 d-flex flex-wrap"></div>
                    <div class="mt-3">
                        <label class="form-label">رابط فيديو تعريفي <span class="text-muted fw-normal">(اختياري)</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-play-circle"></i></span>
                            <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
                        </div>
                        @error('video_url') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- 3. Pricing -->
                <div class="section-card">
                    <div class="section-title"><i class="bi bi-cash-stack"></i> التسعير</div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-5">
                            <label class="form-label"><span class="req">*</span> السعر الأساسي</label>
                            <div class="input-group">
                                <input type="number" name="base_price" class="form-control" id="basePrice" placeholder="0.00" min="0" step="0.01" required oninput="updatePreview()">
{{--                                <select name="currency" class="form-select" id="currency" style="max-width:90px" onchange="updatePreview()">--}}
{{--                                    <option value="JOD">JOD</option>--}}
{{--                                    <option value="USD">USD</option>--}}
{{--                                    <option value="SAR">SAR</option>--}}
{{--                                    <option value="AED">AED</option>--}}
{{--                                    <option value="EUR">EUR</option>--}}
{{--                                </select>--}}
                            </div>
                            @error('base_price') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">نوع التسعير</label>
                            <select name="price_type" class="form-select" id="priceType" required>
                                <option value="fixed">سعر ثابت</option>
                                <option value="hourly">بالساعة</option>
                                <option value="starts_from">يبدأ من</option>
                                <option value="by_agreement">حسب الاتفاق</option>
                            </select>
                            @error('price_type') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">خصم %</label>
                            <input type="number" name="discount" class="form-control" id="discount" placeholder="0" min="0" max="100" oninput="updatePreview()">
                            @error('discount') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
{{--                    <div class="row g-3">--}}
{{--                        <div class="col-sm-6">--}}
{{--                            <label class="form-label">مدة التسليم</label>--}}
{{--                            <div class="input-group">--}}
{{--                                <input type="number" name="delivery_time" class="form-control" placeholder="3" min="1" id="deliveryTime">--}}
{{--                                <select name="delivery_time_unit" class="form-select" style="max-width:110px">--}}
{{--                                    <option value="days">أيام</option>--}}
{{--                                    <option value="hours">ساعات</option>--}}
{{--                                    <option value="weeks">أسابيع</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            @error('delivery_time') <div class="text-danger">{{ $message }}</div> @enderror--}}
{{--                        </div>--}}
{{--                        <div class="col-sm-6">--}}
{{--                            <label class="form-label">عدد المراجعات المجانية</label>--}}
{{--                            <input type="number" name="free_revisions" class="form-control" placeholder="2" min="0">--}}
{{--                            @error('free_revisions') <div class="text-danger">{{ $message }}</div> @enderror--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>

                <!-- 4. Variants -->
                <div class="section-card">
                    <div class="section-title"><i class="bi bi-sliders"></i> المتغيرات والخيارات</div>
                    <p class="text-muted" style="font-size:0.85rem; margin-bottom:1rem">أضف متغيرات للخدمة (مثل الحجم، الجودة، الإضافات) وكل خيار له سعر إضافي خاص به.</p>
                    <div id="variantsContainer"></div>
                    <button type="button" class="btn-add-variant" onclick="addVariant()"><i class="bi bi-plus-circle me-2"></i>إضافة متغير جديد</button>
                </div>

                <!-- 5. Requirements -->
{{--                <div class="section-card">--}}
{{--                    <div class="section-title"><i class="bi bi-clipboard-check"></i> متطلبات العميل</div>--}}
{{--                    <div class="mb-3">--}}
{{--                        <label class="form-label">ما الذي تحتاجه من العميل لتبدأ العمل؟</label>--}}
{{--                        <textarea name="customer_requirements" class="form-control" rows="3" placeholder="مثال: شعار الشركة، الألوان المفضلة، نبذة عن النشاط التجاري..."></textarea>--}}
{{--                        @error('customer_requirements') <div class="text-danger">{{ $message }}</div> @enderror--}}
{{--                    </div>--}}
{{--                    <div class="form-check form-switch mb-2">--}}
{{--                        <input type="checkbox" name="requirements_mandatory" class="form-check-input" id="reqMandatory" checked>--}}
{{--                        <label class="form-check-label" for="reqMandatory" style="font-size:0.875rem">هذه المتطلبات إلزامية قبل بدء العمل</label>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <!-- 6. SEO & Tags -->
{{--                <div class="section-card">--}}
{{--                    <div class="section-title"><i class="bi bi-tags"></i> الكلمات المفتاحية والوسوم</div>--}}
{{--                    <div class="mb-3">--}}
{{--                        <label class="form-label">وسوم الخدمة</label>--}}
{{--                        <div class="tag-input-wrap" id="tagWrap" onclick="document.getElementById('tagInput').focus()">--}}
{{--                            <input type="text" id="tagInput" placeholder="اكتب وسماً واضغط Enter..." onkeydown="handleTag(event)">--}}
{{--                            <input type="hidden" name="tags" id="tagsInput">--}}
{{--                        </div>--}}
{{--                        <div class="form-text">أضف حتى 10 وسوم لتحسين ظهور الخدمة</div>--}}
{{--                    </div>--}}
{{--                    <div class="mb-0">--}}
{{--                        <label class="form-label">وصف SEO <span class="text-muted fw-normal">(اختياري)</span></label>--}}
{{--                        <input type="text" name="seo_description" class="form-control" placeholder="وصف مختصر يظهر في محركات البحث..." maxlength="160" oninput="countChars(this,'sc',160)">--}}
{{--                        <div class="d-flex justify-content-between"><div></div><div class="char-count" id="sc">0/160</div></div>--}}
{{--                        @error('seo_description') <div class="text-danger">{{ $message }}</div> @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}

                <!-- 7. Advanced -->
                <div class="section-card">
                    <div class="section-title"><i class="bi bi-gear"></i> إعدادات متقدمة</div>
                    <div class="row g-3">
{{--                        <div class="col-sm-6">--}}
{{--                            <label class="form-label">اللغة</label>--}}
{{--                            <select name="language" class="form-select">--}}
{{--                                <option value="arabic">العربية</option>--}}
{{--                                <option value="english">الإنجليزية</option>--}}
{{--                                <option value="both">كلتاهما</option>--}}
{{--                            </select>--}}
{{--                            @error('language') <div class="text-danger">{{ $message }}</div> @enderror--}}
{{--                        </div>--}}
{{--                        <div class="col-sm-6">--}}
{{--                            <label class="form-label">نطاق الخدمة</label>--}}
{{--                            <select name="scope" class="form-select">--}}
{{--                                <option value="local">محلي</option>--}}
{{--                                <option value="global">عالمي</option>--}}
{{--                                <option value="online_only">أونلاين فقط</option>--}}
{{--                            </select>--}}
{{--                            @error('scope') <div class="text-danger">{{ $message }}</div> @enderror--}}
{{--                        </div>--}}
                        <div class="col-12">
                            <label class="form-label">أيام التوفر</label>
                            <div class="availability-grid mt-1">
                                <div class="day-check"><input type="checkbox" name="availability_days[]" value="Sunday" id="d0" checked hidden><label for="d0"><i class="bi bi-check2" id="d0i" style="display:none"></i>الأحد</label></div>
                                <div class="day-check"><input type="checkbox" name="availability_days[]" value="Monday" id="d1" checked hidden><label for="d1">الاثنين</label></div>
                                <div class="day-check"><input type="checkbox" name="availability_days[]" value="Tuesday" id="d2" checked hidden><label for="d2">الثلاثاء</label></div>
                                <div class="day-check"><input type="checkbox" name="availability_days[]" value="Wednesday" id="d3" checked hidden><label for="d3">الأربعاء</label></div>
                                <div class="day-check"><input type="checkbox" name="availability_days[]" value="Thursday" id="d4" checked hidden><label for="d4">الخميس</label></div>
                                <div class="day-check"><input type="checkbox" name="availability_days[]" value="Friday" id="d5" hidden><label for="d5">الجمعة</label></div>
                                <div class="day-check"><input type="checkbox" name="availability_days[]" value="Saturday" id="d6" hidden><label for="d6">السبت</label></div>
                            </div>
                            <input type="hidden" name="availability_days" id="availabilityDaysInput">
                        </div>
                        <input type="hidden" name="variants" id="variantsInput">
                        <div class="col-sm-6">
                            <label class="form-label">الحد الأقصى للطلبات المتزامنة</label>
                            <input type="number" name="max_concurrent_orders" class="form-control" placeholder="غير محدود" min="1">
                            @error('max_concurrent_orders') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
{{--                        <div class="col-sm-6">--}}
{{--                            <label class="form-label">تاريخ انتهاء العرض <span class="text-muted fw-normal">(اختياري)</span></label>--}}
{{--                            <input type="date" name="expiry_date" class="form-control">--}}
{{--                            @error('expiry_date') <div class="text-danger">{{ $message }}</div> @enderror--}}
{{--                        </div>--}}
                    </div>
                    <div class="mt-3 d-flex flex-column gap-2">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_featured" class="form-check-input" id="swFeatured">
                            <label class="form-check-label" for="swFeatured" style="font-size:0.875rem"><i class="bi bi-star-fill text-warning me-1"></i>خدمة مميزة (تظهر في الصفحة الرئيسية)</label>
                        </div>
{{--                        <div class="form-check form-switch">--}}
{{--                            <input type="checkbox" name="is_urgent" class="form-check-input" id="swUrgent">--}}
{{--                            <label class="form-check-label" for="swUrgent" style="font-size:0.875rem"><i class="bi bi-lightning-fill text-danger me-1"></i>متاح للتسليم العاجل</label>--}}
{{--                        </div>--}}
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_online" class="form-check-input" id="swOnline" checked>
                            <label class="form-check-label" for="swOnline" style="font-size:0.875rem"><i class="bi bi-wifi me-1 text-success"></i>متاح عبر الإنترنت</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sidebar-sticky">
                <!-- Publish Card -->
                <div class="section-card mb-3">
                    <div class="section-title"><i class="bi bi-send-check-fill"></i> نشر الخدمة</div>
                    <div class="mb-3">
                        <label class="form-label">حالة الخدمة</label>
                        <select name="status" form="appServiceForm" class="form-select" id="svcStatus" required>
                            <option value="active">🟢 نشطة</option>
                            <option value="draft">🟡 مسودة</option>
                            <option value="inactive">🔴 غير نشطة</option>
                            <option value="scheduled">🔵 مجدولة</option>
                        </select>
                        @error('status') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
{{--                    <div class="mb-3">--}}
{{--                        <label class="form-label">مرئية لـ</label>--}}
{{--                        <select name="visibility" form="appServiceForm" class="form-select">--}}
{{--                            <option value="all">الجميع</option>--}}
{{--                            <option value="registered_only">المسجلين فقط</option>--}}
{{--                            <option value="specific_customers">عملاء محددين</option>--}}
{{--                        </select>--}}
{{--                        @error('visibility') <div class="text-danger">{{ $message }}</div> @enderror--}}
{{--                    </div>--}}
                    <div class="completion-bar">
                        <div class="completion-fill" id="completionFill" style="width:20%"></div>
                    </div>
                    <div style="font-size:0.78rem; color:var(--text-muted); margin-bottom:1rem" id="completionText">اكتمال النموذج: 20%</div>
                    <button type="submit" form="appServiceForm" class="btn-publish mb-2"><i class="bi bi-send me-2"></i>نشر الخدمة الآن</button>
                    <a href="{{ route('admin.app-service.index') }}" class="btn btn-link text-danger text-decoration-none w-100 py-1" style="font-size:0.83rem"><i class="bi bi-x-circle me-1"></i>إلغاء</a>
                </div>

                <!-- Price Preview -->
                <div class="section-card mb-3">
                    <div class="section-title"><i class="bi bi-calculator"></i> معاينة السعر</div>
                    <div class="price-preview">
                        <div class="summary-row">
                            <span class="base">السعر الأساسي</span>
                            <span id="prevBase" style="font-weight:500">0.00</span>
                        </div>
                        <div class="summary-row" id="discountRow" style="display:none; color:#ef4444">
                            <span>الخصم</span>
                            <span id="prevDiscount"></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-1" style="border-top:2px solid var(--success-border)">
                            <span style="font-weight:600; font-size:0.9rem">الإجمالي</span>
                            <span class="total" id="prevTotal">0.00 <span class="currency" id="prevCurr">JOD</span></span>
                        </div>
                    </div>
                </div>

                <!-- Service Summary -->
                <div class="section-card mb-3">
                    <div class="section-title"><i class="bi bi-eye"></i> ملخص الخدمة</div>
                    <div class="summary-row">
                        <span class="text-muted" style="font-size:0.83rem">الاسم</span>
                        <span style="font-size:0.83rem; font-weight:500; max-width:150px; text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap" id="sumName">—</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-muted" style="font-size:0.83rem">التصنيف</span>
                        <span style="font-size:0.83rem; font-weight:500" id="sumCat">—</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-muted" style="font-size:0.83rem">الحالة</span>
                        <span class="badge rounded-pill" id="sumStatus" style="background:var(--primary-light); color:var(--primary); font-weight:500; font-size:0.75rem">نشطة</span>
                    </div>
                </div>

                <!-- Tips -->
                <div class="section-card" style="background:var(--primary-light); border-color:var(--primary-border)">
                    <div class="section-title" style="color:var(--primary)"><i class="bi bi-lightbulb-fill"></i> نصائح</div>
                    <ul style="font-size:0.82rem; color:#4338ca; padding-right:1.1rem; margin:0; line-height:1.9">
                        <li>الخدمات بصور عالية الجودة تحصل على 3x زيارات</li>
                        <li>وصف تفصيلي يقلل أسئلة العملاء</li>
{{--                        <li>حدد وقت التسليم بدقة لتجنب الخلافات</li>--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let tags = [];
    let variantCount = 0;
    function countChars(el, id, max) {
        const n = el.value.length;
        const el2 = document.getElementById(id);
        el2.textContent = n + '/' + max;
        el2.style.color = n > max * 0.9 ? '#ef4444' : 'var(--text-muted)';
    }

    function updatePreview() {
        const nameEl = document.getElementById('svcName');
        const catSelect = document.getElementById('svcCat');
        const basePriceEl = document.getElementById('basePrice');
        const discountEl = document.getElementById('discount');
        const currencyEl = document.getElementById('currency');

        const name = nameEl ? nameEl.value : '';
        let catText = '';

        if (catSelect && catSelect.options && catSelect.selectedIndex >= 0) {
            catText = catSelect.options[catSelect.selectedIndex]?.text || '';
        }

        const base = basePriceEl ? parseFloat(basePriceEl.value) || 0 : 0;
        const disc = discountEl ? parseFloat(discountEl.value) || 0 : 0;
        const curr = currencyEl ? currencyEl.value : 'JOD';

        const sumNameEl = document.getElementById('sumName');
        const sumCatEl = document.getElementById('sumCat');
        const prevBaseEl = document.getElementById('prevBase');
        const prevCurrEl = document.getElementById('prevCurr');

        if (sumNameEl) sumNameEl.textContent = name || '—';
        if (sumCatEl) sumCatEl.textContent = catText || '—';
        if (prevBaseEl) prevBaseEl.textContent = base.toFixed(2);
        if (prevCurrEl) prevCurrEl.textContent = curr;

        const subtotal = base;
        const discAmt = subtotal * disc / 100;
        const total = subtotal - discAmt;

        const discountRowEl = document.getElementById('discountRow');
        const prevDiscountEl = document.getElementById('prevDiscount');
        const prevTotalEl = document.getElementById('prevTotal');

        if (discountRowEl && prevDiscountEl) {
            if (disc > 0) {
                discountRowEl.style.display = 'flex';
                prevDiscountEl.textContent = '-' + discAmt.toFixed(2);
            } else {
                discountRowEl.style.display = 'none';
            }
        }

        if (prevTotalEl) {
            prevTotalEl.innerHTML = total.toFixed(2) + ' <span class="currency">' + curr + '</span>';
        }

        updateCompletion();
    }

    function updateCompletion() {
        let score = 0;
        if (document.getElementById('svcName').value.trim()) score += 30;
        // if (document.getElementById('svcDesc').value.trim().length > 20) score += 20;
        if (document.getElementById('svcCat').value) score += 15;
        if (document.getElementById('basePrice').value) score += 55;
        // if (tags.length > 0) score += 10;
        document.getElementById('completionFill').style.width = score + '%';
        document.getElementById('completionText').textContent = 'اكتمال النموذج: ' + score + '%';
    }

    function handleTag(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const input = event.target;
            const tag = input.value.trim();
            if (tag && tags.length < 10 && !tags.includes(tag)) {
                tags.push(tag);
                renderTags();
                input.value = '';
            }
        }
    }

    function renderTags() {
        const wrap = document.getElementById('tagWrap');
        const input = document.getElementById('tagInput');
        const tagsInput = document.getElementById('tagsInput');

        // Remove existing pills
        wrap.querySelectorAll('.tag-pill').forEach(pill => pill.remove());

        // Add pills before input
        tags.forEach((tag, index) => {
            const pill = document.createElement('span');
            pill.className = 'tag-pill';
            pill.innerHTML = `${tag} <button type="button" onclick="removeTag(${index})">×</button>`;
            wrap.insertBefore(pill, input);
        });

        tagsInput.value = tags.join(',');
        updateCompletion();
    }

    function removeTag(index) {
        tags.splice(index, 1);
        renderTags();
    }

    function handleImages(input) {
        const thumbsWrap = document.getElementById('thumbsWrap');
        thumbsWrap.innerHTML = '';

        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrap = document.createElement('div');
                wrap.className = 'img-thumb-wrap';
                wrap.innerHTML = `
                    <img src="${e.target.result}">
                    <button type="button" class="remove-img" onclick="this.parentElement.remove(); updateCompletion()">×</button>
                `;
                thumbsWrap.appendChild(wrap);
                updateCompletion();
            };
            reader.readAsDataURL(file);
        });
    }

    function loadSubCategories(categoryId) {
        const subSelect = document.getElementById('subCategorySelect');
        subSelect.innerHTML = '<option value="">اختر تصنيف فرعي...</option>';

        if (categoryId) {
            const selectedOption = document.querySelector(`#svcCat option[value="${categoryId}"]`);
            if (selectedOption) {
                const childrenData = selectedOption.getAttribute('data-children');
                if (childrenData) {
                    try {
                        const children = JSON.parse(childrenData);
                        if (children && children.length > 0) {
                            subSelect.disabled = false;
                            children.forEach(sub => {
                                const option = document.createElement('option');
                                option.value = sub.id;
                                option.textContent = sub.name;
                                subSelect.appendChild(option);
                            });
                        } else {
                            subSelect.disabled = true;
                        }
                    } catch (e) {
                        console.error('Error parsing children data:', e);
                        subSelect.disabled = true;
                    }
                } else {
                    subSelect.disabled = true;
                }
            }
        } else {
            subSelect.disabled = true;
        }
    }

    // Update availability days hidden input before form submission
    document.getElementById('appServiceForm').addEventListener('submit', function(e) {
        const checkedDays = Array.from(document.querySelectorAll('input[name="availability_days[]"]:checked'))
            .map(cb => cb.value);
        document.getElementById('availabilityDaysInput').value = JSON.stringify(checkedDays);

        // Collect variants data
        const variants = [];
        document.querySelectorAll('.variant-block').forEach(block => {
            const id = block.id;
            const name = document.getElementById(id + '_name')?.value || '';
            const type = document.getElementById(id + '_type')?.value || 'single';
            const required = document.getElementById(id + '_req')?.value || 'required';
            const options = [];

            document.querySelectorAll('#' + id + '_options .option-row').forEach(optRow => {
                const optId = optRow.id;
                const optName = document.getElementById(optId + '_n')?.value || '';
                const priceInput = optRow.querySelector('.selected-option-price');
                const optPrice = priceInput ? priceInput.value : '0';
                const discountPriceInput = optRow.querySelector('.selected-option-discount-price');
                const optDiscountPrice = discountPriceInput ? discountPriceInput.value : '0';
                if (optName) {
                    options.push({
                        name: optName,
                        price: parseFloat(optPrice) || 0,
                        discount_price: parseFloat(optDiscountPrice) || 0
                    });
                }
            });

            if (name) {
                variants.push({
                    name: name,
                    type: type,
                    required: required,
                    options: options
                });
            }
        });

        document.getElementById('variantsInput').value = JSON.stringify(variants);
    });


    function addVariant() {
        variantCount++;
        const id = 'v' + variantCount;
        const container = document.getElementById('variantsContainer');
        const div = document.createElement('div');
        div.className = 'variant-block';
        div.id = id;
        div.innerHTML = `
    <div class="variant-header">
      <div class="variant-title"><i class="bi bi-sliders2"></i> متغير #${variantCount}</div>
      <div class="d-flex align-items-center gap-2">
        <span class="variant-type-badge" id="${id}_typeBadge">اختيار واحد</span>
        <button class="btn-danger-soft" onclick="document.getElementById('${id}').remove(); updatePreview()"><i class="bi bi-trash3"></i></button>
      </div>
    </div>
    <div class="variant-body">
      <div class="row g-3 mb-3">
        <div class="col-sm-5">
          <label class="form-label">اسم المتغير</label>
          <input type="text" class="form-control" placeholder="مثال: الحجم، الجودة، الإضافات" id="${id}_name">
        </div>
        <div class="col-sm-4">
          <label class="form-label">نوع الاختيار</label>
          <select class="form-select" id="${id}_type" onchange="updateTypeBadge('${id}')">
            <option value="single">اختيار واحد</option>
            <option value="multi">اختيار متعدد</option>
            <option value="dropdown">قائمة منسدلة</option>
          </select>
        </div>
        <div class="col-sm-3">
          <label class="form-label">الحقل</label>
          <select class="form-select" id="${id}_req">
            <option value="required">إلزامي</option>
            <option value="optional">اختياري</option>
          </select>
        </div>
      </div>
      <div class="mb-2" style="font-size:0.83rem; font-weight:500; color:var(--text-muted)"><i class="bi bi-list-ul me-1"></i>الخيارات</div>
      <div id="${id}_options"></div>
      <button type="button" class="btn-add-option mt-1" onclick="addOption('${id}')"><i class="bi bi-plus me-1"></i>إضافة خيار</button>
    </div>`;
        container.appendChild(div);
        addOption(id);
        updatePreview();
    }
    function addOption(vid) {
        const container = document.getElementById(vid + '_options');
        const optId = vid + '_opt' + Date.now();
        const div = document.createElement('div');
        div.className = 'option-row';
        div.id = optId;
        div.innerHTML = `
    <div class="option-name">
      <input type="text" class="form-control" placeholder="اسم الخيار (مثل: صغير، كبير...)" id="${optId}_n" style="font-size:0.85rem">
    </div>
    <div class="price-input">
      <div class="input-group input-group-sm">
        <span class="input-group-text" style="font-size:0.75rem; padding:0.3rem 0.5rem">+</span>
        <input type="number" class="form-control selected-option-price" placeholder="0.00" min="0" step="0.01"
          data-label="" data-price="0" style="font-size:0.85rem"
          oninput="this.dataset.price=this.value||0; this.dataset.label=document.getElementById('${optId}_n').value||'خيار'; updatePreview()">
        <span class="input-group-text" style="font-size:0.72rem; padding:0.3rem 0.4rem" id="optCurr_${optId}">JOD</span>
      </div>
    </div>
    <div class="price-input">
      <div class="input-group input-group-sm">
        <span class="input-group-text" style="font-size:0.75rem; padding:0.3rem 0.5rem">%</span>
        <input type="number" class="form-control selected-option-discount-price" placeholder="0.00" min="0" step="0.01"
          data-discount-price="0" style="font-size:0.85rem"
          oninput="this.dataset.discountPrice=this.value||0">
      </div>
    </div>
    <button class="btn-danger-soft" onclick="document.getElementById('${optId}').remove(); updatePreview()" title="حذف"><i class="bi bi-x-lg"></i></button>`;
        container.appendChild(div);
    }
    // Initialize
    updatePreview();
</script>
@endsection
