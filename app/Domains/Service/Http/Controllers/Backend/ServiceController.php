<?php

namespace App\Domains\Service\Http\Controllers\Backend;

use App\Domains\Lookups\Models\Category;
use App\Domains\Lookups\Services\CategoryService;
use App\Domains\Lookups\Services\CountryService;
use App\Domains\Lookups\Services\TagService;
use App\Domains\Merchant\Services\MerchantService;
use App\Domains\Service\Http\Requests\Backend\ServiceRequest;
use App\Domains\Service\Http\Transformers\ServiceTransformer;
use App\Domains\Service\Models\Service;
use App\Domains\Service\Models\ServiceImage;
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



    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'sub_category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'new_price' => 'nullable|numeric',
            'duration' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'images' => 'nullable|array',
            'images.*.image' => 'required', // Validate image as a URL
            'images.*.is_main' => 'required|boolean', // Validate the 'is_main' flag
            'products' => 'nullable|array',
            'products.*.title' => 'required|string',
            'products.*.price' => 'required|numeric',
            'products.*.description' => 'nullable|string',
            'products.*.images' => 'nullable|array',
            'products.*.images.*.image' => 'required', // Validate product image as a URL
            'products.*.images.*.is_main' => 'required|boolean', // Validate the 'is_main' flag
        ]);

        // Find the category and parent category
        $category = Category::query()->where('id', $validated['sub_category_id'])->first();

        // Create the service
        $service = Service::create([
            'merchant_id' => $validated['merchant_id'],
            'sub_category_id' => $validated['sub_category_id'],
            'category_id' => $category->parent_id??1,
            'title' => $validated['title'],
            'title_ar' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'new_price' => $validated['new_price'] ?? null,
            'duration' => $validated['duration']??null,
        ]);

        // Attach tags to the service if any
        if (isset($validated['tags'])) {
            $service->tags()->sync($validated['tags']);
        }

        if (isset($validated['images'])) {
            $images = [];
            $existingImages = $service->images()->get()->toArray();
            foreach ($validated['images'] as $imageData) {
                if ($imageData['image']->isValid()) {
                    $imagePath = $imageData['image']->store('uploads', 'public');
                    $imageUrl = asset('storage/' . $imagePath);
                    $images[] = [
                        'image' => $imageUrl,
                        'is_main' => $imageData['is_main'],
                    ];
                }
            }
            $allImages = array_merge($existingImages, $images);
            $service->images()->createMany($allImages);
        }


        // Handle products and their images
        if (isset($validated['products'])) {
            foreach ($validated['products'] as $productData) {
                $product = ServiceProduct::create([
                    'service_id' => $service->id,
                    'title' => $productData['title'],
                    'price' => $productData['price'],
                    'description' => $productData['description'] ?? null,
                    'order' => $productData['order'] ?? null,
                ]);



                if (isset($productData['images'])) {
                    $images = [];
                    foreach ($productData['images'] as $imageData) {
                        if ($imageData['image']->isValid()) {
                            $imagePath = $imageData['image']->store('uploads', 'public');
                            $imageUrl = asset('storage/' . $imagePath);
                            $images[] = [
                                'image' => $imageUrl,
                                'is_main' => $imageData['is_main'],
                            ];
                        }
                    }
                    $allImages = array_merge($existingImages, $images);
                    $product->images()->createMany($allImages);
                }


            }
        }

        return redirect()->route('admin.service.index')->withFlashSuccess(__('The Service was successfully added'));

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


    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validated = $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'sub_category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'new_price' => 'nullable|numeric',
            'duration' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'images' => 'nullable|array',
            'images.*.image' => 'nullable|file|image',
            'images.*.is_main' => 'nullable|boolean',
            'products' => 'nullable|array',
            'products.*.id' => 'nullable|exists:service_products,id',
            'products.*.title' => 'required|string',
            'products.*.price' => 'required|numeric',
            'products.*.description' => 'nullable|string',
            'products.*.images' => 'nullable|array',
            'products.*.images.*.image' => 'nullable|file|image',
            'products.*.images.*.is_main' => 'nullable|boolean',
        ]);

        // Find the existing service
        $service = Service::findOrFail($id);

        // Update the service
        $category = Category::query()->where('id', $validated['sub_category_id'])->first();
        $service->update([
            'merchant_id' => $validated['merchant_id'],
            'sub_category_id' => $validated['sub_category_id'],
            'category_id' => $category->parent_id ?? 1,
            'title' => $validated['title'],
            'title_ar' => $validated['title'],  // Assuming 'title_ar' is the Arabic title
            'description' => $validated['description'],
            'price' => $validated['price'],
            'new_price' => $validated['new_price'] ?? null,
            'duration' => $validated['duration'] ?? null,
        ]);

        // Attach or detach tags based on the input
        if (isset($validated['tags'])) {
            $service->tags()->sync($validated['tags']);
        }

        // Handle images (update existing ones and add new ones)
        if (isset($validated['images']) && count($validated['images']) > 0) {
            $images = [];
            // Loop over the new images
            foreach ($validated['images'] as $imageData) {
                if (isset($imageData['image']) && $imageData['image']->isValid()) {
                    $imagePath = $imageData['image']->store('uploads', 'public');
                    $imageUrl = asset('storage/' . $imagePath);
                    $images[] = [
                        'image' => $imageUrl,
                        'is_main' => $imageData['is_main'] ?? false,
                    ];
                }
            }

            // Attach or update images (only add new ones)
            $service->images()->createMany($images); // This will add new images without removing the old ones
        }

        // Handle products and their images (update or add new products)
        if (isset($validated['products'])) {
            // Remove products that are not in the updated list
            $existingProductIds = array_column($validated['products'], 'id');
            $service->products()->whereNotIn('id', $existingProductIds)->delete();

            foreach ($validated['products'] as $productData) {
                // Check if product exists with the given id
                $product = ServiceProduct::where('service_id', $service->id)
                    ->where('id', $productData['id'] ?? null)
                    ->first();

                if ($product) {
                    // Update existing product
                    $product->update([
                        'title' => $productData['title'],
                        'price' => $productData['price'],
                        'description' => $productData['description'] ?? null,
                        'order' => $productData['order'] ?? null,
                    ]);
                } else {
                    // Create new product
                    $product = $service->products()->create([
                        'title' => $productData['title'],
                        'price' => $productData['price'],
                        'description' => $productData['description'] ?? null,
                        'order' => $productData['order'] ?? null,
                    ]);
                }

                // Handle product images
                if (isset($productData['images']) && count($productData['images']) > 0) {
                    $images = [];
                    foreach ($productData['images'] as $imageData) {
                        if (isset($imageData['image']) && $imageData['image']->isValid()) {
                            $imagePath = $imageData['image']->store('uploads', 'public');
                            $imageUrl = asset('storage/' . $imagePath);
                            $images[] = [
                                'image' => $imageUrl,
                                'is_main' => $imageData['is_main'] ?? false,
                            ];
                        }
                    }

                    // Attach or update images for the product
                    $product->images()->createMany($images); // This will add new images without removing old ones
                }
            }
        }

        return redirect()->back()->withFlashSuccess(__('The Service was successfully updated'));
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
