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

    /**
     * @param CloudMessage $message
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Kreait\Firebase\Exception\MessagingException
     */
    public function pushNotification(CloudMessage $message): void
    {
        try {
            // Log the attempt to send the Firebase message
            \Log::info('Attempting to send Firebase notification', [
                'message' => $message->jsonSerialize() // Log the serialized message
            ]);

            $this->firebase_messaging->send($message);

            // Log success
            \Log::info('Firebase notification sent successfully');
        } catch (InvalidMessage $invalidMessageException) {
            // Log invalid message error
            \Log::error('Invalid Firebase message', [
                'error' => $invalidMessageException->getMessage(),
                'trace' => $invalidMessageException->getTraceAsString()
            ]);
            report($invalidMessageException);
        } catch (FirebaseException $firebaseException) {
            // Log Firebase-specific error
            \Log::error('Firebase error while sending notification', [
                'error' => $firebaseException->getMessage(),
                'trace' => $firebaseException->getTraceAsString()
            ]);
            report($firebaseException);
        } catch (\Exception $exception) {
            // Log general error
            \Log::error('Unexpected error while sending Firebase notification', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);
            report($exception);
        }
    }
}
