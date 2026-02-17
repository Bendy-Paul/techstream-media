<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            <form action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Honeypot Field (Hidden from users, visible to bots) -->
                <div class="website-field">
                    <label>If you are human, leave this field blank.</label>
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Full Name</label>
                    <input type="text" name="name" class="form-control" required value="">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email Address</label>
                    <input type="email" name="email" class="form-control" required value="">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" required value="">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">I want to...</label>
                    <select name="role" class="form-select">
                        <option value="user">Explore Tech Companies & Jobs</option>
                        <option value="company_owner">List My Company / Post Jobs</option>
                    </select>
                </div>

                <!-- Math CAPTCHA -->
                <!-- <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Security Check: </label>
                    <input type="number" name="captcha" class="form-control" placeholder="Enter result" required>
                </div> -->

                <button type="submit" class="btn btn-primary w-100 mb-3">Sign Up</button>
                <div class="text-center small">
                    Already have an account? <a href="login" class="text-decoration-none">Login</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>