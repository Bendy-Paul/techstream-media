@extends('layouts.company')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 fw-bold mb-0">Edit Event</h2>
        <p class="text-muted small mb-0">Update your event details below.</p>
    </div>
    <a href="{{ route('company.events.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('company.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4">
        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">
            {{-- Basic Info --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Event Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Event Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                               value="{{ old('title', $event->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="6" required>{{ old('description', $event->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Categories --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Categories</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @forelse($categories as $cat)
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]"
                                           value="{{ $cat->id }}" id="cat_{{ $cat->id }}"
                                           {{ in_array($cat->id, old('categories', $selectedCats)) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="cat_{{ $cat->id }}">{{ $cat->name }}</label>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">No event categories available.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Tags --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Tags</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
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
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="col-lg-4">
            {{-- Logistics --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Logistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Start Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="start_datetime"
                               class="form-control @error('start_datetime') is-invalid @enderror"
                               value="{{ old('start_datetime', $event->start_datetime ? \Carbon\Carbon::parse($event->start_datetime)->format('Y-m-d\TH:i') : '') }}" required>
                        @error('start_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">End Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="end_datetime"
                               class="form-control @error('end_datetime') is-invalid @enderror"
                               value="{{ old('end_datetime', $event->end_datetime ? \Carbon\Carbon::parse($event->end_datetime)->format('Y-m-d\TH:i') : '') }}" required>
                        @error('end_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_virtual" id="isVirtual"
                               value="1" onchange="toggleLocation()"
                               {{ old('is_virtual', $event->is_virtual) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isVirtual">Virtual Event</label>
                    </div>

                    <div id="locationFields">
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-semibold">Venue Name</label>
                            <input type="text" name="location_name" class="form-control"
                                   value="{{ old('location_name', $event->location_name) }}" placeholder="e.g. Eko Hotel">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-semibold">City</label>
                            <select name="city_id" class="form-select">
                                <option value="">Select city…</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $event->city_id) == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ticketing & Banner --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Tickets & Banner</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Price (₦)</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $event->price ?? 0) }}" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Ticket Link</label>
                        <input type="url" name="ticket_url" class="form-control"
                               value="{{ old('ticket_url', $event->ticket_url) }}" placeholder="https://…">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Banner Image</label>
                        @if($event->banner_image_url)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $event->banner_image_url) }}"
                                     class="img-fluid rounded border" style="max-height:120px;">
                                <div class="form-text">Upload a new image to replace.</div>
                            </div>
                        @endif
                        <input type="file" name="banner_image_upload" class="form-control form-control-sm" accept="image/*">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold btn-lg">
                <i class="fas fa-save me-2"></i> Save Changes
            </button>
        </div>
    </div>
</form>

<script>
    function toggleLocation() {
        const isVirtual = document.getElementById('isVirtual').checked;
        const locFields = document.getElementById('locationFields');
        locFields.style.opacity = isVirtual ? '0.4' : '1';
        locFields.style.pointerEvents = isVirtual ? 'none' : 'auto';
    }
    toggleLocation();
</script>
@endsection
