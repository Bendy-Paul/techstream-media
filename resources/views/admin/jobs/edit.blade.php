<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
   
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <style>
        .ck-editor__editable {
            min-height: 150px;
        }
    </style>
</head>
<body>
    @include('admin.partials.header')
    @include('admin.partials.sidebar')

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Edit Job Post</h2>
                <p class="text-muted mb-0">Update job listing details.</p>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Back</a>
        </div>

        <form action="{{ route('admin.jobs.update', $job->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">Job Details</h5>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Job Title</label>
                                <input type="text" name="title" class="form-control form-control-lg" placeholder="e.g. Senior Backend Engineer" value="{{ old('title', $job->title) }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Company</label>
                                    <select name="company_id" class="form-select" required>
                                        <option value="">Select Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id', $job->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Job Type</label>
                                    <select name="job_type" class="form-select" required>
                                        <option value="Full-time" {{ old('job_type', $job->job_type) == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                        <option value="Part-time" {{ old('job_type', $job->job_type) == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                        <option value="Contract" {{ old('job_type', $job->job_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="Internship" {{ old('job_type', $job->job_type) == 'Internship' ? 'selected' : '' }}>Internship</option>
                                        <option value="Remote" {{ old('job_type', $job->job_type) == 'Remote' ? 'selected' : '' }}>Remote</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Location</label>
                                    <select name="location_id" class="form-select" required>
                                         <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('location_id', $job->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Expiration Date</label>
                                    <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', \Carbon\Carbon::parse($job->expires_at)->format('Y-m-d')) }}" required>
                                </div>
                            </div>

                             <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Short Summary</label>
                                <textarea name="summary" class="form-control" rows="3" placeholder="Brief overview of the role..." required>{{ old('summary', $job->summary) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Job Description</label>
                                <textarea name="description" id="desc-editor">{{ old('description', $job->description) }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Responsibilities</label>
                                <textarea name="responsibilities" id="resp-editor">{{ old('responsibilities', $job->responsibilities) }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Requirements</label>
                                <textarea name="requirements" id="req-editor">{{ old('requirements', $job->requirements) }}</textarea>
                            </div>

                                                         
                            <!-- Tools -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Tools & Technologies</label>
                                <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                    <div class="d-flex flex-wrap">
                                        @foreach($tools as $tool)
                                            <div class="tool-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="tool_ids[]" value="{{ $tool->id }}" id="tool_{{ $tool->id }}"
                                                    {{ in_array($tool->id, old('tool_ids', $job->tools->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tool_{{ $tool->id }}"><i class="{{ $tool->icon_class }} me-1 text-muted"></i> {{ $tool->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Keywords & Weights -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">
                                    Keywords & Match Weights 
                                    <span class="text-muted" title="Used for calculating match score with candidate resumes">(?)</span>
                                </label>
                                <input type="text" id="keywordFilter" class="form-control mb-2" placeholder="Filter keywords...">
                                <div class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                                    @foreach($keywords as $keyword)
                                        @php
                                            $jobKeyword = $job->keywords->find($keyword->id);
                                            $isChecked = $jobKeyword ? true : false;
                                            $currentWeight = $jobKeyword ? $jobKeyword->pivot->weight : 1;
                                        @endphp
                                    <div class="keyword-item d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                        <div class="form-check">
                                            <input class="form-check-input keyword-check" type="checkbox" 
                                                   id="keyword_{{ $keyword->id }}"
                                                   onchange="toggleWeight(this, {{ $keyword->id }})"
                                                   {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="keyword_{{ $keyword->id }}">
                                                {{ $keyword->name }}
                                            </label>
                                        </div>
                                        
                                        <div class="weight-selector" id="weight_box_{{ $keyword->id }}" style="{{ $isChecked ? 'display:block' : 'display:none' }};">
                                            <select name="keywords[{{ $keyword->id }}]" id="weight_{{ $keyword->id }}" class="form-select form-select-sm" {{ $isChecked ? '' : 'disabled' }}>
                                                <option value="1" {{ $currentWeight == 1 ? 'selected' : '' }}>Nice to have (1)</option>
                                                <option value="2" {{ $currentWeight == 2 ? 'selected' : '' }}>Preferred (2)</option>
                                                <option value="3" {{ $currentWeight == 3 ? 'selected' : '' }}>Required (3)</option>
                                            </select>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                     <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">Requirements & Skills</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Experience Level</label>
                                <select name="experience_level" class="form-select" required>
                                    <option value="Entry" {{ old('experience_level', $job->experience_level) == 'Entry Level' ? 'selected' : '' }}>Entry Level</option>
                                    <option value="Mid" {{ old('experience_level', $job->experience_level) == 'Mid Level' ? 'selected' : '' }}>Mid Level</option>
                                    <option value="Senior" {{ old('experience_level', $job->experience_level) == 'Senior Level' ? 'selected' : '' }}>Senior Level</option>
                                    <option value="Lead" {{ old('experience_level', $job->experience_level) == 'Executive' ? 'selected' : '' }}>Executive</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Education Level</label>
                                <select name="education_level" class="form-select" required>
                                    <option value="High School" {{ old('education_level', $job->education_level) == 'High School' ? 'selected' : '' }}>High School</option>
                                    <option value="Bachelor's Degree" {{ old('education_level', $job->education_level) == "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                                    <option value="Master's Degree" {{ old('education_level', $job->education_level) == "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                                    <option value="PhD" {{ old('education_level', $job->education_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Salary Range</label>
                                <input type="text" name="salary_range" class="form-control" placeholder="e.g. $80k - $100k" value="{{ old('salary_range', $job->salary_range) }}">
                            </div>
                            
                            <hr>
                            
                             <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Application Type</label>
                                <select name="application_type" class="form-select" id="appType" onchange="toggleLink()" required>
                                    <option value="smart_apply" {{ old('application_type', $job->application_type) == 'smart_apply' ? 'selected' : '' }}>Smart Apply</option>
                                    <option value="external" {{ old('application_type', $job->application_type) == 'external' ? 'selected' : '' }}>External</option>
                                </select>
                            </div>
                            
                            <div class="mb-3" id="linkField">
                                <label class="form-label fw-bold small text-muted">Apply Link / Email</label>
                                <input type="text" name="apply_link" class="form-control" placeholder="Enter URL or Email" value="{{ old('apply_link', $job->apply_link) }}">
                            </div>

                             <hr>


                            <button type="submit" class="btn btn-primary w-100 fw-bold">Update Job Post</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize CKEditors
        const editors = ['#desc-editor', '#resp-editor', '#req-editor'];
        editors.forEach(selector => {
            ClassicEditor.create(document.querySelector(selector))
                .catch(error => console.error(error));
        });

        function toggleLink() {
            // Logic if needed to separate Email vs Link input types, currently just one text input
        }

        document.getElementById('keywordFilter').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            document.querySelectorAll('.keyword-item').forEach(function(item) {
                let label = item.querySelector('label').innerText.toLowerCase();
                item.style.display = label.includes(filter) ? 'flex' : 'none';
            });
        });

        function toggleWeight(checkbox, id) {
            const weightBox = document.getElementById('weight_box_' + id);
            const weightSelect = document.getElementById('weight_' + id);
            
            if (checkbox.checked) {
                weightBox.style.display = 'block';
                weightSelect.disabled = false;
            } else {
                weightBox.style.display = 'none';
                weightSelect.disabled = true;
            }
        }
    </script>
</body>
</html>
