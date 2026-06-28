<?php

namespace App\Domains\Wallet\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    public function index()
    {
        return view('backend.wallet.index');
    }
}
