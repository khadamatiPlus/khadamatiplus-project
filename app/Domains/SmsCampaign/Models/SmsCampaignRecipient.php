<?php

namespace App\Domains\SmsCampaign\Models;

use App\Domains\Auth\Models\User;
use App\Models\BaseModel;

class SmsCampaignRecipient extends BaseModel
{
    protected $table = 'sms_campaign_recipients';

    protected $fillable = [
        'campaign_id',
        'user_id',
        'mobile_number',
        'status',
        'response',
        'sent_at',
        'created_by_id',
        'updated_by_id',
    ];

    public function campaign()
    {
        return $this->belongsTo(SmsCampaign::class, 'campaign_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
