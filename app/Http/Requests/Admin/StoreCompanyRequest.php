<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
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
            /* =======================
             | Core Company Info
             ======================= */
            'name'        => ['required', 'string', 'max:255'],
            'tagline'     => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            /* =======================
             | Headquarters
             ======================= */
            'city_id' => ['nullable', 'exists:cities,id'],
            'address' => ['nullable', 'string', 'max:500'],

            /* =======================
             | Categories (pivot)
             ======================= */
            'categories'   => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],

            /* =======================
             | Tech Stack (pivot)
             ======================= */
            'stack_ids'   => ['nullable', 'array'],
            'stack_ids.*' => ['exists:stacks,id'],

            /* =======================
             | Branches
             ======================= */
            'branches' => ['nullable', 'array'],
            'branches.*.city_id' => ['nullable', 'exists:cities,id'],
            'branches.*.address' => ['nullable', 'string', 'max:255'],

            /* =======================
             | Projects
             ======================= */
            'projects' => ['nullable', 'array'],
            'projects.*.title'       => ['nullable', 'string', 'max:255'],
            'projects.*.description' => ['nullable', 'string', 'max:500'],

            /* =======================
             | Gallery Images
             ======================= */
            'gallery'   => ['nullable', 'array'],
            'gallery.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048' // 2MB per image
            ],

            /* =======================
             | Branding Files
             ======================= */
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
            'cover' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096'
            ],

            /* =======================
             | Contact Info
             ======================= */
            'email'       => ['nullable', 'email', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:50'],
            'website_url' => ['nullable', 'url', 'max:255'],

            /* =======================
             | Company Meta
             ======================= */
            'year_founded' => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'team_size'    => ['nullable', Rule::in(['1-10', '11-50', '51-200', '200+'])],
            'starting_cost' => ['nullable', 'numeric', 'min:0'],

            /* =======================
             | Social Links
             ======================= */
            'social' => ['nullable', 'array'],
            'social.twitter'  => ['nullable', 'string', 'max:255'],
            'social.linkedin' => ['nullable', 'url', 'max:255'],
            'social.facebook' => ['nullable', 'url', 'max:255'],

            /* =======================
             | Profile Stats
             ======================= */
            'stats' => ['nullable', 'array'],
            'stats.response_rate' => ['nullable', 'integer', 'min:0', 'max:100'],
            'stats.completeness'  => ['nullable', 'integer', 'min:0', 'max:100'],

            /* =======================
             | Settings
             ======================= */
            'subscription_tier' => [
                'required',
                Rule::in(['free', 'silver', 'gold'])
            ],
            'is_verified' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Company name is required.',
            'city_id.exists' => 'Selected city does not exist.',
            'categories.*.exists' => 'One or more selected categories are invalid.',
            'stack_ids.*.exists' => 'One or more selected tech stacks are invalid.',
            'gallery.*.image' => 'Gallery files must be images.',
            'logo.image' => 'Logo must be an image file.',
            'cover.image' => 'Cover must be an image file.',
            'subscription_tier.in' => 'Invalid subscription tier selected.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_verified' => $this->boolean('is_verified'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}
