@extends('layouts.user')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">My Applications</h2>
    <div class="dropdown">
        <button class="btn btn-white border dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
            All Status
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Applied</a></li>
            <li><a class="dropdown-item" href="#">Interviewing</a></li>
            <li><a class="dropdown-item" href="#">Offered</a></li>
            <li><a class="dropdown-item" href="#">Rejected</a></li>
        </ul>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 py-3 ps-4">Company</th>
                    <th class="border-0 py-3">Role</th>
                    <th class="border-0 py-3">Date Applied</th>
                    <th class="border-0 py-3">Status</th>
                    <th class="border-0 py-3 text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Placeholder Data -->
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-1 me-2" style="width: 32px; height: 32px;"></div>
                            <span class="fw-bold">TechCorp Inc.</span>
                        </div>
                    </td>
                    <td>Senior Developer</td>
                    <td>Oct 24, 2023</td>
                    <td><span class="badge bg-warning bg-opacity-10 text-warning">In Review</span></td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-light"><i class="fas fa-ellipsis-v"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-1 me-2" style="width: 32px; height: 32px;"></div>
                            <span class="fw-bold">StartupX</span>
                        </div>
                    </td>
                    <td>Frontend Engineer</td>
                    <td>Oct 20, 2023</td>
                    <td><span class="badge bg-info bg-opacity-10 text-info">Interview</span></td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-light"><i class="fas fa-ellipsis-v"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="card-footer bg-white border-0 py-3">
        <nav>
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
