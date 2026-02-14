<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
</head>

<body>
    @include('admin.partials.header')
    @include('admin.partials.sidebar')

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Users Management</h2>
                <p class="text-muted mb-0">Manage platform users and permissions.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New User
            </a>
        </div>

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form id="bulkActionForm" action="" method="POST">
            @csrf
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <select name="action" class="form-select form-select-sm d-inline-block w-auto me-2" required>
                                <option value="">Bulk Actions</option>
                                <option value="activate">Activate</option>
                                <option value="suspend">Suspend</option>
                                <option value="delete">Delete</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary" onclick="return confirm('Apply this action to selected users?')">Apply</button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive p-3">
                    <table id="usersTable" class="table table-hover align-middle w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" style="width: 40px;">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Verification</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-check-input user-checkbox">
                                </td>
                                <td>  {{ $loop->iteration }} </td>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-soft-info text-dark border">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>
                                    @if($user->is_active == '1')
                                        <span class="badge bg-primary">Active</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Suspended</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="text-success small"><i class="fas fa-check-circle me-1"></i>Verified</span>
                                    @else
                                        <span class="text-muted small"><i class="fas fa-clock me-1"></i>Pending</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-light text-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
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
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#usersTable').DataTable({
                "pageLength": 10,
                "order": [[1, "asc"]], // Sort by Name by default
                "columnDefs": [
                    { "orderable": false, "targets": [0, 6] } // Disable sorting on checkbox and actions
                ],
                "language": {
                    "searchPlaceholder": "Search users...",
                    "search": ""
                }
            });

            // Select/Deselect All Checkboxes
            $('#selectAll').on('click', function() {
                $('.user-checkbox').prop('checked', this.checked);
            });

            // Update "Select All" if individual checkboxes are changed
            $('.user-checkbox').on('change', function() {
                if ($('.user-checkbox:checked').length == $('.user-checkbox').length) {
                    $('#selectAll').prop('checked', true);
                } else {
                    $('#selectAll').prop('checked', false);
                }
            });
        });

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
</body>
</html>