<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Tech Media Directory</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>


    <style>
        :root {
            --primary: #2563eb;
            --secondary: #3b82f6;
            --dark: #0f172a;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            min-height: 100vh;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* Layout Structure */
        .admin-wrapper {
            display: flex;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            border-right: 1px solid #e2e8f0;
            z-index: 1000;
            transition: 0.3s;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 30px;
            transition: 0.3s;
        }

        /* Nav Links */
        .nav-link {
            color: #64748b;
            padding: 12px 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #eff6ff;
            color: var(--primary);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 700;
            padding: 20px 20px 10px;
            letter-spacing: 0.5px;
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        .bg-gold{
            background-color: #fbbf24;
            color: #0f172a;
        }

        .bg-silver{
            background-color: #94a3b8;
        }

        .tool-check{
            .form-check{
                padding: 0;
                margin: 5px;
            }
            .form-check-input {
    display: none;
}

.form-check-label {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.form-check-input:checked + .form-check-label {
    border-color: #0d6efd;
    background-color: #0d6efd;
    color: white;
}

.form-check-input:checked + .form-check-label i {
    color: white !important;
}

.form-check-label:hover {
    border-color: #adb5bd;
}
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">
        