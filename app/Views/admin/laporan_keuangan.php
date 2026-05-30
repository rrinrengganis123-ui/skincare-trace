<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>

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
                <i class="fas fa-laptop-code"></i>
            </div>
            <div class="sidebar-brand-text mx-2">
                ERP TEKNOLOGI
            </div>
        </a>

        <hr class="sidebar-divider">

        <!-- MENU -->
        <li class="nav-item">
            <a class="nav-link" href="/admin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item active">
            <a class="nav-link" href="/admin/laporan_keuangan">
                <i class="fas fa-table"></i>
                <span>Laporan Keuangan</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>

    <!-- CONTENT -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- TOPBAR -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">

                <span class="navbar-brand font-weight-bold text-primary">
                    <i class="fas fa-cubes"></i> ERP TEKNOLOGI
                </span>

                <form class="form-inline mx-auto">
                    <input type="text" class="form-control" placeholder="Search...">
                </form>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item d-flex align-items-center">
                        <i class="fas fa-user-circle fa-lg text-primary mr-2"></i>
                        <span class="text-dark font-weight-bold mr-2">Ririn</span>
                        <span style="width:10px;height:10px;background:#28a745;border-radius:50%;display:inline-block;"></span>
                    </li>
                </ul>

            </nav>

            <!-- MAIN -->
            <div class="container-fluid">

                <!-- JUDUL -->
                <h1 class="h3 mb-3 text-gray-800">Laporan Keuangan</h1>

                <!-- 🔥 POSISI YANG BENAR: DI SINI -->
                <a href="/admin/tambah" class="btn btn-primary mb-3">
                    + Tambah Data
                </a>

                <!-- CARD -->
                <div class="card shadow">
                    <div class="card-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Nominal</th>
                                    <th>Pemasukan</th>
                                    <th>Pengeluaran</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (!empty($laporan_keuangan)): ?>
                                    <?php foreach ($laporan_keuangan as $row): ?>
                                        <tr>
                                            <td><?= $row['id']; ?></td>
                                            <td><?= $row['tanggal']; ?></td>
                                            <td><?= $row['keterangan']; ?></td>
                                            <td><?= $row['nominal']; ?></td>
                                            <td><?= $row['pemasukan']; ?></td>
                                            <td><?= $row['pengeluaran']; ?></td>


                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            Data tidak ada
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

<script src="/sbadmin/vendor/jquery/jquery.min.js"></script>
<script src="/sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/sbadmin/js/sb-admin-2.min.js"></script>

</body>
</html>