<?php

namespace App\Domains\FirebaseIntegration;

use App\Domains\FirebaseIntegration\Interfaces\FirebaseWorkInterface;
use App\Domains\FirebaseIntegration\Responses\VerifyTokenResponse;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Message;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseIntegration implements FirebaseWorkInterface
{

    /**
     * @var \Kreait\Firebase\Auth $firebase_auth
     */
    protected $firebase_auth;

    /**
     * @var \Kreait\Firebase\Contract\Messaging $firebase_messaging
     */
    protected $firebase_messaging;

    /**
     * FirebaseWork constructor.
     */
    public function __construct(string $app_name = '')
    {
        if(app()->environment(['local','testing','production'])){
            if(empty($app_name)){
                $this->firebase_auth = Firebase::auth();
                $this->firebase_messaging = Firebase::messaging();
            }
            else{
                $this->firebase_auth = Firebase::project($app_name)->auth();
                $this->firebase_messaging = Firebase::project($app_name)->messaging();
            }
        }
    }

    /**
     * @param string $firebaseToken
     * @param string $phoneNumber
     * @return VerifyTokenResponse
     * @throws \Kreait\Firebase\Exception\AuthException
     * @throws \Kreait\Firebase\Exception\FirebaseException
     */
    public function verifyToken(string $firebaseToken, string $phoneNumber):VerifyTokenResponse
    {
        $verifiedIdToken = null;
        if(!str_contains($phoneNumber,'+')){
            $phoneNumber = '+'.$phoneNumber;
        }
        try { // Try to verify the Firebase credential token with Google

            $verifiedIdToken = $this->firebase_auth->verifyIdToken($firebaseToken);

        } catch (\InvalidArgumentException $e) { // If the token has the wrong format

            return new VerifyTokenResponse(false,'Unauthorized - Can\'t parse the token: ' . $e->getMessage());

        } catch (\Exception $e) { // If the token is invalid (expired ...)

            return new VerifyTokenResponse(false,'Unauthorized - Token is invalide: ' . $e->getMessage());
        }

        $uid = $verifiedIdToken->claims()->get('sub');
        $firebaseUser = $this->firebase_auth->getUser($uid);

        if($firebaseUser->phoneNumber != $phoneNumber){
            return new VerifyTokenResponse(false, __('Your session expired, or the provided account token is not valid for your account'));
        }
        else{
            return new VerifyTokenResponse(true, __('Firebase token successfully verified'));
        }
    }

    /**
     * @param string $uid
     * @param string $phoneNumber
     * @return VerifyTokenResponse
     * @throws \Kreait\Firebase\Exception\AuthException
     * @throws \Kreait\Firebase\Exception\FirebaseException
     */
    public function verifyUID(string $uid, string $phoneNumber): VerifyTokenResponse
    {
        $firebaseUser = null;
        if(!str_contains($phoneNumber,'+')){
            $phoneNumber = '+'.$phoneNumber;
        }
        try { // Try to verify the Firebase credential token with Google

            $firebaseUser = $this->firebase_auth->getUser($uid);

        } catch (\InvalidArgumentException $e) { // If the token has the wrong format

            return new VerifyTokenResponse(false,'Unauthorized - Can\'t parse the uid: ' . $e->getMessage());

        } catch (\Exception $e) {
            report($e);
            return new VerifyTokenResponse(false,'Unauthorized - uid is invalid: ' . $e->getMessage());
        }

        if($firebaseUser->phoneNumber != $phoneNumber){
            return new VerifyTokenResponse(false, __('Your session expired, or the provided account uid is not valid for your account'));
        }
        else{
            return new VerifyTokenResponse(true, __('Firebase token successfully verified'));
        }
    }



    public function pushNotification(CloudMessage $message): array
    {
        // Existing implementation
        try {
            \Log::info('Attempting to send Firebase notification', [
                'message_data' => $message->jsonSerialize()
            ]);

            $response = $this->firebase_messaging->send($message);

            if (isset($response['name']) && !empty($response['name'])) {
                \Log::info('Firebase notification sent successfully', [
                    'message_id' => $response['name']
                ]);
                return [
                    'success' => true,
                    'message_id' => $response['name']
                ];
            } else {
                \Log::error('Firebase notification failed: No valid message ID in response', [
                    'response' => $response
                ]);
                throw new \Exception('No valid message ID returned from Firebase');
            }
        } catch (InvalidMessage $invalidMessageException) {
            \Log::error('Invalid Firebase message', [
                'error_message' => $invalidMessageException->getMessage(),
                'error_code' => $invalidMessageException->getCode(),
                'file' => $invalidMessageException->getFile(),
                'line' => $invalidMessageException->getLine(),
                'trace' => $invalidMessageException->getTraceAsString()
            ]);
            report($invalidMessageException);
            throw $invalidMessageException;
        } catch (FirebaseException $firebaseException) {
            \Log::error('Firebase error while sending notification', [
                'error_message' => $firebaseException->getMessage(),
                'error_code' => $firebaseException->getCode(),
                'file' => $firebaseException->getFile(),
                'line' => $firebaseException->getLine(),
                'trace' => $firebaseException->getTraceAsString()
            ]);
            report($firebaseException);
            throw $firebaseException;
        } catch (\Exception $exception) {
            \Log::error('Unexpected error while sending Firebase notification', [
                'error_message' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ]);
            report($exception);
            throw $exception;
        }
    }
}
