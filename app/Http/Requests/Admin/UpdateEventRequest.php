<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming admin middleware handles auth
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $eventId = $this->route('id');

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('events', 'title')->ignore($eventId),
            ],
            'description' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'location_name' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'is_virtual' => 'nullable|boolean',
            'banner_image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Array validation
            'categories' => 'array|exists:categories,id',
            'organizers' => 'array|exists:companies,id',
            'partners' => 'array|exists:companies,id',
            'tags' => 'array|exists:tags,id',

            // Speakers validation
            'speakers' => 'nullable|array',
            'speakers.*.name' => 'required_with:speakers|string|max:255',
            'speakers.*.position' => 'nullable|string|max:255',
            'speaker_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Gallery validation
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
