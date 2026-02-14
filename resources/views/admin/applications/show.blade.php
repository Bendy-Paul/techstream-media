@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0">Application Details</h1>
            <p class="text-muted">
                {{ $application->user->name }} for <span class="fw-semibold">{{ $application->job->title }}</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.applications.index', ['job_id' => $application->job_id]) }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
            
            <form action="{{ route('admin.applications.update', $application->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Status: {{ ucfirst($application->status) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><button class="dropdown-item" type="submit" name="status" value="shortlisted">Mark as Shortlisted</button></li>
                        <li><button class="dropdown-item" type="submit" name="status" value="interviewing">Mark as Interviewing</button></li>
                        <li><button class="dropdown-item" type="submit" name="status" value="hired">Mark as Hired</button></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><button class="dropdown-item text-danger" type="submit" name="status" value="rejected">Mark as Rejected</button></li>
                    </ul>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Cover Letter / Answers -->
            @if($application->cover_letter || $application->answers)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Application Questions</h5>
                </div>
                <div class="card-body">
                    @if($application->cover_letter)
                        <h6 class="fw-bold">Cover Letter</h6>
                        <p class="text-muted mb-4">{{ $application->cover_letter }}</p>
                    @endif

                    @if($application->answers)
                        <h6 class="fw-bold">Screening Questions</h6>
                        <ul class="list-unstyled">
                            @foreach($application->answers as $question => $answer)
                                <li class="mb-3">
                                    <p class="fw-semibold mb-1">{{ $question }}</p>
                                    <p class="text-muted mb-0">{{ $answer }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            @endif

            <!-- Resume Snapshot -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Resume Snapshot</h5>
                    <span class="badge bg-light text-dark">Captured on {{ $application->created_at->format('M d, Y') }}</span>
                </div>
                <div class="card-body">
                    @php
                        $snapshot = $application->resume_snapshot ?? [];
                        // Fallback handling if snapshot is empty but relation exists (legacy/dev support)
                        if (empty($snapshot) && $application->resume) {
                             $snapshot = $application->resume->toArray();
                        }
                    @endphp

                    @if(empty($snapshot))
                        <div class="alert alert-warning">
                            No resume snapshot data available. The resume might have been deleted or not captured correctly.
                        </div>
                    @else
                        <div class="mb-4">
                            <h4 class="fw-bold">{{ $snapshot['title'] ?? 'No Title' }}</h4>
                            <p class="text-muted">{{ $snapshot['summary'] ?? 'No Summary' }}</p>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Skills</h6>
                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    @foreach($snapshot['skills'] ?? [] as $skill)
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10">
                                            {{ $skill['name'] ?? 'Skill' }}
                                        </span>
                                    @endforeach
                                    @if(empty($snapshot['skills']))
                                        <p class="text-muted small">No skills listed.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(!empty($snapshot['experiences']))
                            <h6 class="fw-bold mb-3">Experience</h6>
                            <div class="timeline">
                                @foreach($snapshot['experiences'] as $exp)
                                    <div class="mb-3 pb-3 border-bottom last-no-border">
                                        <h6 class="fw-bold mb-1">{{ $exp['title'] ?? 'Role' }}</h6>
                                        <p class="text-muted small mb-1">{{ $exp['company'] ?? 'Company' }} • {{ $exp['start_date'] ?? '' }} - {{ $exp['end_date'] ?? 'Present' }}</p>
                                        <p class="mb-0 text-secondary">{{ $exp['description'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(!empty($snapshot['education']))
                            <h6 class="fw-bold mt-4 mb-3">Education</h6>
                            <div>
                                @foreach($snapshot['education'] as $edu)
                                    <div class="mb-2">
                                        <h6 class="fw-bold mb-0">{{ $edu['degree'] ?? 'Degree' }}</h6>
                                        <p class="text-muted small mb-0">{{ $edu['school'] ?? 'School' }} • {{ $edu['graduation_date'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Candidate Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body text-center">
                    <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($application->user->name, 0, 1) }}
                    </div>
                    <h5 class="fw-bold mb-0">{{ $application->user->name }}</h5>
                    <p class="text-muted mb-3">{{ $application->user->email }}</p>
                    
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $application->user->email }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i> Contact Candidate
                        </a>
                    </div>
                </div>
            </div>

            <!-- Match Score Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Match Analysis</h6>
                    <div class="d-flex align-items-center mb-3">
                         <div class="display-4 fw-bold {{ $application->match_score >= 80 ? 'text-success' : ($application->match_score >= 50 ? 'text-warning' : 'text-danger') }} me-3">
                             {{ $application->match_score }}%
                         </div>
                         <div class="text-muted small line-height-sm">
                             Match Score based on skills & keywords
                         </div>
                    </div>
                    
                    @if(!empty($application->match_details))
                        <hr>
                        <small class="fw-bold text-uppercase text-muted d-block mb-2">Details</small>
                        <!-- Iterate match details if we implement detailed breakdown later -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
