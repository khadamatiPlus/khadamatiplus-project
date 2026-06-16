<?php

namespace App\Domains\Auth\Http\Controllers\API;

use App\Domains\Auth\Http\Requests\API\MerchantBranchAccessActivationRequest;
use App\Domains\Auth\Http\Transformers\UserTransformer;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\UserService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementApiController extends APIBaseController
{

    /**
     * @var UserService $userService
     */
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }




    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = Auth::user();

        if ($user->fcm_token !== $request->fcm_token) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'FCM token updated successfully',
        ]);
    }

}
