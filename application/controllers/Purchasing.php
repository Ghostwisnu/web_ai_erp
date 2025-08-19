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

        // Load all items for selection (FG and Materials)
        $data['item'] = $this->General_model->get_all_data('master_item');
        $data['material_items'] = $this->General_model->get_material_items();

        // Get Finish Goods
        $data['finish_goods'] = $this->General_model->get_finish_and_half_finish_goods('Barang Jadi');

        // Get Half Finish Goods
        $data['half_finish_goods'] = $this->General_model->get_finish_and_half_finish_goods('Barang Setengah Jadi');

        // Load brands
        $data['master_brand'] = $this->General_model->get_all_data('master_brand');

        // Load BOM data
        $data['bom_all'] = $this->General_model->get_materials_by_kode($kode_bom);

        if (!empty($data['bom_all'])) {
            $data['bom_header'] = $data['bom_all'][0]; // FG header info
            $data['bom_materials'] = $data['bom_all']; // materials (adjust if FG row is duplicated)
        } else {
            $data['bom_header'] = [];
            $data['bom_materials'] = [];
        }

        $data['kode_bom'] = $kode_bom; // pass kode_bom

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('purchasing/edit_bom', $data);
        $this->load->view('templates/footer');
    }



    public function save_bom()
    {
        $data = json_decode($this->input->raw_input_stream, true);

        // FG info
        $fg_item_id   = $data['fg_item_id'];
        $fg_item_name = $data['fg_item_name'];
        $fg_unit      = $data['fg_unit_name'];
        $fg_category  = $data['fg_category_name'];
        $brand_name   = $data['brand_name'];
        $art_color    = $data['art_color'];
        $materials    = $data['materials'];

        if (!$fg_item_id || empty($materials)) {
            echo json_encode(['status' => 'error', 'message' => 'FG and materials are required.']);
            return;
        }

        $fg = $this->General_model->get_row_where('master_item', ['id_item' => $fg_item_id]);
        $fg_kode_item = $fg ? $fg['kode_item'] : null;

        // Use model to generate kode_bom
        $kode_bom = $this->General_model->generate_kode_bom();

        // Insert each material
        foreach ($materials as $mat) {
            $material_name = isset($mat['material_name']) ? $mat['material_name'] : '';
            $material_unit = isset($mat['unit_name']) ? $mat['unit_name'] : '';
            $material_category = isset($mat['category_name']) ? $mat['category_name'] : '';

            $mt = $this->General_model->get_row_where('master_item', ['id_item' => $mat['material_id']]);
            $mt_kode_item = $mt ? $mt['kode_item'] : null;

            $insert = [
                'kode_bom'         => $kode_bom,
                'id_fg_item'       => $fg_item_id,
                'fg_kode_item'     => $fg_kode_item,   // ✅ new field
                'id_mt_item'       => $mat['material_id'],
                'mt_kode_item'     => $mt_kode_item,   // ✅ new field
                'fg_item_name'     => $fg_item_name,
                'brand_name'       => $brand_name,
                'artcolor_name'    => $art_color,
                'mt_item_name'     => $material_name,
                'fg_item_category' => $fg_category,
                'mt_item_category' => $material_category,
                'fg_unit'          => $fg_unit,
                'mt_unit'          => $material_unit,
                'bom_qty'          => $mat['qty'],
                'created_by'       => $this->session->userdata('email'),
                'created_at'       => date('Y-m-d H:i:s'),
            ];

            $this->General_model->insert_data('purchasing_bom', $insert);
        }

        echo json_encode(['status' => 'success', 'kode_bom' => $kode_bom]);
    }

    public function save_edit_bom()
    {
        // Get raw JSON input
        $raw  = $this->input->raw_input_stream;
        $data = json_decode($raw, true);

        // If no JSON, fallback to POST (form data)
        if (!$data) {
            $data = $this->input->post();
        }

        // Extract values safely
        $fg_item_id   = isset($data['fg_item_id']) ? $data['fg_item_id'] : null;
        $fg_item_name = isset($data['fg_item_name']) ? $data['fg_item_name'] : null;
        $fg_unit      = isset($data['fg_unit_name']) ? $data['fg_unit_name'] : null;
        $fg_category  = isset($data['fg_category_name']) ? $data['fg_category_name'] : null;
        $brand_name   = isset($data['brand_name']) ? $data['brand_name'] : null;
        $art_color    = isset($data['art_color']) ? $data['art_color'] : null;
        $materials    = isset($data['materials']) ? $data['materials'] : [];
        $kode_bom     = isset($data['kode_bom']) ? $data['kode_bom'] : null;

        // Validate required fields
        if (!$fg_item_id || !$kode_bom || !isset($materials) || count((array)$materials) === 0) {
            echo json_encode(['status' => 'error', 'message' => 'FG, Kode BOM, and materials are required.']);
            return;
        }

        $fg = $this->General_model->get_row_where('master_item', ['id_item' => $fg_item_id]);
        $fg_kode_item = $fg ? $fg['kode_item'] : null;

        // Delete old BOM rows
        $this->General_model->delete_data('purchasing_bom', ['kode_bom' => $kode_bom]);

        // Insert new materials
        foreach ($materials as $mat) {
            $material_id       = isset($mat['material_id']) ? $mat['material_id'] : null;
            $material_name     = isset($mat['material_name']) ? $mat['material_name'] : '';
            $material_unit     = isset($mat['unit_name']) ? $mat['unit_name'] : '';
            $material_category = isset($mat['category_name']) ? $mat['category_name'] : '';
            $material_qty      = isset($mat['qty']) ? $mat['qty'] : 0;

            $mt = $this->General_model->get_row_where('master_item', ['id_item' => $mat['material_id']]);
            $mt_kode_item = $mt ? $mt['kode_item'] : null;

            // Skip invalid rows
            if (!$material_id) continue;

            $insert = [
                'kode_bom'         => $kode_bom,
                'id_fg_item'       => $fg_item_id,
                'fg_kode_item'     => $fg_kode_item,   // ✅ new field
                'id_mt_item'       => $mat['material_id'],
                'mt_kode_item'     => $mt_kode_item,   // ✅ new field
                'fg_item_name'     => $fg_item_name,
                'brand_name'       => $brand_name,
                'artcolor_name'    => $art_color,
                'mt_item_name'     => $material_name,
                'fg_item_category' => $fg_category,
                'mt_item_category' => $material_category,
                'fg_unit'          => $fg_unit,
                'mt_unit'          => $material_unit,
                'bom_qty'          => $material_qty,
                'created_by'       => $this->session->userdata('email'),
                'created_at'       => date('Y-m-d H:i:s'),
            ];

            $this->General_model->insert_data('purchasing_bom', $insert);
        }

        echo json_encode(['status' => 'success', 'kode_bom' => $kode_bom]);
    }



    public function work_order()
    {
        $data['title'] = 'Work Order';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]);
        $data['bom'] = $this->General_model->get_all_data('purchasing_bom');



        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('purchasing/work_order', $data);
        $this->load->view('templates/footer');
    }
}
