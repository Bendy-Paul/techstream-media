@include('admin.partials.header')
@include('admin.partials.sidebar')

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Company Reviews</h2>
            <p class="text-muted mb-0">Manage all company reviews.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="reviewsTable" class="table table-hover align-middle w-100">
                <thead class="bg-light">
                    <tr>
                        <th>SN</th>
                        <th>Company</th>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr>
                        <td>{{ $loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>
                        <td>{{ $review->company->name ?? 'N/A' }}</td>
                        <td>{{ $review->user->name ?? 'N/A' }}</td>
                        <td>{{ $review->rating }}</td>
                        <td>{{ Str::limit($review->comment, 40) }}</td>
                        <td>
                            @if($review->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($review->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </td>
                        <td>{{ $review->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-sm btn-light text-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</main>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#reviewsTable').DataTable({
            responsive: true,
            lengthChange: true,
            pageLength: 10
        });
    });
</script>
