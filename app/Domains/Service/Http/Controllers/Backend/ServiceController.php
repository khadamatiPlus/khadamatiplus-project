<?php

namespace App\Domains\Service\Http\Controllers\Backend;

use App\Domains\Lookups\Models\Category;
use App\Domains\Lookups\Services\CategoryService;
use App\Domains\Lookups\Services\CountryService;
use App\Domains\Lookups\Services\TagService;
use App\Domains\Merchant\Services\MerchantService;
use App\Domains\Service\Http\Requests\Backend\ServiceRequest;
use App\Domains\Service\Http\Requests\Backend\StoreServiceRequest;
use App\Domains\Service\Http\Requests\Backend\UpdateServiceRequest;
use App\Domains\Service\Http\Transformers\ServiceTransformer;
use App\Domains\Service\Models\Service;
use App\Domains\Service\Models\ServiceImage;
use App\Domains\Service\Models\ServicePrice;
use App\Domains\Service\Models\ServiceProduct;
use App\Domains\Service\Services\ServiceService;
use App\Domains\Lookups\Services\CityService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    private ServiceService $serviceService;
    private CategoryService $categoryService;
    private CountryService $countryService;
    private MerchantService $merchantService;


    public function __construct(ServiceService $serviceService,CategoryService $categoryService,MerchantService $merchantService, TagService $tagService)
    {
        $this->serviceService = $serviceService;
        $this->tagService = $tagService;
        $this->categoryService = $categoryService;
        $this->merchantService = $merchantService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.service.index');
    }
    public function create()
    {
        $tags = $this->tagService->where('parent_id',null)->with('children')->get();
        $categories = $this->categoryService->where('parent_id',null)->with('children')->get();
        $merchants = $this->merchantService->select(['id','name'])->get();
        return view('backend.service.create')
            ->withCategories($categories)
            ->withMerchants($merchants)
            ->withTags($tags);
    }

//    public function store(ServiceRequest $request)
//    {
//        $this->serviceService->store($request->validated());
//        return redirect()->route('admin.service.index')->withFlashSuccess(__('The Service was successfully added'));
//    }



    public function store(StoreServiceRequest $request)
    {

        DB::beginTransaction();

        try {
            // Create the service
            $service = Service::create([
                'merchant_id' => $request->merchant_id,
                'sub_category_id' => $request->sub_category_id,
                'category_id' => Category::findOrFail($request->sub_category_id)->parent_id ?? 1,
                'title' => $request->title,
                'title_ar' => $request->title, // Consider adding Arabic translation
                'description' => $request->description,
                'duration' => $request->duration,
                 'price'=>  $request->prices[0]['amount'],
            ]);

            // Create price options
            $this->createServicePrices($service, $request->prices);

            // Sync tags
            if ($request->has('tags')) {
                $service->tags()->sync($request->tags);
            }

            // Handle service images
            if ($request->hasFile('service_images')) {
                $this->uploadServiceImages($service, $request);
            }

            // Handle products
            if ($request->has('products')) {
                $this->createServiceProducts($service, $request->products);
            }

            DB::commit();

            return redirect()
                ->route('admin.service.index')
                ->withFlashSuccess(__('Service was successfully created'));

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Service creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withFlashDanger(__('Failed to create service. Please try again.'));
        }
    }

    protected function createServicePrices(Service $service, array $prices)
    {
        $priceModels = collect($prices)->map(function ($price) {
            return new ServicePrice([
                'title' => $price['title'],
                'amount' => $price['amount'] ,
            ]);
        });

        $service->prices()->saveMany($priceModels);
    }

    protected function uploadServiceImages(Service $service, Request $request)
    {
        $mainImageIndex = $request->main_service_image ?? 0;

        foreach ($request->file('service_images') as $index => $file) {
            $path = $file->store('services/' . $service->id, 'public');

            $service->images()->create([
                'image' => $path,
                'is_main' => $index == $mainImageIndex,
                'order' => $index,
            ]);
        }
    }

    protected function createServiceProducts(Service $service, array $products)
    {
        foreach ($products as $productData) {
            // First validate non-file data
            $validated = validator($productData, [
                'title' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'order' => 'integer|min:0',
                'main_image_index' => 'nullable|integer|min:0',
            ])->validate();


            $product = $service->products()->create([
                'title' => $validated['title'],
                'price' => $validated['price'] * 100,
                'description' => $validated['description'] ?? null,
                'order' => $validated['order'] ?? 0,
            ]);
            if (isset($productData['images']))
            {
                $this->uploadProductImages($product, [
                    'images' => $productData['images'],
                    'main_image_index' => $validated['main_image_index']
                ]);
            }

        }
    }

    protected function uploadProductImages(ServiceProduct $product, array $productData)
    {
        $mainImageIndex = $productData['main_image_index'];

        // Ensure we have uploaded files
        if (!isset($productData['images']) || !is_array($productData['images'])) {
            return;
        }

        foreach ($productData['images'] as $index => $file) {
            // Skip if not a valid uploaded file
            if (!($file instanceof \Illuminate\Http\UploadedFile)) {
                continue;
            }

            $path = $file->store('products/' . $product->id, 'public');

            $product->images()->create([
                'image' => $path,
                'is_main' => $index == $mainImageIndex,
                'order' => $index,
            ]);
        }
    }


    public function edit(Service $service)
    {

        $tags = $this->tagService->where('parent_id',null)->with('children')->get();
        $categories = $this->categoryService->where('parent_id',null)->with('children')->get();
        $merchants = $this->merchantService->select(['id','name'])->get();
        return view('backend.service.edit',compact('tags'))
            ->withService($service)
            ->withCategories($categories)
            ->withMerchants($merchants);
    }

//    public function update(ServiceRequest $request, $service)
//    {
//        $this->serviceService->update($service, $request->validated());
//        return redirect()->back()->withFlashSuccess(__('The Service was successfully updated'));
//    }


    public function update(UpdateServiceRequest $request, Service $service)
    {
        DB::beginTransaction();

        try {
            // Update basic service info
            $service->update([
                'merchant_id' => $request->merchant_id,
                'sub_category_id' => $request->sub_category_id,
                'category_id' => Category::findOrFail($request->sub_category_id)->parent_id ?? 1,
                'title' => $request->title,
                'description' => $request->description,
                'duration' => $request->duration,
            ]);

            // Handle images
            $this->processServiceImages($service, $request);

            // Update other relationships (prices, products, etc.)
            $this->updateServicePrices($service, $request->prices);
            $service->tags()->sync($request->tags ?? []);

            if ($request->has('products')) {
                $this->updateServiceProducts($service, $request->products);
            }

            DB::commit();

            return redirect()->back()
                ->withFlashSuccess(__('Service updated successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Service update failed: ' . $e->getMessage());

            return back()->withInput()
                ->withFlashDanger(__('Failed to update service. Please try again.'));
        }
    }

    protected function processServiceImages(Service $service, Request $request)
    {
        // 1. Handle image deletions
        if ($request->has('deleted_images')) {
            $deletedImageIds = array_keys(array_filter($request->deleted_images));
            if (!empty($deletedImageIds)) {
                $service->images()->whereIn('id', $deletedImageIds)->delete();
            }
        }

        // 2. Handle new image uploads
        if ($request->hasFile('service_images')) {
            foreach ($request->file('service_images') as $file) {
                $path = $file->store('services/' . $service->id, 'public');
                $service->images()->create([
                    'image' => $path,
                    'is_main' => false
                ]);
            }
        }

        // 3. Update main image
        if ($request->has('main_service_image')) {
            // First reset all images to not main
            $service->images()->update(['is_main' => false]);

            // Then set the selected one as main
            $service->images()
                ->where('id', $request->main_service_image)
                ->update(['is_main' => true]);
        } elseif ($service->images()->count() > 0) {
            // If no main image selected but images exist, set first one as main
            $service->images()->orderBy('id')->first()->update(['is_main' => true]);
        }
    }
    protected function updateServicePrices(Service $service, array $prices)
    {
        // Get existing price IDs from request
        $existingPriceIds = collect($prices)->filter(fn($price) => isset($price['id']))->pluck('id');

        // Delete prices not present in the request
        $service->prices()->whereNotIn('id', $existingPriceIds)->delete();

        // Update or create prices
        foreach ($prices as $priceData) {
            if (isset($priceData['id'])) {
                // Update existing price
                $service->prices()
                    ->where('id', $priceData['id'])
                    ->update([
                        'title' => $priceData['title'],
                        'amount' => $priceData['amount'], // store in cents
                    ]);
            } else {
                // Create new price
                $service->prices()->create([
                    'title' => $priceData['title'],
                    'amount' => $priceData['amount'] ,
                ]);
            }
        }
    }



    protected function updateServiceProducts(Service $service, array $products)
    {
        $existingProductIds = collect($products)->filter(fn($product) => isset($product['id']))->pluck('id');

        // Delete products not present in the request
        $service->products()->whereNotIn('id', $existingProductIds)->delete();

        foreach ($products as $productData) {
            if (isset($productData['id'])) {
                // Update existing product
                $product = $service->products()->find($productData['id']);
                $product->update([
                    'title' => $productData['title'],
                    'price' => $productData['price'] ,
                    'description' => $productData['description'] ?? null,
                    'order' => $productData['order'] ?? 0,
                ]);

                // Handle product images update if needed
                if (isset($productData['images'])) {
                    $this->updateProductImages($product, $productData);
                }
            } else {
                // Create new product
                $product = $service->products()->create([
                    'title' => $productData['title'],
                    'price' => $productData['price'] ,
                    'description' => $productData['description'] ?? null,
                    'order' => $productData['order'] ?? 0,
                ]);

                if (isset($productData['images'])) {
                    $this->uploadProductImages($product, $productData);
                }
            }
        }
    }

    protected function updateServiceImages(Service $service, Request $request)
    {
        // Get IDs of images to keep
        $keepImageIds = $request->input('existing_images', []);

        // Delete images not in the keep list
        $service->images()
            ->whereNotIn('id', $keepImageIds)
            ->delete();

        // Handle new image uploads
        if ($request->hasFile('service_images')) {
            foreach ($request->file('service_images') as $file) {
                $path = $file->store('services/' . $service->id, 'public');

                $image = $service->images()->create([
                    'image' => $path,
                    'is_main' => false
                ]);

                // Add new image ID to keep list
                $keepImageIds[] = $image->id;
            }
        }

        // Update main image
        $this->updateMainImage($service, $request->main_service_image, $keepImageIds);
    }

    protected function updateMainImage(Service $service, $mainImageId, array $keepImageIds)
    {
        // Reset all images to not main
        $service->images()->update(['is_main' => false]);

        // If no main image selected but we have images, use first available
        if (empty($mainImageId)) {
            $mainImageId = $keepImageIds[0] ?? null;
        }

        // Set the selected image as main
        if ($mainImageId) {
            $service->images()
                ->where('id', $mainImageId)
                ->update(['is_main' => true]);
        }
    }

    protected function updateProductImages(ServiceProduct $product, array $productData)
    {
        // Get all existing image IDs that should remain
        $existingImageIds = $productData['existing_images'] ?? [];

        // Delete images not present in the existing_images array
        $product->images()->whereNotIn('id', $existingImageIds)->delete();

        // Handle new image uploads
        if (isset($productData['new_images'])) {
            foreach ($productData['new_images'] as $file) {
                $path = $file->store('products/' . $product->id, 'public');

                $product->images()->create([
                    'image' => $path,
                    'is_main' => false, // Will be updated below
                ]);
            }
        }

        // Update main image
        if (isset($productData['main_image'])) {
            // First reset all images to not main
            $product->images()->update(['is_main' => false]);

            // Then set the selected one as main
            $product->images()
                ->where('id', $productData['main_image'])
                ->update(['is_main' => true]);
        } elseif ($product->images()->count() > 0) {
            // If no main image selected but images exist, set first one as main
            $product->images()->first()->update(['is_main' => true]);
        }
    }


    /**
     * @param $area
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function destroy($area)
    {
        $this->serviceService->destroy($area);
        return redirect()->back()->withFlashSuccess(__('The Service was successfully deleted.'));
    }
    public function getCategorySubs($id){
        $categorySubs=DB::table('categories')->where('parent_id',$id)
            ->pluck('name','id');
        return json_encode($categorySubs);
    }
    public function show(Service $service)
    {
        return view('backend.service.show')
            ->withService($service);
    }
    public function destroyImage($id)
    {
        $image = ServiceImage::findOrFail($id);

        // Delete the image file from storage if it exists
        if ($image->image && file_exists(public_path($image->image))) {
            unlink(public_path($image->image));
        }

        // Delete the record from the database
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
    public function destroyProduct($id)
    {
        try {
            // Find the product by ID
            $product = ServiceProduct::findOrFail($id);

            // Delete associated images
            if ($product->images()->exists()) {
                foreach ($product->images as $image) {
                    // Delete image file from storage if necessary
                    if (\Storage::exists($image->image)) {
                        \Storage::delete($image->image);
                    }
                    $image->delete(); // Delete image record from the database
                }
            }

            // Delete the product
            $product->delete();

            // Return success response
            return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['success' => false, 'message' => 'Failed to delete product.'], 500);
        }
    }
}
