<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
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
        $articleId = $this->route('id'); // Assuming the route parameter is 'id' based on web.php

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('articles', 'title')->ignore($articleId),
            ],
            // author_id is usually not updated, but if allowed:
            // 'author_id' => 'required|exists:users,id', 
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
