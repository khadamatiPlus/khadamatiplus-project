<?php
namespace App\Domains\Subscriber\Http\Controllers\Frontend;
use App\Domains\Subscriber\Http\Requests\Frontend\SubscriberRequest;
use App\Domains\Subscriber\Services\SubscriberService;
use App\Domains\UserPost\Services\UserPostService;
use App\Domains\UserPost\Http\Requests\Frontend\UserPostRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriberController extends Controller
{
    private SubscriberService $subscriberService;

    /**
     * @param SubscriberService $subscriberService
     */
    public function __construct(SubscriberService $subscriberService)
    {
        $this->subscriberService = $subscriberService;

    }

    /**
     * @param SubscriberRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function store(SubscriberRequest $request)
    {
        $this->subscriberService->store($request->validated());
        return redirect()->back()->with(['successSubscriber' => __('Your email has been submitted successfully, we will share it as soon as possible')]);    }
}
