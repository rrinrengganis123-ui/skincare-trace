<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard ERP</title>

    <!-- SB ADMIN CSS -->
    <link href="/sbadmin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/sbadmin/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

<div id="wrapper">

    <!-- SIDEBAR -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion">

        <!-- LOGO -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin/dashboard">
            <div class="sidebar-brand-icon">
                <i class="fas fa-cubes"></i>
            </div>
            <div class="sidebar-brand-text mx-2">
                ERP TEKNOLOGI
            </div>
        </a>

        <hr class="sidebar-divider">

        <!-- MENU -->
        <li class="nav-item active">
            <a class="nav-link" href="/admin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/admin/laporan_keuangan">
                <i class="fas fa-table"></i>
                <span>Laporan Keuangan</span>
            </a>
        </li>

    </ul>

    <!-- CONTENT -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- TOPBAR -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <a href="/logout" class="btn btn-danger">Logout</a>

                <form class="form-inline ml-auto">
                    <input type="text" class="form-control" placeholder="Search...">
                </form>

            </nav>

            <!-- MAIN CONTENT -->
            <div class="container-fluid">

                <h1 class="h3 mb-4 text-gray-800">
                    Selamat Datang di ERP TEKNOLOGI
                </h1>

                <div class="card shadow">
                    <div class="card-body">
                        Sistem berhasil berjalan. Silakan gunakan menu di samping.
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

<!-- JS -->
<script src="/sbadmin/vendor/jquery/jquery.min.js"></script>
<script src="/sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/sbadmin/js/sb-admin-2.min.js"></script>

</body>
</html>