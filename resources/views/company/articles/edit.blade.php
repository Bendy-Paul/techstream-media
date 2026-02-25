@extends('layouts.company')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 fw-bold mb-0">Edit Article</h2>
        <p class="text-muted small mb-0">Update your article content and settings.</p>
    </div>
    <a href="{{ route('company.articles.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<form action="{{ route('company.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4">
        {{-- LEFT: Content --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Article Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                               value="{{ old('title', $article->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Content <span class="text-danger">*</span></label>
                        <textarea name="content" id="ck-editor" class="form-control @error('content') is-invalid @enderror"
                                  rows="14">{{ old('content', $article->content) }}</textarea>
                        @error('content')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Publishing</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured"
                               value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isFeatured">Featured Article</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Categories</label>
                        <div class="border rounded p-2 bg-light" style="max-height:180px; overflow-y:auto;">
                            @forelse($categories as $cat)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]"
                                           value="{{ $cat->id }}" id="cat_{{ $cat->id }}"
                                           {{ in_array($cat->id, old('categories', $selectedCats)) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="cat_{{ $cat->id }}">{{ $cat->name }}</label>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">No categories available.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Tags</label>
                        <div class="d-flex flex-wrap gap-1 border rounded p-2 bg-light" style="max-height:130px;overflow-y:auto;">
                            @foreach($tags as $tag)
                                <div class="form-check form-check-inline m-0">
                                    <input class="form-check-input" type="checkbox" name="tags[]"
                                           value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                                           {{ in_array($tag->id, old('tags', $selectedTags)) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="tag_{{ $tag->id }}">#{{ $tag->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small text-muted fw-semibold">Featured Image</label>
                        @if($article->featured_image_url)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $article->featured_image_url) }}"
                                     class="img-fluid rounded border" style="max-height:120px;">
                                <div class="form-text">Upload a new image to replace.</div>
                            </div>
                        @endif
                        <input type="file" name="featured_image_upload" class="form-control form-control-sm" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        ClassicEditor.create(document.querySelector('#ck-editor')).catch(console.error);
    });
</script>
@endsection
