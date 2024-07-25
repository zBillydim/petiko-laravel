<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'exists:tasks,id',
            'task_title' => 'string|max:255',
            'task_description' => 'string',
            'due_date' => 'date|after_or_equal:today',
            'status' => 'string|in:pending,in-progress,completed',
            'priority' => 'string|in:low,medium,high',
        ];
    }
    public function messages(): array
    {
        return [
            'id.exists' => 'id does not exist',
            'due_date.after_or_equal' => 'due date must be today or later',
            'status.required' => 'status is required',
            'status.in' => 'status must be one of: pending, in-progress, completed',
            'priority.required' => 'priority is required',
            'priority.in' => 'priority must be one of: low, medium, high',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation error',
            'data' => $validator->errors()
        ], 403));
    }
}
