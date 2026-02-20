@extends('layouts.company')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="h4">Company Dashboard</h2>
        <p class="text-muted">Welcome to your company portal.</p>

        @if(!$company)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle mt-1 me-2"></i>
                You have not set up your company profile yet. 
                <a href="{{ route('company.profile.edit') }}" class="alert-link">Setup your profile now</a> to get started.
            </div>
        @elseif(!$company->is_verified)
            <div class="alert alert-info d-flex pb-1">
                <i class="fas fa-info-circle mt-1 me-3 fa-2x"></i>
                <div>
                    <h5>Your Company is Pending Verification</h5>
                    <p>You need to be verified to post jobs, articles, and events. Our team is reviewing your profile. If you haven't filled out all your company details, <a href="{{ route('company.profile.edit') }}" class="alert-link">update them here</a> to expedite the process.</p>
                </div>
            </div>
        @else
            <div class="alert alert-success">
                <i class="fas fa-check-circle mt-1 me-2"></i>
                Your company is verified! You can now manage your jobs, articles, and events.
            </div>
        @endif
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Active Jobs Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="card-title text-muted mb-1 text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.8rem;">Active Jobs</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $activeJobsCount ?? 0 }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="fas fa-briefcase fa-lg"></i>
                    </div>
                </div>
                <p class="text-sm text-muted mb-0"><small>Currently live postings</small></p>
            </div>
            <div class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 4px;"></div>
        </div>
    </div>

    <!-- Previous Jobs Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="card-title text-muted mb-1 text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.8rem;">Previous Jobs</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $previousJobsCount ?? 0 }}</h3>
                    </div>
                    <div class="bg-secondary bg-opacity-10 p-3 rounded-circle text-secondary">
                        <i class="fas fa-history fa-lg"></i>
                    </div>
                </div>
                <p class="text-sm text-muted mb-0"><small>Closed or expired</small></p>
            </div>
            <div class="position-absolute bottom-0 start-0 w-100 bg-secondary" style="height: 4px;"></div>
        </div>
    </div>

    <!-- Total Applicants Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="card-title text-muted mb-1 text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.8rem;">Total Applicants</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalApplicants ?? 0 }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
                <p class="text-sm text-muted mb-0"><small>Across all jobs</small></p>
            </div>
            <div class="position-absolute bottom-0 start-0 w-100 bg-success" style="height: 4px;"></div>
        </div>
    </div>

    <!-- Total Events Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="card-title text-muted mb-1 text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.8rem;">Total Events</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalEvents ?? 0 }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                        <i class="fas fa-calendar-alt fa-lg"></i>
                    </div>
                </div>
                <p class="text-sm text-muted mb-0"><small>Organized or partnered</small></p>
            </div>
            <div class="position-absolute bottom-0 start-0 w-100 bg-info" style="height: 4px;"></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Recent Activities -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="card-title fw-bold mb-0">Recent Activities</h5>
            </div>
            <div class="card-body">
                @if(isset($activities) && $activities->count() > 0)
                    <div class="timeline">
                        @foreach($activities as $activity)
                            <div class="timeline-item d-flex pb-3 mb-3 border-bottom">
                                <div class="timeline-icon bg-light text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div>
                                    <p class="mb-1 text-dark">{{ $activity->description }}</p>
                                    <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                        <p>No recent activities found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
