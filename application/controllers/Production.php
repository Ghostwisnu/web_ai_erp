<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Production extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load necessary libraries and models
        // $this->load->library('session');
        // $this->load->model('General_model');

        // Check if user is logged in
        is_logged_in();
    }

    // Tampilkan list: hanya yang belum dihapus
    public function index()
    {
        $data['title'] = 'Request Order';
        $data['user']  = $this->General_model->get_row_where('master_user', [
            'user_email' => $this->session->userdata('email')
        ]);

        // HANYA aktif (delete_status NULL/0)
        $data['request'] = $this->General_model->get_grouped_request_orders_active();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('production/request_order', $data);
        $this->load->view('templates/footer');
    }

    // Soft delete seluruh baris 1 kode_ro
    public function ro_soft_delete()
    {
        $this->output->set_content_type('application/json');

        $kode_ro = trim($this->input->post('kode_ro', true) ?? '');
        if ($kode_ro === '') {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'kode_ro wajib diisi']));
        }

        $now  = date('Y-m-d H:i:s');
        $user = $this->session->userdata('email') ?? 'system';

        $this->db->where('kode_ro', $kode_ro)
            ->set('delete_status', 1)
            ->set('delete_by', $user)
            ->set('delete_at', $now)
            ->update('pr_ro');

        if ($this->db->affected_rows() < 1) {
            // tidak ketemu / sudah terhapus
            return $this->output->set_status_header(404)
                ->set_output(json_encode(['error' => 'Data tidak ditemukan atau sudah dihapus']));
        }

        return $this->output->set_output(json_encode(['success' => true]));
    }

    public function ro_details()
    {
        $this->output->set_content_type('application/json');

        $kode_ro = $this->input->get('kode_ro', true);
        if (!$kode_ro) {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'kode_ro is required']));
        }

        // Pakai helper privat yang sudah benar kolomnya (brand_name, artcolor_name, dll)
        $data = $this->get_ro_details($kode_ro);
        if (!$data) {
            return $this->output->set_status_header(404)
                ->set_output(json_encode(['error' => 'Request Order not found']));
        }

        // Kembalikan JSON sesuai yang diharapkan view (header & lines)
        return $this->output->set_output(json_encode($data));
    }

    public function create_request_order()
    {
        $data['title'] = 'Create Request Order';
        $data['user']  = $this->General_model->get_row_where('master_user', [
            'user_email' => $this->session->userdata('email')
        ]);



        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('production/create_request_order', $data);
        $this->load->view('templates/footer');
    }


    public function get_material_data()
    {
        $wo_number = $this->input->post('wo_number');
        $materials = $this->General_model->get_materials_by_kode($wo_number);  // Fetch materials based on wo_number
        echo json_encode($materials);
    }

    // Production.php
    public function get_sizerun_data()
    {
        $this->output->set_content_type('application/json');
        $wo_number  = $this->input->post('wo_number', true);
        $brand_name = $this->input->post('brand_name', true);

        if (!$wo_number) {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'wo_number required']));
        }

        // Ambil sizerun dari purchasing_sizerun; fallback ke master_size (qty=0)
        $rows = $this->General_model->get_wo_size_details_by_brand($wo_number, $brand_name);
        return $this->output->set_output(json_encode($rows));
    }


    public function get_unique_wo_data()
    {
        // Fetch distinct wo_number and brand_name combinations
        $this->db->select('wo_number, brand_name, description, category, consumption, total_consumption');
        $this->db->from('purchasing_wo');
        $this->db->group_by('wo_number, brand_name'); // Ensure uniqueness by grouping by wo_number and brand_name
        $this->db->order_by('wo_number'); // Optional: to sort the results by wo_number
        $query = $this->db->get();

        return $query->result_array(); // Return the result as an array
    }


    public function output()
    {
        $data['title'] = 'Production Output';
        $data['user'] = $this->General_model->get_row_where('master_user', [
            'user_email' => $this->session->userdata('email')
        ]);

        $data['request'] = $this->General_model->get_grouped_request_orders_active();
        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('production/production_output', $data);
        $this->load->view('templates/footer');
    }

    // application/controllers/Production.php
    public function production_report()
    {
        // Ambil parameter dengan XSS filter
        $kode_ro = $this->input->get('kode_ro', true);

        if (empty($kode_ro)) {
            $this->session->set_flashdata('message', '<div class="ui negative message">Parameter kode_ro wajib diisi.</div>');
            redirect('Production/output');
            return;
        }

        // Ambil satu baris RO (header) – pastikan belum dihapus (soft delete)
        $ro_details = $this->General_model->get_row_where('pr_ro', [
            'kode_ro'       => $kode_ro,
            'delete_status' => 0
        ]);

        if (!$ro_details) {
            $this->session->set_flashdata('message', '<div class="ui negative message">Data Request Order tidak ditemukan untuk kode: ' . htmlspecialchars($kode_ro, ENT_QUOTES, 'UTF-8') . '</div>');
            redirect('Production/output');
            return;
        }

        // Ambil WO number dari RO header
        $wo_number = $ro_details['wo_number'] ?? '';

        if (empty($wo_number)) {
            $this->session->set_flashdata('message', '<div class="ui negative message">WO Number pada RO ini tidak valid.</div>');
            redirect('Production/output');
            return;
        }

        // Ambil salah satu nilai size_qty dari pr_ro sesuai kode_ro (sesuai kebutuhan view)
        // Fungsi ini sudah kamu sediakan di model; kembalikan 0 jika tidak ada
        $size_qty = (int) ($this->General_model->get_size_qty_by_kode_ro($kode_ro) ?? 0);

        // (Opsional) Ambil header WO kalau diperlukan (tidak wajib untuk view saat ini)
        // $wo_details = $this->General_model->get_row_where('purchasing_wo', ['wo_number' => $wo_number]);

        // Ambil detail sizerun spesifik WO.
        // Model kamu sudah otomatis fallback ke master_size (qty=0) kalau belum ada sizerun untuk WO tsb.
        $sizerun_data = $this->General_model->get_wo_size_details($wo_number);

        // Ambil data user untuk topbar/sidebar
        $user = $this->General_model->get_row_where('master_user', [
            'user_email' => $this->session->userdata('email')
        ]);

        // Siapkan payload ke view
        $data = [
            'title'   => 'Create Production Report',
            'header'  => $ro_details,     // dipakai di header view (wo_number, kode_ro, from_dept)
            'wo_qty'  => $size_qty,       // ditampilkan sebagai "WO Quantity" di section validasi
            'sizerun' => $sizerun_data,   // untuk tabel sizerun (id_sizerun, size_name, size_qty)
            'user'    => $user
        ];

        // Render
        $this->load->view('templates/header',  $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar',  $data);
        $this->load->view('production/production_report', $data);
        $this->load->view('templates/footer');
    }

    public function save_production_report()
    {
        // Cek apakah email ada dalam session
        $email = $this->session->userdata('email');
        if (!$email) {
            $this->session->set_flashdata('message', 'Session email not found!');
            redirect('Production/output');
            return;
        }

        // Ambil data dari form
        $wo_number = $this->input->post('wo_number');
        $kode_ro = $this->input->post('kode_ro');
        $sizerun_qty = $this->input->post('sizerun_qty');
        $mis_category = $this->input->post('mis_category');
        $mis_qty = $this->input->post('mis_qty');
        $hfg_items = json_decode($this->input->post('hfg_items'), true);  // Ambil data HFG yang dipilih

        // Validasi apakah ada item HFG yang dipilih
        if (empty($hfg_items)) {
            $this->session->set_flashdata('message', 'Please select at least one HFG item!');
            redirect('Production/output');
            return;
        }

        // Validasi apakah sizerun_qty dan missing_qty ada dan terisi
        if (empty($wo_number) || empty($kode_ro) || empty($sizerun_qty)) {
            $this->session->set_flashdata('message', 'Required fields are missing or invalid!');
            redirect('Production/output');
            return;
        }

        // Panggil fungsi model untuk menyimpan data
        $status = $this->General_model->save_production_report(
            $wo_number,
            $kode_ro,
            $sizerun_qty,
            $mis_category,
            $mis_qty,
            $hfg_items
        );

        // Feedback ke pengguna
        $this->session->set_flashdata('message', "Production report saved successfully. Status: $status");
        redirect('Production/output');
    }


    // Fungsi untuk menampilkan halaman perbaikan produksi
    public function fix_production($kode_ro)
    {
        $header = $this->General_model->get_row_where('pr_ro', ['kode_ro' => $kode_ro]);

        // Ambil data sizerun untuk kode_ro
        $sizerun = $this->General_model->get_wo_size_details($header['wo_number']);
        $wo_qty = $this->General_model->get_size_qty_for_validation($header['wo_number']);

        // Ambil previous_qty untuk masing-masing size_name dari pr_output
        foreach ($sizerun as &$size) {
            $size['previous_qty'] = $this->General_model->get_previous_qty($kode_ro, $size['size_name']);
        }
        // Ambil data user untuk topbar/sidebar
        $user = $this->General_model->get_row_where('master_user', [
            'user_email' => $this->session->userdata('email')
        ]);

        $data = [
            'title' => 'Perbaiki Produksi',
            'header' => $header,
            'sizerun' => $sizerun,
            'wo_qty' => $wo_qty,
            'user'    => $user
        ];

        $this->load->view('templates/header',  $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar',  $data);
        $this->load->view('production/fix_production_view', $data);  // Pastikan view sudah ada
        $this->load->view('templates/footer');
    }

    public function save_fix_production()
    {
        // Ambil data dari form
        $wo_number    = $this->input->post('wo_number');
        $kode_ro      = $this->input->post('kode_ro');
        $sizerun_qty  = $this->input->post('sizerun_qty');  // [size_id => qty_baru]
        $mis_category = $this->input->post('mis_category'); // [size_id => kategori]
        $mis_qty      = $this->input->post('mis_qty');      // [size_id => qty_missing]

        if (empty($wo_number) || empty($kode_ro) || !is_array($sizerun_qty)) {
            $this->session->set_flashdata('message', 'Input tidak valid.');
            redirect('production/output');
            return;
        }

        // Total WO utk validasi status
        $wo_qty = $this->General_model->get_size_qty_for_validation($wo_number);

        // === DELTA untuk wr_stock: hanya sizerun BARU (tanpa previous, tanpa missing) ===
        $delta_sizerun_total = array_sum(array_map('intval', $sizerun_qty));

        $total_sizerun_qty = 0; // untuk perhitungan status (updated total per size)
        $total_missing_qty = 0;
        $isValid           = true;

        $this->db->trans_start();

        // Proses per size: update pr_output (gabung dengan previous + missing sesuai logika kamu)
        foreach ($sizerun_qty as $size_id => $qty_baru) {
            $qty_baru = (int)$qty_baru;

            $size_name    = $this->General_model->get_size_name($size_id);
            $previous_qty = (int)$this->General_model->get_previous_qty($kode_ro, $size_name);

            $mis_qty_val      = isset($mis_qty[$size_id]) ? (int)$mis_qty[$size_id] : 0;
            $mis_category_val = isset($mis_category[$size_id]) ? $mis_category[$size_id] : 'sudah lengkap';

            // TOTAL TERKINI per size (untuk disimpan ke pr_output & validasi status)
            $updated_size_qty = $qty_baru + $previous_qty + $mis_qty_val;

            $total_sizerun_qty += $updated_size_qty;
            $total_missing_qty += $mis_qty_val;

            if ($updated_size_qty > $wo_qty) {
                $isValid = false;
            }

            // Update baris per size ke pr_output sesuai fungsi kamu
            $this->General_model->update_size_qty($kode_ro, $size_name, $updated_size_qty, $mis_category_val, $mis_qty_val);
        }

        // --- BEGIN: Sinkron wr_stock.checkin pakai DELTA saja (akumulatif tambah) ---
        // Ambil brand/artcolor utk isi insert baru
        $this->db->select('brand_name, artcolor_name, id_wo');
        $this->db->from('pr_ro');
        $this->db->where('wo_number', $wo_number);
        $this->db->where('kode_ro', $kode_ro);
        $ro_row = $this->db->get()->row();

        $brand_name_once    = $ro_row ? $ro_row->brand_name    : null;
        $artcolor_name_once = $ro_row ? $ro_row->artcolor_name : null;
        $id_wo_once         = $ro_row ? (int)$ro_row->id_wo     : (int)$this->input->post('id_wo');

        // Ambil daftar kode_item yang terkait (kalau form kirim hfg_items, bisa pakai itu langsung)
        $kode_items = $this->db->select('DISTINCT kode_item', false)
            ->from('pr_output')
            ->where('wo_number', $wo_number)
            ->where('kode_ro', $kode_ro)
            ->where('kode_item IS NOT NULL', null, false)
            ->get()->result_array();

        if (!empty($kode_items) && $delta_sizerun_total > 0) {
            foreach ($kode_items as $row) {
                $kode_item = $row['kode_item'];

                $existing = $this->db->select('id_sj, checkin')
                    ->get_where('wr_stock', [
                        'wo_number' => $wo_number,
                        'kode_item' => $kode_item
                    ])->row();

                if ($existing) {
                    // Tambahkan hanya DELTA sizerun baru
                    $this->db->set('checkin', "CAST(COALESCE(`checkin`, '0') AS UNSIGNED) + " . (int)$delta_sizerun_total, false);
                    $this->db->where('id_sj', $existing->id_sj);
                    $this->db->update('wr_stock');
                } else {
                    // Insert baru dengan nilai checkin = DELTA
                    $data_stock = [
                        'id_wo'      => $id_wo_once,
                        'wo_number'  => $wo_number,
                        'kode_item'  => $kode_item,
                        'brand'      => $brand_name_once,
                        'artcolor'   => $artcolor_name_once,
                        'checkin'    => (string)(int)$delta_sizerun_total,
                        'created_by' => $this->session->userdata('email'),
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->db->insert('wr_stock', $data_stock);
                }
            }
        }
        // --- END: Sinkron wr_stock.checkin ---

        // Status produksi (pakai total updated untuk validasi)
        if ($total_sizerun_qty == $wo_qty) {
            $status = 'produksi sudah lengkap';
        } else {
            $status = (($total_sizerun_qty + $total_missing_qty) == $wo_qty)
                ? 'produksi belum lengkap'
                : 'produksi belum lengkap';
        }

        // Update status RO
        $data_ro_status = [
            'status_ro'  => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('email'),
        ];
        $this->db->where('kode_ro', $kode_ro);
        $this->db->update('pr_ro', $data_ro_status);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->session->set_flashdata('message', 'Terjadi kesalahan saat menyimpan.');
        } else {
            $this->session->set_flashdata('message', 'Report berhasil disimpan. wr_stock ditambah dengan delta sizerun baru, dan status RO diperbarui.');
        }

        redirect('production/output');
    }

    // --- AJAX: daftar WO (distinct per wo_number), fokus ke FG ---
    public function wo_list()
    {
        $this->output->set_content_type('application/json');

        // Strict 1 row per wo_number
        $sql = "
            SELECT
                MIN(id_wo)             AS id_wo,
                wo_number,
                MIN(fg_kode_item)      AS fg_kode_item,
                MIN(fg_item_name)      AS fg_item_name,
                MIN(fg_category_name)  AS fg_category_name,
                MIN(fg_unit)           AS fg_unit,
                MIN(brand_name)        AS brand_name,
                MIN(artcolor_name)     AS artcolor_name,
                MIN(date_of_order)     AS date_of_order,
                MIN(due_date)          AS due_date
            FROM purchasing_wo
            GROUP BY wo_number
            ORDER BY due_date ASC, wo_number ASC
        ";
        $rows = $this->db->query($sql)->result_array();

        return $this->output->set_output(json_encode(['data' => $rows]));
    }

    // --- AJAX: daftar MT (materials) berdasarkan wo_number terpilih ---
    public function wo_items()
    {
        $this->output->set_content_type('application/json');
        $wo_number  = $this->input->get('wo_number', true);
        $brand_name = $this->input->get('brand_name', true);

        if (!$wo_number) {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'wo_number required']));
        }
        if (!$brand_name) {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'brand_name required']));
        }

        // Ambil 1 baris per item name dengan bom_cons terkecil
        $sql = "
            SELECT 
                t.id_wo,
                t.mt_kode_item     AS kode_item,
                t.mt_item_name     AS item_name,
                t.mt_category_name AS category_name,
                t.mt_unit          AS unit_name,
                t.bom_cons,
                t.bom_qty
            FROM purchasing_wo t
            WHERE t.wo_number = ? 
              AND t.brand_name = ?
              AND CAST(t.bom_cons AS DECIMAL(18,2)) = (
                    SELECT MIN(CAST(x.bom_cons AS DECIMAL(18,2)))
                    FROM purchasing_wo x
                    WHERE x.wo_number   = t.wo_number
                      AND x.brand_name  = t.brand_name
                      AND x.mt_item_name= t.mt_item_name
              )
              AND t.id_wo = (
                    SELECT MIN(y.id_wo)
                    FROM purchasing_wo y
                    WHERE y.wo_number    = t.wo_number
                      AND y.brand_name   = t.brand_name
                      AND y.mt_item_name = t.mt_item_name
                      AND CAST(y.bom_cons AS DECIMAL(18,2)) = CAST(t.bom_cons AS DECIMAL(18,2))
              )
            ORDER BY t.mt_item_name ASC
        ";

        $items = $this->db->query($sql, [$wo_number, $brand_name])->result_array();
        return $this->output->set_output(json_encode(['data' => $items]));
    }
    // === LIST: hanya tampilkan yang delete_status = 0 ===
    public function dept_list()
    {
        // No-cache headers
        $this->output
            ->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0')
            ->set_header('Cache-Control: post-check=0, pre-check=0', false)
            ->set_header('Pragma: no-cache')
            ->set_content_type('application/json');

        $rows = $this->db->select('id_dept, kode_dept, dept_name, created_at, created_by')
            ->from('master_dept')
            ->where('delete_status', 0)
            ->order_by('created_at', 'DESC')
            ->get()->result_array();

        return $this->output->set_output(json_encode(['data' => $rows]));
    }

    public function generate_kode_ro()
    {
        $this->output->set_content_type('application/json');

        $from_code = strtoupper(trim($this->input->get('from_code', true) ?? ''));
        $to_code   = strtoupper(trim($this->input->get('to_code', true) ?? ''));

        if ($from_code === '' || $to_code === '') {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'from_code dan to_code wajib diisi']));
        }

        $prefix = $from_code . '-' . $to_code . '-';

        $row = $this->db->select('MAX(CAST(SUBSTRING_INDEX(kode_ro,"-", -1) AS UNSIGNED)) AS max_seq', false)
            ->from('pr_ro')
            ->like('kode_ro', $prefix, 'after')
            ->get()->row_array();

        $next = (int)($row['max_seq'] ?? 0) + 1;
        $seq  = str_pad($next, 3, '0', STR_PAD_LEFT);
        $kode = $prefix . $seq;

        return $this->output->set_output(json_encode(['kode_ro' => $kode]));
    }

    public function save_ro()
    {
        $this->output->set_content_type('application/json');

        // Payload JSON array
        $rows = json_decode($this->input->raw_input_stream, true);

        if (!is_array($rows) || empty($rows)) {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'Payload kosong / format tidak valid']));
        }

        $now     = date('Y-m-d H:i:s');
        $creator = $this->session->userdata('email') ?? 'system';

        $insert = [];
        $errors = [];

        foreach ($rows as $i => $r) {
            $id_wo     = (int)($r['id_wo'] ?? 0);
            $wo_number = trim($r['wo_number'] ?? '');
            $kode_ro   = trim($r['kode_ro'] ?? '');
            $kode_item = trim($r['kode_item'] ?? '');
            $item_name = trim($r['item_name'] ?? '');
            $category  = trim($r['category'] ?? '');
            $unit      = trim($r['unit'] ?? '');
            $ro_qty    = (string)($r['ro_qty'] ?? '');

            // Total sizerun pairs (WAJIB untuk disimpan)
            $size_qty  = (string)($r['size_qty'] ?? '0');

            $from_dept = trim($r['from_dept'] ?? '');
            $to_dept   = trim($r['to_dept'] ?? '');
            $date_ro   = trim($r['date_ro'] ?? '');

            // robust: dukung brand/artcolor apapun key dari FE
            $brand     = trim($r['brand_name'] ?? $r['brand'] ?? '');
            $artcolor  = trim($r['artcolor_name'] ?? $r['artcolor'] ?? '');

            // Validasi minimal
            if (
                !$id_wo || $wo_number === '' || $kode_ro === '' || $kode_item === '' ||
                $item_name === '' || $category === '' || $unit === '' ||
                $from_dept === '' || $to_dept === '' || $date_ro === '' || $ro_qty === ''
            ) {
                $errors[] = "Baris " . ($i + 1) . " tidak lengkap";
                continue;
            }

            $insert[] = [
                'id_wo'         => $id_wo,
                'wo_number'     => $wo_number,
                'kode_ro'       => $kode_ro,
                'kode_item'     => $kode_item,
                'item_name'     => $item_name,
                'category'      => $category,
                'unit'          => $unit,

                // simpan total sizerun (pairs)
                'size_qty'      => $size_qty,

                'ro_qty'        => $ro_qty,
                'from_dept'     => $from_dept,
                'to_dept'       => $to_dept,
                'brand_name'    => $brand,
                'artcolor_name' => $artcolor,
                'status_ro'     => 'menunggu dikirim',
                'date_ro'       => $date_ro,
                'created_by'    => $creator,
                'created_at'    => $now
            ];
        }

        if (!empty($errors)) {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => implode('; ', $errors)]));
        }

        if (empty($insert)) {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'Tidak ada baris valid untuk disimpan']));
        }

        $this->db->trans_start();
        // Jika kamu sudah menambahkan General_model->insert_ro_batch(), bisa pakai:
        // $ok = $this->General_model->insert_ro_batch($insert);
        // Kalau belum, tetap gunakan insert_batch langsung:
        $ok = $this->db->insert_batch('pr_ro', $insert);
        $this->db->trans_complete();

        if (!$this->db->trans_status() || !$ok) {
            return $this->output->set_status_header(500)
                ->set_output(json_encode(['error' => 'Gagal menyimpan ke database']));
        }

        return $this->output->set_output(json_encode([
            'success'  => true,
            'inserted' => count($insert),
            'kode_ro'  => $insert[0]['kode_ro']
        ]));
    }
    // New method to generate production report
    public function create_production_report()
    {
        $this->output->set_content_type('application/json');

        $kode_ro = $this->input->get('kode_ro', true);
        if (!$kode_ro) {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'kode_ro is required']));
        }

        $ro_details = $this->get_ro_details($kode_ro);
        if (!$ro_details) {
            return $this->output->set_status_header(404)
                ->set_output(json_encode(['error' => 'Request Order not found']));
        }

        return $this->generate_report($ro_details);
    }

    public function get_hfg_data()
    {
        $wo_number = $this->input->get('wo_number');
        $data = $this->General_model->get_hfg_by_wo($wo_number);
        echo json_encode($data);
    }


    // Helper function to fetch request order details
    private function get_ro_details($kode_ro)
    {
        $hdr = $this->db->select('wo_number, kode_ro, from_dept, to_dept, status_ro, date_ro, created_by, created_at, brand_name, artcolor_name')
            ->from('pr_ro')
            ->where('kode_ro', $kode_ro)
            ->group_start()
            ->where('delete_status', 0)
            ->or_where('delete_status IS NULL', null, false)
            ->group_end()
            ->order_by('created_at', 'DESC') // ambil header terbaru
            ->limit(1)
            ->get()->row_array();

        if (!$hdr) return false;

        $lines = $this->db->select('kode_item, item_name, category, unit, ro_qty, size_qty')
            ->from('pr_ro')
            ->where('kode_ro', $kode_ro)
            ->group_start()
            ->where('delete_status', 0)
            ->or_where('delete_status IS NULL', null, false)
            ->group_end()
            ->order_by('id_ro', 'ASC')
            ->get()->result_array();

        // Total sizerun (jumlah size_qty dari semua baris sizerun)
        $total_sizerun = array_sum(array_column($lines, 'size_qty'));

        // Menambahkan total_sizerun ke header
        $hdr['total_sizerun'] = $total_sizerun;

        return ['header' => $hdr, 'lines' => $lines];
    }

    // Logic to generate report, this could be PDF, Excel, etc.
    private function generate_report($ro_details)
    {
        $header = $ro_details['header'];
        $lines  = $ro_details['lines'];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header info
        $sheet->setCellValue('A1', 'WO Number');
        $sheet->setCellValue('B1', 'Kode RO');
        $sheet->setCellValue('C1', 'From Dept');
        $sheet->setCellValue('D1', 'To Dept');
        $sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'Date RO');
        $sheet->setCellValue('G1', 'Created By');
        $sheet->setCellValue('H1', 'Created At');
        $sheet->setCellValue('I1', 'Brand');
        $sheet->setCellValue('J1', 'Art/Color');
        $sheet->setCellValue('K1', 'Total Sizerun (pairs)');

        $sheet->setCellValue('A2', $header['wo_number']);
        $sheet->setCellValue('B2', $header['kode_ro']);
        $sheet->setCellValue('C2', $header['from_dept']);
        $sheet->setCellValue('D2', $header['to_dept']);
        $sheet->setCellValue('E2', $header['status_ro']);
        $sheet->setCellValue('F2', $header['date_ro']);
        $sheet->setCellValue('G2', $header['created_by']);
        $sheet->setCellValue('H2', $header['created_at']);
        $sheet->setCellValue('I2', $header['brand_name'] ?? '');
        $sheet->setCellValue('J2', $header['artcolor_name'] ?? '');
        $sheet->setCellValue('K2', $header['total_sizerun'] ?? '0');

        // Detail header
        $sheet->setCellValue('A4', 'Kode Item');
        $sheet->setCellValue('B4', 'Item Name');
        $sheet->setCellValue('C4', 'Category');
        $sheet->setCellValue('D4', 'Unit');
        $sheet->setCellValue('E4', 'Total Sizerun (pairs)');
        $sheet->setCellValue('F4', 'Quantity');

        // Detail rows
        $row = 5;
        foreach ($lines as $line) {
            $sheet->setCellValue('A' . $row, $line['kode_item']);
            $sheet->setCellValue('B' . $row, $line['item_name']);
            $sheet->setCellValue('C' . $row, $line['category']);
            $sheet->setCellValue('D' . $row, $line['unit']);
            $sheet->setCellValue('E' . $row, $line['size_qty']); // total sizerun per RO
            $sheet->setCellValue('F' . $row, $line['ro_qty']);
            $row++;
        }

        // Simpan file
        $save_path = FCPATH . 'assets/uploads/report/';  // gunakan FCPATH agar path valid di server
        $filename  = 'production_report_' . $header['kode_ro'] . '.xlsx';
        $file_path = $save_path . $filename;

        if (!is_dir($save_path)) {
            mkdir($save_path, 0777, true);
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($file_path);

        return $this->output->set_output(json_encode([
            'success' => true,
            'file'    => base_url('assets/uploads/report/' . $filename)
        ]));
    }
}
