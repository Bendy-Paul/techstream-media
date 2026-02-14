@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold mb-4">Account Settings</h1>

            <!-- Profile Information -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Subscriptions -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Subscriptions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.settings.update-subscriptions') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="newsletter" name="is_subscribed_newsletter" value="1" {{ auth()->user()->is_subscribed_newsletter ? 'checked' : '' }}>
                            <label class="form-check-label" for="newsletter">
                                <strong>Weekly Newsletter</strong>
                                <small class="d-block text-muted">Receive updates about the latest tech news and trends.</small>
                            </label>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="job_board" name="is_subscribed_job_board" value="1" {{ auth()->user()->is_subscribed_job_board ? 'checked' : '' }}>
                            <label class="form-check-label" for="job_board">
                                <strong>Job Alerts</strong>
                                <small class="d-block text-muted">Get notified about new job postings matching your skills.</small>
                            </label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-primary">Update Subscriptions</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-danger shadow-sm rounded-4">
                <div class="card-header bg-danger bg-opacity-10 py-3">
                    <h5 class="fw-bold text-danger mb-0">Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Once you delete your account, there is no going back. Please be certain.</p>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        Delete Account
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger" id="deleteAccountModalLabel">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.account.delete') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> This action is permanent and cannot be undone.
                    </div>
                    <p>Please enter your password to confirm you would like to permanently delete your account.</p>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Deletion</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
