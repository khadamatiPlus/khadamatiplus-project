<?php
namespace App\Domains\Subscriber\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
/**
 * Class SubscriberRequest.
 */
class SubscriberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        switch ($request->method()) {
            case self::METHOD_POST:
                return [
                    'title' => ['required'],
                    'image' => ['nullable'],
                    'post' => ['required'],
                    'room_id' => ['nullable'],
                    'status' => ['nullable'],
                ];
            case self::METHOD_PATCH:
                return [
                    'id' => ['required', 'exists:user_posts,id'],
                    'title' => ['required'],
                    'image' => ['nullable'],
                    'post' => ['required'],
                    'room_id' => ['nullable'],
                    'status' => ['nullable'],
                ];
            case self::METHOD_DELETE:
            default:
                return [
                    'id' => ['required', 'exists:rooms,id']
                ];
        }
    }
    /**
     * @return array
     */
    public function messages(): array
    {
        return [
        ];
    }
}
