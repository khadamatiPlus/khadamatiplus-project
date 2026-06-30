<?php

namespace App\Domains\Delivery\Http\Requests\API;

use App\Domains\AppService\Models\AppService;
use App\Domains\Delivery\Services\AppServiceVariantResolver;
use App\Enums\Core\ErrorTypes;
use App\Http\Requests\JsonRequest;
use InvalidArgumentException;

class RequestOrderRequest extends JsonRequest
{
    protected int $errorType = ErrorTypes::ORDER;

    public function authorize(): bool
    {
        return (bool) $this->user()?->customer_id;
    }

    public function rules(): array
    {
        return [
            'app_service_id' => ['required', 'exists:app_services,id'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.name' => ['required', 'string'],
            'variants.*.selected_options' => ['required', 'array', 'min:1'],
            'variants.*.selected_options.*' => ['required', 'string'],
            'day' => ['nullable', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'time' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $appService = AppService::query()
                ->where('id', $this->input('app_service_id'))
                ->where('status', 'active')
                ->first();

            if (!$appService) {
                $validator->errors()->add('app_service_id', __('The selected app service is not available.'));

                return;
            }

            try {
                app(AppServiceVariantResolver::class)->resolve($appService, $this->input('variants', []));
            } catch (InvalidArgumentException $exception) {
                $validator->errors()->add('variants', $exception->getMessage());
            }
        });
    }
}
