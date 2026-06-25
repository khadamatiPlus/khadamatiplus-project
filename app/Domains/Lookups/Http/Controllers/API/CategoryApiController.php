<?php

namespace App\Domains\Lookups\Http\Controllers\API;

use App\Domains\Lookups\Http\Resources\CategoryResource;
use App\Domains\Lookups\Http\Transformers\CategoryTransformer;
use App\Domains\Lookups\Http\Transformers\VehicleTypeTransformer;
use App\Domains\Lookups\Models\Category;
use App\Domains\Lookups\Services\CategoryService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;

class CategoryApiController extends APIBaseController
{


    private $categoryService;

    /**
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }


    /**
     * Get Categories
     *
     * Returns a list of top-level categories.
     *
     * @group Categories
     *
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Services",
     *       "name_ar": "خدمات"
     *     }
     *   ]
     * }
     */
    public function getCategories(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Filter to get only top-level categories where parent_id is null
            $categories = Category::sorted()
                 // Start a query builder instance
                ->whereNull('parent_id') // Add filter for parent_id = null
                ->get() // Execute the query
                ->transform(function ($category) {
                    return (new CategoryTransformer())->transform($category);
                });

            return $this->successResponse($categories);
        } catch (\Exception $exception) {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }

    /**
     * Get Sub-Categories by Category ID
     *
     * Returns all sub-categories for a given parent category ID.
     *
     * @group Categories
     *
     * @queryParam category_id integer required The ID of the parent category. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 2,
     *       "name": "Cleaning",
     *       "name_ar": "تنظيف",
     *       "parent_id": 1
     *     }
     *   ]
     * }
     *
     * @response 422 {
     *   "success": false,
     *   "message": "The category_id field is required."
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Category not found"
     * }
     */
    public function getSubCategories(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'category_id' => 'required|integer|exists:categories,id'
            ]);

            $category = Category::find($request->category_id);

            if (!$category) {
                return $this->inputValidationErrorResponse('Category not found');
            }

            $subCategories = $category->children()
                ->sorted()
                ->get()
                ->transform(function ($subCategory) {
                    return (new CategoryTransformer())->transform($subCategory);
                });

            return $this->successResponse($subCategories);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return $this->inputValidationErrorResponse($exception->errors());
        } catch (\Exception $exception) {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }





//    public function getCategories(Request $request): \Illuminate\Http\JsonResponse
//    {
//        try {
//            $categories = Category::query()->with('subCategories')->paginate(10);
//            $data = [
//                'success' => true,
//                'message' => 'Categories fetched successfully.',
//                'data' => CategoryResource::collection($categories),
//                'pagination' => [
//                    'total' => $categories->total(),
//                    'per_page' => $categories->perPage(),
//                    'current_page' => $categories->currentPage(),
//                    'last_page' => $categories->lastPage(),
//                    'from' => $categories->firstItem(),
//                    'to' => $categories->lastItem()
//                ]
//            ];
//            return response()->json($data, 200);
//        } catch (\Exception $exception) {
//            report($exception);
//            return $this->internalServerErrorResponse($exception->getMessage());
//        }
//    }
}
