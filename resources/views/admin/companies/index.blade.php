@include('admin.partials.header')
@include('admin.partials.sidebar')

<main class="main-content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Companies Directory</h2>
            <p class="text-muted mb-0">Manage all business listings.</p>
        </div>
        <div>
            <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Add Company
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
                        <th>SN</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Subscription</th>
                        <th>Status</th>
                        <th>Date Added</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    @if($row->logo)
                                    <img src="{{ asset('storage/logos/' . $row->logo) }}" alt="Logo" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-building fa-lg"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $row->name }}</h6>
                                    <small class="text-muted ">{{ $row->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $row->city ? $row->city->name : 'N/A' }}, {{ $row->city && $row->city->state ? $row->city->state->country->name : 'N/A' }}
                        </td>
                        <td>
                            @if($row->subscription_tier == 'free')
                            <span class="badge bg-secondary">free</span>
                            @elseif($row->subscription_tier == 'silver')
                            <span class="badge bg-silver">silver</span>
                            @elseif($row->subscription_tier == 'gold')
                            <span class="badge bg-gold">Gold</span>
                            @endif
                        </td>
                        <td>
                            @if($row->is_verified)
                            <span class="text-success small"><i class="fas fa-check-circle me-1"></i>Verified</span>
                            @else
                            <span class="text-muted small"><i class="fas fa-clock me-1"></i>Pending</span>
                            @endif
                        </td>
                        <td>{{ $row->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.companies.edit', $row->id) }}" class="btn btn-sm btn-light text-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.companies.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this company?');">
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