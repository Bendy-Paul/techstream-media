@extends('layouts.user')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">My Resumes</h2>
    @if($count < $limit)
        <a href="{{ route('user.resumes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Create New Resume
        </a>
    @else
        <button class="btn btn-secondary" disabled title="Limit reached">
            <i class="fas fa-lock me-2"></i> Create New Resume
        </button>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if($resumes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="py-3 ps-4">Title</th>
                                    <th scope="col" class="py-3">Visibility</th>
                                    <th scope="col" class="py-3">Created</th>
                                    <th scope="col" class="py-3 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resumes as $resume)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded me-3 text-primary">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $resume->title }}</h6>
                                                    @if($resume->is_default)
                                                        <span class="badge bg-success text-white" style="font-size: 0.7rem;">Default</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($resume->visibility === 'public')
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Public</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Private</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">
                                            {{ $resume->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="{{ route('user.resumes.show', $resume->id) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('user.resumes.edit', $resume->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('user.resumes.destroy', $resume->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this resume?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3 text-muted">
                            <i class="fas fa-file-invoice fa-3x opacity-25"></i>
                        </div>
                        <h5>No resumes yet</h5>
                        <p class="text-muted mb-4">Create your first resume to start applying for jobs.</p>
                        @if($count < $limit)
                            <a href="{{ route('user.resumes.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Create Resume
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-12">
         <div class="alert alert-info border-0 shadow-sm d-flex align-items-center" role="alert">
            <i class="fas fa-info-circle fs-4 me-3"></i>
            <div>
                You are currently using <strong>{{ $count }} / {{ $limit }}</strong> resume slots. 
                @if(!auth()->user()->isPremium())
                    <a href="#" class="fw-bold text-decoration-none">Upgrade to Premium</a> to create up to 5 resumes.
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
