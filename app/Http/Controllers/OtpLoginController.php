<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OtpLoginController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'country_code' => 'required',
            'mobile_number' => 'required',
        ]);

        $countryCode = $request->input('country_code');
        $mobileNumber = $request->input('mobile_number');
        $fullNumber = $countryCode . $mobileNumber;

        $user = User::where('mobile_number', $mobileNumber)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        $message = "Your OTP code is $otp. It expires in 5 minutes.";
        $this->smsService->sendSms($fullNumber, $message);

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required',
            'otp_code' => 'required|size:6',
        ]);

        $user = User::where('mobile_number', $request->input('mobile_number'))
            ->where('otp_code', $request->input('otp_code'))
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        // Clear OTP after successful login
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Return access token or login response
        $token = $user->createToken('mobile_login')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
