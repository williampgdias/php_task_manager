<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    body {
        background-color: #f5f5f5;
    }

    .task-card {
        transition: all 0.3s ease;
    }

    .task-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .status-pending {
        border-left: 4px solid #ffc107;
    }

    .status-in_progress {
        border-left: 4px solid #0d6efd;
    }

    .status-completed {
        border-left: 4px solid #198754;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">Task Manager</span>
            <button class="btn btn-outline-light btn-sm" id="logoutBtn" style="display:none;">Logout</button>
        </div>
    </nav>

    <div class="container">
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>