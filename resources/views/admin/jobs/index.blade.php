<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
</head>

<body>
    @include('admin.partials.header')
    @include('admin.partials.sidebar')
    <main class="main-content">

        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Job Board</h2>
                <p class="text-muted mb-0">Manage job listings.</p>
            </div>
            <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Post Job</a>
        </div>
        
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Title</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Posted</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $job)
                        <tr>
                            <td class="ps-4 fw-bold">{{$job->title}}</td>
                            <td>{{$job->company->name}}</td>
                            <td><span class="badge bg-light text-dark border">{{$job->job_type}}</span></td>
                            <td>
                                <?php if (strtotime($job['expires_at']) > time()): ?>
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">Expired</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small"><?= date('M d, Y', strtotime($job['created_at'])) ?></td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.jobs.destroy', $job) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{$job->id}}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this job?');"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

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
            $('.table').DataTable({
                responsive: true,
                lengthChange: true,
                pageLength: 10,
                // buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis']
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search jobs..."
                }
            });
        });
    </script>
</body>
</html>