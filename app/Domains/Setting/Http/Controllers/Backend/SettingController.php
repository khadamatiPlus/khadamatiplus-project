<?php

namespace App\Domains\Setting\Http\Controllers\Backend;

use App\Domains\Setting\Services\SettingService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(protected SettingService $settingService)
    {
    }

    public function edit()
    {
        $settings = $this->settingService->all();

        return view('backend.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            if ($key === 'app_profit_percentage') {
                $this->settingService->set($key, $value, 'decimal', 'finance');
                continue;
            }

            $this->settingService->set($key, $value);
        }

        return redirect()->back()->withFlashSuccess(__('Settings were successfully updated'));
    }
}
