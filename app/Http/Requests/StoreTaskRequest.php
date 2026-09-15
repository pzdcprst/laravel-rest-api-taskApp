<?php

namespace App\Http\Requests;

use App\Enums\Priority;
use App\Enums\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'required', 'min:3', 'max: 150'],
            'description' => ['string', 'max:5000'],
            'due_date' => ['date'],
            // 'user_id' => ['required', 'exists:user,id'],
            // 'project_id' => ['required', 'exists:project,id'],
            'status' => ['string', new Enum(Status::class)],
            'priority' => ['string', new Enum(Priority::class)],
        ];
    }
}
