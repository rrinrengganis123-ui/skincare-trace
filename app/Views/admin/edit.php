<?php /** @var array $row */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Data</title>

    <link href="/sbadmin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/sbadmin/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

<div class="container mt-5">

    <h2>Edit Data Laporan</h2>

    <form method="post" action="/admin/update/<?= $row['id']; ?>">

        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control"
                   value="<?= $row['tanggal']; ?>" required>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" class="form-control"
                   value="<?= $row['keterangan']; ?>" required>
        </div>

        <div class="form-group">
            <label>Nominal</label>
            <input type="number" name="nominal" class="form-control"
                   value="<?= $row['nominal']; ?>" required>
        </div>

        <div class="form-group">
            <label>Pemasukan</label>
            <input type="number" name="pemasukan" class="form-control"
                   value="<?= $row['pemasukan']; ?>" required>
        </div>

        <div class="form-group">
            <label>Pengeluaran</label>
            <input type="number" name="pengeluaran" class="form-control"
                   value="<?= $row['pengeluaran']; ?>" required>
        </div>

        <button type="submit" class="btn btn-success mt-3">
            Update
        </button>

        <a href="/admin/laporan_keuangan" class="btn btn-secondary mt-3">
            Kembali
        </a>

    </form>

</div>

</body>
</html>