<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $url;
    protected $user;
    protected $password;
    protected $sender;

    public function __construct()
    {
        // Retrieve the values from environment variables
        $this->url = env('SMS_GATEWAY_URL');
        $this->user = env('SMS_USER');
        $this->password = env('SMS_PASSWORD');
        $this->sender = env('SMS_SENDER');
    }

    public function sendSms($mobileNumber, $message)
    {
        $loginName = $this->user;  // Your login name
        $loginPassword = $this->password;  // Your password
        $sender = $this->sender;  // Sender name (should be registered)

        // Ensure the message is URL encoded
        $encodedMessage = urlencode($message);

        // Prepare the mobile number in the required format (ensure country code is included)
        $fullNumber = $mobileNumber;  // Assuming $mobileNumber already includes the country code

        // Construct the URL with the necessary parameters
        $url = $this->url . "?login_name=$loginName&login_password=$loginPassword&msg=$encodedMessage&mobile_number=$fullNumber&from=$sender&charset=UTF-8";
        Log::info('Full API URL:', [$url]);

        try {
            // Make the HTTP request (POST is more appropriate if API expects it)
            $response = Http::get($url);  // Use GET method as per API documentation, unless POST is specified

            // Log the raw response (for debugging)
            Log::info('SMS API Raw Response:', [$response->body()]);

            // Check if the response is successful
            if ($response->successful()) {
                // Log and return the response if successful
                Log::info('SMS sent successfully');
                Log::info('Response JSON:', [$response->json()]);
                return $response->json();  // Return the JSON response from the SMS API
            } else {
                // Log the failure response
                Log::error('SMS request failed:', ['status' => $response->status(), 'response' => $response->body()]);
                return null;  // Return null if failed
            }
        } catch (\Exception $exception) {
            // Log the exception if an error occurs
            Log::error('SMS API request exception:', ['exception' => $exception->getMessage()]);
            return null;  // Return null in case of an error
        }
    }
}
