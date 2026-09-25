<?php

namespace App\Domains\SmsCampaign\Http\Controllers\Backend;

use App\Domains\SmsCampaign\Http\Requests\Backend\SmsCampaignRequest;
use App\Domains\SmsCampaign\Models\SmsCampaign;
use App\Domains\SmsCampaign\Services\SmsCampaignService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SmsCampaignController extends Controller
{
    public function __construct(protected SmsCampaignService $service)
    {
    }

    public function index(): View
    {
        $campaigns = SmsCampaign::query()->latest()->paginate(20);

        return view('backend.sms-campaign.index', compact('campaigns'));
    }

    public function create(): View
    {
        return view('backend.sms-campaign.create');
    }

    public function store(SmsCampaignRequest $request): RedirectResponse
    {
        $campaign = $this->service->storeCampaign($request->validated());

        return redirect()->route('admin.sms-campaign.index')->with('success', __('Campaign created successfully.'));
    }

    public function send(SmsCampaign $campaign): RedirectResponse
    {
        $this->service->sendCampaign($campaign);

        return redirect()->back()->with('success', __('Campaign sent successfully.'));
    }
}
