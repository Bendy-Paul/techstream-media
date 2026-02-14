<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if ($user && $user->role === 'admin') {
            return true;
        }
        return false;
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
            'description' => 'required|string',
            'summary' => 'required|string|max:300',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'job_type' => 'required|string',
            'experience_level' => 'required|string',
            'education_level' => 'nullable|string',
            'salary_range' => 'nullable|string|max:255',
            'application_type' => 'required|in:smart_apply,external',
            'apply_link' => 'nullable|required_if:application_type,external|url',
            'location_id' => 'nullable|exists:cities,id',
            'tool_ids' => 'nullable|array',
            'tool_ids.*' => 'exists:stacks,id',
            'expires_at' => 'nullable|date|after:today',
        ];
    }
}
