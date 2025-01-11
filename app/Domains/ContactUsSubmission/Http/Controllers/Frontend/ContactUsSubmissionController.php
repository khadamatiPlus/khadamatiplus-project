<?php

namespace App\Domains\ContactUsSubmission\Http\Controllers\Frontend;
use App\Domains\ContactUsSubmission\Http\Requests\Frontend\ContactUsSubmissionRequest;
use App\Domains\ContactUsSubmission\Services\ContactUsSubmissionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactUsSubmissionController extends Controller
{
    private ContactUsSubmissionService $contactUsSubmissionService;

    /**
     * @param ContactUsSubmissionService $contactUsSubmissionService
     */
    public function __construct(ContactUsSubmissionService $contactUsSubmissionService )
    {
        $this->contactUsSubmissionService = $contactUsSubmissionService;
    }

    /**
     * @param ContactUsSubmissionController $request
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(ContactUsSubmissionRequest $request)
    {
        $this->contactUsSubmissionService->store($request->validated());
        return redirect()->back()->with(['success' => __('Thank you for contacting us Your message has been sent successfully, we will contact you as soon as possible')]);
    }
}
