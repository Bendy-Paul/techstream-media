<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Events</title>
</head>
<body>
    @include('admin.partials.header')
    @include('admin.partials.sidebar')

    <main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Events Directory</h2>
            <p class="text-muted mb-0">Manage conferences, hackathons, and meetups.</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Add Event</a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

        </div>
    </div>

    <!-- Events Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Event</th>
                        <th>Type</th>
                        <th>Date & Time</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($events) > 0): ?>
                        <?php foreach ($events as $row): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; overflow:hidden;">
                                        <?php if($row['banner_image_url']): ?>
                                            <img src="../<?= htmlspecialchars($row['banner_image_url']) ?>" style="width:100%; height:100%; object-fit:cover;">
                                        <?php else: ?>
                                            <i class="fas fa-calendar-alt text-muted fa-lg"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 200px;">
                                        <?= htmlspecialchars($row['title']) ?>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border text-capitalize"><?= htmlspecialchars($row['event_type']) ?></span></td>
                            <td>
                                <div class="small text-dark fw-bold"><?= date('M d, Y', strtotime($row['start_datetime'])) ?></div>
                                <div class="small text-muted"><?= date('h:i A', strtotime($row['start_datetime'])) ?></div>
                            </td>
                            <td>
                                <?php if($row['is_virtual']): ?>
                                    <span class="badge bg-info-subtle text-info"><i class="fas fa-video me-1"></i> Virtual</span>
                                <?php else: ?>
                                    <small><i class="fas fa-map-marker-alt text-muted me-1"></i> <?= htmlspecialchars($row['city_name'] ?? 'TBD') ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                    $now = new DateTime();
                                    $start = new DateTime($row['start_datetime']);
                                    if ($start > $now) echo '<span class="badge bg-success">Upcoming</span>';
                                    else echo '<span class="badge bg-secondary">Past</span>';
                                ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.events.edit', $row) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                <form action="{{route('admin.events.destroy', $row)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this event?');"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No events found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
    </div>
</main>
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
                    searchPlaceholder: "Search events..."
                }
            });
        });
    </script>
</body>
</html>