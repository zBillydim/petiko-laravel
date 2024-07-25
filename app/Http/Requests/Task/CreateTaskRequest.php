<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateTaskRequest extends FormRequest
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
            'task_title' => 'required|string',
            'task_description' => 'required|string',
            'due_date' => 'required|date_format:d/m/Y|after_or_equal:today',
            'status' => 'required|string|in:pending,in-progress,completed',
            'priority' => 'required|string|in:low,medium,high',
        ];
    }
    public function messages(): array
    {
        return [
            'task_title.required' => 'task title is required',
            'task_title.string' => 'task title must be a string',
            'task_description.required' => 'task description is required',
            'due_date.required' => 'due date is required',
            'due_date.after_or_equal' => 'due date must be today or later',
            'status.required' => 'status is required',
            'status.in' => 'status must be one of: pending, in-progress, completed',
            'level.required' => 'level is required',
            'level.in' => 'level must be one of: low, medium, high',
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
