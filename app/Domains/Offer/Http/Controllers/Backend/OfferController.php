<?php

namespace App\Domains\Offer\Http\Controllers\Backend;

use App\Domains\Offer\Models\Offer;
use App\Domains\Lookups\Models\Category;
use App\Domains\Lookups\Services\CategoryService;
use App\Domains\AppService\Models\AppService;
use App\Domains\Coupon\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $offers = Offer::with(['coupon', 'category', 'appService', 'createdBy'])->latest()->paginate(10);
        return view('backend.offer.index', compact('offers'));
    }

    public function create()
    {
        $categories = $this->categoryService->where('parent_id', null)->with('children')->get();
        $coupons = Coupon::active()->get();
        $appServices = AppService::where('status', 'active')->get();
        return view('backend.offer.create', compact('categories', 'coupons', 'appServices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coupon_id' => 'nullable|exists:coupons,id',
            'category_id' => 'nullable|exists:categories,id',
            'app_service_id' => 'nullable|exists:app_services,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('offers', 'public');
                $data['image'] = $path;
            }

            // Handle checkboxes
            $data['is_active'] = (bool)$request->input('is_active', true);
            $data['is_featured'] = (bool)$request->input('is_featured', false);
            
            $data['created_by_id'] = auth()->id();
            $data['updated_by_id'] = auth()->id();

            Offer::create($data);

            DB::commit();
            return redirect()->route('admin.offer.index')->withFlashSuccess(__('Offer created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withFlashDanger(__('Failed to create Offer: ' . $e->getMessage()));
        }
    }

    public function show(Offer $offer)
    {
        $offer->load(['coupon', 'category', 'appService', 'createdBy', 'updatedBy']);
        return view('backend.offer.show', compact('offer'));
    }

    public function edit(Offer $offer)
    {
        $categories = $this->categoryService->where('parent_id', null)->with('children')->get();
        $coupons = Coupon::active()->get();
        $appServices = AppService::where('status', 'active')->get();
        return view('backend.offer.edit', compact('offer', 'categories', 'coupons', 'appServices'));
    }

    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coupon_id' => 'nullable|exists:coupons,id',
            'category_id' => 'nullable|exists:categories,id',
            'app_service_id' => 'nullable|exists:app_services,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($offer->image) {
                    Storage::disk('public')->delete($offer->image);
                }
                $path = $request->file('image')->store('offers', 'public');
                $data['image'] = $path;
            }

            // Handle checkboxes
            $data['is_active'] = (bool)$request->input('is_active', false);
            $data['is_featured'] = (bool)$request->input('is_featured', false);

            $data['updated_by_id'] = auth()->id();

            $offer->update($data);

            DB::commit();
            return redirect()->route('admin.offer.index')->withFlashSuccess(__('Offer updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withFlashDanger(__('Failed to update Offer: ' . $e->getMessage()));
        }
    }

    public function destroy(Offer $offer)
    {
        try {
            // Delete image if exists
            if ($offer->image) {
                Storage::disk('public')->delete($offer->image);
            }
            $offer->delete();
            return redirect()->route('admin.offer.index')->withFlashSuccess(__('Offer deleted successfully'));
        } catch (\Exception $e) {
            return back()->withFlashDanger(__('Failed to delete Offer: ' . $e->getMessage()));
        }
    }
}
