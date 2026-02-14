<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'job_type' => 'required|string',
            'location_id' => 'required|exists:cities,id', // maps to city_id in controller
            'experience_level' => 'required|string',
            'education_level' => 'required|string',
            'salary_range' => 'nullable|string',
            'application_type' => 'required|string',
            'apply_link' => 'nullable|url',
            'expires_at' => 'required|date',
            'description' => 'required|string',
            'summary' => 'required|string',
            'responsibilities' => 'required|string',
            'requirements' => 'required|string',
            'tool_ids' => 'array|exists:stacks,id',
        ];
    }
}
