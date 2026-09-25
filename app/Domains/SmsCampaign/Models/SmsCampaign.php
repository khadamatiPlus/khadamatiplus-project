<?php

namespace App\Domains\SmsCampaign\Models;

use App\Domains\Auth\Models\User;
use App\Models\BaseModel;

class SmsCampaign extends BaseModel
{
    protected $table = 'sms_campaigns';

    protected $fillable = [
        'title',
        'message',
        'recipient_type',
        'status',
        'total_recipients',
        'sent_count',
        'failed_count',
        'created_by_id',
        'updated_by_id',
    ];

    public function recipients()
    {
        return $this->hasMany(SmsCampaignRecipient::class, 'campaign_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
