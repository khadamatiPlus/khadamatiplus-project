<?php

namespace App\Domains\Service\Http\Controllers\API;

use App\Domains\Lookups\Models\Category;
use App\Domains\Merchant\Http\Transformers\MerchantTransformer;
use App\Domains\Merchant\Models\Merchant;
use App\Domains\Service\Http\Transformers\ServiceTransformer;
use App\Domains\Service\Models\Service;
use App\Domains\Service\Models\ServiceOption;
use App\Domains\Service\Models\ServiceProduct;
use App\Domains\Service\Services\ServiceService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;

class ServiceApiController extends APIBaseController
{

    private ServiceService $serviceService;


    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function storeService(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'sub_category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'new_price' => 'nullable|numeric',
            'duration' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'images' => 'nullable|array',
            'images.*.image' => 'required|url', // Validate image as a URL
            'images.*.is_main' => 'required|boolean', // Validate the 'is_main' flag
            'products' => 'nullable|array',
            'products.*.title' => 'required|string',
            'products.*.price' => 'required|numeric',
            'products.*.description' => 'nullable|string',
            'products.*.images' => 'nullable|array',
            'products.*.images.*.image' => 'required|url', // Validate product image as a URL
            'products.*.images.*.is_main' => 'required|boolean', // Validate the 'is_main' flag
        ]);

        // Find the category and parent category
        $category = Category::query()->where('id', $validated['sub_category_id'])->first();

        // Create the service
        $service = Service::create([
            'merchant_id' => auth()->user()->merchant_id,
            'sub_category_id' => $validated['sub_category_id'],
            'category_id' => $category->parent_id,
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

        // Handle service images
        if (isset($validated['images'])) {
            $images = [];
            foreach ($validated['images'] as $imageData) {
                $images[] = [
                    'image' => $imageData['image'], // Store the URL
                    'is_main' => $imageData['is_main'],
                ];
            }
            $service->images()->createMany($images);
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
                    $productImages = [];
                    foreach ($productData['images'] as $productImageData) {
                        $productImages[] = [
                            'image' => $productImageData['image'], // Use the URL directly
                            'is_main' => $productImageData['is_main'],
                        ];
                    }
                    $product->images()->createMany($productImages);
                }
            }
        }

        // Return the response with the transformed service
        return response()->json([
            'success' => true,
            'data' => (new ServiceTransformer())->transform($service),
        ]);
    }



    public function updateService(Request $request, $id)
    {
        // Validate incoming request
        $validated = $request->validate([
            'sub_category_id' => 'nullable|exists:categories,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'new_price' => 'nullable|numeric',
            'duration' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'images' => 'nullable|array',
            'images.*.image' => 'nullable|url', // Allow URL or null
            'images.*.is_main' => 'nullable|boolean',
            'products' => 'nullable|array',
            'products.*.id' => 'nullable|exists:service_products,id',
            'products.*.title' => 'nullable|string',
            'products.*.price' => 'nullable|numeric',
            'products.*.description' => 'nullable|string',
            'products.*.images' => 'nullable|array',
            'products.*.images.*.image' => 'nullable|url', // Allow URL for product images
            'products.*.images.*.is_main' => 'nullable|boolean',
        ]);

        // Find the existing service by ID
        $service = Service::findOrFail($id);

        // Update the service details if they are provided
        $service->update([
            'merchant_id' => auth()->user()->merchant_id,
            'sub_category_id' => $validated['sub_category_id'] ?? $service->sub_category_id,
            'category_id' => $validated['sub_category_id'] ? Category::find($validated['sub_category_id'])->parent_id : $service->category_id,
            'title' => $validated['title'] ?? $service->title,
            'description' => $validated['description'] ?? $service->description,
            'price' => $validated['price'] ?? $service->price,
            'new_price' => $validated['new_price'] ?? $service->new_price,
            'duration' => $validated['duration'] ?? $service->duration,
        ]);

        // Attach or update tags if any
        if (isset($validated['tags'])) {
            $service->tags()->sync($validated['tags']);
        }

        // Handle service images (URL-based or uploaded)
        if (isset($validated['images'])) {
            // Delete existing images (if needed, depending on your use case)
            $service->images()->delete();

            $images = [];
            foreach ($validated['images'] as $imageData) {
                $images[] = [
                    'image' => $imageData['image'], // Assume URL is directly passed
                    'is_main' => $imageData['is_main'] ?? false,
                ];
            }

            // Store new images in the service's image relationship
            $service->images()->createMany($images);
        }

        // Handle updating products if any
        if (isset($validated['products'])) {
            foreach ($validated['products'] as $productData) {
                $product = ServiceProduct::updateOrCreate(
                    ['id' => $productData['id'] ?? null, 'service_id' => $service->id], // Find or create by product ID
                    [
                        'title' => $productData['title'] ?? null,
                        'price' => $productData['price'] ?? null,
                        'description' => $productData['description'] ?? null,
                    ]
                );

                // Handle product images (URL-based or uploaded)
                if (isset($productData['images'])) {
                    $product->images()->delete(); // Delete existing images

                    $productImages = [];
                    foreach ($productData['images'] as $productImageData) {
                        $productImages[] = [
                            'image' => $productImageData['image'], // Assume URL is directly passed
                            'is_main' => $productImageData['is_main'] ?? false,
                        ];
                    }

                    $product->images()->createMany($productImages); // Store new product images
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => (new ServiceTransformer)->transform($service),
        ]);
    }


    public function getServiceDetails($id)
    {
        // Find the service by ID
        $service = Service::with(['tags', 'images', 'products'])->find($id);

        // Check if service exists
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        // Return the service details using the transformer
        return $this->successResponse(
            (new ServiceTransformer)->transform($service)
        );
    }

    public function getServices(Request $request)
    {
        // Get the authenticated merchant_id
        $merchant_id = auth()->user()->merchant_id;

        // Base query to get services for the authenticated merchant
        $query = Service::query()->where('merchant_id', $merchant_id);

        // Apply optional search filters if provided
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply pagination
        $services = $query->paginate(10); // Adjust the items per page as needed

        // Transform the paginated data
        $transformedServices = $services->getCollection()->map(function ($service) {
            return (new ServiceTransformer)->transform($service);
        });

        // Return the response using successResponse
        return $this->successResponse([
            'data' => $transformedServices,
            'pagination' => [
                'total' => $services->total(),
                'count' => $services->count(),
                'per_page' => $services->perPage(),
                'current_page' => $services->currentPage(),
                'total_pages' => $services->lastPage(),
            ],
        ]);
    }

    public function deleteService($id)
    {
        // Find the service by ID
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found.',
            ], 404);
        }

        // Check if the authenticated user owns the service or has permission
        if (auth()->user()->merchant_id !== $service->merchant_id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this service.',
            ], 403);
        }

        // Delete related data (optional, depending on your business logic)
        $service->tags()->detach(); // Detach tags
        $service->images()->delete(); // Delete images
        $service->products()->delete(); // Delete associated products (if applicable)

        // Delete the service
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully.',
        ]);
    }


    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('uploads', 'public');
        $imageUrl = asset($imagePath);

        return response()->json([
            'success' => true,
            'url' => $imageUrl,
        ]);
    }


    public function getAllServices(Request $request)
    {
        $query = Service::query();

        // Apply optional search filters if provided
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply optional sub_category_id filter
        if ($request->has('sub_category_id') && !empty($request->input('sub_category_id'))) {
            $subCategoryId = $request->input('sub_category_id');
            $query->where('sub_category_id', $subCategoryId);
        }

        // Apply optional tags filter
        if ($request->has('tags') && !empty($request->input('tags'))) {
            $tags = $request->input('tags'); // Expecting tags as an array or comma-separated string
            if (is_string($tags)) {
                $tags = explode(',', $tags); // Convert comma-separated string to array
            }

            $query->whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('tag_id', $tags);
            });
        }

        $query->orderBy('created_at', 'desc');
        $services = $query->paginate(10); // Adjust the items per page as needed

        // Transform the paginated data
        $transformedServices = $services->getCollection()->map(function ($service) {
            return (new ServiceTransformer)->transform($service);
        });

        // Return the response using successResponse
        return $this->successResponse([
            'data' => $transformedServices,
            'pagination' => [
                'total' => $services->total(),
                'count' => $services->count(),
                'per_page' => $services->perPage(),
                'current_page' => $services->currentPage(),
                'total_pages' => $services->lastPage(),
            ],
        ]);
    }

    public function getServiceById($id)
    {
        // Fetch the merchant by ID
        $service = Service::find($id);

        // Check if the merchant exists
        if (!$service) {
            return $this->errorResponse('Service not found', 404);
        }

        // Transform the merchant data
        $transformedService = (new ServiceTransformer())->transform($service);

        // Return the response
        return $this->successResponse(['data' => $transformedService]);
    }






    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string',
            'value' => 'required|string',
            'type' => 'required|string',
            'value_type' => 'required|string',
        ]);
        $validated['created_by_id'] = \Auth::user()->id;
        $validated['updated_by_id'] = \Auth::user()->id;

        $serviceOption = ServiceOption::create($validated);
        return response()->json(['message' => 'Option created successfully', 'option' => $serviceOption], 201);
    }

    // Update an existing service option
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'value' => 'required|string',
            'type' => 'required|string',
            'value_type' => 'required|string',
        ]);

        $serviceOption = ServiceOption::find($id);

        if (!$serviceOption) {
            return response()->json(['message' => 'Option not found.'], 404);
        }

        $serviceOption->update($validated);

        return response()->json(['message' => 'Option updated successfully', 'option' => $serviceOption]);
    }

    // Delete a service option
    public function destroy($id)
    {
        $serviceOption = ServiceOption::find($id);

        if (!$serviceOption) {
            return response()->json(['message' => 'Option not found.'], 404);
        }

        $serviceOption->delete();

        return response()->json(['message' => 'Option deleted successfully']);
    }

    // Get options for a specific service
    public function index($serviceId)
    {
        $serviceOptions = ServiceOption::where('service_id', $serviceId)->get();

        return response()->json(['options' => $serviceOptions]);
    }

    // Show a specific service option
    public function show($id)
    {
        $serviceOption = ServiceOption::find($id);

        if (!$serviceOption) {
            return response()->json(['message' => 'Option not found.'], 404);
        }

        return response()->json(['option' => $serviceOption]);
    }




}
