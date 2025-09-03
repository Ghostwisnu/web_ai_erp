<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Purchasing extends CI_Controller
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
        $data['title'] = 'Bill Of Material';
        $data['user'] = $this->General_model->get_row_where('master_user', [
            'user_email' => $this->session->userdata('email')
        ]);

        // Get all BOM entries grouped by kode_bom (only first material per BOM)
        $data['bom'] = $this->General_model->get_bom_grouped();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('purchasing/bom', $data);
        $this->load->view('templates/footer');
    }

    public function delete_bom($kode_bom)
    {
        if (!$kode_bom) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Invalid BOM code.</div>');
            redirect('purchasing'); // Redirect back to BOM list
        }

        // Delete all BOM entries with this kode_bom
        $deleted = $this->General_model->delete_data('purchasing_bom', ['kode_bom' => $kode_bom]);

        if ($deleted) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">BOM deleted successfully.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed to delete BOM.</div>');
        }

        redirect('purchasing'); // Redirect back to BOM list
    }



    public function create_bom()
    {
        $data['title'] = 'Bill Of Material';
        $data['user'] = $this->General_model->get_row_where('master_user', [
            'user_email' => $this->session->userdata('email')
        ]);

        // Load all items for selection (FG and Materials)
        $data['item'] = $this->General_model->get_all_data('master_item');
        $data['material_items'] = $this->General_model->get_material_items();

        // Get Finish Goods
        $data['finish_goods'] = $this->General_model->get_finish_and_half_finish_goods('Barang Jadi');

        // Get Half Finish Goods
        $data['half_finish_goods'] = $this->General_model->get_finish_and_half_finish_goods('Barang Setengah Jadi');

        // Load brands
        $data['master_brand'] = $this->General_model->get_all_data('master_brand');

        // Do NOT load old BOM data here — keep form clean for new entry
        //$data['bom'] = $this->General_model->get_all_data('purchasing_bom'); // remove this

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('purchasing/create_bom', $data);
        $this->load->view('templates/footer');
    }

    public function edit_bom($kode_bom)
    {
        $data['title'] = 'Edit Bill Of Material';
        $data['user'] = $this->General_model->get_row_where('master_user', [
            'user_email' => $this->session->userdata('email')
        ]);

        // Load all items for selection
        $data['finish_goods'] = $this->General_model->get_finish_and_half_finish_goods('Barang Jadi') ?? [];
        $data['half_finish_goods'] = $this->General_model->get_finish_and_half_finish_goods('Barang Setengah Jadi') ?? [];
        $data['material_items'] = $this->General_model->get_material_items() ?? [];
        $data['master_brand'] = $this->General_model->get_all_data('master_brand') ?? [];

        // Load BOM data
        $bom_all = $this->General_model->get_materials_by_kode($kode_bom);

        if (!empty($bom_all)) {
            // Header info (assume first row)
            $data['bom_header'] = [
                'id_fg_item'     => $bom_all[0]['id_fg_item'] ?? 0,
                'fg_kode_item'   => $bom_all[0]['fg_kode_item'] ?? '',
                'fg_item_name'   => $bom_all[0]['fg_item_name'] ?? '',
                'fg_unit'        => $bom_all[0]['fg_unit'] ?? '',
                'fg_item_category' => $bom_all[0]['fg_item_category'] ?? '',
                'brand_name'     => $bom_all[0]['brand_name'] ?? '',
                'artcolor_name'  => $bom_all[0]['artcolor_name'] ?? ''
            ];

            // Prepare materials with HFG normalization
            $bom_materials = [];
            foreach ($bom_all as $row) {
                $bom_materials[] = [
                    'id_fg_item'        => $row['id_fg_item'] ?? 0,
                    'fg_kode_item'      => $row['fg_kode_item'] ?? '',
                    'id_hfg_item'       => $row['id_hfg_item'] ?? 0,
                    'hfg_kode_item'     => $row['hfg_kode_item'] ?? '',
                    'id_mt_item'        => $row['id_mt_item'] ?? 0,
                    'mt_kode_item'      => $row['mt_kode_item'] ?? '',
                    'fg_item_name'      => $row['fg_item_name'] ?? '',
                    'hfg_item_name'     => $row['hfg_item_name'] ?? 'HFG None',
                    'mt_item_name'      => $row['mt_item_name'] ?? '',
                    'fg_item_category'  => $row['fg_item_category'] ?? '',
                    'hfg_item_category' => $row['hfg_item_category'] ?? '',
                    'mt_item_category'  => $row['mt_item_category'] ?? '',
                    'fg_unit'           => $row['fg_unit'] ?? '',
                    'hfg_unit'          => $row['hfg_unit'] ?? '',
                    'mt_unit'           => $row['mt_unit'] ?? '',
                    'bom_qty'           => $row['bom_qty'] ?? 0
                ];
            }
            $data['bom_materials'] = $bom_materials;
        } else {
            $data['bom_header'] = [];
            $data['bom_materials'] = [];
        }

        $data['kode_bom'] = $kode_bom;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('purchasing/edit_bom', $data);
        $this->load->view('templates/footer');
    }


    public function save_bom()
    {
        $data = json_decode($this->input->raw_input_stream, true);

        if (empty($data)) {
            echo json_encode(['status' => 'error', 'message' => 'No data received.']);
            return;
        }

        // Generate kode_bom once
        $kode_bom = $this->General_model->generate_kode_bom();

        foreach ($data as $row) {
            $insert = [
                'kode_bom'         => $kode_bom,
                'id_fg_item'       => $row['id_fg_item'],
                'fg_kode_item'     => $row['fg_kode_item'] ?? null,
                'id_hfg_item'      => $row['id_hfg_item'],
                'hfg_kode_item'    => $row['hfg_kode_item'] ?? null,
                'id_mt_item'       => $row['id_mt_item'],
                'mt_kode_item'     => $row['mt_kode_item'] ?? null,
                'fg_item_name'     => $row['fg_item_name'],
                'hfg_item_name'    => $row['hfg_item_name'],
                'mt_item_name'     => $row['mt_item_name'],
                'fg_item_category' => $row['fg_item_category'],
                'hfg_item_category' => $row['hfg_item_category'],
                'mt_item_category' => $row['mt_item_category'],
                'fg_unit'          => $row['fg_unit'],
                'hfg_unit'         => $row['hfg_unit'],
                'mt_unit'          => $row['mt_unit'],
                'brand_name'       => $row['brand_name'],
                'artcolor_name'    => $row['artcolor_name'],
                'bom_qty'          => $row['bom_qty'],
                'created_by'       => $this->session->userdata('email'),
                'created_at'       => date('Y-m-d H:i:s'),
            ];

            $this->General_model->insert_data('purchasing_bom', $insert);
        }

        echo json_encode(['status' => 'success', 'kode_bom' => $kode_bom]);
    }

    public function update_bom($kode_bom)
    {
        // Get JSON payload
        $input = json_decode($this->input->raw_input_stream, true);

        if (!$input || empty($input['materials'])) {
            echo json_encode(['status' => 'error', 'message' => 'No materials received']);
            return;
        }

        $fg_id     = $input['fg_item_id'] ?? null;
        $fg_name   = $input['fg_item_name'] ?? '';
        $fg_unit   = $input['fg_unit_name'] ?? '';
        $fg_cat    = $input['fg_category_name'] ?? '';
        $brand     = $input['brand_name'] ?? '';
        $artcolor  = $input['art_color'] ?? '';

        if (!$fg_id) {
            echo json_encode(['status' => 'error', 'message' => 'Select FG first']);
            return;
        }

        // Generate fg_kode_item if not provided
        $fg_kode_item = $input['fg_kode_item'] ?? $this->General_model->format_kode_item($fg_id);

        // Prepare FG info array
        $fgData = [
            'id'    => $fg_id,
            'name'  => $fg_name,
            'unit'  => $fg_unit,
            'category' => $fg_cat,
            'brand' => $brand,
            'artcolor' => $artcolor,
            'kode'  => $fg_kode_item
        ];

        // Call model to replace BOM safely
        $success = $this->General_model->replace_bom($kode_bom, $fgData, $input['materials']);

        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'BOM replaced successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to replace BOM. Old data remains intact.']);
        }
    }

    public function work_order()
    {
        $data['title'] = 'Work Order';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]);
        $data['work'] = $this->General_model->get_work_orders_grouped();
        // Only FG items
        // $data['fg_items'] = $this->General_model->get_fg_items();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('purchasing/work_order', $data);
        $this->load->view('templates/footer');
    }

    public function create_wo($kode_bom)
    {
        $data['title'] = 'Create WO';
        $data['user'] = $this->General_model->get_row_where('master_user', [
            'user_email' => $this->session->userdata('email')
        ]);

        // Load all items for selection
        $data['finish_goods'] = $this->General_model->get_finish_and_half_finish_goods('Barang Jadi') ?? [];
        $data['half_finish_goods'] = $this->General_model->get_finish_and_half_finish_goods('Barang Setengah Jadi') ?? [];
        $data['material_items'] = $this->General_model->get_material_items() ?? [];
        $data['master_brand'] = $this->General_model->get_all_data('master_brand') ?? [];

        // Load BOM data
        $bom_all = $this->General_model->get_materials_by_kode($kode_bom);

        if (!empty($bom_all)) {
            // Header info (assume first row)
            $data['bom_header'] = [
                'id_fg_item'     => $bom_all[0]['id_fg_item'] ?? 0,
                'fg_kode_item'   => $bom_all[0]['fg_kode_item'] ?? '',
                'fg_item_name'   => $bom_all[0]['fg_item_name'] ?? '',
                'fg_unit'        => $bom_all[0]['fg_unit'] ?? '',
                'fg_item_category' => $bom_all[0]['fg_item_category'] ?? '',
                'brand_name'     => $bom_all[0]['brand_name'] ?? '',
                'artcolor_name'  => $bom_all[0]['artcolor_name'] ?? ''
            ];


            $brand_name = $bom_all[0]['brand_name']; // brand from BOM

            // Get the brand row
            $brand = $this->General_model->get_row_where('master_brand', [
                'brand_name' => $brand_name
            ]);

            $data['sizes'] = [];
            if ($brand) {
                $data['sizes'] = $this->General_model->get_result_where('master_size', [
                    'id_brand' => $brand['id_brand']
                ]);
                // Prepare materials with HFG normalization
                $bom_materials = [];
                foreach ($bom_all as $row) {
                    $bom_materials[] = [
                        'id_fg_item'        => $row['id_fg_item'] ?? 0,
                        'fg_kode_item'      => $row['fg_kode_item'] ?? '',
                        'id_hfg_item'       => $row['id_hfg_item'] ?? 0,
                        'hfg_kode_item'     => $row['hfg_kode_item'] ?? '',
                        'id_mt_item'        => $row['id_mt_item'] ?? 0,
                        'mt_kode_item'      => $row['mt_kode_item'] ?? '',
                        'fg_item_name'      => $row['fg_item_name'] ?? '',
                        'hfg_item_name'     => $row['hfg_item_name'] ?? 'HFG None',
                        'mt_item_name'      => $row['mt_item_name'] ?? '',
                        'fg_item_category'  => $row['fg_item_category'] ?? '',
                        'hfg_item_category' => $row['hfg_item_category'] ?? '',
                        'mt_item_category'  => $row['mt_item_category'] ?? '',
                        'fg_unit'           => $row['fg_unit'] ?? '',
                        'hfg_unit'          => $row['hfg_unit'] ?? '',
                        'mt_unit'           => $row['mt_unit'] ?? '',
                        'bom_qty'           => $row['bom_qty'] ?? 0
                    ];
                }
                $data['bom_materials'] = $bom_materials;
            } else {
                $data['bom_header'] = [];
                $data['bom_materials'] = [];
                $data['sizes'] = [];
            }

            $data['kode_bom'] = $kode_bom;

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('purchasing/create_wo', $data);
            $this->load->view('templates/footer');
        }
    }


    public function get_item_brand()
    {
        $item_id = $this->input->post('item_id');

        $brand = $this->General_model->get_row_where('purchasing_bom', ['id_item' => $item_id]);

        echo json_encode($brand);
    }

    public function get_items_modal()
    {
        $category = $this->input->post('category'); // FG atau HFG
        $items = $this->General_model->get_items_modal($category);

        header('Content-Type: application/json');
        echo json_encode($items);
    }

    // Get BOM details for HFG
    // Get HFG by kode_bom
    public function get_bom_detail()
    {
        $kode_bom = $this->input->post('kode_bom');

        if (!$kode_bom) {
            echo json_encode([]);
            return;
        }

        $result = $this->General_model->get_bom_detail_by_kode_bom($kode_bom);
        echo json_encode($result);
    }

    public function get_material_list()
    {
        $kode_bom = $this->input->post('kode_bom');

        if (!$kode_bom) {
            echo json_encode([]);
            return;
        }

        $result = $this->General_model->get_material_list_by_kode_bom($kode_bom);
        echo json_encode($result);
    }


    public function get_sizes_by_brand()
    {
        $brand_name = $this->input->post('brand_name'); // use name from frontend
        $sizes = $this->General_model->get_sizes_by_brand($brand_name);
        echo json_encode($sizes);
    }

    public function save_wo()
    {
        $payload = json_decode($this->input->raw_input_stream, true);

        // Debugging: Log the received payload
        log_message('debug', 'Payload received: ' . print_r($payload, true));

        // Validate required fields
        if (empty($payload['wo_number']) || empty($payload['fg_item_id']) || empty($payload['date_of_order']) || empty($payload['total_qty'])) {
            echo json_encode(['status' => 'error', 'message' => 'Required fields are missing.']);
            return;
        }

        // Validate kode fields
        if (empty($payload['fg_kode_item']) || empty($payload['hfg_kode_item']) || empty($payload['mt_kode_item'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields (fg_kode_item, hfg_kode_item, or mt_kode_item).']);
            return;
        }

        // Begin Transaction
        $this->db->trans_start();

        try {
            // Prepare Data for purchasing_wo table
            $wo_data = [
                'kode_bom' => $payload['kode_bom'] ?? null,
                'wo_number' => $payload['wo_number'],
                'fg_kode_item' => $payload['fg_kode_item'],
                'hfg_kode_item' => $payload['hfg_kode_item'],
                'mt_kode_item' => $payload['mt_kode_item'],
                'fg_item_name' => $payload['fg_item_name'] ?? 'Unnamed FG',
                'hfg_item_name' => $payload['hfg_item_name'] ?? 'Unnamed HFG',
                'mt_item_name' => $payload['mt_item_name'] ?? 'Unnamed Material',
                'fg_category_name' => $payload['fg_category_name'] ?? 'Unknown Category',
                'hfg_category_name' => $payload['hfg_category_name'] ?? 'Unknown Category',
                'mt_category_name' => $payload['mt_category_name'] ?? 'Unknown Category',
                'fg_unit' => $payload['fg_unit'] ?? 'Unit',
                'hfg_unit' => $payload['hfg_unit'] ?? 'Unit',
                'mt_unit' => $payload['mt_unit'] ?? 'Unit',
                'brand_name' => $payload['brand_name'] ?? 'Unknown Brand',
                'artcolor_name' => $payload['artcolor_name'] ?? 'No Art Color',
                'bom_qty' => $payload['bom_qty'] ?? 0,
                'bom_cons' => $payload['bom_cons'] ?? 0, // Initial value will be overwritten below
                'wo_qty' => $payload['wo_qty'] ?? 0,
                'date_of_order' => date('Y-m-d', strtotime($payload['date_of_order'])),
                'due_date' => !empty($payload['due_date']) ? date('Y-m-d', strtotime($payload['due_date'])) : null,
                'created_by' => $this->session->userdata('email') ?? 'system',
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Log data before insert
            log_message('debug', 'Inserting data into purchasing_wo: ' . print_r($wo_data, true));

            // Insert into purchasing_wo
            $wo_id = $this->General_model->insert_data('purchasing_wo', $wo_data);

            if (!$wo_id) {
                throw new Exception('Failed to insert work order data');
            }

            // Insert size run data if exists
            if (!empty($payload['sizerun']) && is_array($payload['sizerun'])) {
                log_message('debug', 'Inserting size run data: ' . print_r($payload['sizerun'], true));
                $this->General_model->insertSizeRun($wo_id, $payload['sizerun']);
            }

            // If you have multiple materials, insert them as separate records
            if (!empty($payload['materials']) && is_array($payload['materials'])) {
                foreach ($payload['materials'] as $material) {
                    // Ensure the correct consumption calculation for each material
                    $material_cons = $material['cons'] ?? 0;
                    $bom_cons = $material_cons * $payload['total_qty']; // Consumption * Total Size

                    // Debugging: Log the values being used
                    log_message('debug', 'Material Cons: ' . $material_cons . ', Total Size (total_qty): ' . $payload['total_qty'] . ', Calculated bom_cons: ' . $bom_cons);

                    // Prepare the material data to be inserted
                    $material_wo_data = array_merge($wo_data, [
                        'hfg_kode_item' => $material['hfg_kode'] ?? $wo_data['hfg_kode_item'],
                        'mt_kode_item' => $material['kode'] ?? $wo_data['mt_kode_item'],
                        'hfg_item_name' => $material['hfg_name'] ?? $wo_data['hfg_item_name'],
                        'mt_item_name' => $material['material_name'] ?? $wo_data['mt_item_name'],
                        'mt_category_name' => $material['category_name'] ?? $wo_data['mt_category_name'],
                        'mt_unit' => $material['unit_name'] ?? $wo_data['mt_unit'],
                        'bom_qty' => $material_cons, // The base quantity
                        'bom_cons' => $bom_cons, // The correctly calculated total consumption
                    ]);

                    // Log material data before insertion
                    log_message('debug', 'Inserting material data into purchasing_wo: ' . print_r($material_wo_data, true));

                    // Insert into purchasing_wo
                    $this->General_model->insert_data('purchasing_wo', $material_wo_data);
                }
            }

            // Complete Transaction
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }

            echo json_encode(['status' => 'success', 'message' => 'WO created successfully', 'wo_id' => $wo_id]);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error in save_wo: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function delete_wo($wo_number)
    {
        // safety check if no WO number given
        if (!$wo_number) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Work Order number</div>');
            redirect('purchasing/work_order');
        }

        // start transaction to ensure both deletions succeed
        $this->db->trans_start();

        // 1. delete size run records first (child)
        $this->General_model->delete_data('purchasing_sizerun', ['wo_number' => $wo_number]);

        // 2. delete the work order (parent)
        $this->General_model->delete_data('purchasing_wo', ['wo_number' => $wo_number]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // if any query failed
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to delete Work Order.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Work Order deleted successfully.</div>');
        }

        redirect('purchasing/work_order');
    }

    public function edit_wo($wo_number)
    {
        $data['title'] = 'Edit Work Order';
        $data['user'] = $this->General_model->get_row_where('master_user', [
            'user_email' => $this->session->userdata('email')
        ]);

        // --- ambil data header Work Order
        $data['wo'] = $this->General_model->get_row_where('purchasing_wo', [
            'wo_number' => $wo_number
        ]);

        if (!$data['wo']) {
            show_404();
        }

        $data['fg_data']  = $this->General_model->get_fg_data($wo_number);
        $data['hfg_data'] = $this->General_model->get_hfg_data($wo_number);
        $data['mt_data']  = $this->General_model->get_mt_data($wo_number);

        // --- ambil id_brand dari tabel master_brand
        $brand = $this->General_model->get_row_where('master_brand', [
            'brand_name' => $data['wo']['brand_name']
        ]);

        // --- ambil semua size dari master_size untuk brand ini
        $data['sizes'] = $this->General_model->get_result_where('master_size', [
            'id_brand' => $brand['id_brand']
        ]);

        // --- ambil sizerun yang sudah ada di purchasing_wo_size
        $sizerun = $this->General_model->get_result_where('purchasing_sizerun', [
            'wo_number' => $wo_number
        ]);

        $sizerun_map = [];
        foreach ($sizerun as $sz) {
            $sizerun_map[$sz['size_name']] = $sz['size_qty'];
        }

        $data['sizerun_map'] = $sizerun_map;


        // --- ambil master data FG & HFG untuk dropdown
        $data['fg_items']  = $this->General_model->get_items_modal('Barang Jadi');
        $data['hfg_items'] = $this->General_model->get_items_modal('Barang Setengah Jadi');

        // --- load view edit
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('purchasing/edit_wo', $data);
        $this->load->view('templates/footer');
    }

    public function update($wo_number)
    {
        $user_email = $this->session->userdata('email');

        $postData   = json_decode($this->input->post('data'), true);
        $sizeRunArr = json_decode($this->input->post('sizerun'), true);

        if (!$postData || !is_array($postData) || empty($wo_number)) {
            echo json_encode(['status' => 'error', 'message' => 'No data received or WO number empty']);
            return;
        }

        $this->db->trans_start();

        // --- Hapus data lama ---
        $this->db->delete('purchasing_sizerun', ['wo_number' => $wo_number]);
        $this->db->delete('purchasing_wo', ['wo_number' => $wo_number]);

        $woIds = []; // Track inserted WO ids per row (by kode_bom)

        foreach ($postData as $row) {
            // Insert WO row
            $data = [
                'kode_bom'          => $row['kode_bom'] ?? null,
                'wo_number'         => $row['wo_number'] ?? null,
                'fg_kode_item'      => $row['fg_kode_item'] ?? null,
                'hfg_kode_item'     => $row['hfg_kode_item'] ?? null,
                'mt_kode_item'      => $row['mt_kode_item'] ?? null,
                'fg_item_name'      => $row['fg_item_name'] ?? null,
                'hfg_item_name'     => $row['hfg_item_name'] ?? null,
                'mt_item_name'      => $row['mt_item_name'] ?? null,
                'fg_category_name'  => $row['fg_category_name'] ?? null,
                'hfg_category_name' => $row['hfg_category_name'] ?? null,
                'mt_category_name'  => $row['mt_category_name'] ?? null,
                'fg_unit'           => $row['fg_unit'] ?? null,
                'hfg_unit'          => $row['hfg_unit'] ?? null,
                'mt_unit'           => $row['mt_unit'] ?? null,
                'brand_name'        => $row['brand_name'] ?? null,
                'artcolor_name'     => $row['artcolor_name'] ?? null,
                'bom_qty'           => $row['bom_qty'] ?? null,
                'bom_cons'          => $row['consumption'] ?? null,
                'wo_qty'            => $row['wo_qty'] ?? 0,
                'date_of_order'     => $row['date_of_order'] ?? null,
                'due_date'          => $row['due_date'] ?? null,
                'created_by'        => $user_email,
                'created_at'        => date('Y-m-d H:i:s'),
            ];

            $insert_id = $this->General_model->insert_data('purchasing_wo', $data);
            $woIds[$row['kode_bom']] = $insert_id;

            // Insert SizeRun
            if (!empty($sizeRunArr)) {
                foreach ($sizeRunArr as $sr) {
                    $sizeData = [
                        'id_brand'   => $this->General_model->get_brand_id_by_name($sr['brand_name']),
                        'id_wo'      => $insert_id,
                        'wo_number'  => $wo_number,
                        'brand_name' => $sr['brand_name'] ?? null,
                        'size_name'  => $sr['size_name'] ?? null,
                        'size_qty'   => $sr['size_qty'] ?? 0,
                        'created_by' => $user_email,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->General_model->insert_data('purchasing_sizerun', $sizeData);
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update WO']);
        }
    }
}
