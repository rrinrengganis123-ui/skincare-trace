<?php

/**
 * ================================================================
 *  FILE    : app/Views/halaman_sistem.php
 */

/** @var string */
$page  = $page  ?? 'login';
/** @var string */
$title = $title ?? 'Rekam Medis';
/** @var array */
$stats = $stats ?? [
  'total_pasien'     => 0,
  'total_kunjungan'  => 0,
  'kunjungan_today'  => 0,
  'total_invalid'    => 0,
  'recent_kunjungan' => [],
];

$no            = 1;

/** @var array */
$rows     = $rows     ?? [];
/** @var string */
$search   = $search   ?? '';
/** @var int */
$total    = $total    ?? 0;
/** @var int */
$perPage  = $perPage  ?? 10;
/** @var int */
$currPage = $currPage ?? 1;

/** @var array|null */
$pasien = $pasien ?? null;

/** @var array */
$semuaPasien = $semuaPasien ?? [];

/** @var array */
$k = $k ?? [
  'id'            => 0,
  'no_rm'         => '',
  'nama'          => '',
  'tanggal_kunjungan' => 'required|valid_date',
  'dokter'        => '',
  'keluhan'       => '',
  'diagnosa'      => '',
  'tindakan'      => '',
  'created_at'    => '',
  'hash_code'     => '',
  'computed'      => '',
  'is_valid'      => false,
];

/** @var array */
$pasienRows    = $pasienRows    ?? [];
/** @var array */
$kunjunganRows = $kunjunganRows ?? [];
/** @var int   */
$totalRows     = $totalRows     ?? 0;
/** @var int   */
$totalValid    = $totalValid    ?? 0;
/** @var int   */
$totalInvalid  = $totalInvalid  ?? 0;
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Rekam Medis') ?> — E-MECOMS</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    :root {
      --pk-50: #fff0f6;
      --pk-100: #fce7f3;
      --pk-200: #fbcfe8;
      --pk-300: #f9a8d4;
      --pk-400: #f472b6;
      --pk-500: #ec4899;
      --pk-600: #db2777;
      --pk-700: #be185d;
      --soft-bg: #fff5f9;
    }

    body {
      background: var(--soft-bg);
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      width: 230px;
      min-height: 100vh;
      background: linear-gradient(180deg, var(--pk-600), var(--pk-700));
      position: fixed;
      top: 0;
      left: 0;
      z-index: 100;
    }

    .sidebar .brand {
      padding: 20px 20px 14px;
      color: #fff;
      font-size: 1.1rem;
      font-weight: 700;
      border-bottom: 1px solid rgba(255, 255, 255, .2);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .sidebar .brand small {
      display: block;
      font-size: .68rem;
      font-weight: 400;
      opacity: .7;
      margin-top: 2px;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 11px 20px;
      color: rgba(255, 255, 255, .82);
      text-decoration: none;
      font-size: .87rem;
      transition: .18s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: rgba(255, 255, 255, .18);
      color: #fff;
    }

    .sidebar a i {
      font-size: 1rem;
      width: 18px;
      text-align: center;
    }

    .sidebar .nav-section {
      padding: 10px 20px 4px;
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: .08em;
      color: rgba(255, 255, 255, .45);
      text-transform: uppercase;
    }

    .sidebar .sep {
      border-color: rgba(255, 255, 255, .2);
      margin: 6px 20px;
    }

    .sidebar .logout-link {
      color: #fca5a5 !important;
    }

    .sidebar .logout-link:hover {
      background: rgba(239, 68, 68, .2) !important;
    }

    .main-wrap {
      margin-left: 230px;
    }

    .topbar {
      background: #fff;
      border-bottom: 1px solid var(--pk-100);
      padding: 13px 28px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 50;
      box-shadow: 0 1px 8px rgba(236, 72, 153, .06);
    }

    .topbar-title {
      color: var(--pk-700);
      font-weight: 700;
      font-size: 1rem;
      margin: 0;
    }

    .content {
      padding: 24px 28px;
    }

    .stat-card {
      border: none;
      border-radius: 16px;
      padding: 22px 20px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 4px 16px rgba(236, 72, 153, .10);
    }

    .stat-card .stat-icon {
      position: absolute;
      right: 16px;
      top: 16px;
      font-size: 3rem;
      opacity: .15;
    }

    .stat-card .stat-label {
      font-size: .78rem;
      font-weight: 600;
      opacity: .85;
      margin-bottom: 6px;
    }

    .stat-card .stat-value {
      font-size: 2.2rem;
      font-weight: 800;
      line-height: 1;
    }

    .stat-card .stat-sub {
      font-size: .75rem;
      opacity: .7;
      margin-top: 4px;
    }

    .sc-pink {
      background: linear-gradient(135deg, var(--pk-500), var(--pk-600));
      color: #fff;
    }

    .sc-green {
      background: linear-gradient(135deg, #10b981, #059669);
      color: #fff;
    }

    .sc-blue {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      color: #fff;
    }

    .sc-red {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: #fff;
    }

    .sc-safe {
      background: linear-gradient(135deg, #10b981, #059669);
      color: #fff;
    }


    .card {
      border: none;
      border-radius: 14px;
      box-shadow: 0 2px 14px rgba(236, 72, 153, .07);
      background: #fff;
    }

    .card-header-pink {
      background: linear-gradient(90deg, var(--pk-50), #fff);
      border-bottom: 1px solid var(--pk-100);
      padding: 14px 20px;
      border-radius: 14px 14px 0 0;
    }

    .card-header-pink h6 {
      color: var(--pk-700);
      font-weight: 700;
      margin: 0;
    }

    .table thead th {
      background: var(--pk-100);
      color: var(--pk-700);
      font-size: .79rem;
      font-weight: 700;
      border: none;
      padding: 10px 14px;
    }

    .table td {
      vertical-align: middle;
      font-size: .84rem;
      padding: 9px 14px;
    }

    .table-hover tbody tr:hover {
      background: var(--pk-50);
    }

    .badge-valid {
      background: #d1fae5;
      color: #065f46;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: .75rem;
      font-weight: 600;
    }

    .badge-invalid {
      background: #fee2e2;
      color: #991b1b;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: .75rem;
      font-weight: 600;
    }

    .badge-rm {
      background: var(--pk-100);
      color: var(--pk-700);
      font-size: .75rem;
    }

    .badge-role-admin {
      background: var(--pk-100);
      color: var(--pk-700);
      font-size: .78rem;
    }

    .badge-role-user {
      background: #dbeafe;
      color: #1d4ed8;
      font-size: .78rem;
    }

    .btn-pink {
      background: var(--pk-500);
      color: #fff;
      border: none;
    }

    .btn-pink:hover {
      background: var(--pk-600);
      color: #fff;
    }

    .btn-pink-outline {
      border: 1.5px solid var(--pk-400);
      color: var(--pk-600);
      background: transparent;
    }

    .btn-pink-outline:hover {
      background: var(--pk-100);
      color: var(--pk-700);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--pk-400);
      box-shadow: 0 0 0 3px rgba(244, 114, 182, .15);
    }

    .form-label {
      font-weight: 600;
      font-size: .85rem;
      color: #374151;
    }

    .page-link {
      color: var(--pk-600);
    }

    .page-item.active .page-link {
      background: var(--pk-500);
      border-color: var(--pk-500);
    }

    .hash-text {
      font-family: 'Courier New', monospace;
      font-size: .68rem;
      color: #9ca3af;
      word-break: break-all;
      line-height: 1.6;
    }

    .hash-box {
      background: var(--pk-50);
      border: 1px solid var(--pk-200);
      border-radius: 8px;
      padding: 10px 14px;
    }

    .integrity-bar {
      height: 10px;
      border-radius: 5px;
      background: rgba(255, 255, 255, .3);
      overflow: hidden;
    }

    .integrity-fill {
      height: 100%;
      background: rgba(255, 255, 255, .8);
      transition: width .6s;
    }

    .alert-trust {
      background: linear-gradient(135deg, #d1fae5, #a7f3d0);
      border: 1px solid #6ee7b7;
      color: #065f46;
      border-radius: 12px;
    }

    .alert-danger-custom {
      background: linear-gradient(135deg, #fee2e2, #fecaca);
      border: 1px solid #f87171;
      color: #991b1b;
      border-radius: 12px;
    }

    .login-wrap {
      min-height: 100vh;
      display: flex;
      align-items: stretch;
    }

    .login-left {
      width: 50%;
      flex: none;
      background: linear-gradient(145deg, var(--pk-700) 0%, var(--pk-500) 55%, #f472b6 100%);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 60px 40px;
      position: relative;
      overflow: hidden;
    }

    .login-left::before {
      content: '';
      position: absolute;
      width: 340px;
      height: 340px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .06);
      top: -80px;
      left: -80px;
    }

    .login-left::after {
      content: '';
      position: absolute;
      width: 260px;
      height: 260px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .06);
      bottom: -60px;
      right: -60px;
    }

    .login-left .circle-deco {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .12);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 32px;
      position: relative;
      z-index: 1;
      border: 2px solid rgba(255, 255, 255, .2);
    }

    .login-left .circle-deco i {
      font-size: 5rem;
      color: #fff;
    }

    .login-left h2 {
      color: #fff;
      font-weight: 800;
      font-size: 1.7rem;
      text-align: center;
      margin-bottom: 12px;
      position: relative;
      z-index: 1;
    }

    .login-left p {
      color: rgba(255, 255, 255, .75);
      text-align: center;
      font-size: .88rem;
      line-height: 1.7;
      position: relative;
      z-index: 1;
      max-width: 280px;
    }


    .login-right {
      width: 50%;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 52px;
    }

    .login-card {
      width: 100%;
    }

    .login-card .login-title {
      font-size: 1.6rem;
      font-weight: 800;
      color: var(--pk-700);
      margin-bottom: 6px;
    }

    .login-card .login-sub {
      font-size: .85rem;
      color: #9ca3af;
      margin-bottom: 32px;
    }


    .login-input-wrap {
      position: relative;
      margin-bottom: 18px;
    }

    .login-input-wrap label {
      display: block;
      font-size: .82rem;
      font-weight: 600;
      color: #374151;
      margin-bottom: 7px;
    }

    .login-input-wrap .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(calc(-50% + 14px));
      color: var(--pk-400);
      font-size: 1rem;
      pointer-events: none;
    }

    .login-input-wrap input {
      width: 100%;
      padding: 12px 44px;
      border: 1.5px solid #e5e7eb;
      border-radius: 12px;
      font-size: .92rem;
      background: #fafafa;
      transition: border-color .2s, box-shadow .2s, background .2s;
      outline: none;
    }

    .login-input-wrap input:focus {
      border-color: var(--pk-400);
      box-shadow: 0 0 0 3px rgba(244, 114, 182, .15);
      background: #fff;
    }

    .login-input-wrap .toggle-pw {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(calc(-50% + 14px));
      background: none;
      border: none;
      cursor: pointer;
      color: #9ca3af;
      font-size: 1rem;
      padding: 0;
      transition: color .2s;
    }

    .login-input-wrap .toggle-pw:hover {
      color: var(--pk-500);
    }

    .btn-login {
      width: 100%;
      padding: 13px;
      background: linear-gradient(135deg, var(--pk-600), var(--pk-500));
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: .95rem;
      font-weight: 700;
      cursor: pointer;
      transition: all .2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 8px;
      box-shadow: 0 4px 16px rgba(219, 39, 119, .3);
    }

    .btn-login:hover {
      background: linear-gradient(135deg, var(--pk-700), var(--pk-600));
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(219, 39, 119, .4);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .demo-box {
      margin-top: 24px;
      padding: 14px 18px;
      background: var(--pk-50);
      border: 1px solid var(--pk-200);
      border-radius: 12px;
    }

    .demo-box .demo-title {
      font-size: .78rem;
      font-weight: 700;
      color: var(--pk-700);
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .demo-box table {
      width: 100%;
    }

    .demo-box td,
    .demo-box th {
      font-size: .78rem;
      padding: 3px 8px 3px 0;
      color: #374151;
    }

    .demo-box th {
      color: var(--pk-700);
      font-weight: 700;
    }

    .demo-box code {
      background: var(--pk-100);
      color: var(--pk-700);
      padding: 1px 6px;
      border-radius: 4px;
      font-size: .76rem;
    }

    @media (min-width: 769px) {
      .login-left {
        display: flex;
        width: 50%;
        flex: none;
      }

      .login-right {
        width: 50%;
      }
    }

    @media (max-width: 768px) {
      .login-left {
        display: none;
      }

      .login-right {
        width: 100%;
        padding: 40px 28px;
      }
    }
  </style>
</head>

<body>

  <?php
  ?>

  <?php if ($page === 'login'): ?>
    <div class="login-wrap">

      <!-- Panel Kiri — Dekorasi -->
      <div class="login-left">
        <div class="circle-deco">
          <i class="bi bi-hospital-fill"></i>
        </div>
        <h2>Sistem Rekam Medis</h2>
        <p>Platform pengelolaan rekam medis digital yang aman dan terpercaya.</p>

      </div>

      <div class="login-right">
        <div class="login-card">

          <div class="login-title">Selamat Datang 👋</div>
          <div class="login-sub">Masuk ke sistem untuk melanjutkan</div>

          <?php if (session()->getFlashdata('error')): ?>
            <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;
                    padding:10px 14px;border-radius:10px;font-size:.83rem;margin-bottom:16px;">
              <i class="bi bi-exclamation-circle me-1"></i>
              <?= session()->getFlashdata('error') ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="/login">
            <?= csrf_field() ?>

            <div class="login-input-wrap">
              <label>Username</label>
              <i class="bi bi-person-fill input-icon"></i>
              <input type="text"
                name="username"
                placeholder="Masukkan username"
                required
                autocomplete="off"
                value="">
            </div>

            <div class="login-input-wrap">
              <label>Password</label>
              <i class="bi bi-lock-fill input-icon"></i>
              <input type="password"
                id="inputPassword"
                name="password"
                placeholder="Masukkan password"
                required
                autocomplete="new-password"
                value="">
              <button type="button"
                class="toggle-pw"
                id="togglePw"
                onclick="togglePassword()"
                title="Tampilkan / sembunyikan password">
                <i class="bi bi-eye" id="eyeIcon"></i>
              </button>
            </div>

            <button type="submit" class="btn-login">
              <i class="bi bi-box-arrow-in-right"></i> Masuk ke Sistem
            </button>
          </form>

          <!-- Akun Demo -->
          <div class="demo-box">
            <div class="demo-title">
              <i class="bi bi-info-circle"></i> Akun Demo
            </div>
            <table>
              <tr>
                <th>Role</th>
                <th>Username</th>
                <th>Password</th>
              </tr>
              <tr>
                <td><span class="badge badge-role-admin rounded-pill">Admin</span></td>
                <td><code>admin</code></td>
                <td><code>Admin@2007</code></td>
              </tr>
              <tr>
                <td><span class="badge badge-role-user rounded-pill">User</span></td>
                <td><code>user</code></td>
                <td><code>user@2026</code></td>
              </tr>
            </table>
          </div>

        </div>
      </div>
    </div>

    <script>
      function togglePassword() {
        const input = document.getElementById('inputPassword');
        const icon = document.getElementById('eyeIcon');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
      }

      window.addEventListener('load', function() {
        var u = document.querySelector('input[name="username"]');
        var p = document.getElementById('inputPassword');
        if (u) u.value = '';
        if (p) p.value = '';
      });
    </script>

  <?php else: ?>

    <nav class="sidebar">
      <div class="brand">
        <i class="bi bi-hospital-fill"></i>
        <div>
          E-MECOMS
        </div>
      </div>

      <?php

      $uri = uri_string();
      ?>

      <div class="nav-section">Menu Utama</div>

      <a href="/dashboard" class="<?= $uri === 'dashboard' ? 'active' : '' ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
      <a href="/pasien" class="<?= str_starts_with($uri, 'pasien') ? 'active' : '' ?>">
        <i class="bi bi-people-fill"></i> Data Pasien
      </a>
      <a href="/kunjungan" class="<?= str_starts_with($uri, 'kunjungan') ? 'active' : '' ?>">
        <i class="bi bi-clipboard-pulse"></i> Riwayat Kunjungan
      </a>

      <div class="nav-section mt-2">security system</div>

      <a href="/verify" class="<?= str_starts_with($uri, 'verify') ? 'active' : '' ?>">
        <i class="bi bi-shield-check"></i> Verify Data
      </a>

      <hr class="sep">
      <a href="/logout" class="logout-link">
        <i class="bi bi-box-arrow-left"></i> Keluar
      </a>
    </nav>

    <div class="main-wrap">

      <div class="topbar">
        <div>
          <p class="topbar-title mb-0"><?= esc($title ?? '') ?></p>
          <small class="text-muted" style="font-size:.75rem">
            Electronic Medical Record Monitoring System
          </small>
        </div>
        <div class="d-flex align-items-center gap-3">

          <span class="text-muted small">
            <i class="bi bi-calendar3 me-1" style="color:var(--pk-500)"></i>
            <?= date('d M Y') ?>
          </span>

          <div class="d-flex align-items-center gap-2">
            <div style="width:34px;height:34px;border-radius:50%;
                    background:var(--pk-100);display:flex;align-items:center;
                    justify-content:center;color:var(--pk-600)">
              <i class="bi bi-person-fill"></i>
            </div>
            <div>
              <div style="font-size:.82rem;font-weight:600;color:#1f2937;line-height:1.2">
                <?= esc(session()->get('name')) ?>
              </div>
              <span class="badge badge-role-<?= session()->get('role') ?> rounded-pill px-2">
                <?= strtoupper(session()->get('role')) ?>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="px-4 pt-3">
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-trust py-2 mb-0">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= session()->getFlashdata('success') ?>
          </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger-custom py-2 mb-0">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= session()->getFlashdata('error') ?>
          </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger-custom py-2 mb-0">
            <ul class="mb-0 ps-3">
              <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <li class="small"><?= esc($e) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>

      <div class="content">

        <?php
        ?>
        <?php if ($page === 'dashboard'): ?>

          <div class="mb-4">
            <h4 class="fw-bold" style="color:#1f2937">
              Selamat datang, <?= esc(session()->get('name')) ?> 👋
            </h4>

          </div>

          <div class="row g-3 mb-4">

            <div class="col-6 col-xl-3">
              <div class="stat-card sc-pink">
                <i class="bi bi-people-fill stat-icon"></i>
                <div class="stat-label">Total Pasien</div>
                <div class="stat-value"><?= (int)($stats['total_data'] ?? 0) ?></div>
                <div class="stat-sub">Terdaftar dalam sistem</div>
              </div>
            </div>

            <div class="col-6 col-xl-3">
              <div class="stat-card sc-green">
                <i class="bi bi-clipboard-pulse stat-icon"></i>
                <div class="stat-label">Total Kunjungan</div>
                <div class="stat-value"><?= (int)($stats['total_data'] ?? 0) ?></div>
                <div class="stat-sub">Semua riwayat</div>
              </div>
            </div>

            <div class="col-6 col-xl-3">
              <div class="stat-card sc-blue">
                <i class="bi bi-calendar-check stat-icon"></i>
                <div class="stat-label">Kunjungan Hari Ini</div>
                <div class="stat-value"><?= (int)($stats['hari_ini'] ?? 0) ?></div>
                <div class="stat-sub"><?= date('d M Y') ?></div>
              </div>
            </div>

            <?php $totalInvalidDash = (int)($stats['total_invalid'] ?? 0); ?>
            <div class="col-6 col-xl-3">
              <?php if ($totalInvalidDash > 0): ?>
                <div class="stat-card sc-red">
                  <i class="bi bi-shield-x stat-icon"></i>
                  <div class="stat-label">Data Bermasalah</div>
                  <div class="stat-value"><?= $totalInvalidDash ?></div>
                  <div class="stat-sub">Hash tidak cocok!</div>
                </div>
              <?php else: ?>
                <div class="stat-card sc-safe">
                  <i class="bi bi-shield-check stat-icon"></i>
                  <div class="stat-label">Integritas Data</div>
                  <div class="stat-value"><i class="bi bi-check-lg" style="font-size:2rem"></i></div>
                  <div class="stat-sub">Semua data aman</div>
                </div>
              <?php endif; ?>
            </div>

          </div>

          <?php if ($totalInvalidDash > 0): ?>
            <div class="alert alert-danger-custom p-3 mb-4">
              <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                <div>
                  <strong>Peringatan Integritas Data!</strong><br>
                  <span class="small">
                    Ditemukan <strong><?= $totalInvalidDash ?> data</strong> yang hash SHA-256-nya tidak cocok.
                    Kemungkinan ada manipulasi ilegal langsung di database.
                  </span>
                </div>
                <a href="/verify" class="btn btn-sm ms-auto"
                  style="background:#991b1b;color:#fff;white-space:nowrap">
                  <i class="bi bi-shield-check me-1"></i>Cek Sekarang
                </a>
              </div>
            </div>
          <?php endif; ?>

          <div class="row g-3 mb-4">
            <div class="col-12">
              <div class="card p-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                  <i class="bi bi-lightning-fill" style="color:var(--pk-500)"></i>
                  <h6 class="fw-bold mb-0" style="color:var(--pk-700)">Aksi Cepat</h6>
          </div>


<div class="d-flex gap-2">
    <?php if (session()->get('role') === 'admin'): ?>
<a href="/pasien/create" class="btn btn-pink btn-sm">
    <i class="bi bi-person-plus me-1"></i>Tambah Pasien
</a>
<?php endif; ?>

    <a href="/verify" class="btn btn-pink-outline btn-sm">
        <i class="bi bi-shield-check me-1"></i>Verifikasi Integritas
    </a>
</div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header-pink d-flex justify-content-between align-items-center">
              <h6 class="fw-bold mb-0" style="color:var(--pk-700)">
                <i class="bi bi-clock-history me-2"></i>Kunjungan Terbaru
              </h6>
              <a href="/kunjungan" class="btn btn-sm btn-pink-outline">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
            <div class="p-3">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th>No.RM</th>
                    <th>Nama Pasien</th>
                    <th>Dokter</th>
                    <th>Diagnosa</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $recentKunjungan = $stats['terbaru'] ?? []; ?>
                  <?php if (!empty($recentKunjungan)): ?>
                    <?php foreach ($recentKunjungan as $r): ?>
                      <tr>
                        <td>
                          <span class="badge badge-rm rounded-pill">
                            <?= esc((string)($r['no_rm'] ?? '')) ?>
                          </span>
                        </td>
                        <td class="fw-semibold">
  <?= esc((string)($r['nama_pasien'] ?? '')) ?>
</td>

<td><?= esc((string)($r['dokter'] ?? '')) ?></td>
                        <td><?= esc(mb_strimwidth((string)($r['diagnosa'] ?? ''), 0, 40, '...')) ?></td>
                        <td>
                          <span class="text-muted small">
                            <?= esc((string)($r['tanggal_kunjungan'] ?? '')) ?>
                          </span>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada data kunjungan. Silakan input kunjungan pertama.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>


          <?php
          ?>
        <?php elseif ($page === 'pasien' || $page === 'rekam_medis'): ?>
          <?php $pasien = isset($pasien) ? $pasien : (isset($data) ? $data : null); ?>
<div class="card">

    <div class="card-header-pink d-flex justify-content-between align-items-center flex-wrap gap-2">

        <form method="GET" class="d-flex gap-2">
            <input
                type="text"
                name="q"
                value="<?= esc($search) ?>"
                class="form-control form-control-sm"
                placeholder="Cari pasien..."
                style="width:250px">

            <button class="btn btn-sm btn-pink-outline">
                <i class="bi bi-search"></i>
            </button>

            <?php if ($search): ?>
                <a href="/pasien" class="btn btn-sm btn-outline-secondary">
                    Reset
                </a>
            <?php endif; ?>
        </form>

        <?php if (session()->get('role') === 'admin'): ?>
<a href="/rekam-medis/create" class="btn btn-pink btn-sm">
    <i class="bi bi-plus-circle me-1"></i>
    Tambah Pasien
</a>
<?php endif; ?>

    </div>

    <div class="p-3">
        <div class="table-responsive">

            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No.RM</th>
                        <th>Nama Pasien</th>
                        <th>NIK</th>
                        <th>Jenis Kelamin</th>
                        <th>No.HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($rows)): ?>
                        <?php $no = 1; ?>

                        <?php foreach ($rows as $r): ?>
                            <tr>

                                <td><?= $no++ ?></td>

                                <td>
                                    <span class="badge badge-rm rounded-pill">
                                        <?= esc($r['no_rm'] ?? '-') ?>
                                    </span>
                                </td>

                                
<td><?= esc($r['nama_pasien'] ?? '-') ?></td>
<td><?= esc($r['nik'] ?? '-') ?></td>
<td><?= esc($r['jenis_kelamin'] ?? '-') ?></td>
<td><?= esc($r['no_hp'] ?? '-') ?></td>
<td class="d-flex gap-1">
  <?php if (session()->get('role') === 'admin'): ?>
  <a href="/pasien/edit/<?= $r['id'] ?>" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i>
  </a>
  <?php endif; ?>
  <a href="/pasien/detail/<?= $r['id'] ?>" class="btn btn-sm btn-info text-white">
    <i class="bi bi-eye"></i>
  </a>
</td>

                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Data pasien belum tersedia.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>
    </div>
</div>



              <?php $totalPages = (int)ceil($total / $perPage); ?>
              <?php if ($totalPages > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-2">
                  <small class="text-muted">Total <?= $total ?> kunjungan</small>
                  <nav>
                    <ul class="pagination pagination-sm mb-0">
                      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currPage ? 'active' : '' ?>">
                          <a class="page-link"
                            href="?<?= http_build_query(['q' => $search, 'page' => $i]) ?>">
                            <?= $i ?>
                          </a>
                        </li>
                      <?php endfor; ?>
                    </ul>
                  </nav>
                </div>
              <?php endif; ?>
            </div>
          </div>

          </table>
      </div>
    </div>
    </div>

          <?php elseif ($page === 'pasien_form'): ?>
<div class="row">
  <div class="col-lg-7">
    <div class="card p-4">
      <?php if (is_array($pasien)): ?>
      <div class="mb-3 p-3 hash-box">
        <p class="small fw-bold mb-1" style="color:var(--pk-700)">
          <i class="bi bi-shield me-1"></i>Hash SHA-256 Tersimpan
        </p>
        <div class="hash-text"><?= esc((string)($pasien['hash_code'] ?? '')) ?></div>
      </div>
      <?php endif; ?>
      <form method="POST"
            action="<?= is_array($pasien) ? '/pasien/update/'.(int)($pasien['id'] ?? 0) : '/rekam-medis/store' ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama_pasien" class="form-control" required
                   value="<?= esc((string) old('nama_pasien', $pasien['nama_pasien'] ?? '')) ?>"
                   placeholder="Nama lengkap pasien">
          </div>
          <div class="col-md-6">
            <label class="form-label">NIK (16 digit) <span class="text-danger">*</span></label>
            <input type="text" name="nik" class="form-control" maxlength="16" required
                   value="<?= esc((string) old('nik', $pasien['nik'] ?? '')) ?>"
                   placeholder="3273xxxxxxxxxxxx">
          </div>
          <div class="col-md-6">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" class="form-control"
                   value="<?= esc((string) old('tgl_lahir', $pasien['tgl_lahir'] ?? '')) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Jenis Kelamin</label>
            <?php $selectedJK = (string) old('jenis_kelamin', $pasien['jenis_kelamin'] ?? 'L'); ?>
            <select name="jenis_kelamin" class="form-select">
              <option value="L" <?= $selectedJK==='L'?'selected':'' ?>>Laki-laki</option>
              <option value="P" <?= $selectedJK==='P'?'selected':'' ?>>Perempuan</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2"
                      placeholder="Alamat lengkap..."><?= esc((string) old('alamat', $pasien['alamat'] ?? '')) ?></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">No. HP</label>
            <input type="text" name="no_hp" class="form-control"
                   value="<?= esc((string) old('no_hp', $pasien['no_hp'] ?? '')) ?>"
                   placeholder="08xxxxxxxxxx">
          </div>
          <div class="col-12">
            <label class="form-label">Keluhan <span class="text-danger">*</span></label>
            <textarea name="keluhan" class="form-control" rows="2" required
                      placeholder="Keluhan pasien..."><?= esc((string) old('keluhan', $pasien['keluhan'] ?? '')) ?></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Diagnosa <span class="text-danger">*</span></label>
            <textarea name="diagnosa" class="form-control" rows="2" required
                      placeholder="Diagnosa medis..."><?= esc((string) old('diagnosa', $pasien['diagnosa'] ?? '')) ?></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Tindakan</label>
            <textarea name="tindakan" class="form-control" rows="2"
                      placeholder="Tindakan/terapi..."><?= esc((string) old('tindakan', $pasien['tindakan'] ?? '')) ?></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Dokter <span class="text-danger">*</span></label>
            <input type="text" name="dokter" class="form-control" required
                   value="<?= esc((string) old('dokter', $pasien['dokter'] ?? '')) ?>"
                   placeholder="dr. ...">
          </div>
          <div class="col-md-6">
            <label class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_kunjungan" class="form-control" required
                   value="<?= esc((string) old('tanggal_kunjungan', $pasien['tanggal_kunjungan'] ?? date('Y-m-d'))) ?>">
          </div>
          <div class="col-12 d-flex gap-2 pt-2">
            <button class="btn btn-pink px-4">
              <i class="bi bi-save me-1"></i>
              <?= is_array($pasien) ? 'Simpan Perubahan' : 'Daftarkan Pasien' ?>
            </button>
            <a href="/pasien" class="btn btn-outline-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card p-4" style="background:var(--pk-50);border:1px solid var(--pk-200)!important">
      <h6 class="fw-bold mb-3" style="color:var(--pk-700)">
        <i class="bi bi-shield-lock me-2"></i>Tentang Integritas Data
      </h6>
      <ul class="small text-muted ps-3" style="line-height:1.9">
        <li>Setiap data pasien dilindungi dengan <strong>hash SHA-256</strong></li>
        <li>Hash dibuat saat data pertama disimpan</li>
        <li>Saat data diedit, hash <strong>diregenerasi otomatis</strong></li>
        <li>Sistem mendeteksi jika ada manipulasi ilegal</li>
      </ul>
    </div>
  </div>
</div>

  <?php elseif ($page == 'detail'): ?>
    <?php $detail = $detail ?? []; ?>

    <div class="card border-0 shadow-sm rounded-4 p-4">

      <h3 class="mb-4 text-pink fw-bold">
        Detail Rekam Medis
      </h3>

      <table class="table">

        <tr>
          <th>No.RM</th>
          <td><?= $detail['no_rm']; ?></td>
        </tr>

        <tr>
          <th>Nama Pasien</th>
          <td><?= $detail['nama_pasien']; ?></td>
        </tr>

        <tr>
          <th>NIK</th>
          <td><?= $detail['nik']; ?></td>
        </tr>

        <tr>
          <th>Tanggal Lahir</th>
          <td><?= $detail['tgl_lahir']; ?></td>
        </tr>

        <tr>
          <th>Keluhan</th>
          <td><?= $detail['keluhan']; ?></td>
        </tr>

        <tr>
          <th>Diagnosa</th>
          <td><?= $detail['diagnosa']; ?></td>
        </tr>

        <tr>
          <th>Dokter</th>
          <td><?= $detail['dokter']; ?></td>
        </tr>

        <tr>
          <th>Tanggal Kunjungan</th>
          <td><?= $detail['tanggal_kunjungan']; ?></td>
        </tr>

        <tr>
          <th>Hash SHA-256</th>
          <td style="font-size:12px">
            <?= $detail['hash_code']; ?>
          </td>
        </tr>

      </table>

    </div>


        <?php elseif ($page === 'kunjungan'): ?>
<div class="card">
  <div class="card-header-pink d-flex justify-content-between align-items-center flex-wrap gap-2">
    <form method="GET" class="d-flex gap-2">
      <input name="q" value="<?= esc($search) ?>" class="form-control form-control-sm"
             placeholder="Cari nama / diagnosa..." style="width:260px">
      <button class="btn btn-sm btn-pink-outline"><i class="bi bi-search"></i></button>
      <?php if ($search): ?>
        <a href="/kunjungan" class="btn btn-sm btn-outline-secondary">Reset</a>
      <?php endif; ?>
    </form>
  </div>
  <div class="p-3 table-responsive">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>No</th><th>No.RM</th><th>Nama Pasien</th>
          <th>Dokter</th><th>Diagnosa</th><th>Tgl Kunjungan</th><th>Hash</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!empty($rows)): ?>
        <?php $no = 1; foreach ($rows as $r): ?>
        <tr>
          
          <td><?= $no++ ?></td>
<td><span class="badge badge-rm rounded-pill"><?= esc($r['no_rm'] ?? '-') ?></span></td>
<td><?= esc($r['nama_pasien'] ?? '-') ?></td>
<td><?= esc($r['dokter'] ?? '-') ?></td>
<td><?= esc(mb_strimwidth($r['diagnosa'] ?? '', 0, 35, '...')) ?></td>
<td><?= esc($r['tanggal_kunjungan'] ?? '-') ?></td>
<td>
  <?= ($r['is_valid'] ?? false)
    ? '<span class="badge-valid"><i class="bi bi-shield-check me-1"></i>Valid</span>'
    : '<span class="badge-invalid"><i class="bi bi-shield-x me-1"></i>!</span>' ?>
</td>
          
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="7" class="text-center py-4 text-muted">
          <i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada data kunjungan.
        </td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
    
  <?php elseif ($page === 'kunjungan_form'): ?>

    <div class="alert" style="background:var(--pk-100);border:1.5px solid var(--pk-300);
                               border-radius:12px;color:var(--pk-700)">
      <i class="bi bi-exclamation-triangle me-2"></i>
      <strong>Perhatian:</strong> Data kunjungan yang telah disimpan
      <strong>tidak dapat diubah atau dihapus</strong> oleh siapapun.
      Pastikan semua informasi benar sebelum menyimpan.
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="card p-4">
          <form method="POST" action="/kunjungan/store">
            <?= csrf_field() ?>
            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label">
                  Pasien <span class="text-danger">*</span>
                </label>
                <select name="pasien_id" class="form-select" required>
                  <option value="">-- Pilih Pasien --</option>
                  <?php foreach ($semuaPasien as $sp): ?>
                    <option value="<?= (int)($sp['id'] ?? 0) ?>">
                      <?= esc((string)($sp['no_rm'] ?? '') . ' — ' . (string)($sp['nama'] ?? '')) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Tanggal Kunjungan</label>
                <input type="date" name="tanggal_kunjungan" class="form-control"
                  value="<?= date('Y-m-d') ?>">
              </div>

              <div class="col-12">
                <label class="form-label">
                  Keluhan Utama <span class="text-danger">*</span>
                </label>
                <textarea name="keluhan" class="form-control" rows="2" required
                  placeholder="Deskripsikan keluhan yang disampaikan pasien..."></textarea>
              </div>

              <div class="col-12">
                <label class="form-label">
                  Diagnosa <span class="text-danger">*</span>
                </label>
                <textarea name="diagnosa" class="form-control" rows="2" required
                  placeholder="Diagnosa medis berdasarkan pemeriksaan..."></textarea>
              </div>

              <div class="col-12">
                <label class="form-label">Tindakan / Terapi</label>
                <textarea name="tindakan" class="form-control" rows="2"
                  placeholder="Tindakan, resep obat, atau saran medis..."></textarea>
              </div>

              <div class="col-md-6">
                <label class="form-label">
                  Nama Dokter <span class="text-danger">*</span>
                </label>
                <input type="text" name="dokter" class="form-control" required
                  placeholder="dr. ...">
              </div>

              <div class="col-12 d-flex gap-2 pt-2">
                <button class="btn btn-pink px-4">
                  <i class="bi bi-lock me-1"></i>Simpan & Kunci Data
                </button>
                <a href="/kunjungan" class="btn btn-outline-secondary">Batal</a>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card p-4" style="background:var(--pk-50);border:1px solid var(--pk-200)!important">
          <h6 class="fw-bold mb-3" style="color:var(--pk-700)">
            <i class="bi bi-lock-fill me-2"></i>Mengapa Data Terkunci?
          </h6>
          <ul class="small text-muted ps-3" style="line-height:1.9">
            <li>Rekam medis adalah dokumen <strong>hukum</strong></li>
            <li>Data harus <strong>akurat dan tidak bisa diubah</strong> secara sepihak</li>
            <li>Hash SHA-256 dikunci saat penyimpanan pertama</li>
            <li>Jika data diubah langsung di DB, hash tidak cocok dan sistem akan <strong>mendeteksi manipulasi</strong></li>
          </ul>
        </div>
      </div>
    </div>


    <?php
    ?>
  <?php elseif ($page === 'kunjungan_detail'): ?>

    <div class="row g-4">

      <div class="col-md-7">
        <div class="card p-4">
          <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge badge-rm rounded-pill fs-6 px-3 py-2">
              <?= esc((string)($k['no_rm'] ?? '')) ?>
            </span>
            <h5 class="fw-bold mb-0"><?= esc((string)($k['nama'] ?? '')) ?></h5>
          </div>
          <table class="table table-bordered table-sm">
            <tr>
              <th width="170" style="background:var(--pk-50)">Tanggal Kunjungan</th>
              <td><?= esc((string)($k['tanggal_kunjungan'] ?? '')) ?></td>
            </tr>
            <tr>
              <th style="background:var(--pk-50)">Dokter Pemeriksa</th>
              <td><?= esc((string)($k['dokter'] ?? '')) ?></td>
            </tr>
            <tr>
              <th style="background:var(--pk-50)">Keluhan Utama</th>
              <td><?= esc((string)($k['keluhan'] ?? '')) ?></td>
            </tr>
            <tr>
              <th style="background:var(--pk-50)">Diagnosa</th>
              <td><?= esc((string)($k['diagnosa'] ?? '')) ?></td>
            </tr>
            <tr>
              <th style="background:var(--pk-50)">Tindakan / Terapi</th>
              <td><?= esc((string)($k['tindakan'] ?? '') ?: '—') ?></td>
            </tr>
            <tr>
              <th style="background:var(--pk-50)">Dicatat pada</th>
              <td class="text-muted small"><?= esc((string)($k['created_at'] ?? '')) ?></td>
            </tr>
          </table>
          <a href="/kunjungan" class="btn btn-outline-secondary btn-sm mt-2">
            <i class="bi bi-arrow-left me-1"></i>Kembali
          </a>
        </div>
      </div>

      <div class="col-md-5">
        <div class="card p-4 h-100">
          <h6 class="fw-bold mb-3" style="color:var(--pk-700)">
            <i class="bi bi-shield me-2"></i>Verifikasi Integritas SHA-256
          </h6>

          <p class="small text-muted mb-1 fw-semibold">Hash tersimpan di database:</p>
          <div class="hash-box mb-3">
            <div class="hash-text"><?= esc((string)($k['hash_code'] ?? '')) ?></div>
          </div>

          <p class="small text-muted mb-1 fw-semibold">Hash dihitung ulang dari data saat ini:</p>
          <div class="hash-box mb-3">
            <div class="hash-text"><?= esc((string)($k['computed'] ?? '')) ?></div>
          </div>

          <hr style="border-color:var(--pk-200)">

          <div class="text-center py-2">
            <?php if ($k['is_valid'] ?? false): ?>
              <div style="font-size:3.5rem;color:#10b981">
                <i class="bi bi-shield-check-fill"></i>
              </div>
              <div class="badge-valid mt-2 d-inline-block fs-6 px-4 py-2">
                ✓ Data Valid — Terverifikasi
              </div>
              <p class="text-muted small mt-3 mb-0">
                Kedua hash identik. Data rekam medis ini <strong>belum pernah dimanipulasi</strong>
                sejak pertama kali dicatat.
              </p>
            <?php else: ?>
              <div style="font-size:3.5rem;color:#ef4444">
                <i class="bi bi-shield-x-fill"></i>
              </div>
              <div class="badge-invalid mt-2 d-inline-block fs-6 px-4 py-2">
                ✗ Data Rusak / Dimanipulasi!
              </div>
              <p class="text-muted small mt-3 mb-0">
                Hash tidak cocok. Data ini <strong>kemungkinan telah diubah</strong>
                secara langsung di database tanpa melalui sistem.
              </p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>


    <?php
    ?>
  <?php elseif ($page === 'verify'): ?>

    <div class="row g-3 mb-4">

      <div class="col-md-4">
        <?php $pctValid = $totalRows > 0 ? round($totalValid / $totalRows * 100) : 100; ?>
        <div class="stat-card <?= $totalInvalid > 0 ? 'sc-red' : 'sc-safe' ?> h-100">
          <div style="font-size:3rem;opacity:.9">
            <i class="bi bi-shield-<?= $totalInvalid > 0 ? 'x' : 'check' ?>-fill"></i>
          </div>
          <div class="stat-value mt-2"><?= $totalValid ?>/<?= $totalRows ?></div>
          <div class="stat-label mt-1">
            <?= $totalInvalid > 0 ? "$totalInvalid data bermasalah!" : 'Semua data aman' ?>
          </div>

          <div class="integrity-bar mt-3">
            <div class="integrity-fill" style="width:<?= $pctValid ?>%"></div>
          </div>
          <small style="opacity:.8"><?= $pctValid ?>% integritas data</small>
        </div>
      </div>

      <div class="col-md-8 d-flex align-items-center">
        <div class="<?= $totalInvalid > 0 ? 'alert-danger-custom' : 'alert-trust' ?> p-4 rounded-3 w-100">
          <?php if ($totalInvalid === 0): ?>
            <h5 class="fw-bold mb-2">
              <i class="bi bi-check-circle-fill me-2"></i>Sistem Dalam Kondisi Aman
            </h5>
            <p class="small mb-0">
              Seluruh <strong><?= $totalRows ?> baris data</strong> (<?= count($pasienRows) ?> pasien
              + <?= count($kunjunganRows) ?> kunjungan) telah diverifikasi.
              Hash SHA-256 yang tersimpan <strong>identik</strong> dengan hash yang dihitung ulang
              dari data saat ini. Tidak ada tanda-tanda manipulasi ilegal.
            </p>
          <?php else: ?>
            <h5 class="fw-bold mb-2">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>Peringatan Integritas!
            </h5>
            <p class="small mb-0">
              Ditemukan <strong><?= $totalInvalid ?> dari <?= $totalRows ?> baris</strong>
              yang hash SHA-256-nya <strong>tidak cocok</strong>.
              Ini mengindikasikan data telah diubah secara langsung di database
              tanpa melalui sistem — tindakan ilegal yang melanggar integritas rekam medis.
              Segera laporkan ke administrator.
            </p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header-pink d-flex align-items-center gap-2">
        <h6 class="fw-bold mb-0" style="color:var(--pk-700)">
          <i class="bi bi-people me-2"></i>Verifikasi Data Pasien
        </h6>
        <span class="badge badge-rm rounded-pill"><?= count($pasienRows) ?> baris</span>
        <?php $invP = count(array_filter($pasienRows, fn($r) => !$r['is_valid'])); ?>
        <?php if ($invP > 0): ?>
          <span class="badge bg-danger rounded-pill"><?= $invP ?> bermasalah</span>
        <?php else: ?>
          <span class="badge bg-success rounded-pill">Semua valid</span>
        <?php endif; ?>
      </div>
      <div class="p-3 table-responsive">
        <table class="table table-sm table-hover mb-0">
          <thead>
            <tr>
              <th>No.RM</th>
              <th>Nama</th>
              <th>Hash Tersimpan (DB)</th>
              <th>Hash Terhitung Ulang</th>
              <th>Cocok?</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>

            

<?php foreach ($pasienRows as $p): ?>
  <tr class="<?= !($p['is_valid'] ?? true) ? 'table-danger' : '' ?>">
    <td><span class="badge badge-rm rounded-pill"><?= esc($p['no_rm'] ?? '-') ?></span></td>
    <td><?= esc($p['nama_pasien'] ?? '-') ?></td>
    <td class="hash-text"><?= esc(substr($p['stored'] ?? '', 0, 18)) ?>...</td>
    <td class="hash-text"><?= esc(substr($p['computed'] ?? '', 0, 18)) ?>...</td>
    <td><?= ($p['stored']??'')===($p['computed']??'')
      ? '<span class="text-success fw-bold">✓ Cocok</span>'
      : '<span class="text-danger fw-bold">✗ Berbeda</span>' ?></td>
    <td><?= ($p['is_valid']??false)
      ? '<span class="badge-valid"><i class="bi bi-shield-check me-1"></i>Terverifikasi</span>'
      : '<span class="badge-invalid"><i class="bi bi-shield-x me-1"></i>MANIPULASI!</span>' ?></td>
  </tr>
<?php endforeach; ?>
<?php if (empty($pasienRows)): ?>
  <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data pasien.</td></tr>
<?php endif; ?>

          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-header-pink d-flex align-items-center gap-2">
        <h6 class="fw-bold mb-0" style="color:var(--pk-700)">
          <i class="bi bi-clipboard-pulse me-2"></i>Verifikasi Data Kunjungan (Immutable)
        </h6>
        <span class="badge badge-rm rounded-pill"><?= count($kunjunganRows) ?> baris</span>
        <?php $invK = count(array_filter($kunjunganRows, fn($r) => !$r['is_valid'])); ?>
        <?php if ($invK > 0): ?>
          <span class="badge bg-danger rounded-pill"><?= $invK ?> bermasalah</span>
        <?php else: ?>
          <span class="badge bg-success rounded-pill">Semua valid</span>
        <?php endif; ?>
      </div>
      <div class="p-3 table-responsive">
        <table class="table table-sm table-hover mb-0">
          <thead>
            <tr>
              <th>No.RM</th>
              <th>Nama Pasien</th>
              <th>Dokter</th>
              <th>Diagnosa</th>
              <th>Tgl Kunjungan</th>
              <th>Hash Tersimpan</th>
              <th>Status</th>
            </tr>
          </thead>

          <tbody>
            <?php $no = 1 + (($currPage - 1) * $perPage); ?>

            <?php foreach ($kunjunganRows as $r): ?>
              <tr>

                <td>
                  <span class="badge badge-rm rounded-pill">
                    <?= esc($r['no_rm'] ?? '') ?>
                  </span>
                </td>

                <td><?= esc($r['nama_pasien'] ?? '-') ?></td>

                <td><?= esc($r['dokter'] ?? '-') ?></td>
<td><?= esc(mb_strimwidth($r['diagnosa'] ?? '', 0, 30, '...')) ?></td>
<td><?= esc($r['tanggal_kunjungan'] ?? '-') ?></td>
<td class="hash-text"><?= esc(substr($r['stored'] ?? '', 0, 14)) ?>...</td>

                <td>
  <?= ($r['is_valid'] ?? false)
    ? '<span class="badge-valid"><i class="bi bi-shield-check me-1"></i>Terverifikasi</span>'
    : '<span class="badge-invalid"><i class="bi bi-shield-x me-1"></i>MANIPULASI!</span>' ?>
</td>

              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php endif; ?>

</div>
</div>

<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>