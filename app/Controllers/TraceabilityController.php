<?php

namespace App\Controllers;

use CodeIgniter\Controller;


class TraceabilityController extends Controller
{
    protected $db;

    public function __construct()
{
    $this->db = \Config\Database::connect();
    helper(['url', 'form', 'cookie']);
}

    private function sess(): \CodeIgniter\Session\Session
    {
        return \Config\Services::session();
    }

    private function isLoggedIn(): bool
    {
        return (bool) $this->sess()->get('user_id');
    }

    private function getRole(): string
    {
        return $this->sess()->get('role') ?? '';
    }

    private function requireRole(string ...$roles): void
    {
        if (!$this->isLoggedIn() || !in_array($this->getRole(), $roles)) {
            $this->sess()->setFlashdata('error', 'Akses ditolak. Silakan login.');
            redirect()->to('/traceability/login')->send();
            exit;
        }
    }

    public function login()
{
    if ($this->isLoggedIn()) {
        return $this->redirectByRole();
    }

    $data = ['error' => $this->sess()->getFlashdata('error')];
    return $this->renderView('login', $data);
}

    public function loginPost()
{
    $username = trim($this->request->getPost('username'));
    $password = trim($this->request->getPost('password'));

    $user = $this->db->query(
        "SELECT * FROM users WHERE username = ? LIMIT 1",
        [$username]
    )->getRowArray();

    if ($user && password_verify($password, $user['password'])) {
        $this->sess()->set([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'role'      => $user['role'],
        ]);
        return $this->redirectByRole();
    }

    $this->sess()->setFlashdata('error', 'Username atau password salah.');
    return redirect()->to('/traceability/login');
}

    public function logout()
    {
        $this->sess()->destroy();
        return redirect()->to('/traceability/login');
    }

    private function redirectByRole()
    {
        $map = [
            'supplier'     => '/traceability/supplier',
            'manufacturer' => '/traceability/manufacturer',
            'distributor'  => '/traceability/distributor',
        ];
        $role = $this->getRole();
        return redirect()->to($map[$role] ?? '/traceability/login');
    }

    public function supplier()
    {
        $this->requireRole('supplier');
        $userId = $this->sess()->get('user_id');

        $search = $this->request->getGet('search');

$sql = "SELECT r.*, COUNT(p.id) as used_in_production
        FROM raw_material_batches r
        LEFT JOIN production_batches p ON p.raw_batch_id = r.id
        WHERE r.supplier_id = ?";

$params = [$userId];

if ($search) {
    $sql .= " AND (r.material_name LIKE ? OR r.batch_code LIKE ? OR r.origin_location LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " GROUP BY r.id ORDER BY r.created_at DESC";

$batches = $this->db->query($sql, $params)->getResultArray();

        return $this->renderView('supplier', [
    'batches'   => $batches,
    'full_name' => $this->sess()->get('full_name'),
    'success'   => $this->sess()->getFlashdata('success'),
    'error'     => $this->sess()->getFlashdata('error'),
    'search'    => $search ?? '',
]);
    }

    public function supplierStore()
    {
        $this->requireRole('supplier');
        $userId = $this->sess()->get('user_id');

        $batchCode = 'RAW-' . strtoupper(substr(md5(uniqid()), 0, 8));

        $this->db->table('raw_material_batches')->insert([
            'batch_code'       => $batchCode,
            'supplier_id'      => $userId,
            'material_name'    => $this->request->getPost('material_name'),
            'origin_location'  => $this->request->getPost('origin_location'),
            'harvest_date'     => $this->request->getPost('harvest_date'),
            'quantity_kg'      => $this->request->getPost('quantity_kg'),
            'certifications'   => $this->request->getPost('certifications'),
            'notes'            => $this->request->getPost('notes'),
            'created_at'       => date('Y-m-d H:i:s'),
        ]);

        $this->sess()->setFlashdata('success', "Batch $batchCode berhasil ditambahkan.");
        return redirect()->to('/traceability/supplier');
    }

    public function supplierEdit($id)
{
    $this->requireRole('supplier');
    $userId = $this->sess()->get('user_id');

    $batch = $this->db->table('raw_material_batches')
        ->where('id', $id)
        ->where('supplier_id', $userId)
        ->get()->getRowArray();

    if (!$batch) {
        $this->sess()->setFlashdata('error', 'Data tidak ditemukan.');
        return redirect()->to('/traceability/supplier');
    }

    $batches = $this->db->query("
        SELECT r.*, COUNT(p.id) as used_in_production
        FROM raw_material_batches r
        LEFT JOIN production_batches p ON p.raw_batch_id = r.id
        WHERE r.supplier_id = ?
        GROUP BY r.id
        ORDER BY r.created_at DESC
    ", [$userId])->getResultArray();

    return $this->renderView('supplier', [
        'batches'    => $batches,
        'edit_batch' => $batch,
        'full_name'  => $this->sess()->get('full_name'),
        'success'    => $this->sess()->getFlashdata('success'),
        'error'      => $this->sess()->getFlashdata('error'),
    ]);
}

public function supplierUpdate($id)
{
    $this->requireRole('supplier');
    $userId = $this->sess()->get('user_id');

    $this->db->table('raw_material_batches')
        ->where('id', $id)
        ->where('supplier_id', $userId)
        ->update([
            'material_name'   => $this->request->getPost('material_name'),
            'origin_location' => $this->request->getPost('origin_location'),
            'harvest_date'    => $this->request->getPost('harvest_date'),
            'quantity_kg'     => $this->request->getPost('quantity_kg'),
            'certifications'  => $this->request->getPost('certifications'),
            'notes'           => $this->request->getPost('notes'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

    $this->sess()->setFlashdata('success', 'Batch berhasil diperbarui.');
    return redirect()->to('/traceability/supplier');
}

    public function supplierDelete($id)
{
    $this->requireRole('supplier');
    $userId = $this->sess()->get('user_id');

    // Cek apakah batch sudah dipakai di production_batches
    $used = $this->db->table('production_batches')
        ->where('raw_batch_id', $id)
        ->countAllResults();

    if ($used > 0) {
        $this->sess()->setFlashdata('error', 'Batch tidak dapat dihapus karena sudah digunakan dalam data produksi.');
        return redirect()->to('/traceability/supplier');
    }

    $this->db->table('raw_material_batches')
        ->where('id', $id)
        ->where('supplier_id', $userId)
        ->delete();

    $this->sess()->setFlashdata('success', 'Batch berhasil dihapus.');
    return redirect()->to('/traceability/supplier');
}

    public function manufacturer()
    {
        $this->requireRole('manufacturer');
        $userId = $this->sess()->get('user_id');

        // Ambil semua raw batch dari semua supplier untuk dipilih
        $rawBatches = $this->db->table('raw_material_batches')
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        $products = $this->db->query("
            SELECT p.*, r.batch_code AS raw_batch_code, r.material_name,
                   u.full_name AS manufacturer_name
            FROM production_batches p
            JOIN raw_material_batches r ON p.raw_batch_id = r.id
            JOIN users u ON p.manufacturer_id = u.id
            WHERE p.manufacturer_id = ?
            ORDER BY p.created_at DESC
        ", [$userId])->getResultArray();

        return $this->renderView('manufacturer', [
            'raw_batches' => $rawBatches,
            'products'    => $products,
            'full_name'   => $this->sess()->get('full_name'),
            'success'     => $this->sess()->getFlashdata('success'),
            'error'       => $this->sess()->getFlashdata('error'),
        ]);
    }

    public function manufacturerStore()
    {
        $this->requireRole('manufacturer');
        $userId = $this->sess()->get('user_id');

        $productionCode = 'PROD-' . strtoupper(substr(md5(uniqid()), 0, 8));

        $this->db->table('production_batches')->insert([
            'production_code'    => $productionCode,
            'manufacturer_id'    => $userId,
            'raw_batch_id'       => $this->request->getPost('raw_batch_id'),
            'product_name'       => $this->request->getPost('product_name'),
            'product_type'       => $this->request->getPost('product_type'),
            'production_date'    => $this->request->getPost('production_date'),
            'expiry_date'        => $this->request->getPost('expiry_date'),
            'quantity_units'     => $this->request->getPost('quantity_units'),
            'bpom_number'        => $this->request->getPost('bpom_number'),
            'ingredients_summary'=> $this->request->getPost('ingredients_summary'),
            'process_notes'      => $this->request->getPost('process_notes'),
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        $this->sess()->setFlashdata('success', "Produksi $productionCode berhasil dicatat.");
        return redirect()->to('/traceability/manufacturer');
    }

    public function manufacturerEdit($id)
{
    $this->requireRole('manufacturer');
    $userId = $this->sess()->get('user_id');

    $product = $this->db->table('production_batches')
        ->where('id', $id)
        ->where('manufacturer_id', $userId)
        ->get()->getRowArray();

    if (!$product) {
        $this->sess()->setFlashdata('error', 'Data tidak ditemukan.');
        return redirect()->to('/traceability/manufacturer');
    }

    $rawBatches = $this->db->table('raw_material_batches')
        ->orderBy('created_at', 'DESC')
        ->get()->getResultArray();

    
    $products = $this->db->query("
        SELECT p.*, r.batch_code AS raw_batch_code, r.material_name,
               u.full_name AS manufacturer_name
        FROM production_batches p
        JOIN raw_material_batches r ON p.raw_batch_id = r.id
        JOIN users u ON p.manufacturer_id = u.id
        WHERE p.manufacturer_id = ?
        ORDER BY p.created_at DESC
    ", [$userId])->getResultArray();

    return $this->renderView('manufacturer', [
        'raw_batches'  => $rawBatches,
        'products'     => $products,
        'edit_product' => $product,
        'full_name'    => $this->sess()->get('full_name'),
        'success'      => $this->sess()->getFlashdata('success'),
        'error'        => $this->sess()->getFlashdata('error'),
        'search'       => '',
    ]);
}

public function manufacturerUpdate($id)
{
    $this->requireRole('manufacturer');
    $userId = $this->sess()->get('user_id');

    $this->db->table('production_batches')
        ->where('id', $id)
        ->where('manufacturer_id', $userId)
        ->update([
            'raw_batch_id'        => $this->request->getPost('raw_batch_id'),
            'product_name'        => $this->request->getPost('product_name'),
            'product_type'        => $this->request->getPost('product_type'),
            'production_date'     => $this->request->getPost('production_date'),
            'expiry_date'         => $this->request->getPost('expiry_date'),
            'quantity_units'      => $this->request->getPost('quantity_units'),
            'bpom_number'         => $this->request->getPost('bpom_number'),
            'ingredients_summary' => $this->request->getPost('ingredients_summary'),
            'process_notes'       => $this->request->getPost('process_notes'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

    $this->sess()->setFlashdata('success', 'Data produksi berhasil diperbarui.');
    return redirect()->to('/traceability/manufacturer');
}

    public function manufacturerDelete($id)
    {
        $this->requireRole('manufacturer');
        $userId = $this->sess()->get('user_id');

        $this->db->table('production_batches')
            ->where('id', $id)
            ->where('manufacturer_id', $userId)
            ->delete();

        $this->sess()->setFlashdata('success', 'Data produksi berhasil dihapus.');
        return redirect()->to('/traceability/manufacturer');
    }

    public function distributor()
    {
        $this->requireRole('distributor');
        $userId = $this->sess()->get('user_id');

        $productions = $this->db->query("
            SELECT p.*, r.batch_code AS raw_batch_code, r.material_name
            FROM production_batches p
            JOIN raw_material_batches r ON p.raw_batch_id = r.id
            ORDER BY p.created_at DESC
        ")->getResultArray();

        $search = $this->request->getGet('search');

$sql = "SELECT s.*, p.product_name, p.production_code, p.product_type,
               u.full_name AS distributor_name
        FROM shipments s
        JOIN production_batches p ON s.production_batch_id = p.id
        JOIN users u ON s.distributor_id = u.id
        WHERE s.distributor_id = ?";

$params = [$userId];

if ($search) {
    $sql .= " AND (s.resi_code LIKE ? OR p.product_name LIKE ? OR s.destination_city LIKE ? OR s.destination_store LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY s.created_at DESC";

$shipments = $this->db->query($sql, $params)->getResultArray();

        return $this->renderView('distributor', [
    'productions' => $productions,
    'shipments'   => $shipments,
    'full_name'   => $this->sess()->get('full_name'),
    'success'     => $this->sess()->getFlashdata('success'),
    'error'       => $this->sess()->getFlashdata('error'),
    'base_url'    => base_url(),
    'search'      => $search ?? '',
]);
    }

    public function distributorStore()
    {
        $this->requireRole('distributor');
        $userId = $this->sess()->get('user_id');

        
        $resiCode = 'RESI-' . strtoupper(substr(md5(uniqid()), 0, 10));
        $trackingToken = bin2hex(random_bytes(16)); // token publik untuk URL QR

        $this->db->table('shipments')->insert([
            'resi_code'            => $resiCode,
            'tracking_token'       => $trackingToken,
            'distributor_id'       => $userId,
            'production_batch_id'  => $this->request->getPost('production_batch_id'),
            'destination_store'    => $this->request->getPost('destination_store'),
            'destination_city'     => $this->request->getPost('destination_city'),
            'shipment_date'        => $this->request->getPost('shipment_date'),
            'arrival_date'         => $this->request->getPost('arrival_date'),
            'logistics_partner'    => $this->request->getPost('logistics_partner'),
            'storage_temp'         => $this->request->getPost('storage_temp'),
            'notes'                => $this->request->getPost('notes'),
            'created_at'           => date('Y-m-d H:i:s'),
        ]);

        $this->sess()->setFlashdata('success', "Pengiriman $resiCode berhasil. QR Code siap digenerate.");
        return redirect()->to('/traceability/distributor');
    }

        public function distributorEdit($id)
{
    $this->requireRole('distributor');
    $userId = $this->sess()->get('user_id');

    $shipment = $this->db->table('shipments')
        ->where('id', $id)
        ->where('distributor_id', $userId)
        ->get()->getRowArray();

    if (!$shipment) {
        $this->sess()->setFlashdata('error', 'Data tidak ditemukan.');
        return redirect()->to('/traceability/distributor');
    }

    $productions = $this->db->query("
        SELECT p.*, r.batch_code AS raw_batch_code, r.material_name
        FROM production_batches p
        JOIN raw_material_batches r ON p.raw_batch_id = r.id
        ORDER BY p.created_at DESC
    ")->getResultArray();

    $shipments = $this->db->query("
        SELECT s.*, p.product_name, p.production_code, u.full_name AS distributor_name
        FROM shipments s
        JOIN production_batches p ON s.production_batch_id = p.id
        JOIN users u ON s.distributor_id = u.id
        WHERE s.distributor_id = ?
        ORDER BY s.created_at DESC
    ", [$userId])->getResultArray();

    return $this->renderView('distributor', [
        'productions'   => $productions,
        'shipments'     => $shipments,
        'edit_shipment' => $shipment,
        'full_name'     => $this->sess()->get('full_name'),
        'success'       => $this->sess()->getFlashdata('success'),
        'error'         => $this->sess()->getFlashdata('error'),
        'base_url'      => base_url(),
    ]);
}

public function distributorUpdate($id)
{
    $this->requireRole('distributor');
    $userId = $this->sess()->get('user_id');

    $this->db->table('shipments')
        ->where('id', $id)
        ->where('distributor_id', $userId)
        ->update([
            'production_batch_id' => $this->request->getPost('production_batch_id'),
            'destination_store'   => $this->request->getPost('destination_store'),
            'destination_city'    => $this->request->getPost('destination_city'),
            'shipment_date'       => $this->request->getPost('shipment_date'),
            'arrival_date'        => $this->request->getPost('arrival_date'),
            'logistics_partner'   => $this->request->getPost('logistics_partner'),
            'storage_temp'        => $this->request->getPost('storage_temp'),
            'notes'               => $this->request->getPost('notes'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

    $this->sess()->setFlashdata('success', 'Data pengiriman berhasil diperbarui.');
    return redirect()->to('/traceability/distributor');
}

    public function distributorDelete($id)
    {
        $this->requireRole('distributor');
        $userId = $this->sess()->get('user_id');

        $this->db->table('shipments')
            ->where('id', $id)
            ->where('distributor_id', $userId)
            ->delete();

        $this->sess()->setFlashdata('success', 'Data pengiriman berhasil dihapus.');
        return redirect()->to('/traceability/distributor');
    }

    public function generateQR($token)
    {
        $this->requireRole('distributor');
        $trackingUrl = base_url("traceability/track/$token");
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($trackingUrl);
        return redirect()->to($qrUrl);
    }

    public function track($token = null)
    {
        if (!$token) {
            return $this->renderView('track', ['shipment' => null, 'error' => 'Token tidak valid.']);
        }

        $shipment = $this->db->query("
            SELECT s.*,
                   p.product_name, p.product_type, p.production_code, p.production_date,
                   p.expiry_date, p.quantity_units, p.bpom_number, p.ingredients_summary,
                   p.process_notes,
                   r.batch_code AS raw_batch_code, r.material_name, r.origin_location,
                   r.harvest_date, r.quantity_kg, r.certifications,
                   us.full_name AS supplier_name,
                   um.full_name AS manufacturer_name,
                   ud.full_name AS distributor_name
            FROM shipments s
            JOIN production_batches p  ON s.production_batch_id = p.id
            JOIN raw_material_batches r ON p.raw_batch_id = r.id
            JOIN users us ON r.supplier_id = us.id
            JOIN users um ON p.manufacturer_id = um.id
            JOIN users ud ON s.distributor_id = ud.id
            WHERE s.tracking_token = ?
        ", [$token])->getRowArray();

        return $this->renderView('track', [
            'shipment' => $shipment,
            'error'    => $shipment ? null : 'Data tidak ditemukan untuk token ini.',
        ]);
    }

    public function trackByResi()
    {
        $resi = $this->request->getGet('resi');
        if (!$resi) {
            return $this->renderView('track', ['shipment' => null, 'error' => null]);
        }

        $shipment = $this->db->table('shipments')->where('resi_code', $resi)->get()->getRowArray();
        if ($shipment) {
            return redirect()->to("/traceability/track/{$shipment['tracking_token']}");
        }

        return $this->renderView('track', ['shipment' => null, 'error' => "Resi '$resi' tidak ditemukan."]);
    }

    private function renderView(string $page, array $data = []): string
    {
        // Semua view ada di satu file: app/Views/traceability.php
        $data['_page'] = $page;
        return view('traceability', $data);
    }
}