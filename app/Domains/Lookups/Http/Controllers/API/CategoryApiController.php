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


    public function getCategories(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Filter to get only top-level categories where parent_id is null
            $categories = Category::query()
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
