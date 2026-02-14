<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    @include('admin.partials.header')
    @include('admin.partials.sidebar')

<main class="main-content">
    
    <!-- Top Mobile Toggle & Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard</h2>
            <p class="text-muted mb-0">Overview of your directory performance.</p>
        </div>
        <button class="btn btn-outline-primary d-lg-none" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-4 mb-5">
        <!-- Card 1 -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Total Companies</p>
                            <h3 class="fw-bold text-dark mb-0">{{ $totalCompanies }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-2 rounded">
                            <i class="fas fa-building text-primary fa-lg"></i>
                        </div>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success small"><i class="fas fa-arrow-up"></i> +12%</span> <span class="text-muted small">vs last month</span>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Pending Verify</p>
                            <h3 class="fw-bold text-dark mb-0">-</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-2 rounded">
                            <i class="fas fa-user-shield text-warning fa-lg"></i>
                        </div>
                    </div>
                    <a href="verification-requests.php" class="text-decoration-none small text-warning fw-bold">Review Requests <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Active Events</p>
                            <h3 class="fw-bold text-dark mb-0">{{ $totalevents }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-2 rounded">
                            <i class="fas fa-calendar-check text-info fa-lg"></i>
                        </div>
                    </div>
                    <span class="text-muted small">Upcoming this month</span>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Articles</p>
                            <h3 class="fw-bold text-dark mb-0">{{ $totalArticles }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-2 rounded">
                            <i class="fas fa-pen-nib text-success fa-lg"></i>
                        </div>
                    </div>
                    <span class="text-muted small">Published news</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Recent Companies</h5>
            <a href="companies.php" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Company Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $row)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <?php if($row['logo_url']): ?>
                                        <img src="../<?php echo $row['logo_url']; ?>" alt="Logo" style="width:100%; height:100%; object-fit:cover;" class="rounded">
                                    <?php else: ?>
                                        <i class="fas fa-building text-muted"></i>
                                    <?php endif; ?>
                                </div>
                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($row['name']); ?></span>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border">Tech</span></td>
                        <td>
                            <?php if($row['is_verified']): ?>
                                <span class="badge bg-success">Verified</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td>
                            <a href="company-details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light text-primary"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</main>


</body>
</html>