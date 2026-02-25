@extends('layouts.company')

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="{{ route('company.jobs.index') }}">Jobs</a></li>
                <li class="breadcrumb-item active">Applicants</li>
            </ol>
        </nav>
        <h2 class="h4 fw-bold mb-0">{{ $job->title }}</h2>
        <p class="text-muted small mb-0">
            <i class="fas fa-users me-1"></i> {{ $metrics['total'] }} total applicants
            @if($job->city) · <i class="fas fa-map-marker-alt me-1"></i>{{ $job->city->name }} @endif
            · <span class="badge bg-light text-dark border">{{ $job->job_type }}</span>
        </p>
    </div>
    <a href="{{ route('company.jobs.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to Jobs
    </a>
</div>

{{-- Metrics Cards --}}
<div class="row g-3 mb-4">
    @php
        $cards = [
            ['label' => 'New', 'value' => $metrics['new'], 'icon' => 'fas fa-inbox', 'color' => 'primary', 'filter' => 'applied'],
            ['label' => 'Shortlisted', 'value' => $metrics['shortlisted'], 'icon' => 'fas fa-star', 'color' => 'warning', 'filter' => 'shortlisted'],
            ['label' => 'Interviewing', 'value' => $metrics['interviewing'], 'icon' => 'fas fa-comments', 'color' => 'info', 'filter' => 'interviewing'],
            ['label' => 'Hired', 'value' => $metrics['hired'], 'icon' => 'fas fa-check-circle', 'color' => 'success', 'filter' => 'hired'],
            ['label' => 'Rejected', 'value' => $metrics['rejected'], 'icon' => 'fas fa-times-circle', 'color' => 'danger', 'filter' => 'rejected'],
            ['label' => 'Avg Score', 'value' => $metrics['avg_score'].'%', 'icon' => 'fas fa-chart-bar', 'color' => 'secondary', 'filter' => null],
        ];
    @endphp
    @foreach($cards as $card)
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ $card['filter'] ? request()->fullUrlWithQuery(['status' => $card['filter']]) : '#' }}"
               class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 {{ $status == $card['filter'] ? 'border-' . $card['color'] . ' border' : '' }} position-relative overflow-hidden">
                    <div class="card-body p-3 text-center">
                        <div class="text-{{ $card['color'] }} mb-1"><i class="{{ $card['icon'] }}"></i></div>
                        <div class="h4 fw-bold mb-0 text-dark">{{ $card['value'] }}</div>
                        <div class="small text-muted">{{ $card['label'] }}</div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 bg-{{ $card['color'] }}" style="height:3px;"></div>
                </div>
            </a>
        </div>
    @endforeach
</div>

{{-- Filters & Sort Bar --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3 d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <span class="text-muted small fw-semibold me-1">Filter:</span>
            @foreach(['applied' => 'New', 'shortlisted' => 'Shortlisted', 'interviewing' => 'Interviewing', 'offered' => 'Offered', 'hired' => 'Hired', 'rejected' => 'Rejected'] as $val => $label)
                <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'page' => 1]) }}"
                   class="btn btn-sm {{ $status == $val ? 'btn-primary' : 'btn-outline-secondary' }}">
                    {{ $label }}
                </a>
            @endforeach
            @if($status)
                <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => 1]) }}" class="btn btn-sm btn-link text-muted ps-0">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            @endif
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="text-muted small fw-semibold">Sort:</span>
            @foreach(['created_at' => 'Date Applied', 'match_score' => 'Match Score', 'status' => 'Status'] as $col => $label)
                @php
                    $newDir = ($sort === $col && $direction === 'asc') ? 'desc' : 'asc';
                    $icon = $sort === $col ? ($direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort';
                @endphp
                <a href="{{ request()->fullUrlWithQuery(['sort' => $col, 'direction' => $newDir, 'page' => 1]) }}"
                   class="btn btn-sm {{ $sort === $col ? 'btn-secondary' : 'btn-outline-secondary' }}">
                    <i class="fas {{ $icon }} me-1"></i>{{ $label }}
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Bulk Action Form wraps the table --}}
<form action="{{ route('company.jobs.applicants.bulk-update', $job->id) }}" method="POST" id="bulkForm">
    @csrf

    <div class="card border-0 shadow-sm mb-3">
        {{-- Bulk Actions Toolbar --}}
        <div class="card-header bg-white py-2 d-flex gap-2 align-items-center" id="bulkToolbar" style="display:none!important;">
            <span class="small text-muted me-auto"><span id="selectedCount">0</span> selected</span>
            <span class="text-muted small">Change status to:</span>
            @foreach(['shortlisted' => 'Shortlist', 'interviewing' => 'Interviewing', 'offered' => 'Offer', 'hired' => 'Hired', 'rejected' => 'Reject'] as $val => $label)
                <button type="submit" name="status" value="{{ $val }}"
                        class="btn btn-sm {{ $val === 'rejected' ? 'btn-outline-danger' : 'btn-outline-primary' }}"
                        onclick="return confirmBulk('{{ $label }}')">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width:3%">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>Candidate</th>
                        <th style="width:110px">Score</th>
                        <th>Status</th>
                        <th>Applied</th>
                        <th>Cover Letter</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        @php
                            $score = $application->match_score ?? 0;
                            $scoreColor = $score >= 80 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
                            $statusColors = [
                                'applied'      => 'primary',
                                'shortlisted'  => 'warning',
                                'interviewing' => 'info',
                                'offered'      => 'purple',
                                'hired'        => 'success',
                                'rejected'     => 'danger',
                                'withdrawn'    => 'secondary',
                            ];
                            $badgeColor = $statusColors[$application->status] ?? 'secondary';
                            $snapshot = $application->resume_snapshot ?? [];
                        @endphp
                        <tr>
                            {{-- Checkbox --}}
                            <td class="ps-4">
                                <input type="checkbox" name="application_ids[]" value="{{ $application->id }}"
                                       class="form-check-input app-checkbox">
                            </td>

                            {{-- Candidate --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width:40px;height:40px;font-size:1rem;font-weight:700;">
                                        {{ strtoupper(substr($application->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $application->user->name ?? '—' }}</div>
                                        <small class="text-muted">{{ $application->user->email ?? '' }}</small>
                                        @if(!empty($snapshot['title']))
                                            <div><span class="badge bg-light text-dark border small">{{ $snapshot['title'] }}</span></div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Score --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:6px;width:60px;">
                                        <div class="progress-bar bg-{{ $scoreColor }}" style="width:{{ $score }}%"></div>
                                    </div>
                                    <span class="fw-bold text-{{ $scoreColor }} small">{{ $score }}%</span>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td>
                                <span class="badge bg-{{ $badgeColor }} bg-opacity-15 text-{{ $badgeColor }} border border-{{ $badgeColor }} border-opacity-25">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>

                            {{-- Applied At --}}
                            <td>
                                <span class="text-muted small" title="{{ $application->created_at }}">
                                    {{ $application->created_at->diffForHumans() }}
                                </span>
                            </td>

                            {{-- Cover Letter --}}
                            <td>
                                @if($application->cover_letter)
                                    <button type="button" class="btn btn-sm btn-link p-0 text-muted"
                                            data-bs-toggle="tooltip" title="{{ Str::limit($application->cover_letter, 120) }}">
                                        <i class="fas fa-comment-dots"></i>
                                    </button>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-end pe-4">
                                {{-- View Resume --}}
                                <button type="button" class="btn btn-sm btn-outline-secondary me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resumeModal{{ $application->id }}"
                                        title="View Resume Snapshot">
                                    <i class="fas fa-file-user me-1"></i> Resume
                                </button>

                                {{-- Status change dropdown --}}
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-exchange-alt me-1"></i> Status
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @foreach(['applied' => 'New / Applied', 'shortlisted' => 'Shortlist', 'interviewing' => 'Interviewing', 'offered' => 'Offer', 'hired' => 'Hired', 'rejected' => 'Reject'] as $val => $label)
                                            <li>
                                                <form action="{{ route('company.jobs.applicants.update', [$job->id, $application->id]) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf @method('PUT')
                                                    <button type="submit" name="status" value="{{ $val }}"
                                                            class="dropdown-item {{ $val === 'rejected' ? 'text-danger' : '' }} {{ $application->status === $val ? 'fw-bold' : '' }}">
                                                        {{ $label }}
                                                        @if($application->status === $val) <i class="fas fa-check ms-1 text-success small"></i> @endif
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                        @if($application->user?->email)
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a href="mailto:{{ $application->user->email }}" class="dropdown-item">
                                                    <i class="fas fa-envelope me-2"></i> Email Candidate
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        {{-- ======= RESUME SNAPSHOT MODAL ======= --}}
                        <div class="modal fade" id="resumeModal{{ $application->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <div>
                                            <h5 class="modal-title fw-bold">
                                                {{ $application->user?->name ?? 'Candidate' }}'s Resume
                                            </h5>
                                            <small class="text-muted">
                                                Snapshot captured {{ $application->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body pt-2">
                                        @php $snap = $application->resume_snapshot ?? []; @endphp
                                        @if(empty($snap))
                                            <div class="alert alert-warning">No resume snapshot available.</div>
                                        @else
                                            {{-- Header --}}
                                            <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                                     style="width:56px;height:56px;font-size:1.4rem;font-weight:700;">
                                                    {{ strtoupper(substr($application->user?->name ?? '?', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h5 class="mb-0 fw-bold">{{ $snap['title'] ?? 'Resume' }}</h5>
                                                    <p class="mb-0 text-muted small">{{ $application->user?->email }}</p>
                                                    @if(!empty($snap['summary']))
                                                        <p class="mb-0 small mt-1">{{ $snap['summary'] }}</p>
                                                    @endif
                                                </div>
                                                <div class="ms-auto text-center">
                                                    <div class="fw-bold display-6 text-{{ $scoreColor }}">{{ $score }}%</div>
                                                    <div class="small text-muted">Match</div>
                                                </div>
                                            </div>

                                            {{-- Skills --}}
                                            @if(!empty($snap['skills']))
                                                <h6 class="fw-bold mb-2">Skills</h6>
                                                <div class="d-flex flex-wrap gap-2 mb-4">
                                                    @foreach($snap['skills'] as $skill)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                                                            {{ $skill['name'] ?? $skill }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Experience --}}
                                            @if(!empty($snap['experiences']))
                                                <h6 class="fw-bold mb-2">Experience</h6>
                                                @foreach($snap['experiences'] as $exp)
                                                    <div class="mb-3 pb-3 border-bottom">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="fw-semibold">{{ $exp['title'] ?? '—' }}</span>
                                                            <small class="text-muted">
                                                                {{ $exp['start_date'] ?? '' }}
                                                                — {{ $exp['end_date'] ?? 'Present' }}
                                                            </small>
                                                        </div>
                                                        <div class="small text-muted">{{ $exp['company'] ?? '' }}</div>
                                                        @if(!empty($exp['description']))
                                                            <p class="small text-secondary mt-1 mb-0">{{ $exp['description'] }}</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endif

                                            {{-- Education --}}
                                            @if(!empty($snap['education']))
                                                <h6 class="fw-bold mb-2 mt-3">Education</h6>
                                                @foreach($snap['education'] as $edu)
                                                    <div class="mb-2">
                                                        <div class="fw-semibold">{{ $edu['degree'] ?? '—' }}</div>
                                                        <div class="small text-muted">
                                                            {{ $edu['school'] ?? '' }}
                                                            @if(!empty($edu['graduation_date'])) · {{ $edu['graduation_date'] }} @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                            {{-- Screening Q&A --}}
                                            @if(!empty($application->answers))
                                                <hr>
                                                <h6 class="fw-bold mb-2">Screening Answers</h6>
                                                @foreach($application->answers as $question => $answer)
                                                    <div class="mb-3">
                                                        <p class="fw-semibold mb-1 small">{{ $question }}</p>
                                                        <p class="text-muted mb-0 small">{{ $answer }}</p>
                                                    </div>
                                                @endforeach
                                            @endif

                                            {{-- Cover Letter --}}
                                            @if($application->cover_letter)
                                                <hr>
                                                <h6 class="fw-bold mb-2">Cover Letter</h6>
                                                <p class="text-muted small">{{ $application->cover_letter }}</p>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        @if($application->user?->email)
                                            <a href="mailto:{{ $application->user->email }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-envelope me-1"></i> Email Candidate
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ======= END MODAL ======= --}}

                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-users fa-2x mb-3 d-block text-light"></i>
                                @if($status)
                                    No <strong>{{ $status }}</strong> applicants.
                                    <a href="{{ route('company.jobs.applicants', $job->id) }}">View all</a>
                                @else
                                    No applications received yet.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div>{{ $applications->links() }}</div>
        <small class="text-muted">
            Showing {{ $applications->firstItem() }}–{{ $applications->lastItem() }} of {{ $applications->total() }}
        </small>
    </div>
</form>

{{-- Top Score badge --}}
@if($metrics['top_score'] > 0)
    <div class="text-end mt-2">
        <small class="text-muted">
            <i class="fas fa-trophy text-warning me-1"></i>
            Top applicant score: <strong>{{ $metrics['top_score'] }}%</strong>
        </small>
    </div>
@endif

<script>
// ---- Select All ----
const selectAll  = document.getElementById('selectAll');
const checkboxes = document.querySelectorAll('.app-checkbox');
const toolbar    = document.getElementById('bulkToolbar');
const counter    = document.getElementById('selectedCount');

function updateToolbar() {
    const checked = document.querySelectorAll('.app-checkbox:checked').length;
    counter.textContent = checked;
    toolbar.style.display = checked > 0 ? 'flex' : 'none';
}

selectAll.addEventListener('change', function () {
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateToolbar();
});

checkboxes.forEach(cb => cb.addEventListener('change', function () {
    selectAll.checked = [...checkboxes].every(c => c.checked);
    updateToolbar();
}));

function confirmBulk(action) {
    const cnt = document.querySelectorAll('.app-checkbox:checked').length;
    if (cnt === 0) { alert('Select at least one applicant.'); return false; }
    return confirm(`Mark ${cnt} applicant(s) as "${action}"?`);
}

// Tooltips
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
        .forEach(el => new bootstrap.Tooltip(el));
});
</script>
@endsection
