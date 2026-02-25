@extends('layouts.company')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 fw-bold mb-0">Post a Job</h2>
        <p class="text-muted small mb-0">Fill in the details below to create a new job listing.</p>
    </div>
    <a href="{{ route('company.jobs.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('company.jobs.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        {{-- LEFT: Main Content --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Job Content</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Job Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="e.g. Senior Backend Engineer" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Short Summary <span class="text-danger">*</span> <span class="text-muted">(max 500 chars)</span></label>
                        <textarea name="summary" class="form-control @error('summary') is-invalid @enderror"
                                  rows="3" maxlength="500" placeholder="A brief overview of the role..." required>{{ old('summary') }}</textarea>
                        @error('summary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Full Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="7" placeholder="Detailed job description..." required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Responsibilities <span class="text-muted">(one per line)</span></label>
                        <textarea name="responsibilities" class="form-control" rows="5"
                                  placeholder="- Design and build APIs&#10;- Collaborate with the frontend team">{{ old('responsibilities') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Requirements <span class="text-muted">(one per line)</span></label>
                        <textarea name="requirements" class="form-control" rows="5"
                                  placeholder="- 3+ years of Laravel experience&#10;- Strong SQL knowledge">{{ old('requirements') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Job Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Employment Type <span class="text-danger">*</span></label>
                        <select name="job_type" class="form-select @error('job_type') is-invalid @enderror" required>
                            @foreach(['Full-time', 'Part-time', 'Contract', 'Internship', 'Remote'] as $jt)
                                <option value="{{ $jt }}" {{ old('job_type') == $jt ? 'selected' : '' }}>{{ $jt }}</option>
                            @endforeach
                        </select>
                        @error('job_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Experience Level <span class="text-danger">*</span></label>
                        <select name="experience_level" class="form-select" required>
                            @foreach(['Entry' => 'Entry Level', 'Mid' => 'Mid Level', 'Senior' => 'Senior Level', 'Lead' => 'Lead / Executive'] as $val => $label)
                                <option value="{{ $val }}" {{ old('experience_level') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Education Level</label>
                        <select name="education_level" class="form-select">
                            <option value="">Not specified</option>
                            @foreach(["Diploma", "Bachelor's Degree", "Master's Degree", "PhD"] as $ed)
                                <option value="{{ $ed }}" {{ old('education_level') == $ed ? 'selected' : '' }}>{{ $ed }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Salary Range</label>
                        <input type="text" name="salary_range" class="form-control"
                               value="{{ old('salary_range') }}" placeholder="e.g. ₦200k – ₦350k / month">
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">City</label>
                        <select name="city_id" class="form-select">
                            <option value="">Select city…</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_remote" value="1"
                               id="isRemote" {{ old('is_remote') ? 'checked' : '' }}>
                        <label class="form-check-label" for="isRemote">Remote Position</label>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Application Method <span class="text-danger">*</span></label>
                        <select name="application_type" class="form-select" id="appType" onchange="toggleApplyLink()" required>
                            <option value="smart_apply" {{ old('application_type','smart_apply') == 'smart_apply' ? 'selected' : '' }}>
                                Smart Apply (recommended)
                            </option>
                            <option value="external" {{ old('application_type') == 'external' ? 'selected' : '' }}>External Link</option>
                        </select>
                    </div>

                    <div class="mb-3" id="applyLinkField" style="{{ old('application_type') == 'external' ? '' : 'display:none;' }}">
                        <label class="form-label small text-muted fw-semibold">Apply Link / Email</label>
                        <input type="text" name="apply_link" class="form-control"
                               value="{{ old('apply_link') }}" placeholder="https://company.com/careers">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small text-muted fw-semibold">Expiry Date <span class="text-danger">*</span></label>
                        <input type="date" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror"
                               value="{{ old('expires_at', now()->addDays(30)->format('Y-m-d')) }}" required>
                        @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="fas fa-paper-plane me-2"></i> Post Job
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function toggleApplyLink() {
        const type = document.getElementById('appType').value;
        document.getElementById('applyLinkField').style.display = type === 'external' ? '' : 'none';
    }
</script>
@endsection
