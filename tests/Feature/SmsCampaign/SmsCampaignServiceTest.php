<?php

namespace Tests\Feature\SmsCampaign;

use App\Domains\Auth\Models\User;
use App\Domains\Customer\Models\Customer;
use App\Domains\Merchant\Models\Merchant;
use App\Domains\SmsCampaign\Services\SmsCampaignService;
use App\Services\SmsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmsCampaignServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_sends_sms_campaign_recipients(): void
    {
        $merchantUser = new User([
            'name' => 'Merchant User',
            'email' => 'merchant@example.com',
            'mobile_number' => '966500000001',
            'password' => bcrypt('secret123'),
            'active' => true,
            'type' => User::TYPE_USER,
        ]);
        $merchantUser->save();

        $merchant = new Merchant([
            'name' => 'Merchant One',
            'profile_id' => $merchantUser->id,
            'created_by_id' => $merchantUser->id,
            'updated_by_id' => $merchantUser->id,
        ]);
        $merchant->save();
        $merchantUser->merchant_id = $merchant->id;
        $merchantUser->save();

        $customerUser = new User([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'mobile_number' => '966500000002',
            'password' => bcrypt('secret123'),
            'active' => true,
            'type' => User::TYPE_USER,
        ]);
        $customerUser->save();

        $customer = new Customer([
            'name' => 'Customer One',
            'profile_id' => $customerUser->id,
            'created_by_id' => $customerUser->id,
            'updated_by_id' => $customerUser->id,
        ]);
        $customer->save();
        $customerUser->customer_id = $customer->id;
        $customerUser->save();

        $this->app->instance(SmsService::class, new class extends SmsService {
            public function sendSms($mobileNumber, $message)
            {
                return ['success' => true, 'mobile' => $mobileNumber];
            }
        });

        $service = app(SmsCampaignService::class);

        $campaign = $service->storeCampaign([
            'title' => 'Offer Alert',
            'message' => 'Hello from campaign',
            'recipient_type' => 'all',
        ]);

        $this->assertEquals(2, $campaign->recipients()->count());
        $this->assertEquals(2, $campaign->total_recipients);

        $service->sendCampaign($campaign);

        $this->assertTrue($campaign->recipients()->where('status', 'sent')->exists());
        $this->assertEquals('completed', $campaign->status);
    }
}
