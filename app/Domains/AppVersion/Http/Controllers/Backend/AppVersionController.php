<?php
namespace App\Domains\AppVersion\Http\Controllers\Backend;
use App\Domains\AppVersion\Http\Requests\Backend\AppVersionRequest;
use App\Domains\AppVersion\Models\AppVersion;
use App\Domains\AppVersion\Services\AppVersionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    private AppVersionService $appVersionService;

    /**
     * @param AppVersionService $appVersionService
     */
    public function __construct(AppVersionService $appVersionService)
    {
        $this->appVersionService = $appVersionService;
    }

    /**
     * @param AppVersion $appVersion
     * @return mixed
     */
    public function edit(AppVersion $appVersion)
    {
        return view('backend.app-version.edit')
            ->withAppVersion($appVersion);
    }

    /**
     * @param AppVersionRequest $request
     * @param $appVersion
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update(AppVersionRequest $request, $appVersion)
    {
        $this->appVersionService->update($appVersion, $request->validated());

        return redirect()->back()->withFlashSuccess(__('The App Versions was successfully updated'));
    }

}
