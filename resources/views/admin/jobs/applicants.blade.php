@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0">Applicants: {{ $job->title }}</h1>
            <p class="text-muted"><i class="fas fa-building me-1"></i> {{ optional($job->company)->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Jobs
            </a>
        </div>
    </div>

    @if($applications->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <div class="mb-3 display-4 text-muted opacity-25">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="fw-bold text-muted">No Applications Yet</h4>
                <p class="text-muted">There are no candidates for this job position at the moment.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 ps-4">Candidate</th>
                                <th class="py-3">Match Score</th>
                                <th class="py-3">Applied Date</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                {{ substr($application->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ $application->user->name }}</div>
                                                <small class="text-muted">{{ $application->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px; width: 60px;">
                                                <div class="progress-bar {{ $application->match_score >= 80 ? 'bg-success' : ($application->match_score >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $application->match_score }}%" 
                                                     aria-valuenow="{{ $application->match_score }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100"></div>
                                            </div>
                                            <span class="fw-bold {{ $application->match_score >= 80 ? 'text-success' : ($application->match_score >= 50 ? 'text-warning' : 'text-danger') }}">
                                                {{ $application->match_score }}%
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-muted">
                                        {{ $application->created_at->format('M d, Y') }}
                                        <small class="d-block">{{ $application->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.applications.update', $application->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm border-0 bg-transparent fw-bold {{ match($application->status) {
                                                'applied' => 'text-info',
                                                'shortlisted' => 'text-primary',
                                                'interviewing' => 'text-warning',
                                                'hired' => 'text-success',
                                                'rejected' => 'text-danger',
                                                'withdrawn' => 'text-secondary',
                                                default => 'text-muted'
                                            } }}" onchange="this.form.submit()">
                                                <option value="applied" {{ $application->status == 'applied' ? 'selected' : '' }}>Applied</option>
                                                <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                                <option value="interviewing" {{ $application->status == 'interviewing' ? 'selected' : '' }}>Interviewing</option>
                                                <option value="hired" {{ $application->status == 'hired' ? 'selected' : '' }}>Hired</option>
                                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.applications.show', $application->id) }}" class="btn btn-sm btn-outline-primary">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($applications->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $applications->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
