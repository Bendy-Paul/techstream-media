<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>
<body>
    @include('admin.partials.header')
    @include('admin.partials.sidebar')

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Edit Event</h2>
                <p class="text-muted mb-0">Update event details.</p>
            </div>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Back</a>
        </div>

        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <!-- Basic Info -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">Event Details</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Event Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Description</label>
                                <textarea name="description" id="ckeditor-editor" class="form-control" rows="5">{{ old('description', $event->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Date & Location -->
                    <div class="card border-0 shadow-sm mb-4">
                         <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">Date, Time & Location</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Start Date & Time</label>
                                    <input type="datetime-local" name="start_datetime" class="form-control" value="{{ old('start_datetime', $event->start_datetime) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">End Date & Time</label>
                                    <input type="datetime-local" name="end_datetime" class="form-control" value="{{ old('end_datetime', $event->end_datetime) }}" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_virtual" value="1" id="isVirtual" {{ old('is_virtual', $event->is_virtual) ? 'checked' : '' }} onchange="toggleLocation()">
                                    <label class="form-check-label" for="isVirtual">This is a virtual event</label>
                                </div>
                            </div>

                            <div id="locationFields" class="{{ old('is_virtual', $event->is_virtual) ? 'd-none' : '' }}">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Location Name (Venue)</label>
                                    <input type="text" name="location_name" class="form-control" value="{{ old('location_name', $event->location_name) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">City</label>
                                    <select name="city_id" class="form-select">
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id', $event->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Speakers -->
                     <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">Speakers</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSpeaker()">+ Add Speaker</button>
                        </div>
                        <div class="card-body" id="speakers-container">
                            <p class="text-muted small mb-3">Re-upload images if you modify speakers. Images are preserved unless you change the speaker details.</p>
                            @foreach($event->speakers as $index => $speaker)
                            <div class="row g-2 align-items-center mb-2 speaker-row">
                                <div class="col-md-4">
                                     <input type="text" name="speakers[{{$index}}][name]" class="form-control form-control-sm" placeholder="Name" value="{{ $speaker->name }}">
                                </div>
                                <div class="col-md-4">
                                     <input type="text" name="speakers[{{$index}}][position]" class="form-control form-control-sm" placeholder="Position" value="{{ $speaker->position }}">
                                </div>
                                <div class="col-md-3">
                                     @if($speaker->image_path)
                                        <input type="hidden" name="speakers[{{$index}}][existing_image]" value="{{ $speaker->image_path }}">
                                        <div class="small text-success"><i class="fas fa-check"></i> Image Set</div>
                                     @endif
                                     <input type="file" name="speaker_images[{{$index}}]" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-1 text-end">
                                     <button type="button" class="btn btn-sm text-danger" onclick="removeSpeaker(this)"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                     <!-- Setup -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">Settings</h5>
                             
                             <!-- Banner Image -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Banner Image</label>
                                @if($event->banner_image_url)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $event->banner_image_url) }}" class="img-fluid rounded" style="max-height: 150px;">
                                    </div>
                                @endif
                                <input type="file" name="banner_image_upload" class="form-control">
                            </div>

                            <hr>
                            
                             <!-- Categories -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Category</label>
                                <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                                    @foreach($categories as $cat)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $cat->id }}" id="cat_{{ $cat->id }}"
                                                {{ in_array($cat->id, old('categories', $event->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cat_{{ $cat->id }}">{{ $cat->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                             <!-- Organizers -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Organizers</label>
                                <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                                     @foreach($companies as $comp)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="organizers[]" value="{{ $comp->id }}" id="org_{{ $comp->id }}"
                                             {{ in_array($comp->id, old('organizers', $event->organizers->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="org_{{ $comp->id }}">{{ $comp->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Partners -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Partners</label>
                                <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                                     @foreach($companies as $comp)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="partners[]" value="{{ $comp->id }}" id="partner_{{ $comp->id }}"
                                            {{ in_array($comp->id, old('partners', $event->partners->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="partner_{{ $comp->id }}">{{ $comp->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Tags -->
                             <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Tags</label>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($tags as $tag)
                                        <div class="form-check form-check-inline m-0">
                                            <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                                            {{ in_array($tag->id, old('tags', $event->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="tag_{{ $tag->id }}">#{{ $tag->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold">Update Event</button>
                        </div>
                    </div>
                    
                    <!-- Gallery -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">Gallery</h6>
                        </div>
                        <div class="card-body">
                             <!-- Existing Gallery -->
                             <div class="row g-2 mb-3">
                                @foreach($event->galleries as $img)
                                    <div class="col-4 position-relative">
                                        <img src="{{ asset('storage/' . $img->image_path) }}" class="img-thumbnail" style="height: 60px; widht:100%; object-fit:cover;">
                                        <div class="form-check position-absolute top-0 end-0 m-1 bg-white rounded">
                                            <input class="form-check-input ms-1" type="checkbox" name="delete_gallery_ids[]" value="{{ $img->id }}" title="Delete">
                                        </div>
                                    </div>
                                @endforeach
                             </div>
                             
                             <label class="form-label fw-bold small text-muted">Add More Images</label>
                             <input type="file" name="gallery[]" class="form-control" multiple>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#ckeditor-editor'))
            .catch(error => {
                console.error(error);
            });

        function toggleLocation() {
            var isVirtual = document.getElementById('isVirtual').checked;
            var locationFields = document.getElementById('locationFields');
            if (isVirtual) {
                locationFields.classList.add('d-none');
            } else {
                locationFields.classList.remove('d-none');
            }
        }
        
        // Speaker Index Counter based on existing speakers
        let speakerIndex = {{ $event->speakers->count() }};

        function addSpeaker() { // Updated to avoid re-declaring speakerIndex
            const container = document.getElementById('speakers-container');
            const html = `
                <div class="row g-2 align-items-center mb-2 speaker-row">
                    <div class="col-md-4">
                        <input type="text" name="speakers[${speakerIndex}][name]" class="form-control form-control-sm" placeholder="Name" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="speakers[${speakerIndex}][position]" class="form-control form-control-sm" placeholder="Position">
                    </div>
                    <div class="col-md-3">
                        <input type="file" name="speaker_images[${speakerIndex}]" class="form-control form-control-sm">
                    </div>
                     <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-sm text-danger" onclick="removeSpeaker(this)"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            speakerIndex++;
        }

        function removeSpeaker(btn) {
            btn.closest('.speaker-row').remove();
        }
    </script>
</body>
</html>
