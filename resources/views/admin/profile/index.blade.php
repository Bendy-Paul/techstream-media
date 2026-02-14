<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Admin</title>
</head>
<body>
    @include('admin.partials.header')
    @include('admin.partials.sidebar')

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">My Profile</h2>
                <p class="text-muted mb-0">Manage your account settings.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                                <p class="text-muted mb-0">{{ $user->email }}</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3">Profile Information</h5>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Full Name</div>
                            <div class="col-sm-9 fw-bold">{{ $user->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Email Address</div>
                            <div class="col-sm-9 fw-bold">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Role</div>
                            <div class="col-sm-9 fw-bold"><span class="badge bg-primary">Administrator</span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Joined At</div>
                            <div class="col-sm-9 fw-bold">{{ $user->created_at->format('F d, Y') }}</div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>