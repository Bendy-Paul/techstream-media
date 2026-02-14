@extends('layouts.user')

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-end mb-5">
        <div class="col-lg-8">
            <h1 class="display-6 fw-bold text-dark mb-1">Your Collection</h1>
            <p class="text-muted fs-5 mb-0">Everything you've bookmarked, organized in one place.</p>
        </div>
        <div class="col-lg-4 text-lg-end d-none d-lg-block">
            <div class="d-inline-flex gap-3">
                <div class="text-center">
                    <div class="fw-bold text-dark">{{ count($savedJobs) }}</div>
                    <small class="text-muted">Jobs</small>
                </div>
                <div class="vr"></div>
                <div class="text-center">
                    <div class="fw-bold text-dark">{{ count($savedCompanies) }}</div>
                    <small class="text-muted">Companies</small>
                </div>
                <div class="vr"></div>
                <div class="text-center">
                    <div class="fw-bold text-dark">{{ count($savedEvents) }}</div>
                    <small class="text-muted">Events</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-5">
            <div class="segmented-control p-1 bg-light rounded-pill d-inline-flex">
                <button class="control-item active" data-filter="jobs">
                    <i class="fas fa-briefcase me-2"></i>Jobs
                </button>
                <button class="control-item" data-filter="companies">
                    <i class="fas fa-building me-2"></i>Companies
                </button>
                <button class="control-item" data-filter="events">
                    <i class="fas fa-calendar-day me-2"></i>Events
                </button>
            </div>
        </div>

        <div class="col-12">
            <div class="filter-section" id="jobs-section">
                <div class="row g-4">
                    @forelse($savedJobs as $job)
                        <div class="col-xl-4 col-md-6">
                            <div class="modern-card job-card h-100">
                                <div class="card-content">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="company-logo-sm">
                                            {{ substr($job->company->name ?? 'U', 0, 1) }}
                                        </div>
                                        @include('partials.save-button', ['item' => $job])
                                    </div>
                                    <h5 class="fw-bold"><a href="{{ route('job.show', $job->slug) }}" class="text-dark text-decoration-none stretched-link">{{ $job->title }}</a></h5>
                                    <p class="text-muted small mb-3">{{ $job->company->name ?? 'Unknown' }} • {{ $job->city->name ?? 'Remote' }}</p>
                                    <div class="d-flex gap-2">
                                        <span class="badge-modern">New</span>
                                        <span class="badge-modern">{{ $job->type ?? 'Full-time' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">No saved jobs found.</div>
                    @endforelse
                </div>
            </div>

            <div class="filter-section d-none" id="companies-section">
                <div class="row g-4">
                    @foreach($savedCompanies as $company)
                        <div class="col-md-6 col-lg-3">
                            <div class="modern-card text-center py-4">
                                <img src="{{ $company->logo_url ?? 'https://placehold.co/60' }}" class="rounded-circle mb-3" width="64">
                                <h6 class="fw-bold mb-1">{{ $company->name }}</h6>
                                <p class="small text-muted mb-3">{{ $company->city->name ?? 'Global' }}</p>
                                <div class="px-3">@include('partials.save-button', ['item' => $company])</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="filter-section d-none" id="events-section">
                <div class="row g-4">
                    @foreach($savedEvents as $event)
                        <div class="col-lg-6">
                            <div class="modern-card d-flex p-0 overflow-hidden">
                                <div class="event-date-box bg-dark text-white d-flex flex-column justify-content-center px-4 text-center">
                                    <span class="h4 mb-0 fw-bold">{{ \Carbon\Carbon::parse($event->start_datetime)->format('d') }}</span>
                                    <span class="small text-uppercase opacity-75">{{ \Carbon\Carbon::parse($event->start_datetime)->format('M') }}</span>
                                </div>
                                <div class="p-4 flex-grow-1">
                                    <h5 class="fw-bold mb-1">{{ $event->title }}</h5>
                                    <p class="small text-muted mb-0"><i class="fas fa-map-marker-alt me-1"></i>{{ $event->location_name }}</p>
                                </div>
                                <div class="p-4">@include('partials.save-button', ['item' => $event])</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Segmented Control Styles */
    .segmented-control { border: 1px solid #eef0f2; }
    .control-item {
        border: none;
        background: transparent;
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        color: #6c757d;
        transition: all 0.2s ease;
    }
    .control-item.active {
        background: #fff;
        color: #000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Modern Card Styles */
    .modern-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 1.25rem;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        border-color: #0d6efd33;
    }

    .company-logo-sm {
        width: 40px;
        height: 40px;
        background: #f8f9fa;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #0d6efd;
    }

    .badge-modern {
        background: #f1f4f9;
        color: #4a5568;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .event-date-box { min-width: 100px; }
</style>

<script>
    document.querySelectorAll('.control-item').forEach(button => {
        button.addEventListener('click', () => {
            // Toggle Buttons
            document.querySelectorAll('.control-item').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Toggle Sections
            const filter = button.getAttribute('data-filter');
            document.querySelectorAll('.filter-section').forEach(section => {
                section.classList.add('d-none');
                if (section.id === `${filter}-section`) {
                    section.classList.remove('d-none');
                }
            });
        });
    });
</script>

@include('partials.scripts-reviews-save')
@endsection