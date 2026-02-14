<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
</head>
<body>
    @include('admin.partials.header')
    @include('admin.partials.sidebar')
    
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Edit Article</h2>
            <p class="text-muted mb-0">Update content for the news section.</p>
       </div>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Back</a>
    </div>

    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="author_id" value="{{ $article->author_id }}">
        
        <div class="row">
            <!-- Left Column: Content -->
            <div class="col-md-8">
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Article Title</label>
                            <input type="text" name="title" class="form-control form-control-lg" placeholder="Enter title here..." value="{{ old('title', $article->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Content</label>
                            <textarea name="content" class="form-control" id="ckeditor-editor" rows="15">{{ old('content', $article->content) }}</textarea>
                            <div class="form-text">You can use rich text formatting.</div>
                            <!-- CKEditor 5 WYSIWYG Editor -->
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    ClassicEditor
                                        .create(document.querySelector('#ckeditor-editor'))
                                        .catch(error => {
                                            console.error(error);
                                        });
                                });
                            </script>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Company Tagging -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="fw-bold mb-0">Tag Companies</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-text mb-2">Select companies mentioned in this article. It will appear on their profiles.</div>
                                <div class="border rounded p-2 bg-light" style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($companies as $company)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="companies[]" value="{{ $company->id }}" id="comp_{{ $company->id }}"
                                                {{ in_array($company->id, old('companies', $article->companies->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="comp_{{ $company->id }}">
                                                {{ $company->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Event Tagging -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="fw-bold mb-0">Tag Events</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-text mb-2">Select events mentioned in this article. It will appear on their profiles.</div>
                                <div class="border rounded p-2 bg-light" style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($events as $event)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="events[]" value="{{ $event->id }}" id="event_{{ $event->id }}"
                                                {{ in_array($event->id, old('events', $article->events->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="event_{{ $event->id }}">
                                                {{ $event->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Publishing</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featureCheck" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="featureCheck">Featured</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Categories</label>
                            <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                                @foreach ($categories as $cat)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $cat->id }}" id="cat_{{ $cat->id }}"
                                            {{ in_array($cat->id, old('categories', $article->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cat_{{ $cat->id }}">
                                            {{ $cat->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>

                        <!-- Featured Image with Gallery Selection -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Featured Image</label>

                            <!-- Hidden input to store selected image URL -->
                            <input type="hidden" name="selected_image" id="selected_image_input">
                            <input type="hidden" name="remove_image" id="remove_image_input" value="0">

                            <!-- Image Preview Area -->
                            <div id="image_preview_container" class="mb-2 {{ $article->featured_image_url ? '' : 'd-none' }}">
                                <img src="{{ $article->featured_image_url ? asset( (str_starts_with($article->featured_image_url, 'storage') ? '' : 'storage/') . $article->featured_image_url) : '' }}" id="image_preview" class="img-fluid rounded border" style="max-height: 150px;">
                                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeImage()">Remove</button>
                            </div>

                            <!-- Buttons -->
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mediaGalleryModal">
                                    <i class="fas fa-images me-1"></i> Choose from Gallery
                                </button>
                                <div class="text-center text-muted small my-1">- OR -</div>
                                <input type="file" name="featured_image_upload" class="form-control form-control-sm">
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Update Article</button>
                    </div>
                </div>



                <!-- Topic Tags -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Article Tags (#)</h6>
                    </div>
                    <div class="card-body">
                        <input type="text" name="new_tags" class="form-control mb-2" placeholder="Add tags separated by comma (e.g. AI, Funding)">
                        <div class="d-flex flex-wrap gap-1">
                            @foreach ($tags as $tag)
                                <div class="form-check form-check-inline m-0">
                                    <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                                        {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="tag_{{ $tag->id }}">#{{ $tag->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<!-- Media Gallery Modal -->
<div class="modal fade" id="mediaGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2" id="gallery_grid">
                    @php
                        $galleryImages = Storage::disk('public')->files('uploads/articles');
                    @endphp
                    @foreach($galleryImages as $img)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail gallery-img" style="cursor:pointer; height:100px; object-fit:cover; width:100%;" onclick="selectImage('storage/{{ $img }}')" loading="lazy">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function selectImage(url) {
        document.getElementById('selected_image_input').value = url;
        document.getElementById('image_preview').src = '/' + url.replace(/^\/+/, '');
        document.getElementById('image_preview_container').classList.remove('d-none');
         document.getElementById('remove_image_input').value = '0'; // Reset remove flag
        // Close modal
        var myModalEl = document.getElementById('mediaGalleryModal');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        modal.hide();
    }

    function removeImage() {
        document.getElementById('selected_image_input').value = '';
        document.getElementById('image_preview').src = '';
        document.getElementById('image_preview_container').classList.add('d-none');
        document.getElementById('remove_image_input').value = '1'; // Set remove flag
    }
</script>
</body>

</html>
