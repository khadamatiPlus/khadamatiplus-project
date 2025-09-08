<?php
namespace App\Domains\Highlight\Http\Controllers\Backend;
use App\Domains\Highlight\Http\Requests\Backend\HighlightRequest;
use App\Domains\Highlight\Models\Highlight;
use App\Domains\Highlight\Services\HighlightService;
use App\Domains\FirebaseIntegration\FirebaseIntegration;
use App\Domains\Merchant\Services\MerchantService;
use App\Domains\Notification\Http\Requests\Backend\NotificationRequest;
use App\Domains\Lookups\Services\CategoryService;
use App\Domains\Service\Services\ServiceService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;

class HighlightController extends Controller
{
    private HighlightService $highlightService;

    public function __construct(HighlightService $highlightService, CategoryService $categoryService, MerchantService $merchantService, ServiceService $serviceService)
    {
        $this->highlightService = $highlightService;
        $this->categoryService = $categoryService;
        $this->merchantService = $merchantService;
        $this->serviceService = $serviceService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.highlight.index');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = $this->categoryService->get();
        $merchants = $this->merchantService->get();
        $services = $this->serviceService->get();
        return view('backend.highlight.create', compact('services', 'categories', 'merchants'));
    }

    /**
     * @param HighlightRequest $request
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(HighlightRequest $request)
    {
        $this->highlightService->store($request->validated());
        return redirect()->route('admin.highlight.index')->withFlashSuccess(__('The Highlight was successfully added'));
    }

    /**
     * @param Highlight $highlight
     * @return mixed
     */
    public function edit(Highlight $highlight)
    {
        $categories = $this->categoryService->get();
        $merchants = $this->merchantService->get();
        $services = $this->serviceService->get();
        return view('backend.highlight.edit', compact('services', 'categories', 'merchants'))
            ->withHighlight($highlight);
    }

    /**
     * @param HighlightRequest $request
     * @param $highlight
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update(HighlightRequest $request, $highlight)
    {
        $this->highlightService->update($highlight, $request->validated());
        return redirect()->back()->withFlashSuccess(__('The Highlight was successfully updated'));
    }

    /**
     * @param $highlight
     * @return mixed
     */
    public function destroy($highlight)
    {
        $this->highlightService->destroy($highlight);
        return redirect()->back()->withFlashSuccess(__('The Highlight was successfully deleted.'));
    }
}
