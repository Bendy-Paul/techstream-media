<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;


class StoreArticleRequest extends FormRequest
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
            //
            'title' => 'required|string|max:255|unique:articles,title',
            'author_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
            
            // Image Validation: Either a file upload OR a string path from gallery
            'featured_image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'selected_image' => 'nullable|string',

            // Array Validation (Tags, Categories, etc.)
            'companies' => 'array|exists:companies,id',
            'events' => 'array|exists:events,id',
            'tags' => 'array|exists:tags,id',
            'categories' => 'array|exists:categories,id',
        ];
    }
}
