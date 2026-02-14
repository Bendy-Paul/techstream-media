<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All States | TechMedia</title>
    @include('partials.links')

    <style>
        .state-list {
            display: flex;
            flex-wrap: wrap;
            gap: 1em;
        }
        .state-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1em 1.5em;
            font-weight: 600;
            /* color: #007bff; */
            text-decoration: none;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            transition: background 0.2s;
        }
        .state-item:hover {
            background: #e3f0fa;
        }
    </style>
</head>
<body>
@include('partials.navbar')

<div class="container py-5">
    <h2 class="fw-bold mb-4 text-primary-accent">All States</h2>
    <div class="state-list">
        <?php foreach ($states as $state): ?>
            <a href="states/<?= htmlspecialchars($state['slug']) ?>" class="state-item">
                <?= htmlspecialchars($state['name']) ?> <span class="badge bg-light text-dark ms-2"><?= $state['company_count'] ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

@include('partials.footer')
</body>
</html>
