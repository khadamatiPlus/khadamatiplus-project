<?php

namespace App\Domains\AppService\Http\Controllers\Backend;

use App\Domains\AppService\Models\AppService;
use App\Domains\Lookups\Models\Category;
use App\Domains\Lookups\Services\CategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppServiceController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $appServices = AppService::with(['category', 'subCategory', 'createdBy'])->latest()->paginate(10);
        return view('backend.app-service.index', compact('appServices'));
    }

    public function create()
    {
        $categories = $this->categoryService->where('parent_id', null)->with('children')->get();
        return view('backend.app-service.create')->withCategories($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'price_type' => 'required|string|in:fixed,hourly,starts_from,by_agreement',
            'status' => 'required|string|in:active,draft,inactive,scheduled',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            // Set default values for nullable fields that have database constraints
            $data['discount'] = $request->input('discount', 0);
            $data['delivery_time'] = $request->input('delivery_time');
            $data['delivery_time_unit'] = $request->input('delivery_time_unit', 'days');
            $data['free_revisions'] = $request->input('free_revisions', 0);
            $data['requirements_mandatory'] = $request->input('requirements_mandatory', true);
            $data['language'] = $request->input('language', 'arabic');
            $data['scope'] = $request->input('scope', 'local');
            $data['is_featured'] = $request->input('is_featured', false);
            $data['is_urgent'] = $request->input('is_urgent', false);
            $data['is_online'] = $request->input('is_online', true);

            // Handle images
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('app-services', 'public');
                    $imagePaths[] = $path;
                }
                $data['images'] = $imagePaths;
            } else {
                $data['images'] = null;
            }

            // Handle tags
            if ($request->has('tags') && !empty($request->tags)) {
                $tags = $request->tags;
                if (is_string($tags)) {
                    $tags = explode(',', $tags);
                }
                $data['tags'] = $tags;
            } else {
                $data['tags'] = null;
            }

            // Handle availability days
            if ($request->has('availability_days') && !empty($request->availability_days)) {
                $availabilityDays = $request->availability_days;
                if (is_string($availabilityDays)) {
                    $availabilityDays = json_decode($availabilityDays, true);
                }
                $data['availability_days'] = $availabilityDays;
            } else {
                $data['availability_days'] = null;
            }

            // Handle variants
            if ($request->has('variants') && !empty($request->variants)) {
                $variants = $request->variants;
                // If variants is a string (already JSON encoded), decode it first
                if (is_string($variants)) {
                    $variants = json_decode($variants, true);
                }
                $data['variants'] = $variants;
            } else {
                $data['variants'] = null;
            }

            // Handle checkboxes
            $data['requirements_mandatory'] = $request->has('requirements_mandatory');
            $data['is_featured'] = $request->has('is_featured');
            $data['is_urgent'] = $request->has('is_urgent');
            $data['is_online'] = $request->has('is_online');

            $data['created_by_id'] = auth()->id();
            $data['updated_by_id'] = auth()->id();

            AppService::create($data);

            DB::commit();
            return redirect()->route('admin.app-service.index')->withFlashSuccess(__('App Service created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withFlashDanger(__('Failed to create App Service: ' . $e->getMessage()));
        }
    }

    public function show(AppService $appService)
    {
        $appService->load(['category', 'subCategory', 'createdBy', 'updatedBy']);
        return view('backend.app-service.show', compact('appService'));
    }

    public function edit(AppService $appService)
    {
        $categories = $this->categoryService->where('parent_id', null)->with('children')->get();
        return view('backend.app-service.edit', compact('appService', 'categories'));
    }

    public function update(Request $request, AppService $appService)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'price_type' => 'required|string|in:fixed,hourly,starts_from,by_agreement',
            'status' => 'required|string|in:active,draft,inactive,scheduled',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            \Log::info('AppService Update - Request data:', [
                'has_files' => $request->hasFile('images'),
                'files_count' => $request->hasFile('images') ? count($request->file('images')) : 0,
                'has_deleted_images' => $request->has('deleted_images'),
                'deleted_images' => $request->deleted_images,
                'current_images' => $appService->images,
            ]);

            // Handle images
            $currentImages = $appService->images ?? [];

            // Delete images that were marked for deletion
            if ($request->has('deleted_images')) {
                $deletedImages = $request->deleted_images;
                \Log::info('Processing deleted images:', ['deleted_images' => $deletedImages]);

                if (is_string($deletedImages)) {
                    $deletedImages = json_decode($deletedImages, true);
                }
                if (is_array($deletedImages)) {
                    foreach ($deletedImages as $deletedImage) {
                        \Log::info('Deleting image:', ['image' => $deletedImage]);
                        // Remove from storage
                        if (file_exists(storage_path('app/public/' . $deletedImage))) {
                            unlink(storage_path('app/public/' . $deletedImage));
                        }
                        // Remove from current images array
                        $currentImages = array_filter($currentImages, function($img) use ($deletedImage) {
                            return $img !== $deletedImage;
                        });
                    }
                }
            }

            // Add new images
            if ($request->hasFile('images')) {
                \Log::info('Processing new images:', ['count' => count($request->file('images'))]);
                foreach ($request->file('images') as $image) {
                    $path = $image->store('app-services', 'public');
                    \Log::info('Image stored:', ['path' => $path]);
                    $currentImages[] = $path;
                }
            }

            $data['images'] = array_values($currentImages);
            \Log::info('Final images array:', ['images' => $data['images']]);

            // Handle tags
            if ($request->has('tags')) {
                $tags = $request->tags;
                if (is_string($tags)) {
                    $tags = explode(',', $tags);
                }
                $data['tags'] = $tags;
            }

            // Handle availability days
            if ($request->has('availability_days')) {
                $availabilityDays = $request->availability_days;
                if (is_string($availabilityDays)) {
                    $availabilityDays = json_decode($availabilityDays, true);
                }
                $data['availability_days'] = $availabilityDays;
            }

            // Handle variants
            if ($request->has('variants') && !empty($request->variants)) {
                $variants = $request->variants;
                // If variants is a string (already JSON encoded), decode it first
                if (is_string($variants)) {
                    $variants = json_decode($variants, true);
                }
                $data['variants'] = $variants;
            } else {
                $data['variants'] = null;
            }

            $data['updated_by_id'] = auth()->id();

            $appService->update($data);

            DB::commit();
            return redirect()->route('admin.app-service.index')->withFlashSuccess(__('App Service updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withFlashDanger(__('Failed to update App Service: ' . $e->getMessage()));
        }
    }

    public function destroy(AppService $appService)
    {
        try {
            $appService->delete();
            return redirect()->route('admin.app-service.index')->withFlashSuccess(__('App Service deleted successfully'));
        } catch (\Exception $e) {
            return back()->withFlashDanger(__('Failed to delete App Service: ' . $e->getMessage()));
        }
    }
}
