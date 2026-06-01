<?php

$_page    = $_page    ?? 'login';
$batches  = $batches  ?? [];
$products = $products ?? [];
$shipments    = $shipments    ?? [];
$raw_batches  = $raw_batches  ?? [];
$productions  = $productions  ?? [];
$full_name    = $full_name    ?? '';
$success      = $success      ?? null;
$error        = $error        ?? null;
$shipment     = $shipment     ?? null;
$base_url     = $base_url     ?? base_url();
$search = $search ?? '';
$edit_product = $edit_product ?? null;
$edit_shipment = $edit_shipment ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SkincareTrace — Sistem Keterlacakan Produk</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>

:root {
  --cream:    #FFF5F8;
  --ivory:    #FFE8F0;
  --sand:     #F5C6D8;
  --warm:     #E8829A;
  --gold:     #D4607A;
  --deep:     #A33358;
  --bark:     #7A1F3D;
  --charcoal: #3D0A20;
  --green:    #C06080;
  --green-lt: #FFE0EC;
  --rose:     #E05070;
  --rose-lt:  #FFF0F3;
  --sky:      #B07898;
  --sky-lt:   #F5E8F2;
  --warn-lt:  #FFF0E8;
  --warn:     #C8603A;
  --radius:   12px;
  --radius-lg:20px;
  --shadow:   0 2px 16px rgba(60,30,10,0.10);
  --shadow-lg:0 8px 40px rgba(60,30,10,0.14);
}

* { margin:0; padding:0; box-sizing:border-box; }

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--cream);
  color: var(--charcoal);
  min-height: 100vh;
  font-size: 14px;
  line-height: 1.6;
}

h1,h2,h3,h4 { font-family: 'Cormorant Garamond', serif; font-weight: 600; }

a { color: var(--gold); text-decoration: none; }
a:hover { text-decoration: underline; }

.btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 20px; border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13px;
  font-weight: 500; cursor: pointer; border: none;
  transition: all .18s ease; letter-spacing: .01em;
}
.btn-primary   { background: var(--gold);   color: #fff; }
.btn-primary:hover { background: var(--deep); transform: translateY(-1px); }
.btn-secondary { background: var(--sand);   color: var(--bark); }
.btn-secondary:hover { background: var(--warm); color: #fff; }
.btn-green     { background: var(--green);  color: #fff; }
.btn-green:hover { background: #3a6347; transform: translateY(-1px); }
.btn-sky       { background: var(--sky);    color: #fff; }
.btn-sky:hover { background: #4a728e; transform: translateY(-1px); }
.btn-danger    { background: var(--rose);   color: #fff; }
.btn-danger:hover { background: #a85a55; }
.btn-sm { padding: 6px 14px; font-size: 12px; }
.btn-icon-only { padding: 7px 10px; }

.badge {
  display: inline-block; padding: 3px 10px; border-radius: 99px;
  font-size: 11px; font-weight: 600; letter-spacing: .03em;
}
.badge-green { background: var(--green-lt); color: var(--green); }
.badge-sky   { background: var(--sky-lt);   color: var(--sky);   }
.badge-gold  { background: var(--warn-lt);  color: var(--warn);  }
.badge-rose  { background: var(--rose-lt);  color: var(--rose);  }

.alert { padding: 12px 18px; border-radius: var(--radius); font-size: 13px; margin-bottom: 18px; }
.alert-success { background: var(--green-lt); color: var(--green); border-left: 4px solid var(--green); }
.alert-error   { background: var(--rose-lt);  color: var(--rose);  border-left: 4px solid var(--rose);  }

.layout { display: flex; min-height: 100vh; }

.sidebar {
  width: 240px; min-height: 100vh; background: linear-gradient(180deg, #7A1F3D 0%, #A33358 100%);
  display: flex; flex-direction: column; position: fixed; top:0; left:0; z-index: 100;
}
.sidebar-brand {
  padding: 28px 24px 20px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
}
.sidebar-brand .brand-logo {
  font-family: 'Cormorant Garamond', serif;
  font-size: 22px; font-weight: 700; color: var(--warm);
  line-height: 1.1;
}
.sidebar-brand .brand-sub {
  font-size: 11px; color: rgba(255,255,255,0.4); margin-top: 3px;
  letter-spacing: .08em; text-transform: uppercase;
}
.sidebar-user {
  padding: 16px 24px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
}
.sidebar-user .user-name { color: #fff; font-size: 13px; font-weight: 500; }
.sidebar-user .user-role {
  font-size: 11px; color: var(--warm);
  text-transform: uppercase; letter-spacing: .06em;
}
.sidebar-nav { flex:1; padding: 16px 0; }
.nav-item a {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 24px; color: rgba(255,255,255,0.6);
  font-size: 13px; transition: all .15s;
}
.nav-item a:hover, .nav-item.active a {
  color: #fff; background: rgba(255,255,255,0.07); text-decoration: none;
}
.nav-item.active a { border-left: 3px solid var(--warm); }
.sidebar-footer { padding: 16px 24px; }
.sidebar-footer a {
  color: rgba(255,255,255,0.4); font-size: 12px;
  display: flex; align-items: center; gap: 8px;
}
.sidebar-footer a:hover { color: var(--rose); text-decoration: none; }

.main { margin-left: 240px; flex:1; padding: 32px 36px; }

.topbar {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 28px;
}
.topbar h1 { font-size: 28px; color: var(--bark); }
.topbar-right { display: flex; gap: 10px; align-items: center; }

.stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
.stat-card {
  background: #fff; border-radius: var(--radius-lg); padding: 20px 22px;
  box-shadow: var(--shadow); border: 1px solid var(--sand);
}
.stat-card .stat-num { font-size: 32px; font-family: 'Cormorant Garamond', serif; color: var(--gold); font-weight: 700; }
.stat-card .stat-lbl { font-size: 12px; color: #888; margin-top: 2px; }

.card {
  background: #fff; border-radius: var(--radius-lg); padding: 24px;
  box-shadow: var(--shadow); border: 1px solid var(--sand); margin-bottom: 24px;
}
.card-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 18px;
}
.card-header h3 { font-size: 18px; color: var(--bark); }

.tbl { width: 100%; border-collapse: collapse; }
.tbl th {
  background: var(--ivory); color: var(--deep); font-size: 11px;
  text-transform: uppercase; letter-spacing: .06em;
  padding: 10px 14px; text-align: left; font-weight: 600;
}
.tbl td { padding: 12px 14px; border-bottom: 1px solid var(--sand); font-size: 13px; vertical-align: middle; }
.tbl td:first-child { white-space: nowrap; }
.tbl tr:last-child td { border-bottom: none; }
.tbl tr:hover td { background: var(--ivory); }
.tbl .code { font-family: monospace; background: var(--ivory); padding: 2px 8px; border-radius: 6px; font-size: 12px; }
.empty-row td { text-align: center; padding: 40px; color: #aaa; }

.form-panel {
  background: var(--ivory); border-radius: var(--radius-lg); padding: 24px;
  border: 1px solid var(--sand); margin-bottom: 24px;
}
.form-panel h3 { font-size: 18px; color: var(--bark); margin-bottom: 18px; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap: 14px; }
.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-group label { font-size: 12px; font-weight: 500; color: var(--deep); text-transform: uppercase; letter-spacing: .04em; }
.form-group input, .form-group select, .form-group textarea {
  padding: 9px 12px; border: 1.5px solid var(--sand); border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13px; background: #fff;
  transition: border .15s; outline: none; color: var(--charcoal);
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
  border-color: var(--warm);
}
.form-group textarea { resize: vertical; min-height: 70px; }
.form-full { grid-column: 1/-1; }

.qr-box {
  display: inline-flex; flex-direction: column; align-items: center;
  background: var(--ivory); border-radius: var(--radius-lg); padding: 16px;
  border: 1px solid var(--sand); gap: 10px;
}
.qr-box img { width: 120px; height: 120px; border-radius: 8px; }
.qr-box .qr-label { font-size: 11px; color: #888; text-align: center; }

.login-wrap {
  min-height: 100vh; display: flex; align-items: stretch;
}
.login-left {
  flex: 1; background: linear-gradient(160deg, #7A1F3D 0%, #A33358 50%, #C06080 100%);
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 60px 48px; position: relative; overflow: hidden;
}
.login-left::before {
  content: ''; position: absolute; top: -100px; left: -100px;
  width: 500px; height: 500px; border-radius: 50%;
  background: radial-gradient(circle, rgba(201,168,130,.15) 0%, transparent 65%);
}
.login-left::after {
  content: ''; position: absolute; bottom: -60px; right: -60px;
  width: 300px; height: 300px; border-radius: 50%;
  background: radial-gradient(circle, rgba(184,144,106,.12) 0%, transparent 65%);
}
.login-left-content { position: relative; z-index: 1; text-align: center; color: #fff; }
.login-left-content .ll-icon {
  width: 80px; height: 80px; background: rgba(201,168,130,.25);
  border: 2px solid rgba(201,168,130,.4); border-radius: 24px;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: 38px; margin-bottom: 28px;
}
.login-left-content h2 {
  font-family: 'Cormorant Garamond', serif; font-size: 38px;
  color: var(--warm); margin-bottom: 12px;
}
.login-left-content p { font-size: 14px; color: rgba(255,255,255,.55); line-height: 1.8; max-width: 300px; margin: 0 auto; }
.login-left-features { margin-top: 48px; display: flex; flex-direction: column; gap: 16px; }
.ll-feat {
  display: flex; align-items: center; gap: 14px;
  background: rgba(255,255,255,.06); border-radius: 12px; padding: 14px 18px;
  border: 1px solid rgba(255,255,255,.08);
}
.ll-feat .feat-icon { font-size: 22px; }
.ll-feat .feat-text { font-size: 13px; color: rgba(255,255,255,.7); }
.ll-feat .feat-text strong { color: #fff; display: block; font-size: 13px; margin-bottom: 2px; }

.login-divider {
  width: 1px; background: linear-gradient(to bottom, transparent, var(--sand), transparent);
  flex-shrink: 0;
}

.login-right {
  flex: 1; background: var(--cream);
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 60px 48px;
}
.login-card {
  background: #fff; border-radius: 24px;
  padding: 44px 44px 36px; width: 100%; max-width: 400px;
  box-shadow: var(--shadow-lg); border: 1px solid var(--sand);
}
.login-logo { text-align: center; margin-bottom: 28px; }
.login-logo .lg-icon {
  width: 56px; height: 56px; background: var(--warm); border-radius: 16px;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: 26px; margin-bottom: 12px;
}
.login-logo h1 { font-size: 26px; color: var(--bark); }
.login-logo p { font-size: 12px; color: #aaa; margin-top: 3px; }
.login-field { margin-bottom: 14px; }
.login-field label {
  display: block; font-size: 11px; font-weight: 600; color: var(--deep);
  text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px;
}
.login-field input {
  width: 100%; padding: 11px 14px; border: 1.5px solid var(--sand);
  border-radius: var(--radius); font-size: 14px; outline: none; transition: border .15s;
}
.login-field input:focus { border-color: var(--warm); }
.login-btn {
  width: 100%; padding: 13px; background: var(--gold); color: #fff;
  border: none; border-radius: var(--radius); font-size: 14px; font-weight: 600;
  cursor: pointer; margin-top: 8px; transition: background .2s;
  font-family: 'DM Sans', sans-serif;
}
.login-btn:hover { background: var(--deep); }
.login-demo {
  margin-top: 22px; background: var(--ivory); border-radius: var(--radius);
  padding: 14px 16px;
}
.login-demo p { font-size: 11px; color: var(--deep); font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .04em; }
.demo-acc { display: flex; flex-direction: column; gap: 5px; }
.demo-acc .acc-row {
  display: flex; justify-content: space-between; align-items: center;
  font-size: 12px; color: var(--bark); cursor: pointer; padding: 3px 0;
}
.demo-acc .acc-row:hover { color: var(--gold); }
.demo-acc .acc-row .acc-role {
  background: var(--sand); padding: 2px 8px; border-radius: 6px;
  font-size: 10px; color: var(--deep); font-weight: 600;
}
.login-public { margin-top: 14px; text-align: center; }
.login-public a { font-size: 12px; color: var(--sky); }

.track-wrap {
  min-height: 100vh; background: var(--cream);
}
.track-header {
  background: var(--bark); color: #fff; padding: 24px 0;
  text-align: center;
}
.track-header h1 { font-size: 30px; color: var(--warm); margin-bottom: 4px; }
.track-header p { font-size: 13px; color: rgba(255,255,255,.55); }
.track-body { max-width: 800px; margin: 0 auto; padding: 36px 24px; }

.search-box {
  display: flex; gap: 10px; margin-bottom: 32px;
}
.search-box input {
  flex:1; padding: 12px 16px; border: 1.5px solid var(--sand);
  border-radius: var(--radius); font-size: 14px; outline: none;
  font-family: 'DM Sans', sans-serif;
}
.search-box input:focus { border-color: var(--warm); }

.timeline { position: relative; padding-left: 32px; }
.timeline::before {
  content: ''; position: absolute; left: 14px; top: 0; bottom: 0;
  width: 2px; background: linear-gradient(to bottom, var(--green), var(--warm), var(--sky));
  border-radius: 2px;
}
.tl-item { position: relative; margin-bottom: 24px; }
.tl-dot {
  position: absolute; left: -24px; top: 16px;
  width: 20px; height: 20px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 10px; color: #fff; font-weight: 700; z-index: 1;
  box-shadow: 0 0 0 3px var(--cream);
}
.tl-dot.s1 { background: var(--green); }
.tl-dot.s2 { background: var(--warm);  }
.tl-dot.s3 { background: var(--sky);   }

.tl-card {
  background: #fff; border-radius: var(--radius-lg); padding: 20px 22px;
  box-shadow: var(--shadow); border: 1px solid var(--sand);
}
.tl-card .tl-stage {
  font-size: 11px; font-weight: 700; text-transform: uppercase;
  letter-spacing: .07em; margin-bottom: 6px;
}
.tl-card .tl-title { font-size: 19px; color: var(--bark); margin-bottom: 10px; }
.tl-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px 20px; margin-top: 10px; }
.tl-field label { font-size: 10px; text-transform: uppercase; letter-spacing: .05em; color: #aaa; display: block; }
.tl-field span  { font-size: 13px; font-weight: 500; color: var(--charcoal); }

.product-hero {
  background: linear-gradient(135deg, var(--warm) 0%, var(--gold) 100%);
  border-radius: var(--radius-lg); padding: 24px; color: #fff; margin-bottom: 28px;
  box-shadow: var(--shadow-lg);
}
.product-hero h2 { font-size: 26px; margin-bottom: 4px; }
.product-hero .ph-sub { opacity: .8; font-size: 13px; margin-bottom: 14px; }
.product-hero .ph-meta { display: flex; flex-wrap: wrap; gap: 10px; }
.ph-tag {
  background: rgba(255,255,255,.22); border-radius: 99px;
  padding: 4px 14px; font-size: 12px; font-weight: 500;
}
.verify-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--green-lt); color: var(--green);
  border-radius: var(--radius); padding: 10px 16px;
  font-size: 13px; font-weight: 600; margin-bottom: 20px;
}
.qr-inline { text-align: center; margin-top: 20px; }
.qr-inline img { width: 160px; border-radius: var(--radius); box-shadow: var(--shadow); }
.qr-inline p { font-size: 11px; color: #aaa; margin-top: 8px; }

@media(max-width:900px){
  .sidebar { width: 60px; }
  .sidebar-brand .brand-logo { display: none; }
  .sidebar-brand .brand-sub  { display: none; }
  .sidebar-user { display: none; }
  .nav-item a span { display: none; }
  .main { margin-left: 60px; }
}
@media(max-width:600px){
  .main { padding: 16px; }
  .form-grid { grid-template-columns: 1fr; }
  .tl-grid { grid-template-columns: 1fr; }
  .login-card { padding: 28px 20px; }
  .search-box { flex-direction: column; }
}

@media print {
  .sidebar, .btn, .form-panel, .topbar-right { display: none !important; }
  .main { margin-left: 0; }
}
</style>
</head>
<body>

<?php if ($_page === 'login'): ?>

<div class="login-wrap">
  
  <div class="login-left">
    <div class="login-left-content">
      <div class="ll-icon">🌸</div>
      <h2>SkincareTrace</h2>
      <p>Platform keterlacakan produk skincare dari hulu ke hilir secara transparan</p>
      
    </div>
  </div>

  <div class="login-divider"></div>
  <div class="login-right">
  
  <div class="login-card">
    <div class="login-logo">
      <div class="lg-icon">✨</div>
      <h1>SkincareTrace</h1>
      <p>Sistem Keterlacakan Produk Skincare</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-error"><?= esc($error) ?></div>
    <?php endif; ?>

    <form action="<?= base_url('traceability/login') ?>" method="post" autocomplete="off">
      <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
      <div class="login-field">
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" required autofocus autocomplete="new-password">
      </div>
      <div class="login-field">
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required autocomplete="new-password">
      </div>
      <button type="submit" class="login-btn">Masuk ke Dashboard →</button>
    </form>

    <div class="login-demo">
      <p>🔑 Akun Demo</p>
      <div class="demo-acc">
        <div class="acc-row" onclick="fillLogin('supplier','Supplier@2026')">
  <span>supplier / Supplier@2026</span>
          <span class="acc-role">Supplier</span>
        </div>
        <div class="acc-row" onclick="fillLogin('manufacture','Manufaktur@2026')">
  <span>manufacture / Manufaktur@2026</span>
          <span class="acc-role">Manufacturer</span>
        </div>
        <div class="acc-row" onclick="fillLogin('distributor','Distribusi@2026')">
  <span>distributor / Distribusi@2026</span>
          <span class="acc-role">Distributor</span>
        </div>
      </div>
    </div>
    <div class="login-public">
      <a href="<?= base_url('traceability/track-search') ?>">🔍 Lacak produk tanpa login →</a>
    </div>
  </div>
</div>
<script>
function fillLogin(u, p) {
  document.querySelector('[name=username]').value = u;
  document.querySelector('[name=password]').value = p;
}
</script>

<?php elseif ($_page === 'supplier'): ?>
<?= renderSidebar('supplier', $full_name) ?>
<div class="main">
  <div class="topbar">
    <h1>🌱 Manajemen Bahan Baku</h1>
    <div class="topbar-right">
      <span style="font-size:13px;color:#888">Selamat datang, <strong><?= esc($full_name) ?></strong></span>
    </div>
  </div>

  <?php if ($success): ?><div class="alert alert-success">✅ <?= esc($success) ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="alert alert-error">⚠️ <?= esc($error) ?></div><?php endif; ?>

  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-num"><?= count($batches) ?></div>
      <div class="stat-lbl">Total Batch Bahan Baku</div>
    </div>
    <div class="stat-card">
  <div class="stat-num"><?= array_sum(array_column($batches, 'quantity_kg')) ?></div>
  <div class="stat-lbl">Total Kuantitas (kg)</div>
</div>
<div class="stat-card">
  <div class="stat-num" style="color:var(--green)"><?= count(array_filter($batches, fn($b) => $b['used_in_production'] > 0)) ?></div>
  <div class="stat-lbl">Batch Dipakai Produksi</div>
</div>
</div><!-- /stat-grid -->

<?php if (!empty($edit_batch)): ?>
<div class="form-panel" style="border-left: 4px solid var(--warm);">
  <h3>✏️ Edit Batch: <?= esc($edit_batch['batch_code']) ?></h3>
  <form action="<?= base_url("traceability/supplier/update/{$edit_batch['id']}") ?>" method="post">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
    <div class="form-grid">
      <div class="form-group">
        <label>Nama Bahan Baku *</label>
        <input type="text" name="material_name" value="<?= esc($edit_batch['material_name']) ?>" required>
      </div>
      <div class="form-group">
        <label>Lokasi Asal *</label>
        <input type="text" name="origin_location" value="<?= esc($edit_batch['origin_location']) ?>" required>
      </div>
      <div class="form-group">
        <label>Tanggal Panen/Produksi *</label>
        <input type="date" name="harvest_date" value="<?= esc($edit_batch['harvest_date']) ?>" required>
      </div>
      <div class="form-group">
        <label>Kuantitas (kg) *</label>
        <input type="number" name="quantity_kg" value="<?= esc($edit_batch['quantity_kg']) ?>" min="0.1" step="0.1" required>
      </div>
      <div class="form-group">
        <label>Sertifikasi</label>
        <input type="text" name="certifications" value="<?= esc($edit_batch['certifications']) ?>">
      </div>
      <div class="form-group form-full">
        <label>Catatan Tambahan</label>
        <textarea name="notes"><?= esc($edit_batch['notes']) ?></textarea>
      </div>
    </div>
    <div style="margin-top:16px;display:flex;gap:10px">
      <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
      <a href="<?= base_url('traceability/supplier') ?>" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
<?php endif; ?>

  <div class="form-panel">
    <h3>➕ Tambah Batch Bahan Baku Baru</h3>
    <form action="<?= base_url('traceability/supplier/store') ?>" method="post">
      <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
      <div class="form-grid">
        <div class="form-group">
          <label>Nama Bahan Baku *</label>
          <input type="text" name="material_name" placeholder="cth: Ekstrak Lidah Buaya Organik" required>
        </div>
        <div class="form-group">
          <label>Lokasi Asal *</label>
          <input type="text" name="origin_location" placeholder="cth: Jember, Jawa Timur" required>
        </div>
        <div class="form-group">
          <label>Tanggal Panen/Produksi *</label>
          <input type="date" name="harvest_date" required>
        </div>
        <div class="form-group">
          <label>Kuantitas (kg) *</label>
          <input type="number" name="quantity_kg" placeholder="cth: 500" min="0.1" step="0.1" required>
        </div>
        <div class="form-group">
          <label>Sertifikasi</label>
          <input type="text" name="certifications" placeholder="cth: Organik USDA, Halal MUI">
        </div>
        <div class="form-group form-full">
          <label>Catatan Tambahan</label>
          <textarea name="notes" placeholder="Informasi tambahan tentang bahan baku..."></textarea>
        </div>
      </div>
      <div style="margin-top:16px">
        <button type="submit" class="btn btn-primary">Simpan Batch Bahan Baku</button>
      </div>
    </form>
  </div>

  <div class="card">
    <div class="card-header">
  <h3>📦 Daftar Batch Bahan Baku</h3>
  <form action="<?= base_url('traceability/supplier') ?>" method="get" 
        style="display:flex;gap:8px;align-items:center">
    <input type="text" name="search" value="<?= esc($search) ?>"
           placeholder="🔍 Cari nama bahan, kode, lokasi..."
           style="padding:7px 12px;border:1.5px solid var(--sand);border-radius:var(--radius);
                  font-size:13px;outline:none;width:250px;font-family:'DM Sans',sans-serif">
    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    <?php if ($search): ?>
      <a href="<?= base_url('traceability/supplier') ?>" class="btn btn-secondary btn-sm">✕ Reset</a>
    <?php endif; ?>
  </form>
</div>
    <div style="overflow-x:auto">
      <table class="tbl">
        <thead>
          <tr>
            <th>Kode Batch</th>
            <th>Nama Bahan</th>
            <th>Lokasi Asal</th>
            <th>Tgl Panen</th>
            <th>Qty (kg)</th>
            <th>Sertifikasi</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($batches)): ?>
            <tr class="empty-row"><td colspan="7">Belum ada data batch. Silakan tambahkan.</td></tr>
          <?php else: ?>
            <?php foreach ($batches as $b): ?>
            <tr>
              <td><span class="code"><?= esc($b['batch_code']) ?></span></td>
              <td><?= esc($b['material_name']) ?></td>
              <td><?= esc($b['origin_location']) ?></td>
              <td><?= esc($b['harvest_date']) ?></td>
              <td><?= number_format($b['quantity_kg'], 1) ?></td>
              <td>
  <?php if ($b['certifications']): ?>
    <span class="badge badge-green"><?= esc($b['certifications']) ?></span>
  <?php else: ?><span style="color:#ccc">—</span><?php endif; ?>
</td>
<td>
  <?php if ($b['used_in_production'] > 0): ?>
    <span class="badge badge-sky">✓ Dipakai Produksi</span>
  <?php else: ?>
    <span class="badge badge-gold">⏳ Belum Dipakai</span>
  <?php endif; ?>
</td>

<td>
  <div style="display:flex;gap:6px;align-items:center">
    <a href="<?= base_url("traceability/supplier/edit/{$b['id']}") ?>"
       class="btn btn-secondary btn-sm btn-icon-only" title="Edit">✏️</a>
    <form action="<?= base_url("traceability/supplier/delete/{$b['id']}") ?>" method="post"
          onsubmit="return confirm('Hapus batch ini?')">
      <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
      <input type="hidden" name="_method" value="DELETE">
      <button class="btn btn-danger btn-sm btn-icon-only" type="submit" title="Hapus">🗑</button>
    </form>
  </div>
</td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php elseif ($_page === 'manufacturer'): ?>

<?= renderSidebar('manufacturer', $full_name) ?>
<div class="main">
  <div class="topbar">
    <h1>🏭 Manajemen Produksi</h1>
    <div class="topbar-right">
      <span style="font-size:13px;color:#888">Selamat datang, <strong><?= esc($full_name) ?></strong></span>
    </div>
  </div>

  <?php if ($success): ?><div class="alert alert-success">✅ <?= esc($success) ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="alert alert-error">⚠️ <?= esc($error) ?></div><?php endif; ?>

  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-num"><?= count($products) ?></div>
      <div class="stat-lbl">Total Batch Produksi</div>
    </div>
    <div class="stat-card">
      <div class="stat-num"><?= array_sum(array_column($products, 'quantity_units')) ?></div>
      <div class="stat-lbl">Total Unit Diproduksi</div>
    </div>
    <div class="stat-card">
      <div class="stat-num"><?= count($raw_batches) ?></div>
      <div class="stat-lbl">Batch Bahan Baku Tersedia</div>
    </div>
  </div>

  <?php if (!empty($edit_product)): ?>
<div class="form-panel" style="border-left:4px solid var(--warm)">
  <h3>✏️ Edit Produksi: <?= esc($edit_product['production_code']) ?></h3>
  <form action="<?= base_url("traceability/manufacturer/update/{$edit_product['id']}") ?>" method="post">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
    <div class="form-grid">
      <div class="form-group">
        <label>Pilih Batch Bahan Baku *</label>
        <select name="raw_batch_id" required>
          <?php foreach ($raw_batches as $rb): ?>
            <option value="<?= $rb['id'] ?>" <?= $rb['id'] == $edit_product['raw_batch_id'] ? 'selected' : '' ?>>
              [<?= esc($rb['batch_code']) ?>] <?= esc($rb['material_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Nama Produk *</label>
        <input type="text" name="product_name" value="<?= esc($edit_product['product_name']) ?>" required>
      </div>
      <div class="form-group">
        <label>Jenis Produk *</label>
        <select name="product_type" required>
          <?php foreach (['Serum','Moisturizer','Toner','Sunscreen','Face Wash','Eye Cream','Mask','Lip Balm','Body Lotion','Essence'] as $type): ?>
            <option <?= $edit_product['product_type'] === $type ? 'selected' : '' ?>><?= $type ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Tanggal Produksi *</label>
        <input type="date" name="production_date" value="<?= esc($edit_product['production_date']) ?>" required>
      </div>
      <div class="form-group">
        <label>Tanggal Kadaluarsa *</label>
        <input type="date" name="expiry_date" value="<?= esc($edit_product['expiry_date']) ?>" required>
      </div>
      <div class="form-group">
        <label>Jumlah Unit *</label>
        <input type="number" name="quantity_units" value="<?= esc($edit_product['quantity_units']) ?>" required>
      </div>
      <div class="form-group">
        <label>Nomor BPOM</label>
        <input type="text" name="bpom_number" value="<?= esc($edit_product['bpom_number']) ?>">
      </div>
      <div class="form-group form-full">
        <label>Ringkasan Bahan</label>
        <textarea name="ingredients_summary"><?= esc($edit_product['ingredients_summary']) ?></textarea>
      </div>
      <div class="form-group form-full">
        <label>Catatan Proses</label>
        <textarea name="process_notes"><?= esc($edit_product['process_notes']) ?></textarea>
      </div>
    </div>
    <div style="margin-top:16px;display:flex;gap:10px">
      <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
      <a href="<?= base_url('traceability/manufacturer') ?>" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
<?php endif; ?>

  <div class="form-panel">
    <h3>➕ Input Data Produksi Skincare</h3>
    <form action="<?= base_url('traceability/manufacturer/store') ?>" method="post">
      <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
      <div class="form-grid">
        <div class="form-group">
          <label>Pilih Batch Bahan Baku *</label>
          <select name="raw_batch_id" required>
            <option value="">— Pilih Batch —</option>
            <?php foreach ($raw_batches as $rb): ?>
              <option value="<?= $rb['id'] ?>">
                [<?= esc($rb['batch_code']) ?>] <?= esc($rb['material_name']) ?> — <?= esc($rb['origin_location']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Nama Produk *</label>
          <input type="text" name="product_name" placeholder="cth: Glow Serum Vitamin C" required>
        </div>
        <div class="form-group">
          <label>Jenis Produk *</label>
          <select name="product_type" required>
            <option value="">— Pilih Jenis —</option>
            <option>Serum</option>
            <option>Moisturizer</option>
            <option>Toner</option>
            <option>Sunscreen</option>
            <option>Face Wash</option>
            <option>Eye Cream</option>
            <option>Mask</option>
            <option>Lip Balm</option>
            <option>Body Lotion</option>
            <option>Essence</option>
          </select>
        </div>
        <div class="form-group">
          <label>Tanggal Produksi *</label>
          <input type="date" name="production_date" required>
        </div>
        <div class="form-group">
          <label>Tanggal Kadaluarsa *</label>
          <input type="date" name="expiry_date" required>
        </div>
        <div class="form-group">
          <label>Jumlah Unit *</label>
          <input type="number" name="quantity_units" placeholder="cth: 1000" min="1" required>
        </div>
        <div class="form-group">
          <label>Nomor BPOM</label>
          <input type="text" name="bpom_number" placeholder="cth: NA18220300001">
        </div>
        <div class="form-group form-full">
          <label>Ringkasan Bahan (Ingredients)</label>
          <textarea name="ingredients_summary" placeholder="cth: Aqua, Niacinamide 10%, Ascorbic Acid 5%, Hyaluronic Acid..."></textarea>
        </div>
        <div class="form-group form-full">
          <label>Catatan Proses Produksi</label>
          <textarea name="process_notes" placeholder="cth: Diproduksi dalam kondisi GMP, suhu ruangan dijaga 18-22°C..."></textarea>
        </div>
      </div>
      <div style="margin-top:16px">
        <button type="submit" class="btn btn-green">Simpan Data Produksi</button>
      </div>
    </form>
  </div>

  <div class="card-header">
  <h3>🧴 Daftar Batch Produksi</h3>
  <form action="<?= base_url('traceability/manufacturer') ?>" method="get"
        style="display:flex;gap:8px;align-items:center">
    <input type="text" name="search" value="<?= esc($search ?? '') ?>"
           placeholder="🔍 Cari produk, kode, jenis..."
           style="padding:7px 12px;border:1.5px solid var(--sand);border-radius:var(--radius);
                  font-size:13px;outline:none;width:220px;font-family:'DM Sans',sans-serif">
    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    <?php if (!empty($search)): ?>
      <a href="<?= base_url('traceability/manufacturer') ?>" class="btn btn-secondary btn-sm">✕ Reset</a>
    <?php endif; ?>
  </form>
</div>

    <div style="overflow-x:auto">
      <table class="tbl">
        <thead>
          <tr>
            <th>Kode Produksi</th>
            <th>Produk</th>
            <th>Jenis</th>
            <th>Bahan Baku</th>
            <th>Tgl Produksi</th>
            <th>Kadaluarsa</th>
            <th>Unit</th>
            <th>BPOM</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($products)): ?>
            <tr class="empty-row"><td colspan="9">Belum ada data produksi.</td></tr>
          <?php else: ?>
            <?php foreach ($products as $p): ?>
            <tr>
              <td><span class="code"><?= esc($p['production_code']) ?></span></td>
              <td><strong><?= esc($p['product_name']) ?></strong></td>
              <td><span class="badge badge-gold"><?= esc($p['product_type']) ?></span></td>
              <td><?= esc($p['material_name']) ?> <span class="code" style="font-size:10px"><?= esc($p['raw_batch_code']) ?></span></td>
              <td><?= esc($p['production_date']) ?></td>
              <td><?= esc($p['expiry_date']) ?></td>
              <td><?= number_format($p['quantity_units']) ?></td>
              <td>
                <?php if ($p['bpom_number']): ?>
                  <span class="badge badge-sky"><?= esc($p['bpom_number']) ?></span>
                <?php else: ?><span style="color:#ccc">—</span><?php endif; ?>
              </td>

                    <td>
  <div style="display:flex;gap:6px;align-items:center">
    <a href="<?= base_url("traceability/manufacturer/edit/{$p['id']}") ?>"
       class="btn btn-secondary btn-sm btn-icon-only" title="Edit">✏️</a>
    <form action="<?= base_url("traceability/manufacturer/delete/{$p['id']}") ?>" method="post"
          onsubmit="return confirm('Hapus data produksi ini?')">
      <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
      <input type="hidden" name="_method" value="DELETE">
      <button class="btn btn-danger btn-sm btn-icon-only" type="submit" title="Hapus">🗑</button>
    </form>
  </div>
</td>

              
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php elseif ($_page === 'distributor'): ?>

<?= renderSidebar('distributor', $full_name) ?>
<div class="main">
  <div class="topbar">
    <h1>🚚 Manajemen Distribusi & QR Code</h1>
    <div class="topbar-right">
      <span style="font-size:13px;color:#888"><strong><?= esc($full_name) ?></strong></span>
    </div>
  </div>

  <?php if ($success): ?><div class="alert alert-success">✅ <?= esc($success) ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="alert alert-error">⚠️ <?= esc($error) ?></div><?php endif; ?>

  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-num"><?= count($shipments) ?></div>
      <div class="stat-lbl">Total Pengiriman</div>
    </div>
    <div class="stat-card">
      <div class="stat-num"><?= count($productions) ?></div>
      <div class="stat-lbl">Batch Produksi Tersedia</div>
    </div>
  </div>

        <?php if (!empty($edit_shipment)): ?>
<div class="form-panel" style="border-left:4px solid var(--warm)">
  <h3>✏️ Edit Pengiriman: <?= esc($edit_shipment['resi_code']) ?></h3>
  <form action="<?= base_url("traceability/distributor/update/{$edit_shipment['id']}") ?>" method="post">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
    <div class="form-grid">
      <div class="form-group">
        <label>Pilih Batch Produksi *</label>
        <select name="production_batch_id" required>
          <?php foreach ($productions as $pr): ?>
            <option value="<?= $pr['id'] ?>" <?= $pr['id'] == $edit_shipment['production_batch_id'] ? 'selected' : '' ?>>
              [<?= esc($pr['production_code']) ?>] <?= esc($pr['product_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Toko / Retailer Tujuan *</label>
        <input type="text" name="destination_store" value="<?= esc($edit_shipment['destination_store']) ?>" required>
      </div>
      <div class="form-group">
        <label>Kota Tujuan *</label>
        <input type="text" name="destination_city" value="<?= esc($edit_shipment['destination_city']) ?>" required>
      </div>
      <div class="form-group">
        <label>Tanggal Pengiriman *</label>
        <input type="date" name="shipment_date" value="<?= esc($edit_shipment['shipment_date']) ?>" required>
      </div>
      <div class="form-group">
        <label>Estimasi Tiba</label>
        <input type="date" name="arrival_date" value="<?= esc($edit_shipment['arrival_date']) ?>">
      </div>
      <div class="form-group">
        <label>Mitra Logistik</label>
        <select name="logistics_partner">
          <?php foreach (['JNE','J&T Express','SiCepat','Anteraja','Armada Internal','DHL','TIKI'] as $mitra): ?>
            <option <?= $edit_shipment['logistics_partner'] === $mitra ? 'selected' : '' ?>><?= $mitra ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Suhu Penyimpanan</label>
        <input type="text" name="storage_temp" value="<?= esc($edit_shipment['storage_temp']) ?>">
      </div>
      <div class="form-group form-full">
        <label>Catatan</label>
        <textarea name="notes"><?= esc($edit_shipment['notes']) ?></textarea>
      </div>
    </div>
    <div style="margin-top:16px;display:flex;gap:10px">
      <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
      <a href="<?= base_url('traceability/distributor') ?>" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
<?php endif; ?>

  <div class="form-panel">
    <h3>➕ Input Data Pengiriman & Generate QR</h3>
    <form action="<?= base_url('traceability/distributor/store') ?>" method="post">
      <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
      <div class="form-grid">
        <div class="form-group">
          <label>Pilih Batch Produksi *</label>
          <select name="production_batch_id" required>
            <option value="">— Pilih Batch Produksi —</option>
            <?php foreach ($productions as $pr): ?>
              <option value="<?= $pr['id'] ?>">
                [<?= esc($pr['production_code']) ?>] <?= esc($pr['product_name']) ?> (<?= esc($pr['product_type']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Toko / Retailer Tujuan *</label>
          <input type="text" name="destination_store" placeholder="cth: Sociolla Jakarta Selatan" required>
        </div>
        <div class="form-group">
          <label>Kota Tujuan *</label>
          <input type="text" name="destination_city" placeholder="cth: Jakarta Selatan" required>
        </div>
        <div class="form-group">
          <label>Tanggal Pengiriman *</label>
          <input type="date" name="shipment_date" required>
        </div>
        <div class="form-group">
          <label>Estimasi Tiba</label>
          <input type="date" name="arrival_date">
        </div>
        <div class="form-group">
          <label>Mitra Logistik</label>
          <select name="logistics_partner">
            <option value="">— Pilih Mitra —</option>
            <option>JNE</option>
            <option>J&T Express</option>
            <option>SiCepat</option>
            <option>Anteraja</option>
            <option>Armada Internal</option>
            <option>DHL</option>
            <option>TIKI</option>
          </select>
        </div>
        <div class="form-group">
          <label>Suhu Penyimpanan (°C)</label>
          <input type="text" name="storage_temp" placeholder="cth: 15-25°C">
        </div>
        <div class="form-group form-full">
          <label>Catatan Pengiriman</label>
          <textarea name="notes" placeholder="cth: Hindari paparan sinar matahari langsung, simpan di tempat sejuk..."></textarea>
        </div>
      </div>
      <div style="margin-top:16px">
        <button type="submit" class="btn btn-sky">Simpan & Generate QR Code</button>
      </div>
    </form>
  </div>

  <div class="card">
    <div class="card-header">
  <h3>📦 Daftar Pengiriman & QR Code</h3>
  <form action="<?= base_url('traceability/distributor') ?>" method="get"
        style="display:flex;gap:8px;align-items:center">
    <input type="text" name="search" value="<?= esc($search ?? '') ?>"
           placeholder="🔍 Cari resi, produk, kota..."
           style="padding:7px 12px;border:1.5px solid var(--sand);border-radius:var(--radius);
                  font-size:13px;outline:none;width:220px;font-family:'DM Sans',sans-serif">
    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    <?php if (!empty($search)): ?>
      <a href="<?= base_url('traceability/distributor') ?>" class="btn btn-secondary btn-sm">✕ Reset</a>
    <?php endif; ?>
  </form>
</div>
    <div style="overflow-x:auto">
      <table class="tbl">
        <thead>
          <tr>
            <th>Nomor Resi</th>
            <th>Produk</th>
            <th>Toko Tujuan</th>
            <th>Kota</th>
            <th>Mitra</th>
            <th>Tgl Kirim</th>
            <th>QR Code</th>
            <th>Link Lacak</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($shipments)): ?>
            <tr class="empty-row"><td colspan="9">Belum ada data pengiriman.</td></tr>
          <?php else: ?>
            <?php foreach ($shipments as $s): ?>
            <?php $trackUrl = base_url("traceability/track/{$s['tracking_token']}"); ?>
            <?php $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($trackUrl); ?>
            <tr>
              <td><span class="code"><?= esc($s['resi_code']) ?></span></td>
              <td><strong><?= esc($s['product_name']) ?></strong>
                <div style="font-size:11px;color:#aaa"><?= esc($s['production_code']) ?></div>
              </td>
              <td><?= esc($s['destination_store']) ?></td>
              <td><?= esc($s['destination_city']) ?></td>
              <td>
                <?php if ($s['logistics_partner']): ?>
                  <span class="badge badge-sky"><?= esc($s['logistics_partner']) ?></span>
                <?php else: ?>—<?php endif; ?>
              </td>
              <td><?= esc($s['shipment_date']) ?></td>
              <td>
                <div class="qr-box">
                  <a href="<?= $qrUrl ?>" target="_blank">
                    <img src="<?= $qrUrl ?>" alt="QR <?= esc($s['resi_code']) ?>" loading="lazy">
                  </a>
                  <span class="qr-label">Klik untuk download</span>
                </div>
              </td>
              <td>
                <a href="<?= $trackUrl ?>" target="_blank" class="btn btn-green btn-sm">🔍 Lihat</a>
                <div style="margin-top:6px">
                  <input type="text" value="<?= esc($trackUrl) ?>" readonly
                    style="font-size:10px;padding:4px 6px;border:1px solid #ddd;border-radius:4px;width:160px"
                    onclick="this.select();document.execCommand('copy');alert('URL disalin!')">
                </div>
              </td>
              <td>
                <div style="display:flex;gap:6px;align-items:center">
  <a href="<?= base_url("traceability/distributor/edit/{$s['id']}") ?>"
     class="btn btn-secondary btn-sm btn-icon-only" title="Edit">✏️</a>
  <form action="<?= base_url("traceability/distributor/delete/{$s['id']}") ?>" method="post"
        onsubmit="return confirm('Hapus data pengiriman ini?')">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
    <input type="hidden" name="_method" value="DELETE">
    <button class="btn btn-danger btn-sm btn-icon-only" type="submit" title="Hapus">🗑</button>
  </form>
</div>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php elseif ($_page === 'track'): ?>

<div class="track-wrap">
  <div class="track-header">
  <h1>🌿 SkincareTrace</h1>
  <p>Verifikasi keaslian & lacak perjalanan produk skincare Anda</p>
  <div style="margin-top:12px">
    <a href="<?= base_url('traceability/track-search') ?>" 
       style="color:rgba(255,255,255,.6);font-size:12px;text-decoration:none">
      ← Cari produk lain
    </a>
  </div>
</div>

  <div class="track-body">
    <!-- Form Cari via Resi -->
    <form action="<?= base_url('traceability/track-search') ?>" method="get">
      <div class="search-box">
        <input type="text" name="resi" placeholder="🔍  Masukkan nomor resi (cth: RESI-XXXXXXXX)..."
               value="<?= esc(service('request')->getGet('resi') ?? '') ?>">
        <button type="submit" class="btn btn-primary">Lacak</button>
        <a href="<?= base_url('traceability/login') ?>" class="btn btn-secondary">Login Aktor</a>
      </div>
    </form>

    <div style="text-align:center;margin-top:-16px;margin-bottom:24px">
      <span style="font-size:12px;color:#aaa">Coba resi demo: </span>
      <a href="<?= base_url('traceability/track-search?resi=RESI-DEMO0001') ?>"
   style="font-size:12px;color:var(--warm);font-weight:600">RESI-DEMO0001</a>
<span style="color:#ddd;margin:0 6px">·</span>
<a href="<?= base_url('traceability/track-search?resi=RESI-DEMO0003') ?>"
   style="font-size:12px;color:var(--warm);font-weight:600">RESI-DEMO0003</a>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-error">⚠️ <?= esc($error) ?></div>
    <?php endif; ?>

    <?php if ($shipment): ?>
      
      <div class="product-hero">
        <h2><?= esc($shipment['product_name']) ?></h2>
        <div class="ph-sub"><?= esc($shipment['product_type']) ?> · <?= esc($shipment['production_code']) ?></div>
        <div class="ph-meta">
          <span class="ph-tag">📦 Resi: <?= esc($shipment['resi_code']) ?></span>
          <span class="ph-tag">📅 Kadaluarsa: <?= esc($shipment['expiry_date']) ?></span>
          <?php if ($shipment['bpom_number']): ?>
            <span class="ph-tag">✅ BPOM: <?= esc($shipment['bpom_number']) ?></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="verify-badge">
        ✅ Produk terverifikasi — data lengkap tersedia dari sumber bahan baku
      </div>
      <button onclick="window.print()" 
    style="padding:9px 18px;background:var(--bark);color:#fff;border:none;border-radius:var(--radius);
           font-size:13px;cursor:pointer;font-family:'DM Sans',sans-serif">
    🖨️ Cetak / Simpan PDF
  </button>
</div>

      <div class="timeline">

        <div class="tl-item">
          <div class="tl-dot s1">1</div>
          <div class="tl-card">
            <div class="tl-stage" style="color:var(--green)">🌱 Tahap 1 — Penyedia Bahan Baku</div>
            <div class="tl-title">Bahan Baku: <?= esc($shipment['material_name']) ?></div>
            <div class="tl-grid">
              <div class="tl-field">
                <label>Kode Batch</label>
                <span><?= esc($shipment['raw_batch_code']) ?></span>
              </div>
              <div class="tl-field">
                <label>Supplier</label>
                <span><?= esc($shipment['supplier_name']) ?></span>
              </div>
              <div class="tl-field">
                <label>Lokasi Asal</label>
                <span>📍 <?= esc($shipment['origin_location']) ?></span>
              </div>
              <div class="tl-field">
                <label>Tanggal Panen</label>
                <span><?= esc($shipment['harvest_date']) ?></span>
              </div>
              <div class="tl-field">
                <label>Kuantitas</label>
                <span><?= number_format($shipment['quantity_kg'], 1) ?> kg</span>
              </div>
              <?php if ($shipment['certifications']): ?>
              <div class="tl-field">
                <label>Sertifikasi</label>
                <span><span class="badge badge-green"><?= esc($shipment['certifications']) ?></span></span>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="tl-item">
          <div class="tl-dot s2">2</div>
          <div class="tl-card">
            <div class="tl-stage" style="color:var(--warm)">🏭 Tahap 2 — Produksi & Pengolahan</div>
            <div class="tl-title"><?= esc($shipment['product_name']) ?></div>
            <div class="tl-grid">
              <div class="tl-field">
                <label>Kode Produksi</label>
                <span><?= esc($shipment['production_code']) ?></span>
              </div>
              <div class="tl-field">
                <label>Produsen</label>
                <span><?= esc($shipment['manufacturer_name']) ?></span>
              </div>
              <div class="tl-field">
                <label>Tanggal Produksi</label>
                <span><?= esc($shipment['production_date']) ?></span>
              </div>
              <div class="tl-field">
                <label>Tanggal Kadaluarsa</label>
                <span><?= esc($shipment['expiry_date']) ?></span>
              </div>
              <div class="tl-field">
                <label>Jumlah Diproduksi</label>
                <span><?= number_format($shipment['quantity_units']) ?> unit</span>
              </div>
              <?php if ($shipment['bpom_number']): ?>
              <div class="tl-field">
                <label>No. BPOM</label>
                <span><span class="badge badge-sky"><?= esc($shipment['bpom_number']) ?></span></span>
              </div>
              <?php endif; ?>
              <?php if ($shipment['ingredients_summary']): ?>
              <div class="tl-field" style="grid-column:1/-1">
                <label>Komposisi Bahan</label>
                <span><?= esc($shipment['ingredients_summary']) ?></span>
              </div>
              <?php endif; ?>
              <?php if ($shipment['process_notes']): ?>
              <div class="tl-field" style="grid-column:1/-1">
                <label>Proses Produksi</label>
                <span><?= esc($shipment['process_notes']) ?></span>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="tl-item">
          <div class="tl-dot s3">3</div>
          <div class="tl-card">
            <div class="tl-stage" style="color:var(--sky)">🚚 Tahap 3 — Distribusi & Pengiriman</div>
            <div class="tl-title">Dikirim ke: <?= esc($shipment['destination_store']) ?></div>
            <div class="tl-grid">
              <div class="tl-field">
                <label>Nomor Resi</label>
                <span><?= esc($shipment['resi_code']) ?></span>
              </div>
              <div class="tl-field">
                <label>Distributor</label>
                <span><?= esc($shipment['distributor_name']) ?></span>
              </div>
              <div class="tl-field">
                <label>Kota Tujuan</label>
                <span>📍 <?= esc($shipment['destination_city']) ?></span>
              </div>
              <div class="tl-field">
                <label>Tanggal Kirim</label>
                <span><?= esc($shipment['shipment_date']) ?></span>
              </div>
              <?php if ($shipment['arrival_date']): ?>
              <div class="tl-field">
                <label>Estimasi Tiba</label>
                <span><?= esc($shipment['arrival_date']) ?></span>
              </div>
              <?php endif; ?>
              <?php if ($shipment['logistics_partner']): ?>
              <div class="tl-field">
                <label>Mitra Logistik</label>
                <span><span class="badge badge-sky"><?= esc($shipment['logistics_partner']) ?></span></span>
              </div>
              <?php endif; ?>
              <?php if ($shipment['storage_temp']): ?>
              <div class="tl-field">
                <label>Suhu Penyimpanan</label>
                <span>🌡 <?= esc($shipment['storage_temp']) ?></span>
              </div>
              <?php endif; ?>
            </div>

            <div class="qr-inline">
              <?php $thisUrl = base_url("traceability/track/{$shipment['tracking_token']}"); ?>
              <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($thisUrl) ?>"
                   alt="QR Code Produk">
              <p>Scan QR ini untuk berbagi info produk</p>
            </div>
          </div>
        </div>

        <div class="tl-item">
          <div class="tl-dot" style="background:var(--rose)">✓</div>
          <div class="tl-card">
            <div class="tl-stage" style="color:var(--rose)">🛒 Tahap 4 — Di Tangan Konsumen</div>
            <div class="tl-title">Anda memegang produk yang telah terverifikasi</div>
            <p style="font-size:13px;color:#888;margin-top:8px">
              Seluruh rantai pasok dari bahan baku hingga ke tangan Anda telah terdokumentasi
              dan dapat dipertanggungjawabkan. Nikmati produk skincare Anda dengan aman! 🌿
            </p>
          </div>
        </div>

      </div>
    <?php elseif (!$error): ?>
      
      <div style="text-align:center;padding:60px 20px">
        <div style="font-size:72px;margin-bottom:16px">🫧</div>
        <h2 style="font-size:24px;color:var(--bark);margin-bottom:8px">Lacak Produk Skincare Anda</h2>
        <p style="color:#888;max-width:400px;margin:0 auto 32px">
          Masukkan nomor resi atau scan QR Code yang terdapat pada kemasan produk
          untuk melihat perjalanan lengkap dari bahan baku hingga sampai ke tangan Anda.
        </p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;max-width:600px;margin:0 auto">
          <div style="background:#fff;border-radius:16px;padding:20px;border:1px solid var(--sand);text-align:center">
            <div style="font-size:32px;margin-bottom:8px">🌱</div>
            <div style="font-size:13px;font-weight:600;color:var(--bark);margin-bottom:4px">Asal Bahan Baku</div>
            <div style="font-size:12px;color:#aaa">Dari mana bahan baku berasal</div>
          </div>
          <div style="background:#fff;border-radius:16px;padding:20px;border:1px solid var(--sand);text-align:center">
            <div style="font-size:32px;margin-bottom:8px">🏭</div>
            <div style="font-size:13px;font-weight:600;color:var(--bark);margin-bottom:4px">Proses Produksi</div>
            <div style="font-size:12px;color:#aaa">Detail pabrik & tanggal produksi</div>
          </div>
          <div style="background:#fff;border-radius:16px;padding:20px;border:1px solid var(--sand);text-align:center">
            <div style="font-size:32px;margin-bottom:8px">✅</div>
            <div style="font-size:13px;font-weight:600;color:var(--bark);margin-bottom:4px">Sertifikasi BPOM</div>
            <div style="font-size:12px;color:#aaa">Nomor izin edar resmi</div>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div><!-- /track-body -->
</div><!-- /track-wrap -->

<?php endif; ?>

<?php

function renderSidebar(string $role, string $name): string
{
    $roleLabel = [
        'supplier'     => '🌱 Supplier',
        'manufacturer' => '🏭 Manufacturer',
        'distributor'  => '🚚 Distributor',
    ];
    $icon = ['supplier'=>'🌱','manufacturer'=>'🏭','distributor'=>'🚚'];

    $nav = '<nav class="sidebar-nav">';
    $items = [
        'supplier'     => [['href' => base_url('traceability/supplier'),     'icon' => '🌿', 'label' => 'Bahan Baku']],
        'manufacturer' => [['href' => base_url('traceability/manufacturer'), 'icon' => '🧴', 'label' => 'Produksi']],
        'distributor'  => [['href' => base_url('traceability/distributor'),  'icon' => '📦', 'label' => 'Pengiriman']],
    ];
    foreach (($items[$role] ?? []) as $item) {
        $nav .= "<div class='nav-item active'><a href='{$item['href']}'>{$item['icon']} <span>{$item['label']}</span></a></div>";
    }
    $nav .= "<div class='nav-item'><a href='" . base_url('traceability/track-search') . "'>🔍 <span>Cek Produk</span></a></div>";
    $nav .= '</nav>';

    return "
    <aside class='sidebar'>
      <div class='sidebar-brand'>
        <div class='brand-logo'>SkincareTrace</div>
        <div class='brand-sub'>Traceability System</div>
      </div>
      <div class='sidebar-user'>
        <div class='user-name'>{$name}</div>
        <div class='user-role'>{$roleLabel[$role]}</div>
      </div>
      {$nav}
      <div class='sidebar-footer'>
        <a href='" . base_url('traceability/logout') . "'>↩ <span>Logout</span></a>
      </div>
    </aside>";
}
?>
</body>
</html>