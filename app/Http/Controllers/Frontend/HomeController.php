<?php

namespace App\Http\Controllers\Frontend;

use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Log;

/**
 * Class HomeController.
 */
class HomeController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $currentDay = now()->format('l'); // Example: Saturday
        $currentTime = now()->format('H:i:s');

            Log::info('oo111www');
            Log::info($currentDay);

        return view('frontend.index');
    }
    public function providerIndex()
    {
        return view('frontend.index-provider');
    }
}
