<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tech Tools</title>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>

@include('admin.partials.header')
@include('admin.partials.sidebar')

    <main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Manage Tech Tools</h2>
    </div>

    <div class="row">
        <!-- Add Stack Form -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Add New Tool</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.tools.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="small text-muted">Tool Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Docker" required>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted">Icon Class (FontAwesome)</label>
                            <input type="text" name="icon_class" class="form-control" placeholder="e.g. fab fa-docker">
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted">Category</label>
                            <select name="category" class="form-select">
                                <option value="Frontend">Frontend</option>
                                <option value="Backend">Backend</option>
                                <option value="Database">Database</option>
                                <option value="Mobile">Mobile</option>
                                <option value="DevOps">DevOps</option>
                                <option value="Design">Design</option>
                                <option value="Data">Data/AI</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <button type="submit" name="add_stack" class="btn btn-primary w-100">Add Tool</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stacks List -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table id="toolsTable" class="table table-hover align-middle w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Icon</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tools as $tool): ?>
                            <tr>
                                <td class="ps-4"><i class="<?= htmlspecialchars($tool['icon_class'] ?? 'fas fa-code') ?> text-muted fa-lg"></i></td>
                                <td class="fw-bold"><?= htmlspecialchars($tool['name']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($tool['category']) ?></span></td>
                                <td class="text-end pe-4">
                                    <form method="POST" action="{{ route('admin.tools.destroy', $tool) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($tool['id']) ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this tool?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#toolsTable').DataTable({
            responsive: true,
            lengthChange: true,
            pageLength: 10
        });
    });
</script>
</body>
</html>