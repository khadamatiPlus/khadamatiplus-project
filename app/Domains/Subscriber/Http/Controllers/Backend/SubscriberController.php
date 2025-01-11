<?php

namespace App\Domains\Subscriber\Http\Controllers\Backend;
use App\Domains\UserPost\Http\Requests\Backend\UserPostRequest;
use App\Domains\Room\Services\RoomService;
use App\Domains\UserPost\Models\UserPost;
use App\Domains\UserPost\Services\UserPostService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{



    public function __construct()
    {

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.subscriber.index');
    }

}

