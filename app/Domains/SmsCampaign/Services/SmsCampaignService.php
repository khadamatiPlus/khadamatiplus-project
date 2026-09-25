<?php

namespace App\Domains\SmsCampaign\Services;

use App\Domains\Auth\Models\User;
use App\Domains\SmsCampaign\Models\SmsCampaign;
use App\Services\SmsService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class SmsCampaignService
{
    public function __construct(protected SmsService $smsService)
    {
    }

    public function storeCampaign(array $data): SmsCampaign
    {
        $campaign = SmsCampaign::create([
            'title' => Arr::get($data, 'title'),
            'message' => Arr::get($data, 'message'),
            'recipient_type' => Arr::get($data, 'recipient_type', 'all'),
            'status' => 'draft',
            'created_by_id' => Auth::check() ? Auth::id() : null,
        ]);

        $users = $this->resolveRecipients(Arr::get($data, 'recipient_type', 'all'));

        $campaign->total_recipients = count($users);
        $campaign->save();

        foreach ($users as $user) {
            $campaign->recipients()->create([
                'user_id' => $user->id,
                'mobile_number' => $user->mobile_number,
                'status' => 'pending',
            ]);
        }

        return $campaign;
    }

    public function sendCampaign(SmsCampaign $campaign): SmsCampaign
    {
        $campaign->status = 'sending';
        $campaign->save();

        $sentCount = 0;
        $failedCount = 0;

        foreach ($campaign->recipients()->get() as $recipient) {
            $response = $this->smsService->sendSms($recipient->mobile_number, $campaign->message);

            if ($response) {
                $recipient->status = 'sent';
                $recipient->response = is_array($response) ? json_encode($response) : $response;
                $recipient->sent_at = now();
                $sentCount++;
            } else {
                $recipient->status = 'failed';
                $recipient->response = 'SMS gateway returned no response';
                $failedCount++;
            }

            $recipient->save();
        }

        $campaign->sent_count = $sentCount;
        $campaign->failed_count = $failedCount;
        $campaign->status = $sentCount > 0 ? 'completed' : 'failed';
        $campaign->save();

        return $campaign;
    }

    protected function resolveRecipients(string $recipientType): array
    {
        $query = User::query()->whereNotNull('mobile_number')->where('active', true);

        if ($recipientType === 'merchants') {
            $query->whereNotNull('merchant_id');
        } elseif ($recipientType === 'customers') {
            $query->whereNotNull('customer_id');
        }

        return $query->get()->all();
    }
}
