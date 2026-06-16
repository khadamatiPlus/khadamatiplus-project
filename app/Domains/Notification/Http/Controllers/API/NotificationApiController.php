<?php

namespace App\Domains\Notification\Http\Controllers\API;
use App\Domains\Notification\Http\Transformers\NotificationTransformer;
use App\Domains\Notification\Services\NotificationService;
use App\Http\Controllers\APIBaseController;
use Illuminate\Http\Request;

class NotificationApiController extends APIBaseController
{

    /**
     * @var NotificationService $notificationService
     */
    private $notificationService;

    /**
     * @param NotificationService $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }


    public function getNotifications(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->successResponse($this->notificationService
                ->applyQueryFilters()
                ->paginate(10,['*'],'page',$request->input('page') ?? 1)
                ->getCollection()
                ->transform(function ($notifications) {
                    return (new NotificationTransformer)->transform($notifications);
                }));
        } catch (\Exception $exception) {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }
    public function getCustomerNotifications(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->successResponse($this->notificationService
                ->applyQueryFilters()
                ->paginate(10,['*'],'page',$request->input('page') ?? 1)
                ->getCollection()
                ->transform(function ($notifications) {
                    return (new NotificationTransformer)->transform($notifications);
                }));
        } catch (\Exception $exception) {
            report($exception);
            return $this->internalServerErrorResponse($exception->getMessage());
        }
    }
}
