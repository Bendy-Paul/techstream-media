<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body p-5">
                        
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="text-primary" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="fw-bold mb-2">{{ __('Verify your email') }}</h2>
                            <p class="text-muted">
                                {{ __('You\'re almost there! We sent an email to') }} 
                                <strong>{{ auth()->user()->email }}</strong>.<br>
                                {{ __('Just click on the link in that email to complete your signup. If you don\'t see it, you may need to check your spam folder.') }}
                            </p>
                        </div>

                        <!-- Success Message -->
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <div class="d-flex">
                                    <div class="me-2">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="mt-4">
                            <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    {{ __('Resend Verification Email') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary w-100 py-2">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                        
                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">
                                {{ __('Still can\'t find the email? No problem.') }}
                                <br>
                                {{ __('Contact our support team for help.') }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>