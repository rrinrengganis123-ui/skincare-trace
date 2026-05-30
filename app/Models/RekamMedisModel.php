<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ================================================================
 *  FILE    : app/Models/RekamMedisModel.php

    USE db_rekam_medis;

    CREATE TABLE IF NOT EXISTS rekam_medis (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        no_rm         VARCHAR(25)   NOT NULL UNIQUE,
        nama_pasien   VARCHAR(100)  NOT NULL,
        nik           VARCHAR(16)   NOT NULL,
        tgl_lahir     DATE          NOT NULL,
        jenis_kelamin ENUM('L','P') NOT NULL DEFAULT 'L',
        alamat        TEXT,
        no_hp         VARCHAR(20),
        keluhan       TEXT          NOT NULL,
        diagnosa      TEXT          NOT NULL,
        tindakan      TEXT,
        dokter        VARCHAR(100)  NOT NULL,
        tgl_kunjungan DATE          NOT NULL,
        hash_code     VARCHAR(64)   NOT NULL COMMENT 'SHA-256 integrity hash',
        created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS users (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        name       VARCHAR(100) NOT NULL,
        username   VARCHAR(50)  NOT NULL UNIQUE,
        password   VARCHAR(255) NOT NULL,
        role       ENUM('admin','user') DEFAULT 'user',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    -- Jalankan ini untuk buat akun login:
    INSERT INTO users (name, username, password, role) VALUES
    ('Administrator', 'admin',
     '$2y$10$YourHashHere',
     'admin'),
    ('Petugas Medis', 'user',
     '$2y$10$YourHashHere',
     'user');
    -- Ganti '$2y$10$YourHashHere' dengan hasil:
    -- php -r "echo password_hash('Admin@2007', PASSWORD_BCRYPT);"
    -- php -r "echo password_hash('user@2026',  PASSWORD_BCRYPT);"

 *  ================================================================
 */

class RekamMedisModel extends Model
{
    protected $table         = 'rekam_medis';
    protected $primaryKey    = 'id';
    protected $useTimestamps = false;   
    protected $allowedFields = [
        'no_rm', 'nama_pasien', 'nik', 'tgl_lahir', 'jenis_kelamin',
        'alamat', 'no_hp', 'keluhan', 'diagnosa', 'tindakan',
        'dokter', 'tanggal_kunjungan', 'hash_code', 'created_at',
    ];

    /**
     * Buat hash SHA-256 dari data rekam medis.
     * Dipanggil saat INSERT maupun UPDATE.
     *
     * @param  array  $d  Data satu baris rekam_medis
     * @return string     64-karakter hex string
     */
    public static function buatHash(array $d): string
    {

        $raw = implode('|', [
            $d['no_rm']         ?? '',
            $d['nama_pasien']   ?? '',
            $d['nik']           ?? '',
            $d['tgl_lahir']     ?? '',
            $d['jenis_kelamin'] ?? '',
            $d['keluhan']       ?? '',
            $d['diagnosa']      ?? '',
            $d['tindakan']      ?? '',
            $d['dokter']        ?? '',
            $d['tanggal_kunjungan'] ?? '',
        ]);

        return hash('sha256', $raw);
    }

    /**
     * Verifikasi integritas satu baris data.
     * Hitung ulang hash dari data saat ini, bandingkan dengan hash tersimpan.
     * Menggunakan hash_equals() untuk mencegah timing attack.
     *
     * @param  array  $row  Satu baris dari tabel rekam_medis
     * @return bool         true = data asli, false = data dimanipulasi
     */
    public static function verifikasiHash(array $row): bool
    {
        $hashTerhitung = self::buatHash($row);
        return hash_equals($hashTerhitung, (string)($row['hash_code'] ?? ''));
    }

    /**
     * Cari user berdasarkan username untuk proses login.
     * Menggunakan tabel 'users' (bukan rekam_medis).
     *
     * @param  string     $username
     * @return array|null  Data user atau null jika tidak ditemukan
     */
    public function cariUser(string $username): ?array
    {
        return $this->db
                    ->table('users')
                    ->where('username', $username)
                    ->get()
                    ->getRowArray();
    }

    /**
     * Ambil semua data rekam medis.
     * Mendukung pencarian dan pagination manual.
     * Setiap baris dilengkapi status is_valid dari verifikasi hash.
     *
     * @param  string $search   Kata kunci pencarian (nama/no_rm/diagnosa)
     * @param  int    $perPage  Jumlah baris per halaman
     * @return array            ['rows', 'total', 'perPage', 'page']
     */
    public function ambilSemua(string $search = '', int $perPage = 10): array
    {
        $builder = $this->db->table('rekam_medis');

        if ($search !== '') {
            $builder->groupStart()
                        ->like('nama_pasien', $search)
                        ->orLike('no_rm',     $search)
                        ->orLike('diagnosa',  $search)
                        ->orLike('dokter',    $search)
                    ->groupEnd();
        }

        $total = $builder->countAllResults(false);

        $page   = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;

        $rows = $builder
                    ->orderBy('tanggal_kunjungan', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->limit($perPage, $offset)
                    ->get()
                    ->getResultArray();

        foreach ($rows as &$row) {
            $row['is_valid'] = self::verifikasiHash($row);
        }

        return [
            'rows'    => $rows,
            'total'   => $total,
            'perPage' => $perPage,
            'page'    => $page,
        ];
    }

    /**
     * Ambil satu baris rekam medis berdasarkan ID.
     *
     * @param  int        $id
     * @return array|null
     */
    public function ambilSatuById(int $id): ?array
    {
        return $this->db
                    ->table('rekam_medis')
                    ->where('id', $id)
                    ->get()
                    ->getRowArray();
    }

    /**
     * Simpan data rekam medis baru.
     * No. RM dibuat otomatis, hash SHA-256 dihitung sebelum INSERT.
     *
     * @param  array $d  Data dari form (tanpa no_rm dan hash_code)
     * @return bool
     */
    public function simpanBaru(array $d): bool
    {

        $d['no_rm'] = $this->buatNoRM();
        $d['hash_code'] = self::buatHash($d);
        $d['created_at'] = date('Y-m-d H:i:s');

        return $this->db->table('rekam_medis')->insert($d);
    }

    /**
     * Generate Nomor Rekam Medis otomatis.
     * Format: RM-YYYYMMDD-001, RM-YYYYMMDD-002, dst.
     *
     * @return string  Contoh: RM-20250528-001
     */
    private function buatNoRM(): string
    {
        $prefix = 'RM-' . date('Ymd') . '-';

        $count = $this->db
                      ->table('rekam_medis')
                      ->like('no_rm', $prefix, 'after')
                      ->countAllResults();

        return $prefix . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Update data rekam medis + regenerasi hash SHA-256 otomatis.
     * no_rm TIDAK berubah saat update (tetap pakai yang lama).
     *
     * @param  int   $id
     * @param  array $d   Data baru dari form
     * @return bool
     */
    public function perbaruiData(int $id, array $d): bool
    {

        $existing   = $this->ambilSatuById($id);
        $d['no_rm'] = $existing['no_rm'] ?? '';

        $d['hash_code'] = self::buatHash($d);

        return $this->db
                    ->table('rekam_medis')
                    ->where('id', $id)
                    ->update($d);
    }

    /**
     * Hapus satu baris rekam medis.
     * Hanya bisa dipanggil dari controller setelah cek role admin.
     *
     * @param  int  $id
     * @return bool
     */
    public function hapusData(int $id): bool
    {
        return $this->db
                    ->table('rekam_medis')
                    ->where('id', $id)
                    ->delete();
    }

    /**
     * Verifikasi integritas SEMUA baris rekam_medis.
     * Setiap baris dikembalikan dengan tambahan field:
     *   - stored   : hash yang tersimpan di DB
     *   - computed : hash yang dihitung ulang dari data saat ini
     *   - is_valid : true jika keduanya cocok
     *
     * @return array
     */
    public function verifikasiSemua(): array
    {
        $rows = $this->db
                     ->table('rekam_medis')
                     ->orderBy('id', 'ASC')
                     ->get()
                     ->getResultArray();

        return array_map(function (array $row): array {
            $computed        = self::buatHash($row);
            $row['stored']   = $row['hash_code'];
            $row['computed'] = $computed;
            $row['is_valid'] = hash_equals($computed, $row['hash_code']);
            return $row;
        }, $rows);
    }

    /**
     * Kumpulkan semua angka untuk halaman Dashboard.
     *
     * @return array
     */
    public function statistikDashboard(): array
    {
        
        $totalData = $this->db->table('rekam_medis')->countAll();
        
        $hariIni = $this->db
                        ->table('rekam_medis')
                        ->where('tanggal_kunjungan', date('Y-m-d'))
                        ->countAllResults();

        
        $semuaData   = $this->verifikasiSemua();
        $totalInvalid = count(array_filter($semuaData, fn($r) => !$r['is_valid']));

        $terbaru = $this->db
                        ->table('rekam_medis')
                        ->orderBy('tanggal_kunjungan', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();

        $totalDokter = $this->db
                            ->table('rekam_medis')
                            ->select('dokter')
                            ->where('dokter IS NOT NULL', null, false)
                            ->countAllResults();

        return [
            'total_data'    => $totalData,
            'hari_ini'      => $hariIni,
            'total_invalid' => $totalInvalid,
            'terbaru'       => $terbaru,
            'total_dokter'  => $totalDokter,
        ];
    }
}