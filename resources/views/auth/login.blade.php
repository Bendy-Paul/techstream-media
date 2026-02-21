<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @include('partials.links')
        <style>
        body {
            background-color: #f8fafc;
        }

        .auth-card {
            max-width: 500px;
            margin: 80px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 40px;
            border: 1px solid rgba(37, 99, 235, 0.1);
        }

        .form-control {
            padding: 12px;
            border-radius: 8px;
        }

        .btn-primary {
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
        }

        /* Honeypot hidden field */
        .website-field {
            display: none;
        }
    </style>
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="auth-card">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember_me">
                    <label class="form-check-label small fw-bold text-muted" for="remember_me">Remember Me</label>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </div>

                <div class="text-center">
                    Don't have an account? <a href="register" class="text-decoration-none">Sign Up</a>
                </div>

                @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif
            </form>
        </div>
    </div>

</body>

</html>