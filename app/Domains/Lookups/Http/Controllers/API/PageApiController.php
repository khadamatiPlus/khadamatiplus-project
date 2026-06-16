<?php

namespace App\Domains\Lookups\Http\Controllers\API;

use App\Domains\Lookups\Services\PageService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;

/**
 * Class PageApiController
 */
class PageApiController extends APIBaseController
{

    protected $pageService;

    /**
     * @param PageService $pageService
     */
    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }


    public function getPageBySlug(Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            return $this->successResponse($this->pageService->bySlug($request->slug)->get()->map(function ($page){
                return [
                    'title' => $page->title,
                    'description' => $page->description
                ];
            })->first());
        }
        catch (\Exception $exception){
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }
}
