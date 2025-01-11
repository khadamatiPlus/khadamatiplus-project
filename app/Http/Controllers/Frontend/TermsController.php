<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Lookups\Models\Page;

/**
 * Class TermsController.
 */
class TermsController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('frontend.pages.terms');
    }

    public function privacy()
    {
        $privacy=Page::query()->where('slug','privacy-policy')->first();
        return view('frontend.pages.privacy',compact('privacy'));
    }
}
