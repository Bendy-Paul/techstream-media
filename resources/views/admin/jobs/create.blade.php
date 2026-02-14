<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Job</title>

    <!-- jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
</head>

<body>
    @include('admin.partials.header')
    @include('admin.partials.sidebar')
    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Post New Job</h2>
                <p class="text-muted mb-0">Create a new job listing.</p>
            </div>
            <a href="jobs.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Back</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.jobs.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- MAIN CONTENT -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">

                            <!-- Job Title -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Job Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>

                            <!-- Company -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Company</label>
                                <div class="input-group">
                                    <select name="company_id" id="companySelect" class="form-select">
                                        <option value="">-- Select company --</option>
                                        @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ e($company->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Job Description -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Job Description</label>
                                <textarea name="description" class="form-control" rows="6" required></textarea>
                            </div>

                            <!-- Job Summary -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">
                                    Job Summary <span class="text-muted">(short overview)</span>
                                </label>
                                <textarea name="summary" class="form-control" rows="3" maxlength="300" required></textarea>
                            </div>

                            <!-- Responsibilities -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">
                                    Responsibilities <span class="text-muted">(one per line)</span>
                                </label>
                                <textarea name="responsibilities" class="form-control" rows="5"
                                    placeholder="- Build UI components&#10;- Collaborate with backend team"></textarea>
                            </div>

                            <!-- Requirements -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">
                                    Requirements <span class="text-muted">(skills & experience)</span>
                                </label>
                                <textarea name="requirements" class="form-control" rows="5"
                                    placeholder="- 3+ years experience&#10;- Strong PHP/Laravel knowledge"></textarea>
                            </div>

                                                        <!-- Skills (Tools) -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Tools & Technologies</label>
                                <input type="text" id="toolFilter" class="form-control mb-2" placeholder="Filter tools...">
                                <div class="d-flex flex-wrap g-2 border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                    @foreach($tools as $tool)
                                    <div class="tool-check me-2 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tool_ids[]" value="{{ $tool->id }}" id="tool_{{ $tool->id }}">
                                            <label class="form-check-label small" for="tool_{{ $tool->id }}">
                                                <i class="{{ $tool->icon_class }} me-1 text-muted"></i> {{ ($tool->name) }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
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
                                    <div class="keyword-item d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                        <div class="form-check">
                                            <!-- Checkbox to toggle inclusion -->
                                            <input class="form-check-input keyword-check" type="checkbox" 
                                                   name="keywords[{{ $keyword->id }}]" 
                                                   value="1" 
                                                   id="keyword_{{ $keyword->id }}"
                                                   onchange="toggleWeight(this, {{ $keyword->id }})">
                                            <label class="form-check-label" for="keyword_{{ $keyword->id }}">
                                                {{ $keyword->name }}
                                            </label>
                                        </div>
                                        
                                        <!-- Weight Selector (Hidden by default or disabled) -->
                                        <div class="weight-selector" id="weight_box_{{ $keyword->id }}" style="display:none;">
                                            <select name="keywords[{{ $keyword->id }}]" id="weight_{{ $keyword->id }}" class="form-select form-select-sm" disabled>
                                                <option value="1">Nice to have (1)</option>
                                                <option value="2">Preferred (2)</option>
                                                <option value="3">Required (3)</option>
                                            </select>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="form-text small">
                                    Select keywords and assign importance. 
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">Job Details</h6>
                        </div>

                        <div class="card-body">

                            <!-- Job Type -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Employment Type</label>
                                <select name="job_type" class="form-select">
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Internship">Internship</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Education Level</label>
                                <select name="education_level" class="form-select">
                                    <option value="">Not specified</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="Bachelors">Bachelor's</option>
                                    <option value="Masters">Master's</option>
                                    <option value="PhD">PhD</option>
                                </select>
                            </div>

                            <!-- Location -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Location</label>

                                <select name="location_id" id="locationSelect" class="form-select">
                                    <option value="">Select Location</option>
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }} - {{ $city->state->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_remote" value="1" id="remoteCheck">
                                <label class="form-check-label" for="remoteCheck">Remote</label>
                            </div>

                            <!-- Experience Level -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Experience Level</label>
                                <select name="experience_level" class="form-select">
                                    <option value="Entry">Entry</option>
                                    <option value="Mid" selected>Mid</option>
                                    <option value="Senior">Senior</option>
                                    <option value="Lead">Lead</option>
                                </select>
                            </div>

                            <!-- Salary -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Salary Range (optional)</label>
                                <input type="text" name="salary_range" class="form-control" placeholder="₦150k - ₦250k">
                            </div>

                            <!-- APPLICATION METHOD -->
                            <hr>
                            <label class="form-label fw-bold small text-muted">Application Method</label>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="application_type"
                                    value="smart_apply" id="smartApply" checked>
                                <label class="form-check-label" for="smartApply">
                                    Smart Apply (recommended)
                                </label>
                            </div>

                            <!-- Smart Apply Question -->
                            <div class="mb-3">
                                <label class="form-label small text-muted">
                                    Optional screening question
                                </label>
                                <input type="text" name="screening_question" class="form-control"
                                    placeholder="Why are you a good fit for this role?">
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="application_type"
                                    value="external" id="externalApply">
                                <label class="form-check-label" for="externalApply">
                                    External Apply
                                </label>
                            </div>

                            <div class="mb-3">
                                <input type="text" name="apply_link" class="form-control"
                                    placeholder="https://company.com/careers">
                            </div>

                            <!-- Expiry -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Expiry Date</label>
                                <input type="date" name="expires_at" class="form-control"
                                    value="{{ now()->addDays(30)->format('Y-m-d') }}">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                Post Job
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </form>

    </main>
</body>
<script>
    $(document).ready(function() {
        $('#companySelect').select2({
            placeholder: 'Select company'
        });
        $('#locationSelect').select2({
            placeholder: 'Select location'
        });
    });

    document.getElementById('toolFilter').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('[id^="tool_"]').forEach(function(checkbox) {
            let label = checkbox.nextElementSibling.innerText.toLowerCase();
            checkbox.closest('.tool-check').style.display =
                label.includes(filter) ? '' : 'none';
        });
    });

    document.getElementById('keywordFilter').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('.keyword-item').forEach(function(item) {
            let label = item.querySelector('label').innerText.toLowerCase();
            item.style.display = label.includes(filter) ? 'flex' : 'none'; // Flex because of d-flex
        });
    });

    function toggleWeight(checkbox, id) {
        const weightBox = document.getElementById('weight_box_' + id);
        const weightSelect = document.getElementById('weight_' + id);
        
        if (checkbox.checked) {
            weightBox.style.display = 'block';
            weightSelect.disabled = false;
            // The checkbox value doesn't matter much if the select is enabled and has the same name,
            // BUT HTML forms with duplicate names are tricky.
            // If I give them the SAME name "keywords[id]", the last one takes precedence.
            // So if checkbox is checked, I want the SELECT to be the submitted value.
            // If checkbox is unchecked, I want NOTHING submitted.
            
            // Checkbox: name="keywords_check[id]" (dummy)
            // Select: name="keywords[id]"
            
            // Let's adjust the HTML in the previous creation block to be robust.
            // Actually, simplest way:
            // Checkbox enables/disables the Select.
            // Select has the name "keywords[id]". 
            // BUT if Select is disabled, it won't submit.
            // So if Checkbox is checked -> Select Enabled -> Submits value 1,2, or 3.
            // If Checkbox unchecked -> Select Disabled -> Nothing submitted.
            // Perfect.
        } else {
            weightBox.style.display = 'none';
            weightSelect.disabled = true;
        }
    }
</script>


</html>