<?php

namespace App\Controllers;

use App\Models\RekamMedisModel;

/**
 * ================================================================
 *  FILE    : app/Controllers/MedisController.php
 */

class MedisController extends BaseController
{
    private RekamMedisModel $model;

    public function __construct()
{
    $this->model = new RekamMedisModel();
    helper(['url', 'form']);
}

    public function login(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('halaman_sistem', [
            'page'  => 'login',
            'title' => 'Login',
        ]);
    }

    public function doLogin(): \CodeIgniter\HTTP\RedirectResponse
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->model->cariUser((string)$username);

        if ($user && password_verify((string)$password, $user['password'])) {
            session()->set([
                'logged_in' => true,
                'user_id'   => $user['id'],
                'name'      => $user['name'],
                'role'      => $user['role'],
            ]);
            return redirect()->to('/dashboard');
        }

        return redirect()->back()
                         ->with('error', 'Username atau password salah.');
    }

    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->destroy();
        return redirect()->to('/login')
                         ->with('success', 'Berhasil logout.');
    }

    public function dashboard(): string
    {
        return view('halaman_sistem', [
            'page'  => 'dashboard',
            'title' => 'Dashboard',
            'stats' => $this->model->statistikDashboard(),
        ]);
    }

    public function kunjungan(): string
{
    $search = (string)($this->request->getGet('q') ?? '');
    $data   = $this->model->ambilSemua($search, 10);

    return view('halaman_sistem', [
        'page'     => 'kunjungan',
        'title'    => 'Riwayat Kunjungan',
        'search'   => $search,
        'rows'     => $data['rows'],
        'total'    => $data['total'],
        'perPage'  => $data['perPage'],
        'currPage' => $data['page'],
    ]);
}

    public function rekamMedis(): string
{
    $search = (string)($this->request->getGet('q') ?? '');
    $data   = $this->model->ambilSemua($search, 10);

    return view('halaman_sistem', [
        'page'     => 'pasien',
        'title'    => 'Data Rekam Medis',
        'search'   => $search,
        'rows'     => $data['rows'],
        'total'    => $data['total'],
        'perPage'  => $data['perPage'],
        'currPage' => $data['page'],
        'pasien'   => null,
    ]);
}

public function rekamMedisCreate(): string
{
    return view('halaman_sistem', [
        'page'   => 'pasien_form',
        'title'  => 'Input Rekam Medis Baru',
        'data'   => null,
        'pasien' => null,
    ]);
}

    public function rekamMedisStore(): \CodeIgniter\HTTP\RedirectResponse
    {
        
        $rules = [
    'nama_pasien'        => 'required|min_length[3]',
    'nik'                => 'required|exact_length[16]',
    'jenis_kelamin'      => 'required|in_list[L,P]',
    'keluhan'            => 'required|min_length[5]',
    'diagnosa'           => 'required|min_length[3]',
    'dokter'             => 'required|min_length[3]',
    'tanggal_kunjungan'  => 'required',

];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $d = $this->request->getPost([
    'nama_pasien', 'nik', 'tgl_lahir', 'jenis_kelamin',
    'alamat', 'no_hp', 'keluhan', 'diagnosa',
    'tindakan', 'dokter', 'tanggal_kunjungan',
]);

        $this->model->simpanBaru($d);

        return redirect()->to('/rekam-medis')
                         ->with('success', 'Data rekam medis berhasil disimpan dan dikunci dengan SHA-256.');
    }

    public function rekamMedisEdit(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $data = $this->model->ambilSatuById($id);

        if (!$data) {
            return redirect()->to('/rekam-medis')
                             ->with('error', 'Data tidak ditemukan.');
        }

        return view('halaman_sistem', [
    'page'   => 'pasien_form',
    'title'  => 'Edit Rekam Medis',
    'data'   => $data,
    'pasien' => $data,
]);
    }

    /**
     * Proses form edit rekam medis (POST).
     * Hash SHA-256 diregenerasi otomatis setelah update.
     */
    public function rekamMedisUpdate(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $rules = [
            'nama_pasien'   => 'required|min_length[3]',
            'nik'           => 'required|exact_length[16]',
            'tgl_lahir'     => 'required|valid_date',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'keluhan'       => 'required|min_length[5]',
            'diagnosa'      => 'required|min_length[3]',
            'dokter'        => 'required|min_length[3]',
            'tanggal_kunjungan' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $d = $this->request->getPost([
    'nama_pasien', 'nik', 'tgl_lahir', 'jenis_kelamin',
    'alamat', 'no_hp', 'keluhan', 'diagnosa',
    'tindakan', 'dokter', 'tanggal_kunjungan',
]);

        // Update + regenerasi hash otomatis di Model
        $this->model->perbaruiData($id, $d);

        return redirect()->to('/rekam-medis')
                         ->with('success', 'Data diperbarui. Hash SHA-256 diregenerasi otomatis.');
    }

    public function rekamMedisDelete(int $id): \CodeIgniter\HTTP\RedirectResponse
{
    $this->model->hapusData($id);

    return redirect()->to('/rekam-medis')
                     ->with('success', 'Data berhasil dihapus.');
}



public function detail(int $id): string|\CodeIgniter\HTTP\RedirectResponse
{
    $data = $this->model->ambilSatuById($id);

    if (!$data) {
        return redirect()->to('/kunjungan')
                         ->with('error', 'Data tidak ditemukan.');
    }

    return view('halaman_sistem', [
        'page'   => 'detail',
        'title'  => 'Detail Rekam Medis',
        'detail' => $data,
    ]);
}



public function verify(): string
{
    $semuaData    = $this->model->verifikasiSemua();
    $totalRows    = count($semuaData);
    $totalValid   = count(array_filter($semuaData, fn($r) => $r['is_valid']));
    $totalInvalid = $totalRows - $totalValid;

    return view('halaman_sistem', [
        'page'          => 'verify',
        'title'         => 'Verifikasi Integritas Data',
        'pasienRows'    => $semuaData,
        'kunjunganRows' => $semuaData,
        'totalRows'     => $totalRows,
        'totalValid'    => $totalValid,
        'totalInvalid'  => $totalInvalid,
    ]);
}
}