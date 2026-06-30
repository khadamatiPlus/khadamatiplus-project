<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;  // Add the Log facade

class FirebaseNotificationService
{
    protected $firebase;

    public function __construct()
    {
        $this->firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials'))
            ->createMessaging();
    }

    public function sendPushNotification($deviceToken, $title, $body)
    {
        if (empty($deviceToken)) {
            return false;
        }

        try {
            // Log the notification attempt
            Log::info('Sending push notification', [
                'deviceToken' => $deviceToken,
                'title' => $title,
                'body' => $body,
            ]);

            // Create the message
            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification([
                    'title' => $title,
                    'body' => $body,
                ]);

            // Send the message
            $this->firebase->send($message);

            // Log successful notification sending
            Log::info('Push notification sent successfully', [
                'deviceToken' => $deviceToken,
                'title' => $title,
                'body' => $body,
            ]);

            return true;
        } catch (FirebaseException $e) {
            // Log the exception if there's an error
            Log::error('Error sending push notification', [
                'deviceToken' => $deviceToken,
                'title' => $title,
                'body' => $body,
                'error' => $e->getMessage(),
            ]);

            // Handle exception
            return false;
        }
    }

    /**
     * @param  array<int, string|null>  $deviceTokens
     */
    public function sendPushNotificationToMany(array $deviceTokens, string $title, string $body): int
    {
        $sentCount = 0;

        foreach (array_unique(array_filter($deviceTokens)) as $deviceToken) {
            if ($this->sendPushNotification($deviceToken, $title, $body)) {
                $sentCount++;
            }
        }

        return $sentCount;
    }
}
