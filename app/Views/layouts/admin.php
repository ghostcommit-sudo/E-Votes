<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin - E-Votes' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/admin.css') ?>" rel="stylesheet">
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white" id="sidebar-wrapper">
            <div class="sidebar-heading p-3">E-Votes Admin</div>
            <div class="list-group list-group-flush">
                <a href="<?= base_url('admin-system/dashboard') ?>" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                <a href="<?= base_url('admin-system/candidates') ?>" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-users me-2"></i> Candidates
                </a>
                <a href="<?= base_url('admin-system/students') ?>" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-user-graduate me-2"></i> Students
                </a>
                <a href="<?= base_url('admin-system/classes') ?>" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-school me-2"></i> Classes
                </a>
                <a href="<?= base_url('admin-system/periods') ?>" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-calendar-alt me-2"></i> Periods
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-2"></i><?= session()->get('adminName') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= base_url('admin-system/logout') ?>">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show m-3">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show m-3">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Main Content -->
            <main class="container-fluid py-4">
                <h1 class="h3 mb-4"><?= $title ?></h1>
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/admin.js') ?>"></script>
    
    <script>
        // Toggle sidebar
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#sidebar-wrapper").toggleClass("toggled");
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html> 