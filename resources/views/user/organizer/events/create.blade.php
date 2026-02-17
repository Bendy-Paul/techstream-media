@extends('layouts.user')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Create New Event</h4>
                <a href="{{ route('user.organizer.events.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('user.organizer.events.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h5 class="mb-3 text-muted border-bottom pb-2">Event Details</h5>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. Annual Tech Conference 2026">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="start_datetime" class="form-label">Start Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('start_datetime') is-invalid @enderror" id="start_datetime" name="start_datetime" value="{{ old('start_datetime') }}" required>
                            @error('start_datetime')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_datetime" class="form-label">End Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('end_datetime') is-invalid @enderror" id="end_datetime" name="end_datetime" value="{{ old('end_datetime') }}" required>
                            @error('end_datetime')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="banner_image_upload" class="form-label">Banner Image</label>
                        <input type="file" class="form-control @error('banner_image_upload') is-invalid @enderror" id="banner_image_upload" name="banner_image_upload" accept="image/*">
                        <div class="form-text">Recommended size: 1200x600px. Max size: 2MB.</div>
                        @error('banner_image_upload')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h5 class="mb-3 mt-4 text-muted border-bottom pb-2">Location & Type</h5>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_virtual" name="is_virtual" value="1" {{ old('is_virtual') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_virtual">This is a virtual event</label>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="location_name" class="form-label">Location Name / Venue</label>
                            <input type="text" class="form-control @error('location_name') is-invalid @enderror" id="location_name" name="location_name" value="{{ old('location_name') }}" placeholder="e.g. Eko Hotels & Suites (or Zoom Link)">
                            @error('location_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="city_id" class="form-label">City</label>
                            <select class="form-select @error('city_id') is-invalid @enderror" id="city_id" name="city_id">
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="mb-3 mt-4 text-muted border-bottom pb-2">Ticketing</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Price (0 for Free)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', 0) }}">
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ticket_url" class="form-label">Ticket / Registration URL</label>
                            <input type="url" class="form-control @error('ticket_url') is-invalid @enderror" id="ticket_url" name="ticket_url" value="{{ old('ticket_url') }}" placeholder="https://...">
                            @error('ticket_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="mb-3 mt-4 text-muted border-bottom pb-2">Categorization</h5>

                    <div class="mb-3">
                        <label class="form-label">Categories</label>
                        <div class="row g-2">
                            @foreach($categories as $category)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="cat_{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cat_{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <select class="form-select" name="tags[]" multiple aria-label="Tags" size="4">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Hold Ctrl/Cmd to select multiple.</div>
                    </div>

                    <h5 class="mb-3 mt-4 text-muted border-bottom pb-2">Speakers</h5>
                    
                    <div id="speakers-container">
                        <!-- Speaker Rows will be added here -->
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mb-4" id="add-speaker-btn">
                        <i class="fas fa-plus me-1"></i> Add Speaker
                    </button>

                    <div class="d-grid gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-lg">Submit Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<template id="speaker-template">
    <div class="card bg-light border-0 mb-3 speaker-row">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h6 class="card-subtitle text-muted">Speaker <span class="speaker-index"></span></h6>
                <button type="button" class="btn-close remove-speaker-btn" aria-label="Remove"></button>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="speakers[{index}][name]" placeholder="Name" required>
                </div>
                <div class="col-md-6">
                     <input type="text" class="form-control" name="speakers[{index}][position]" placeholder="Position / Title">
                </div>
                <div class="col-md-12">
                    <input type="file" class="form-control" name="speaker_images[{index}]" accept="image/*">
                    <div class="form-text small">Speaker Photo</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('speakers-container');
        const addBtn = document.getElementById('add-speaker-btn');
        const template = document.getElementById('speaker-template');
        let speakerCount = 0;

        addBtn.addEventListener('click', function() {
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('.speaker-row');
            
            row.innerHTML = row.innerHTML.replace(/{index}/g, speakerCount);
            row.querySelector('.speaker-index').textContent = speakerCount + 1;
            
            // Allow checking name attribute if needed for backend array handling
            // Currently using speakers[0][name], etc. based on replacement
            
            row.querySelector('.remove-speaker-btn').addEventListener('click', function() {
                row.remove();
            });

            container.appendChild(clone);
            speakerCount++;
        });
    });
</script>
@endsection
