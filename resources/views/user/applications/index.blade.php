@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold">My Applications</h1>
        <a href="{{ route('jobs') }}" class="btn btn-primary">Find More Jobs</a>
    </div>

    @if($applications->isEmpty())
        <div class="text-center py-5 border rounded-4 bg-light">
            <div class="mb-3 display-1 text-muted">
                <i class="fas fa-file-invoice"></i>
            </div>
            <h3 class="fw-bold text-muted">No Applications Yet</h3>
            <p class="text-muted mb-4">You haven't applied to any jobs yet. Start your search today!</p>
            <a href="{{ route('jobs') }}" class="btn btn-primary px-4">Browse Jobs</a>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 ps-4">Job Title</th>
                                <th class="border-0 py-3">Company</th>
                                <th class="border-0 py-3">Applied Date</th>
                                <th class="border-0 py-3">Status</th>
                                <th class="border-0 py-3 pe-4 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">
                                            <a href="{{ route('job.show', optional($application->job)->slug ?? '#') }}" class="text-decoration-none text-dark">
                                                {{ optional($application->job)->title ?? 'Job Removed' }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        @if(optional($application->job)->company)
                                            <a href="{{ route('company-profile', $application->job->company->slug) }}" class="text-decoration-none text-secondary">
                                                {{ $application->job->company->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        {{ $application->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($application->status) {
                                                'applied' => 'bg-info bg-opacity-10 text-info',
                                                'shortlisted' => 'bg-primary bg-opacity-10 text-primary',
                                                'interviewing' => 'bg-warning bg-opacity-10 text-warning',
                                                'hired' => 'bg-success bg-opacity-10 text-success',
                                                'rejected' => 'bg-danger bg-opacity-10 text-danger',
                                                'withdrawn' => 'bg-secondary bg-opacity-10 text-secondary',
                                                default => 'bg-light text-muted',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} border border-opacity-10 rounded-pill px-3 py-2">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        @if($application->status !== 'withdrawn' && $application->status !== 'rejected')
                                            <form action="{{ route('applications.destroy', $application->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to withdraw this application?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                    Withdraw
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm rounded-pill" disabled>
                                                {{ ucfirst($application->status) }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <!-- If we had pagination, it would go here -->
        </div>
    @endif
</div>
@endsection
