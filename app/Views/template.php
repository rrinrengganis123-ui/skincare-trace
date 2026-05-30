<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">CI4 Admin</div>
        </a>

        <hr class="sidebar-divider">

        <!-- Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="<?= base_url('dashboard') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

    </ul>
    <!-- End Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <span class="ml-3 font-weight-bold">Dashboard</span>

            </nav>
            <!-- End Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <?= $this->renderSection('content') ?>

            </div>
            <!-- End Page Content -->

        </div>
        <!-- End Main Content -->

    </div>
    <!-- End Content Wrapper -->

</div>
<!-- End Page Wrapper -->

<!-- JS -->
<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>

</body>
</html>