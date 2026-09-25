<?php

namespace App\Domains\SmsCampaign\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class SmsCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'recipient_type' => ['required', 'in:all,merchants,customers'],
        ];
    }
}
