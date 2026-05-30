<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tambah Data</title>

    <link href="/sbadmin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/sbadmin/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h3 class="mb-4">Tambah Data Keuangan</h3>

    <div class="card shadow">
        <div class="card-body">

            <form method="post" action="/admin/simpan">

                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Nominal</label>
                    <input type="number" name="nominal" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Pemasukan</label>
                    <input type="number" name="pemasukan" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Pengeluaran</label>
                    <input type="number" name="pengeluaran" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="/admin/laporan_keuangan" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>