@extends('layouts.company')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-0 fw-bold">Manage Jobs</h2>
        <p class="text-muted small mb-0">Job listings posted by your company.</p>
    </div>
    <a href="{{ route('company.jobs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Post a Job
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Title</th>
                    <th>Type</th>
                    <th>Applicants</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $job->title }}</div>
                            <small class="text-muted">{{ $job->city?->name ?? ($job->is_remote ? 'Remote' : '—') }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $job->job_type ?? '—' }}</span></td>
                        <td>
                            @if($job->application_type === 'smart_apply')
                                @php $count = $job->applications()->count(); @endphp
                                <a href="{{ route('company.jobs.applicants', $job->id) }}"
                                   class="btn btn-sm {{ $count > 0 ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    <i class="fas fa-users me-1"></i> {{ $count }}
                                    {{ Str::plural('Applicant', $count) }}
                                </a>
                            @else
                                <span class="text-muted small"><i class="fas fa-external-link-alt me-1"></i> External</span>
                            @endif
                        </td>
                        <td>
                            @if($job->expires_at)
                                @php $expired = \Carbon\Carbon::parse($job->expires_at)->isPast(); @endphp
                                <span class="{{ $expired ? 'text-danger' : 'text-muted' }} small">
                                    {{ \Carbon\Carbon::parse($job->expires_at)->format('M d, Y') }}
                                    @if($expired) <span class="badge bg-danger-subtle text-danger ms-1">Expired</span> @endif
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColor = ['active' => 'success', 'draft' => 'secondary', 'closed' => 'danger'][$job->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">{{ ucfirst($job->status ?? 'draft') }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('company.jobs.edit', $job->id) }}" class="btn btn-sm btn-outline-secondary me-1">
                                <i class="fas fa-pen me-1"></i> Edit
                            </a>
                            <form action="{{ route('company.jobs.destroy', $job->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this job posting? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fas fa-briefcase fa-2x mb-3 d-block text-light"></i>
                            No jobs posted yet. <a href="{{ route('company.jobs.create') }}">Post one now</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $jobs->links() }}</div>
@endsection
