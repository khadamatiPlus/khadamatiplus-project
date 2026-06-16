<?php

namespace App\Domains\Coupon\Http\Controllers\Backend;

use App\Domains\Coupon\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with(['createdBy', 'updatedBy'])->latest()->paginate(10);
        return view('backend.coupon.index', compact('coupons'));
    }

    public function create()
    {
        return view('backend.coupon.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            
            // Set default values
            $data['is_active'] = (bool)$request->input('is_active', false);
            $data['used_count'] = 0;
            
            $data['created_by_id'] = auth()->id();
            $data['updated_by_id'] = auth()->id();

            Coupon::create($data);

            DB::commit();
            return redirect()->route('admin.coupon.index')->withFlashSuccess(__('Coupon created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withFlashDanger(__('Failed to create Coupon: ' . $e->getMessage()));
        }
    }

    public function show(Coupon $coupon)
    {
        $coupon->load(['createdBy', 'updatedBy']);
        return view('backend.coupon.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        return view('backend.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            
            // Handle checkbox
            $data['is_active'] = (bool)$request->input('is_active', false);

            $data['updated_by_id'] = auth()->id();

            $coupon->update($data);

            DB::commit();
            return redirect()->route('admin.coupon.index')->withFlashSuccess(__('Coupon updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withFlashDanger(__('Failed to update Coupon: ' . $e->getMessage()));
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();
            return redirect()->route('admin.coupon.index')->withFlashSuccess(__('Coupon deleted successfully'));
        } catch (\Exception $e) {
            return back()->withFlashDanger(__('Failed to delete Coupon: ' . $e->getMessage()));
        }
    }
}
