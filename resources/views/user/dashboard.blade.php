@extends('layouts.user')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="h4 mb-0">Welcome back, {{ auth()->user()->name }}!</h2>
        <p class="text-muted">Here's what's happening with your account today.</p>
        
        <!-- Resume Builder Promo -->
        <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden position-relative mb-4">
            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                <i class="fas fa-file-alt fa-5x"></i>
            </div>
            <div class="card-body position-relative z-1 p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="fw-bold mb-2">Build Your Professional Resume</h3>
                        <p class="mb-3 opacity-90">Create a standout resume in minutes with our builder. 
                            @if(auth()->user()->isPremium())
                                You have <strong>Premium Access</strong> (Up to 5 resumes).
                            @else
                                Free plan includes 1 resume. Upgrade for more.
                            @endif
                        </p>
                        <div class="d-flex align-items-center gap-3">
                            @if($resumeCount < $resumeLimit)
                                <a href="{{ route('user.resumes.create') }}" class="btn btn-light text-primary fw-bold px-4">
                                    <i class="fas fa-plus me-2"></i>Create New Resume
                                </a>
                            @else
                                <button class="btn btn-light text-primary fw-bold px-4 opacity-75" disabled>
                                    <i class="fas fa-lock me-2"></i>Limit Reached
                                </button>
                                @if(!auth()->user()->isPremium())
                                    <a href="#" class="btn btn-outline-light fw-bold">Upgrade to Premium</a>
                                @endif
                            @endif
                            <span class="ms-2 border-start border-white border-opacity-25 ps-3">
                                <strong>{{ $resumeCount }}</strong> / {{ $resumeLimit }} Resumes Used
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Progress Bar at bottom -->
            <div class="progress" style="height: 6px; border-radius: 0;">
                <div class="progress-bar bg-white" role="progressbar" 
                     style="width: {{ ($resumeCount / $resumeLimit) * 100 }}%; opacity: 0.8;" 
                     aria-valuenow="{{ $resumeCount }}" aria-valuemin="0" aria-valuemax="{{ $resumeLimit }}"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Stats Cards -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary me-3">
                        <i class="fas fa-bookmark fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-0">Saved Items</h6>
                        <h3 class="fw-bold mb-0">{{ $savedItemsCount }}</h3>
                    </div>
                </div>
                <a href="{{ route('user.saved-items') }}" class="small text-decoration-none">View Details <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success me-3">
                        <i class="fas fa-briefcase fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-0">Applications</h6>
                        <h3 class="fw-bold mb-0">{{ $applicationsCount }}</h3>
                    </div>
                </div>
                <a href="{{ route('user.applications.index') }}" class="small text-decoration-none">View History <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info me-3">
                        <i class="fas fa-bell fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-0">Notifications</h6>
                        <h3 class="fw-bold mb-0">3</h3>
                    </div>
                </div>
                <a href="#" class="small text-decoration-none">View All <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div> --}}
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($activities as $activity)
                        <li class="list-group-item px-0 py-3 d-flex align-items-center {{ !$loop->last ? 'border-bottom' : '' }}">
                            @php
                                $icon = 'fas fa-circle';
                                $color = 'text-secondary';
                                $text = 'Activity';
                                $subtext = '';

                                switch($activity->type) {
                                    case 'job_applied':
                                        $icon = 'fas fa-briefcase';
                                        $color = 'text-primary';
                                        $text = 'Applied for ' . ($activity->meta['job_title'] ?? 'a Job');
                                        $subtext = $activity->meta['company_name'] ?? '';
                                        break;
                                    case 'jobapplication_updated':
                                        $icon = 'fas fa-briefcase';
                                        $color = 'text-primary';
                                        $text = 'Status updated for ' . ($activity->meta['job_title'] ?? 'a Job');
                                        $subtext = $activity->meta['company_name'] ?? '';
                                        break;
                                    case 'jobapplication_deleted':
                                        $icon = 'fas fa-briefcase';
                                        $color = 'text-primary';
                                        $text = 'Application deleted for ' . ($activity->meta['job_title'] ?? 'a Job');
                                        $subtext = $activity->meta['company_name'] ?? '';
                                        break;
                                    case 'job_saved':
                                        $icon = 'fas fa-bookmark';
                                        $color = 'text-warning';
                                        $text = 'Saved ' . ($activity->meta['title'] ?? 'a Job');
                                        $subtext = 'Saved to collection';
                                        break;
                                    case 'event_saved':
                                        $icon = 'fas fa-calendar-alt';
                                        $color = 'text-success';
                                        $text = 'Saved ' . ($activity->meta['title'] ?? 'a Job');
                                        $subtext = 'Saved to collection';
                                        break;
                                    case 'company_saved':
                                        $icon = 'fas fa-building';
                                        $color = 'text-primary';
                                        $text = 'Saved ' . ($activity->meta['title'] ?? 'a Job');
                                        $subtext = 'Saved to collection';
                                        break;
                                    case 'resume_updated':
                                        $icon = 'fas fa-file-alt';
                                        $color = 'text-info';
                                        $text = 'Updated Resume';
                                        $subtext = $activity->subject->title ?? 'Resume';
                                        break;
                                    case 'user_updated': // Assuming profile update logs this
                                        $icon = 'fas fa-user-edit';
                                        $color = 'text-success';
                                        $text = 'Updated Profile Information';
                                        $subtext = 'Personal Details';
                                        break;
                                    case 'saveditem_deleted':
                                        $icon = 'fas fa-trash';
                                        $color = 'text-danger';
                                        $text = 'Deleted ' . ($activity->meta['title'] ?? 'a Job');
                                        $subtext = 'Deleted from collection';
                                        break;
                                    default:
                                        $text = ucfirst(str_replace('_', ' ', $activity->type));
                                }
                            @endphp
                            
                            <div class="bg-light p-2 rounded me-3 text-center" style="width: 40px;">
                                <i class="{{ $icon }} {{ $color }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $text }}</h6>
                                <small class="text-muted">{{ $subtext }}</small>
                            </div>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </li>
                    @empty
                        <li class="list-group-item px-0 py-3 text-center text-muted">
                            No recent activity.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" class="rounded-circle mb-3" width="80" alt="Profile">
                <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                <p class="text-muted small mb-3">{{ auth()->user()->email }}</p>
                <div class="d-grid">
                    <a href="{{ route('user.settings') }}" class="btn btn-outline-primary btn-sm">Edit Profile</a>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="card-title mb-0">Recommended for You</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action border-0 px-4 py-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">React Developer</h6>
                            <small class="text-muted">New</small>
                        </div>
                        <p class="mb-1 small text-muted">Remote • Full-time</p>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action border-0 px-4 py-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Product Designer</h6>
                        </div>
                        <p class="mb-1 small text-muted">Lagos • Contract</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
