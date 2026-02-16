<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $job->title }} at {{ optional($job->company)->name }} | Tech Media Directory</title>
    @include('partials.links')

    <style>
        .job-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 60px 0;
            border-bottom: 1px solid #cbd5e1;
        }

        .company-logo-large {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .company-logo-large img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .job-content {
            font-size: 1.05rem;
            line-height: 1.7;
            color: #334155;
        }

        .job-content h3 {
            font-weight: 700;
            color: #1e293b;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        .job-content ul {
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .job-content li {
            margin-bottom: 0.5rem;
        }

        .sidebar-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .btn-apply {
            display: block;
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            text-align: center;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
        }

        .meta-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            color: #64748b;
        }

        .meta-item i {
            width: 24px;
            color: var(--primary-accent);
            margin-right: 0.5rem;
        }
        
        .skill-tag {
            background: #eff6ff;
            color: #2563eb;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <!-- Job Header -->
    <section class="job-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-4">
                        <div class="company-logo-large me-4">
                            @if(optional($job->company)->logo_url)
                                <img src="{{ asset($job->company->logo_url) }}" alt="{{ optional($job->company)->name }}">
                            @else
                                <span class="fw-bold text-muted fs-3">{{ substr(optional($job->company)->name ?? 'C', 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <h1 class="fw-bold mb-1">{{ $job->title }}</h1>
                            <p class="text-muted mb-0 fs-5 d-flex align-items-center flex-wrap gap-2">
                                <a href="{{ route('company-profile', optional($job->company)->slug ?? '#') }}" class="text-decoration-none text-muted fw-semibold">
                                    {{ optional($job->company)->name ?? 'Confidential' }}
                                </a>
                                <span class="mx-1">•</span>
                                <span>{{ $job->job_type }}</span>
                                <span class="mx-1">•</span>
                                <span>{{ $job->created_at->diffForHumans() }}</span>
                                @if($job->is_remote)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 ms-2">Remote</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    @if($job->application_type === 'smart_apply' && auth()->user() && auth()->user()->role === 'user')
                        @auth
                            <button type="button" class="btn btn-primary btn-apply text-white mb-2" data-bs-toggle="modal" data-bs-target="#applyModal" {{auth()->user()->applications()->where('job_id', $job->id)->exists() ? 'disabled' : ''}}>
                                Apply Now <i class="fas fa-paper-plane ms-2"></i>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-apply text-white mb-2">
                                Login to Apply <i class="fas fa-sign-in-alt ms-2"></i>
                            </a>
                        @endauth
                    @else
                        <a href="{{ $job->apply_link ?? '#' }}" class="btn btn-primary btn-apply text-white mb-2" target="_blank">
                            Apply Now <i class="fas fa-external-link-alt ms-2"></i>
                        </a>
                    @endif
                </div>

    <!-- Apply Modal -->
    <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="applyModalLabel">Apply for {{ $job->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('job.apply') }}" method="POST">
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $job->id }}">
                    
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> You are applying using your <strong>Tech Media Directory Profile</strong>.
                        </div>

                        <p class="mb-3">By clicking "Confirm Apply", you agree to share your:</p>
                        <ul class="mb-4 text-muted">
                            <li>Resume & Skills Snapshot</li>
                            <li>Contact Information</li>
                            <li>Profile Match Score</li>
                        </ul>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="consent" id="consentCheck" required>
                            <label class="form-check-label" for="consentCheck">
                                I agree to share my data with <strong>{{ optional($job->company)->name }}</strong> for recruitment purposes.
                            </label>
                        </div>

                        <small class="text-muted d-block mb-3">
                            Your application data will be stored for the duration of the recruitment process.
                        </small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                
                <div class="bg-white rounded-4 p-4 p-md-5 border mb-4">
                    <h4 class="fw-bold mb-4">Job Description</h4>
                    <div class="job-content">
                        {!! $job->description !!}
                    </div>

                    @if($job->responsibilities)
                    <hr class="my-5">
                    <h4 class="fw-bold mb-4">Key Responsibilities</h4>
                    <div class="job-content">
                        {!! $job->responsibilities !!}
                    </div>
                    @endif

                    @if($job->requirements)
                    <hr class="my-5">
                    <h4 class="fw-bold mb-4">Requirements</h4>
                    <div class="job-content">
                        {!! $job->requirements !!}
                    </div>
                    @endif

                    <!-- Reviews -->
                    <hr class="my-5">
                    @include('partials.reviews', ['item' => $job])
                </div>

            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                
                <!-- Job Overview -->
                <div class="sidebar-card">
                    <!-- Smart Match Section -->
                    @auth
                        @if(isset($matchScore))
                            <div class="mb-4 pb-4 border-bottom">
                                <h5 class="fw-bold mb-3">Smart Match</h5>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="progress flex-grow-1" style="height: 10px; border-radius: 5px;">
                                        <div class="progress-bar {{ $matchScore >= 80 ? 'bg-success' : ($matchScore >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                             role="progressbar" 
                                             style="width: {{ $matchScore }}%;" 
                                             aria-valuenow="{{ $matchScore }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"></div>
                                    </div>
                                    <span class="ms-3 fw-bold {{ $matchScore >= 80 ? 'text-success' : ($matchScore >= 50 ? 'text-warning' : 'text-danger') }}">
                                        {{ $matchScore }}%
                                    </span>
                                </div>
                                <small class="text-muted">Based on your default resume profile.</small>
                            </div>
                        @endif
                    @else
                        <div class="mb-4 pb-4 border-bottom">
                             <h5 class="fw-bold mb-2">Smart Match</h5>
                             <p class="small text-muted mb-2">Log in to see if your profile matches this job.</p>
                             <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100">Login to Check Match</a>
                        </div>
                    @endauth

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Job Overview</h5>
                        @include('partials.save-button', ['item' => $job])
                    </div>
                    
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <div>
                            <small class="d-block text-muted">Posted Date</small>
                            <span class="fw-semibold">{{ $job->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <small class="d-block text-muted">Location</small>
                            <span class="fw-semibold">
                                @if($job->is_remote)
                                    Remote
                                @else
                                    {{ $job->city->name ?? (optional($job->company)->address ?? (optional(optional($job->company)->city)->name ?? 'Not specified')) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <small class="d-block text-muted">Company Address</small>
                            <span class="fw-semibold">
                                {{ $job->company->address }}
                            </span>
                        </div>
                    </div>

                    @if($job->salary_range)
                    <div class="meta-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <div>
                            <small class="d-block text-muted">Salary</small>
                            <span class="fw-semibold">{{ $job->salary_range }}</span>
                        </div>
                    </div>
                    @endif

                    @if($job->experience_level)
                    <div class="meta-item">
                        <i class="fas fa-briefcase"></i>
                        <div>
                            <small class="d-block text-muted">Experience</small>
                            <span class="fw-semibold">{{ $job->experience_level }}</span>
                        </div>
                    </div>
                    @endif

                    @if($job->education_level)
                    <div class="meta-item">
                        <i class="fas fa-graduation-cap"></i>
                        <div>
                            <small class="d-block text-muted">Education</small>
                            <span class="fw-semibold">{{ $job->education_level }}</span>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Skills -->
                @if($job->tools->count() > 0)
                <div class="sidebar-card">
                    <h5 class="fw-bold mb-3">Tools & Technologies</h5>
                    <div>
                        @foreach($job->tools as $tool)
                            <span class="skill-tag"><i class="{{ $tool->icon_class }}"></i> {{ $tool->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Company Info -->
                <div class="sidebar-card text-center">
                    <div class="mx-auto mb-3" style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        @if(optional($job->company)->logo_url)
                            <img src="{{ asset($job->company->logo_url) }}" style="width:100%; height:100%; object-fit:contain;">
                        @else
                            <span class="fw-bold text-muted">{{ substr(optional($job->company)->name ?? 'C', 0, 1) }}</span>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-1">{{ optional($job->company)->name }}</h5>
                    <p class="text-muted small mb-3">
                        {{ Str::limit(optional($job->company)->tagline, 60) }}
                    </p>
                    <a target="_blank" href="{{ route('company-profile', optional($job->company)->slug ?? '#') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100">View Company Profile</a>
                </div>

            </div>
        </div>
    </div>

    @include('partials.footer')
    @include('partials.scripts-reviews-save')
</body>
</html>
