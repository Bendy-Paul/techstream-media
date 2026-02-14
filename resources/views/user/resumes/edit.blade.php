@extends('layouts.user')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Edit Resume</h2>
    <a href="{{ route('user.resumes.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-2"></i> Back to List
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('user.resumes.update', $resume->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Basic Info -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title mb-0 fw-bold">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Resume Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $resume->title) }}" required>
                    </div>
                    
                    <div class="mb-3">
                         <label for="summary" class="form-label">Professional Summary</label>
                         <textarea class="form-control" id="summary" name="summary" rows="4">{{ old('summary', $resume->summary) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Experience -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Experience</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-experience">
                        <i class="fas fa-plus me-1"></i> Add Position
                    </button>
                </div>
                <div class="card-body" id="experience-container">
                    @forelse($resume->experiences as $index => $exp)
                        <div class="experience-item border rounded p-3 mb-3 bg-light position-relative">
                            <input type="hidden" name="experience[{{ $index }}][id]" value="{{ $exp->id }}">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item" aria-label="Close"></button>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Job Title</label>
                                    <input type="text" name="experience[{{ $index }}][job_title]" class="form-control form-control-sm" value="{{ $exp->job_title }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Company</label>
                                    <input type="text" name="experience[{{ $index }}][company_name]" class="form-control form-control-sm" value="{{ $exp->company_name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Start Date</label>
                                    <input type="date" name="experience[{{ $index }}][start_date]" class="form-control form-control-sm" value="{{ $exp->start_date ? $exp->start_date->format('Y-m-d') : '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">End Date</label>
                                    <input type="date" name="experience[{{ $index }}][end_date]" class="form-control form-control-sm end-date-input" value="{{ $exp->end_date ? $exp->end_date->format('Y-m-d') : '' }}" {{ $exp->is_current ? 'disabled' : '' }}>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" name="experience[{{ $index }}][is_current]" value="1" {{ $exp->is_current ? 'checked' : '' }} onchange="toggleEndDate(this)">
                                        <label class="form-check-label small">I currently work here</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Description</label>
                                    <textarea name="experience[{{ $index }}][description]" class="form-control form-control-sm" rows="2">{{ $exp->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center" id="no-experience-msg">No experience added yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Education -->
             <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Education</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-education">
                        <i class="fas fa-plus me-1"></i> Add Education
                    </button>
                </div>
                <div class="card-body" id="education-container">
                    @forelse($resume->education as $index => $edu)
                        <div class="education-item border rounded p-3 mb-3 bg-light position-relative">
                            <input type="hidden" name="education[{{ $index }}][id]" value="{{ $edu->id }}">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item" aria-label="Close"></button>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold">School / Institution</label>
                                    <input type="text" name="education[{{ $index }}][institution]" class="form-control form-control-sm" value="{{ $edu->institution }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Degree</label>
                                    <input type="text" name="education[{{ $index }}][degree]" class="form-control form-control-sm" value="{{ $edu->degree }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Field of Study</label>
                                    <input type="text" name="education[{{ $index }}][field_of_study]" class="form-control form-control-sm" value="{{ $edu->field_of_study }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Start Date</label>
                                    <input type="date" name="education[{{ $index }}][start_date]" class="form-control form-control-sm" value="{{ $edu->start_date ? $edu->start_date->format('Y-m-d') : '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">End Date</label>
                                    <input type="date" name="education[{{ $index }}][end_date]" class="form-control form-control-sm" value="{{ $edu->end_date ? $edu->end_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                        </div>
                    @empty
                         <p class="text-muted text-center" id="no-education-msg">No education added yet.</p>
                    @endforelse
                </div>
            </div>
            
            <!-- Skills -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title mb-0 fw-bold">Skills</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                         <label class="form-label">Select Skills</label>
                        <div class="row g-2">
                             @php
                                $currentSkills = $resume->skills->first()->skills ?? [];
                                if (!is_array($currentSkills)) {
                                    $currentSkills = []; 
                                }
                             @endphp
                             @foreach($stacks as $stack)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="skills[]" value="{{ $stack->id }}" id="skill_{{ $stack->id }}" 
                                            {{ (is_array(old('skills')) ? in_array($stack->id, old('skills')) : in_array($stack->id, $currentSkills)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="skill_{{ $stack->id }}">
                                            @if($stack->icon_class) <i class="{{ $stack->icon_class }} me-1"></i> @endif
                                            {{ $stack->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                         <div class="form-text">Check all that apply.</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 mb-5">Update Resume</button>
        </div>

        <!-- Sidebar Settings -->
        <div class="col-lg-4">
             <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title mb-0 fw-bold">Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Visibility</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" id="visibility_private" value="private" {{ old('visibility', $resume->visibility) == 'private' ? 'checked' : '' }}>
                            <label class="form-check-label" for="visibility_private">
                                Private
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" id="visibility_public" value="public" {{ old('visibility', $resume->visibility) == 'public' ? 'checked' : '' }}>
                            <label class="form-check-label" for="visibility_public">
                                Public
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" {{ old('is_default', $resume->is_default) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_default">Set as Default Resume</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Templates (Same as Create) -->
<template id="experience-template">
    <div class="experience-item border rounded p-3 mb-3 bg-light position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item" aria-label="Close"></button>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Job Title</label>
                <input type="text" name="experience[INDEX][job_title]" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-6">
                 <label class="form-label small fw-bold">Company</label>
                 <input type="text" name="experience[INDEX][company_name]" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-6">
                 <label class="form-label small fw-bold">Start Date</label>
                 <input type="date" name="experience[INDEX][start_date]" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-6">
                 <label class="form-label small fw-bold">End Date</label>
                 <input type="date" name="experience[INDEX][end_date]" class="form-control form-control-sm end-date-input">
                 <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" name="experience[INDEX][is_current]" value="1" onchange="toggleEndDate(this)">
                    <label class="form-check-label small">I currently work here</label>
                 </div>
            </div>
            <div class="col-12">
                 <label class="form-label small fw-bold">Description</label>
                 <textarea name="experience[INDEX][description]" class="form-control form-control-sm" rows="2"></textarea>
            </div>
        </div>
    </div>
</template>

<template id="education-template">
    <div class="education-item border rounded p-3 mb-3 bg-light position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item" aria-label="Close"></button>
         <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label small fw-bold">School / Institution</label>
                <input type="text" name="education[INDEX][institution]" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-6">
                 <label class="form-label small fw-bold">Degree</label>
                 <input type="text" name="education[INDEX][degree]" class="form-control form-control-sm">
            </div>
             <div class="col-md-6">
                 <label class="form-label small fw-bold">Field of Study</label>
                 <input type="text" name="education[INDEX][field_of_study]" class="form-control form-control-sm">
            </div>
            <div class="col-md-6">
                 <label class="form-label small fw-bold">Start Date</label>
                 <input type="date" name="education[INDEX][start_date]" class="form-control form-control-sm">
            </div>
            <div class="col-md-6">
                 <label class="form-label small fw-bold">End Date</label>
                 <input type="date" name="education[INDEX][end_date]" class="form-control form-control-sm">
            </div>
         </div>
    </div>
</template>

<script>
    // Initialize indexes based on existing count to avoid collisions
    let expIndex = {{ $resume->experiences->count() }};
    let eduIndex = {{ $resume->education->count() }};

    function toggleEndDate(checkbox) {
        const container = checkbox.closest('.col-md-6');
        const dateInput = container.previousElementSibling; // Assuming direct sibling structure from template
        // Better selector to be safe
        const row = checkbox.closest('.row');
        const endDateInput = row.querySelector('.end-date-input');
        
        if (endDateInput) {
            endDateInput.disabled = checkbox.checked;
            if (checkbox.checked) endDateInput.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initial setup for existing items delete buttons
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.experience-item, .education-item').remove();
                // Check if empty and show placeholder if needed
                if(document.querySelectorAll('.experience-item').length === 0) {
                     const placheolder = document.getElementById('no-experience-msg');
                     if(placheolder) placheolder.style.display = 'block';
                }
                 if(document.querySelectorAll('.education-item').length === 0) {
                     const placheolder = document.getElementById('no-education-msg');
                     if(placheolder) placheolder.style.display = 'block';
                }
            });
        });

        // Experience Logic (Same as Create)
        document.getElementById('add-experience').addEventListener('click', function() {
            const template = document.getElementById('experience-template').content.cloneNode(true);
            const container = document.getElementById('experience-container');
            const placeholder = document.getElementById('no-experience-msg');
            
            if (placeholder) placeholder.style.display = 'none';

            container.appendChild(template);
            const newItem = container.lastElementChild;
            
            newItem.querySelectorAll('[name*="INDEX"]').forEach(input => {
                input.name = input.name.replace('INDEX', expIndex);
            });
            expIndex++;

            newItem.querySelector('.remove-item').addEventListener('click', function() {
                newItem.remove();
                 if (container.children.length === 1) { // +1 for p
                      if (placeholder) placeholder.style.display = 'block';
                }
            });
        });

        // Education Logic (Same as Create)
        document.getElementById('add-education').addEventListener('click', function() {
            const template = document.getElementById('education-template').content.cloneNode(true);
            const container = document.getElementById('education-container');
            const placeholder = document.getElementById('no-education-msg');
            
            if (placeholder) placeholder.style.display = 'none';

            container.appendChild(template);
            const newItem = container.lastElementChild;
            
            newItem.querySelectorAll('[name*="INDEX"]').forEach(input => {
                input.name = input.name.replace('INDEX', eduIndex);
            });
            eduIndex++;

             newItem.querySelector('.remove-item').addEventListener('click', function() {
                newItem.remove();
                if (container.children.length === 1) {
                      if (placeholder) placeholder.style.display = 'block';
                }
            });
        });
    });
</script>
@endsection
