<?php

namespace App\Controllers;

use App\Models\LaporanModel;

class Admin extends BaseController
{
    public function __construct()
    {
        // 🔐 CEK LOGIN
        if (!session()->get('isLoggedIn')) {
            header('Location: /login');
            exit();
        }
    }

    public function dashboard()
    {
        return view('admin/dashboard');
    }

    public function laporan_keuangan()
    {
        $model = new LaporanModel();

        $data['laporan_keuangan'] = $model->findAll();

        return view('admin/laporan_keuangan', $data);
    }
}