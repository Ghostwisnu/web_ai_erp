<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse extends CI_Controller
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

    public function index()
    {
        $data['title'] = 'Stock Warehouse'; // atau 'Stock Warehouse' kalau tetap
        $data['user']  = $this->General_model->get_row_where(
            'master_user',
            ['user_email' => $this->session->userdata('email')]
        );

        $data['master_items'] = $this->General_model->get_items_with_details();


        $this->load->view('templates/header',  $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar',  $data);

        // Ganti ini dengan view tab yang sudah kuberi (kalau kamu pakai nama lain, sesuaikan)
        $this->load->view('warehouse/stock', $data);
        // atau kalau kamu menempelkannya di file stock.php: $this->load->view('warehouse/stock', $data);

        $this->load->view('templates/footer');
    }

    public function get_wr_stock()
    {
        $item_name = $this->input->get('item_name', true);
        $kode_item = $this->input->get('kode_item', true);

        $this->db->from('wr_stock');
        // ⬇⬇⬇ tambahkan "checkout" di sini
        $this->db->select('
        id_sj, kode_sj, no_sj, wo_number, kode_item, item_name, unit_name,
        category_name, brand, artcolor, checkin, checkout, date_arrive, from_dept, to_dept,
        created_by, created_at
    ');

        if ($kode_item !== null && $kode_item !== '') $this->db->where('kode_item', $kode_item);
        if ($item_name !== null && $item_name !== '') $this->db->where('item_name', $item_name);

        $this->db->order_by('created_at', 'DESC');
        $rows = $this->db->get()->result_array();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['data' => $rows]));
    }

    public function get_wr_sizerun()
    {
        $kode_sj   = $this->input->get('kode_sj', true);
        $wo_number = $this->input->get('wo_number', true);
        $kode_item = $this->input->get('kode_item', true);
        $brand     = $this->input->get('brand_name', true);

        $this->db->from('wr_sizerun');
        if ($kode_sj !== null && $kode_sj !== '')     $this->db->where('kode_sj', $kode_sj);
        if ($wo_number !== null && $wo_number !== '') $this->db->where('wo_number', $wo_number);
        if ($kode_item !== null && $kode_item !== '') $this->db->where('kode_item', $kode_item);
        if ($brand !== null && $brand !== '')         $this->db->where('brand_name', $brand);

        $data = $this->db->get()->result_array();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'data'    => $data
            ]));
    }


    public function checkin()
    {
        // Load the user dashboard view
        $data['title'] = 'Check IN';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]); // Get logged-in user's email
        $data['checkin'] = $this->General_model->get_all_data('wr_stock');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('warehouse/checkin', $data);
        $this->load->view('templates/footer');
    }

    public function delete_by_kodesj($kode_sj)
    {
        // Delete from wr_stock table where kode_sj matches
        $this->db->where('kode_sj', $kode_sj);
        $this->db->delete('wr_stock');  // Delete from wr_stock table

        // Delete from wr_sizerun table where kode_sj matches
        $this->db->where('kode_sj', $kode_sj);
        $this->db->delete('wr_sizerun');  // Delete from wr_sizerun table

        // Set a success flash message
        $this->session->set_flashdata('message', 'Records deleted successfully.');

        // Redirect back to the warehouse page (or any other page)
        redirect('warehouse/index');
    }

    public function checkout()
    {
        $data['title'] = 'Check Out';
        $data['user']  = $this->General_model->get_row_where(
            'master_user',
            ['user_email' => $this->session->userdata('email')]
        );

        // Data list RO untuk tabel
        $data['request'] = $this->General_model->get_grouped_ro_for_checkout(true);

        $this->load->view('templates/header',  $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar',  $data);
        $this->load->view('warehouse/checkout', $data); // <-- pastikan nama file view
        $this->load->view('templates/footer');
    }

    public function ro_details()
    {
        $this->output->set_content_type('application/json');
        $kode_ro = $this->input->get('kode_ro', true);

        if (!$kode_ro) {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'kode_ro required']));
        }

        // Header dasar
        $hdr = $this->db->select('id_wo, wo_number, kode_ro, from_dept, to_dept, status_ro, date_ro, created_by, created_at')
            ->from('pr_ro')
            ->where('kode_ro', $kode_ro)
            ->where('delete_status', 0)
            ->order_by('created_at', 'ASC')
            ->limit(1)->get()->row_array();

        if (!$hdr) {
            return $this->output->set_status_header(404)
                ->set_output(json_encode(['error' => 'RO not found']));
        }

        // Ambil brand/artcolor representatif dari purchasing_wo (berdasarkan wo_number)
        $ba = $this->db->select('MIN(brand_name) AS brand_name, MIN(artcolor_name) AS artcolor_name', false)
            ->from('purchasing_wo')
            ->where('wo_number', $hdr['wo_number'])
            ->limit(1)->get()->row_array();

        $hdr['brand_name']    = $ba['brand_name']    ?? null;
        $hdr['artcolor_name'] = $ba['artcolor_name'] ?? null;
        // alias agar front-end yang pakai "brand" / "artcolor" juga aman
        $hdr['brand']    = $hdr['brand_name'];
        $hdr['artcolor'] = $hdr['artcolor_name'];

        // Lines
        $lines = $this->db->select('kode_item, item_name, category, unit, ro_qty')
            ->from('pr_ro')
            ->where('kode_ro', $kode_ro)
            ->where('delete_status', 0)
            ->order_by('id_ro', 'ASC')
            ->get()->result_array();

        return $this->output->set_output(json_encode([
            'header' => $hdr,
            'lines'  => $lines
        ]));
    }

    // === API: generate kode SJ: {KODE_FROM}-{KODE_TO}-{SEQ} ===
    public function generate_kode_sj()
    {
        $this->output->set_content_type('application/json');

        $from_dept = trim($this->input->get('from_dept', true) ?? '');
        $to_dept   = trim($this->input->get('to_dept', true) ?? '');

        if ($from_dept === '' || $to_dept === '') {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'from_dept dan to_dept wajib diisi']));
        }

        // cari kode_dept dari master_dept (soft-delete aware)
        $from_code = $this->db->select('kode_dept')->from('master_dept')
            ->where(['dept_name' => $from_dept, 'delete_status' => 0])
            ->limit(1)->get()->row('kode_dept');
        $to_code = $this->db->select('kode_dept')->from('master_dept')
            ->where(['dept_name' => $to_dept, 'delete_status' => 0])
            ->limit(1)->get()->row('kode_dept');

        // fallback sederhana jika tidak ada di master_dept
        $from_code = strtoupper($from_code ?: substr($from_dept, 0, 3));
        $to_code   = strtoupper($to_code   ?: substr($to_dept,   0, 3));

        $prefix = $from_code . '-' . $to_code . '-';

        // cari max sequence existing di wr_stock
        $row = $this->db
            ->select('MAX(CAST(SUBSTRING_INDEX(kode_sj,"-", -1) AS UNSIGNED)) AS max_seq', false)
            ->from('wr_stock')
            ->like('kode_sj', $prefix, 'after')
            ->get()->row_array();

        $next = (int)($row['max_seq'] ?? 0) + 1;
        $seq  = str_pad($next, 3, '0', STR_PAD_LEFT);
        $kode_sj = $prefix . $seq;

        return $this->output->set_output(json_encode(['kode_sj' => $kode_sj]));
    }

    // Warehouse.php
    public function generate_no_sj()
    {
        $this->output->set_content_type('application/json');

        // Ambil tanggal dari query (?date=YYYY-MM-DD). Default: hari ini.
        $dateYmd = $this->input->get('date', true);
        if (!$dateYmd || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateYmd)) {
            $dateYmd = date('Y-m-d');
        }
        $yyyymmdd = date('Ymd', strtotime($dateYmd));

        // Cari max nomor untuk hari tsb: OUT-<seq>-<YYYYMMDD>
        $like = 'OUT-%-' . $yyyymmdd;
        $row = $this->db
            ->select('MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no_sj, "-", 2), "-", -1) AS UNSIGNED)) AS max_seq', false)
            ->from('wr_stock')
            ->like('no_sj', $like, 'after')
            ->get()->row_array();

        $next = (int)($row['max_seq'] ?? 0) + 1;
        $seq  = str_pad($next, 4, '0', STR_PAD_LEFT); // 4 digit, ubah jika mau 3/5

        $no_sj = 'OUT-' . $seq . '-' . $yyyymmdd;

        echo json_encode(['no_sj' => $no_sj]);
    }

    public function checkout_save()
    {
        $this->output->set_content_type('application/json');

        $payload = json_decode($this->input->raw_input_stream, true);
        if (!is_array($payload)) {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'Payload invalid']));
        }

        $kode_ro = trim($payload['kode_ro'] ?? '');
        $header  = $payload['header'] ?? [];
        $lines   = $payload['lines']  ?? [];

        if ($kode_ro === '' || empty($header) || empty($lines)) {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'Data tidak lengkap']));
        }

        // id_wo & wo_number fallback dari pr_ro bila kosong
        $id_wo = (int)($header['id_wo'] ?? 0);
        if ($id_wo <= 0) {
            $tmp = $this->db->select('id_wo')->from('pr_ro')
                ->where('kode_ro', $kode_ro)->where('delete_status', 0)
                ->limit(1)->get()->row_array();
            $id_wo = (int)($tmp['id_wo'] ?? 0);
        }

        $wo_number = trim($header['wo_number'] ?? '');
        if ($wo_number === '') {
            $tmp = $this->db->select('wo_number')->from('pr_ro')
                ->where('kode_ro', $kode_ro)->where('delete_status', 0)
                ->limit(1)->get()->row_array();
            $wo_number = trim($tmp['wo_number'] ?? '');
        }

        $kode_sj   = trim($header['kode_sj']   ?? '');
        $no_sj     = trim($header['no_sj']     ?? '');
        $from_dept = trim($header['from_dept'] ?? '');
        $to_dept   = trim($header['to_dept']   ?? '');
        $date_sj   = trim($header['date_sj']   ?? '');
        $creator   = trim($header['created_by'] ?? ($this->session->userdata('email') ?? 'system'));
        $now       = date('Y-m-d H:i:s');

        if ($kode_sj === '' || $from_dept === '' || $to_dept === '' || $date_sj === '') {
            return $this->output->set_status_header(400)
                ->set_output(json_encode(['error' => 'Header SJ tidak lengkap']));
        }

        // === NEW: ambil brand/artcolor dari payload atau fallback ke purchasing_wo
        $brandName = trim($header['brand_name']    ?? $header['brand']    ?? '');
        $artName   = trim($header['artcolor_name'] ?? $header['artcolor'] ?? '');

        if ($brandName === '' || $artName === '') {
            $ba = $this->db->select('MIN(brand_name) AS brand_name, MIN(artcolor_name) AS artcolor_name', false)
                ->from('purchasing_wo')
                ->where('wo_number', $wo_number)
                ->limit(1)->get()->row_array();
            $brandName = $brandName !== '' ? $brandName : ($ba['brand_name']    ?? '');
            $artName   = $artName   !== '' ? $artName   : ($ba['artcolor_name'] ?? '');
        }

        // Siapkan batch insert ke wr_stock (checkout)
        $insert = [];
        foreach ($lines as $i => $l) {
            $kode_item = trim($l['kode_item'] ?? '');
            $item_name = trim($l['item_name'] ?? '');
            $category  = trim($l['category']  ?? '');
            $unit      = trim($l['unit']      ?? '');
            $checkout  = (string)($l['checkout'] ?? '0');

            if ($kode_item === '' || $item_name === '' || $category === '' || $unit === '') {
                return $this->output->set_status_header(400)
                    ->set_output(json_encode(['error' => "Baris " . ($i + 1) . " tidak lengkap"]));
            }

            $insert[] = [
                'id_wo'         => $id_wo,
                'kode_sj'       => $kode_sj,
                'no_sj'         => $no_sj,
                'from_dept'     => $from_dept,   // sudah “swap” di front-end
                'to_dept'       => $to_dept,
                'kode_bom'      => null,
                'wo_number'     => $wo_number,
                'kode_item'     => $kode_item,
                'category_name' => $category,
                'unit_name'     => $unit,
                'item_name'     => $item_name,
                'brand'         => $brandName,   // << simpan brand
                'artcolor'      => $artName,     // << simpan art/color
                'bom_cons'      => null,
                'checkin'       => null,
                'checkout'      => $checkout,
                'created_by'    => $creator,
                'created_at'    => $now,
                'date_arrive'   => $date_sj
            ];
        }

        // Simpan + update status RO
        $this->db->trans_start();

        if (!empty($insert)) {
            $this->db->insert_batch('wr_stock', $insert);
        }

        $this->db->where('kode_ro', $kode_ro)
            ->where('delete_status', 0)
            ->update('pr_ro', ['status_ro' => 'sudah dikirim']);

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            return $this->output->set_status_header(500)
                ->set_output(json_encode(['error' => 'Gagal menyimpan checkout']));
        }

        return $this->output->set_output(json_encode([
            'success'  => true,
            'inserted' => count($insert),
            'kode_sj'  => $kode_sj
        ]));
    }

    // application/controllers/Warehouse.php
    public function wo_item_totals()
    {
        // Ambil parameter
        $wo_number = $this->input->get('wo_number', true);
        $items     = $this->input->get('items'); // bisa array atau string (comma-separated)

        if (!$wo_number) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'wo_number is required']));
        }

        // Normalisasi items
        if (is_string($items)) {
            // support ?items=MT-0001,MT-0002
            $items = array_filter(array_map('trim', explode(',', $items)));
        } elseif (!is_array($items)) {
            $items = [];
        }

        $map = [];
        $sum_ro = 0.0;
        $sum_in = 0.0;

        foreach ($items as $kode_item) {
            $ro_total = (float) ($this->General_model->get_total_ro_qty($wo_number, $kode_item) ?? 0);
            $in_total = (float) ($this->General_model->get_total_checkin_qty($wo_number, $kode_item) ?? 0);

            $map[$kode_item] = [
                'ro_total'       => $ro_total,
                'checkin_total'  => $in_total,
                'remaining'      => $ro_total - $in_total, // >0 berarti masih kurang
                'status'         => ($in_total >= $ro_total) ? 'cukup' : 'kurang'
            ];

            $sum_ro += $ro_total;
            $sum_in += $in_total;
        }

        $summary = [
            'ro_total'      => $sum_ro,
            'checkin_total' => $sum_in,
            'remaining'     => $sum_ro - $sum_in,
            'status'        => ($sum_in >= $sum_ro) ? 'cukup' : 'kurang'
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'items'   => $map,
                'summary' => $summary
            ]));
    }


    public function create_sj()
    {
        // Load the user dashboard view
        $data['title'] = 'Create SJ';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]); // Get logged-in user's email
        $data['wo_list'] = $this->db->order_by('wo_number', 'ASC')->get('purchasing_wo')->result_array();
        // Fetch the latest record from the wr_stock table to get the last 'kode_sj'
        $this->db->order_by('id_sj', 'DESC'); // Get the latest record first
        $this->db->like('kode_sj', 'IN-'); // Assuming 'IN-' is the prefix for the kode_sj
        $last_stock = $this->db->get('wr_stock')->row();

        // Extract the last 'new_number' from 'kode_sj' (e.g., IN-001-2025-08-27 -> 001)
        if ($last_stock) {
            // Extract the numeric part of the 'kode_sj' (e.g., IN-001-2025-08-27 -> 001)
            $last_number = (int) substr($last_stock->kode_sj, 3, 3);  // Extract the 3 digits after 'IN-'
        } else {
            // Start from 1 if no previous records exist
            $last_number = 0;
        }

        // Increment the number for the new 'new_number'
        $new_number = str_pad($last_number + 1, 3, '0', STR_PAD_LEFT);  // Increment and pad with leading zeros

        // Get today's date in Y-m-d format (e.g., 2025-08-27)
        $current_date = date('Y-m-d');

        // Generate the new 'kode_sj' using the incremented 'new_number' and the current date
        $kode_sj = 'IN-' . $new_number . '-' . $current_date;

        // Pass this $kode_sj to the view
        $data['kode_sj'] = $kode_sj;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('warehouse/create_sj', $data);
        $this->load->view('templates/footer');
    }

    public function get_size_run_data()
    {
        // Get the brand_name from the request
        $brand_name = $this->input->get('brand');

        // Fetch the size data for the given brand using the model
        $size_data = $this->General_model->get_sizes_by_brand($brand_name);

        // Log for debugging to check the returned size data
        log_message('debug', 'Size data for brand ' . $brand_name . ': ' . print_r($size_data, true));

        // Return the size data as JSON
        echo json_encode(['sizes' => $size_data]);
    }

    public function save_stock()
    {
        $rows_json = $this->input->post('rows');
        $sizerun_json = $this->input->post('sizerun_rows');

        $rows = json_decode($rows_json, true) ?: [];
        $sizerun_rows = json_decode($sizerun_json, true) ?: [];

        if (empty($rows)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'No rows to save']));
        }

        $now = date('Y-m-d H:i:s');
        $user = $this->session->userdata('email') ?: 'system';

        $this->db->trans_begin();

        // ---- wr_stock ----
        $insertStock = [];
        foreach ($rows as $r) {
            $checkin = (string)($r['checkin'] ?? '0');
            $category_name = $r['category_name'] ?? '';

            // Validasi Checkin QTY berdasarkan kategori
            if ($category_name === 'Material' && $checkin <= 0) {
                // Jika kategori "Material" dan Checkin QTY <= 0, lewati baris ini
                continue;
            }

            // Menyiapkan data untuk batch insert
            $insertStock[] = [
                'id_wo' => (int)($r['id_wo'] ?? 0),
                'id_sj' => 0, // isi jika punya id_sj header
                'kode_sj' => $r['kode_sj'] ?? '',
                'no_sj' => $r['no_sj'] ?? '',
                'kode_bom' => $r['kode_bom'] ?? '',
                'wo_number' => $r['wo_number'] ?? '',
                'kode_item' => $r['kode_item'] ?? '',
                'category_name' => $r['category_name'] ?? '',
                'unit_name' => $r['unit_name'] ?? '',
                'item_name' => $r['item_name'] ?? '',
                'brand' => $r['brand'] ?? '',
                'artcolor' => $r['artcolor'] ?? '',
                'bom_cons' => (string)($r['bom_cons'] ?? '0'),
                'checkin' => $checkin,
                'checkout' => (string)($r['checkout'] ?? ''),
                'created_by' => $user,
                'created_at' => $now,
                'date_arrive' => !empty($r['date_arrive']) ? $r['date_arrive'] : null,
                'from_dept' => $r['from_dept'] ?? null,
                'to_dept' => $r['to_dept'] ?? null,
            ];
        }

        if (!empty($insertStock)) {
            $this->db->insert_batch('wr_stock', $insertStock);
        }

        // ---- wr_sizerun ----
        if (!empty($sizerun_rows)) {
            $brandCache = [];
            foreach ($sizerun_rows as $sr) {
                $bn = trim($sr['brand_name'] ?? '');
                if ($bn !== '' && !array_key_exists($bn, $brandCache)) {
                    $brandCache[$bn] = $this->General_model->get_brand_id_by_name($bn);
                }
            }

            $insertSize = [];
            foreach ($sizerun_rows as $sr) {
                $insertSize[] = [
                    'id_wo' => (int)($sr['id_wo'] ?? 0),
                    'id_brand' => isset($brandCache[$sr['brand_name']]) ? (int)($brandCache[$sr['brand_name']] ?? 0) : null,
                    'kode_sj' => $sr['kode_sj'] ?? '',
                    'kode_item' => $sr['kode_item'] ?? '',
                    'wo_number' => $sr['wo_number'] ?? '',
                    'brand_name' => $sr['brand_name'] ?? '',
                    'size_name' => $sr['size_name'] ?? '',
                    'sizeq_qty' => (string)($sr['sizeq_qty'] ?? '0'),
                    'created_by' => $user,
                    'created_at' => $now
                ];
            }

            if (!empty($insertSize)) {
                $this->db->insert_batch('wr_sizerun', $insertSize);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'DB error: ' . $this->db->error()['message']]));
        }

        $this->db->trans_commit();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true]));
    }
}
