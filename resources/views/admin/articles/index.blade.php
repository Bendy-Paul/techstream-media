@include('admin.partials.header')
@include('admin.partials.sidebar')

<main class="main-content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Articles</h2>
            <p class="text-muted mb-0">Manage all business listings.</p>
        </div>
        <div>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Add Article
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
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

    <!-- Companies Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="companiesTable" class="table table-hover align-middle w-100">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">SN</th>
                        <th>Image</th>
                        <th class="ps-4">Article</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Published</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $row)
                    <tr>
                        <td class="ps-4">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex bg-light rounded me-3" style="width: 50px; height: 50px; overflow:hidden;">
                                @if($row['featured_image_url'] ?? false)
                                <img src="{{ asset($row['featured_image_url']) }}" alt="Img" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                                @else
                                <div class="h-100 text-muted">
                                    <i class="fas fa-image"></i>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="ps-4" style="max-width: 300px;">
                            <div class=" align-items-center">
                                <div>
                                    <div class="fw-bold text-dark text-truncate">{{ $row['title'] ?? '' }}</div>
                                    <small class="text-muted">Slug: {{ $row['slug'] ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-user-circle text-muted me-1 small"></i>
                            {{ $row->user->name ?? 'Unknown' }}
                        </td>
                        <td>
                            @if(($row['status'] ?? 'draft') == 'published')
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Published</span>
                            @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Draft</span>
                            @endif
                        </td>
                        <td>{{ number_format($row['views'] ?? 0) }}</td>
                        <td class="text-muted small">
                            @if(!empty($row['published_at']))
                            {{ \Carbon\Carbon::parse($row['published_at'])->format('M d, Y') }}
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.articles.edit', $row->id) }}" class="btn btn-sm btn-light text-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.articles.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this article?');">
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
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<style>
    .dataTables_wrapper .dataTables_filter {
        float: right;
        margin-bottom: 1rem;
    }

    .dataTables_wrapper .dataTables_length {
        float: left;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
        margin-top: 1rem;
    }
</style>

<!-- DataTables & Extensions -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#companiesTable').DataTable({
            responsive: true,
            lengthChange: true,
            pageLength: 10,
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis']
        }).buttons().container().appendTo('#companiesTable_wrapper .col-md-6:eq(0)');
    });
</script>