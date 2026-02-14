@extends('layouts.user')

@section('content')
<h2 class="h4 mb-4">Account Settings</h2>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action active">General</a>
            <a href="#" class="list-group-item list-group-item-action">Notifications</a>
            <a href="#" class="list-group-item list-group-item-action">Privacy</a>
            <a href="#" class="list-group-item list-group-item-action text-danger">Delete Account</a>
        </div>
    </div>
    
    <div class="col-md-9">
        <!-- General Settings -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title mb-0">General Information</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" value="John">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" value="Doe">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="john@example.com">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
        
        <!-- Notifications -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title mb-0">Email Notifications</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="newsCheck" checked>
                        <label class="form-check-label" for="newsCheck">
                            Weekly Newsletter
                        </label>
                        <div class="form-text">Receive the latest tech news and updates.</div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="jobCheck" checked>
                        <label class="form-check-label" for="jobCheck">
                            Job Alerts
                        </label>
                        <div class="form-text">Get notified when new jobs matching your profile are posted.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Preferences</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
