<?php
namespace App\Domains\Notification\Http\Controllers\Backend;
use App\Domains\Delivery\Http\Transformers\OrderTransformer;
use App\Domains\FirebaseIntegration\FirebaseIntegration;
use App\Domains\Lookups\Models\Category;
use App\Domains\Notification\Http\Requests\Backend\NotificationRequest;
use App\Domains\Lookups\Services\CategoryService;
use App\Domains\Merchant\Services\MerchantBranchService;
use App\Domains\Notification\Models\Notification;
use App\Domains\Notification\Services\NotificationService;
use App\Domains\Item\Services\ItemService;
use App\Domains\Service\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
class NotificationController extends Controller
{
    private NotificationService $notificationService;

    private FirebaseIntegration $firebaseIntegration;
    /**
     * @param NotificationService $notificationService

     * @param FirebaseIntegration $firebaseIntegration
     */
    public function __construct(NotificationService $notificationService,FirebaseIntegration $firebaseIntegration)
    {
        $this->notificationService = $notificationService;
        $this->firebaseIntegration = resolve(FirebaseIntegration::class);
    }
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.notification.index');
    }
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        // Add these lines to fetch categories and services
        $categories = Category::all(); // Adjust based on your Category model
        $services = Service::all(); // Adjust based on your Service model

        return view('backend.notification.create', compact('categories', 'services'));
    }
    /**
     * @param NotificationRequest $request
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(NotificationRequest $request)
    {
        $this->notificationService->store($request->validated());
        return redirect()->route('admin.notification.index')->withFlashSuccess(__('The Notification was successfully added'));
    }
    /**
     * @param Notification $notification
     * @return mixed
     */
    public function edit(Notification $notification)
    {
        $categories = Category::all(); // Adjust based on your model
        $services = Service::all(); // Adjust based on your model
        return view('backend.notification.edit', compact( 'categories', 'services'))
            ->withNotification($notification);
    }
    /**
     * @param NotificationRequest $request
     * @param $notification
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update(NotificationRequest $request, $notification)
    {
        $this->notificationService->update($notification, $request->validated());
        return redirect()->back()->withFlashSuccess(__('The Notification was successfully updated'));
    }
    /**
     * @param $notification
     * @return mixed
     */
    public function destroy($notification)
    {
        $this->notificationService->destroy($notification);
        return redirect()->back()->withFlashSuccess(__('The Notification was successfully deleted.'));
    }
    public function sendNotification(Request $request)
    {
        try {
            // Fetch the notification with relationships
            $notification = Notification::with(['category', 'service'])
                ->where('id', $request->input('notificationId'))
                ->firstOrFail();

            // Check if notification_type is null and set a default
            if ($notification->notification_type === null) {
                // Set a default notification type for existing notifications
                $notification->notification_type = 'informative';
                $notification->save(); // Save the change to the database
            }

            // Prepare the base notification data
            $notificationData = [
                'notification_id' => $notification->id,
                'notification_icon' => storageBaseLink(\App\Enums\Core\StoragePaths::NOTIFICATION_ICON . $notification->notification_icon),
                'title' => ['en' => $notification->title, 'ar' => $notification->title_ar],
                'description' => ['en' => $notification->description, 'ar' => $notification->description_ar],
                'type' => $notification->type, // recipient type (merchant/user)
                'notification_type' => $notification->notification_type, // category/service/informative
            ];

            // Add category or service data based on notification type
            if ($notification->notification_type === 'category' && $notification->category) {
                $notificationData['category'] = [
                    'id' => $notification->category->id,
                    'name' => [
                        'en' => $notification->category->name,
                        'ar' => $notification->category->name_ar ?? $notification->category->name
                    ],
                    'image' => $notification->category->image ?
                        storageBaseLink(\App\Enums\Core\StoragePaths::CATEGORY_IMAGE . $notification->category->image) : null
                ];
            } elseif ($notification->notification_type === 'service' && $notification->service) {
                $notificationData['service'] = [
                    'id' => $notification->service->id,
                    'name' => [
                        'en' => $notification->service->name,
                        'ar' => $notification->service->name_ar ?? $notification->service->name
                    ],
                    'image' => $notification->service->image ?
                        storageBaseLink(\App\Enums\Core\StoragePaths::SERVICE_FILE . $notification->service->image) : null
                ];
            }

            // Encode the data for Firebase
            $data = json_encode($notificationData, JSON_UNESCAPED_SLASHES);

            // Log the prepared notification data
            \Log::info('Preparing to send notification', [
                'notification_id' => $notification->id,
                'notification_type' => $notification->notification_type,
                'data' => $notificationData
            ]);

            // Determine the target topic based on recipient type
            $topic = $notification->type === 'merchant' ? 'merchants' : 'users';

            // Send the notification via Firebase
            $this->firebaseIntegration->pushNotification(
                CloudMessage::withTarget('topic', $topic)
                    ->withData(['notification_data' => $data])
                    ->withNotification(
                        \Kreait\Firebase\Messaging\Notification::create()
                            ->withTitle($notification->title)
                            ->withBody($notification->description)
                    )
            );

            // Update notification status and sent time
            $notification->is_sent = 1;
//            $notification->sent_at = now();
            $notification->save(); // Use save() instead of update() for single model

            // Log success
            \Log::info('Notification sent successfully', [
                'notification_id' => $notification->id,
                'topic' => $topic,
                'notification_type' => $notification->notification_type
            ]);

            return response()->json(['success' => true, 'message' => 'Notification sent successfully']);
        } catch (\Exception $exception) {
            // Log the error
            \Log::error('Failed to send notification', [
                'notification_id' => $request->input('notificationId'),
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

            report($exception);
            return response()->json([
                'success' => false,
                'error' => 'Failed to send notification: ' . $exception->getMessage()
            ], 500);
        }
    }
}
