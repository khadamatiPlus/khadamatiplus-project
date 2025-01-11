<?php

namespace App\Domains\ContactUsSubmission\Http\Controllers\Backend;
use App\Domains\ContactUsSubmission\Services\ContactUsSubmissionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactUsSubmissionController extends Controller
{
    private ContactUsSubmissionService $contactUsSubmissionService;

    /**
     * @param ContactUsSubmissionService $contactUsSubmissionService
     */
    public function __construct(ContactUsSubmissionService $contactUsSubmissionService)
    {
        $this->contactUsSubmissionService = $contactUsSubmissionService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.contact-us-submission.index');

    }
    public function destroy($game)
    {
        $this->contactUsSubmissionService->destroy($game);
        return redirect()->back()->withFlashSuccess(__('The Message  was successfully deleted.'));
    }


}
