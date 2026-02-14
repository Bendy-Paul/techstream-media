<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResumeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Add authorization logic if needed, e.g., ensure user owns the resume
        // Relying on controller policy/check for now
        return true; 
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'visibility' => 'required|in:private,public',
            'is_default' => 'boolean',
            
            // Experience
            'experience' => 'nullable|array',
            'experience.*.id' => 'nullable|integer', // For updating existing
            'experience.*.company_name' => 'required|string|max:255',
            'experience.*.job_title' => 'required|string|max:255',
            'experience.*.start_date' => 'required|date',
            'experience.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',
            'experience.*.is_current' => 'boolean',
            'experience.*.description' => 'nullable|string',

            // Education
            'education' => 'nullable|array',
            'education.*.id' => 'nullable|integer', // For updating existing
            'education.*.institution' => 'required|string|max:255',
            'education.*.degree' => 'nullable|string|max:255',
            'education.*.field_of_study' => 'nullable|string|max:255',
            'education.*.start_date' => 'nullable|date',
            'education.*.end_date' => 'nullable|date|after_or_equal:education.*.start_date',
            'education.*.grade' => 'nullable|string|max:255',

            // Skills
            'skills' => 'nullable|array',
            'skills.*' => 'integer|exists:stacks,id', 
        ];
    }
}
