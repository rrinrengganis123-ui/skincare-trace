<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ERP Dashboard</title>

    <!-- CSS -->
    <link href="/sbadmin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/sbadmin/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

<!-- PAGE WRAPPER -->
<div id="wrapper">

    <!-- SIDEBAR -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- LOGO -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin/dashboard">
            <div class="sidebar-brand-icon">
                <i class="fas fa-cubes"></i>
            </div>
            <div class="sidebar-brand-text mx-3">
                ERP TEKNOLOGI
            </div>
        </a>

        <hr class="sidebar-divider">

        <!-- MENU DASHBOARD -->
        <li class="nav-item active">
            <a class="nav-link" href="/admin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- MENU LAPORAN -->
        <li class="nav-item">
            <a class="nav-link" href="/admin/laporan_keuangan">
                <i class="fas fa-table"></i>
                <span>Laporan Keuangan</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <!-- LOGOUT -->
        <li class="nav-item">
            <a class="nav-link" href="/logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>
    <!-- END SIDEBAR -->


    <!-- CONTENT WRAPPER -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- MAIN CONTENT -->
        <div id="content">

            <!-- TOPBAR -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">

                <!-- SEARCH -->
                <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search...">
                        <div class="input-group-append">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

            </nav>

            <!-- PAGE CONTENT -->
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
        <!-- END CONTENT -->

    </div>
    <!-- END CONTENT WRAPPER -->

</div>
<!-- END WRAPPER -->

<!-- JS -->
<script src="/sbadmin/vendor/jquery/jquery.min.js"></script>
<script src="/sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/sbadmin/js/sb-admin-2.min.js"></script>

</body>
</html>