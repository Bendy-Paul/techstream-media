<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            // Basic Info
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'organizers' => 'nullable|array',
            'organizers.*' => 'exists:companies,id',
            'partners' => 'nullable|array',
            'partners.*' => 'exists:companies,id',

            // Tags
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'new_tags' => 'nullable|string',

            // Logistics
            'start_datetime' => 'required|date|after:now',
            'end_datetime'  => 'required|date|after:start_datetime',
            'is_virtual'    => 'boolean',
            'location_name' => 'required_if:is_virtual,0,null|nullable|string|max:255',
            'city_id'       => 'required_if:is_virtual,0,null|nullable|exists:cities,id',

            // Speakers (Dynamic Array)
            'speakers'             => 'nullable|array',
            'speakers.*.name'     => 'required_with:speakers.*.position|nullable|string|max:255',
            'speakers.*.position' => 'required_with:speakers.*.name|nullable|string|max:255',
            'speaker_images' => 'nullable|array',
            'speaker_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Gallery & Banner
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg|max:5120', // 5MB limit
            'banner_image_upload' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',

            // Socials & Community (Nested Objects)
            'social.linkedin' => 'nullable|url',
            'social.twitter' => 'nullable|url',
            'social.facebook' => 'nullable|url',
            'social.instagram' => 'nullable|url',
            'community.whatsapp' => 'nullable|url',
            'community.telegram' => 'nullable|url',
            'community.newsletter' => 'nullable|url',

            // Ticketing
            'price' => 'required|numeric|min:0',
            'ticket_url' => 'nullable|url',
            'is_featured' => 'nullable|boolean',
        ];
    }

    /**
     * Custom error messages (Optional)
     */
    public function messages(): array
    {
        return [
            'start_datetime.after' => 'The event cannot start in the past.',
            'location_name.required_unless' => 'A venue name is required for physical events.',
            'speakers.*.name.required_with' => 'Please provide a name for all added speakers.',
        ];
    }
}
