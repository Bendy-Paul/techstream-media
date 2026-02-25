@extends('layouts.company')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-0 fw-bold">Manage Articles</h2>
        <p class="text-muted small mb-0">News and blog posts published under your company.</p>
    </div>
    <a href="{{ route('company.articles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Write Article
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Title</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Date</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $article->title }}</div>
                        </td>
                        <td>
                            @php $statusColor = ['published' => 'success', 'draft' => 'secondary'][$article->status] ?? 'secondary'; @endphp
                            <span class="badge bg-{{ $statusColor }}">{{ ucfirst($article->status) }}</span>
                        </td>
                        <td>
                            @if($article->is_featured)
                                <i class="fas fa-star text-warning" title="Featured"></i>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $article->created_at->format('M d, Y') }}</small></td>
                        <td class="text-end pe-4">
                            <a href="{{ route('company.articles.edit', $article->id) }}" class="btn btn-sm btn-outline-secondary me-1">
                                <i class="fas fa-pen me-1"></i> Edit
                            </a>
                            <form action="{{ route('company.articles.destroy', $article->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this article? This cannot be undone.')">
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
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="fas fa-newspaper fa-2x mb-3 d-block text-light"></i>
                            No articles yet. <a href="{{ route('company.articles.create') }}">Write your first one</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $articles->links() }}
</div>
@endsection
