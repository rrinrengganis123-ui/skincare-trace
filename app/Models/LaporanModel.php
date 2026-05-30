<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table = 'laporan_keuangan';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tanggal',
        'keterangan',
        'nominal',
        'pemasukan',
        'pengeluaran'
    ];
}