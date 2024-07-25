<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExportTaskRequest extends FormRequest
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
            "filename" => "required|string",
            "type" => "required|in:csv,xlsx",
        ];
    }
    public function messages(): array
    {
        return [
            "filename.required" => "filename is required",
            "type.required" => "type is required",
            "type.in" => "type must be one of: csv, xlsx",
        ];
    }   
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "success" => false,
            "message" => "Validation error",
            "data" => $validator->errors()
        ], 403));
    }
}
