<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateTaskRequest
 */
class UpdateTaskRequest extends FormRequest
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
     * @return array<string, array|string>
     */
    public function rules(): array
    {
        return [
            'title'       => 'string|max:50',
            'description' => 'string|max:255',
            'due_date'    => 'date_format:Y-m-d',
            'status'      => 'in:TODO,IN-PROGRESS,COMPLETED',
            'position'    => 'int'
        ];
    }
}
