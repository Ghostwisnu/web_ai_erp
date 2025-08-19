<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Master extends CI_Controller
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
        // Load the master data view
        $data['title'] = 'Category';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]);
        $data['categories'] = $this->General_model->get_all_data('master_category'); // Fetch all categories

        $this->form_validation->set_rules('category', 'Category', 'required|trim|is_unique[master_category.category_name]', [
            'required' => 'Category name is required.',
            'is_unique' => 'This category name already exists.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, load the view with errors
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master_data/category', $data);
            $this->load->view('templates/footer');
        } else {
            // If validation passes, insert new category
            $categoryData = [
                'category_name' => $this->input->post('category', true),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $data['user']['user_name'] // Assuming created_by is the email of the user
            ];
            $insertedId = $this->General_model->insert_data('master_category', $categoryData);

            if ($insertedId) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New category added successfully!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed to add new category.</div>');
            }
            redirect('master'); // Redirect to the master data page
        }
    }

    public function delete($id)
    {
        // Delete category by ID
        $this->General_model->delete_data('master_category', ['id_category' => $id]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Category deleted successfully!</div>');
        redirect('master'); // Redirect to the master data page
    }

    public function item()
    {
        // Load the user dashboard view
        $data['title'] = 'Item';
        $data['user'] = $this->General_model->get_row_where(
            'master_user',
            ['user_email' => $this->session->userdata('email')]
        );
        $data['item']       = $this->General_model->get_items_with_details();
        $data['categories'] = $this->General_model->get_all_data('master_category');
        $data['units']      = $this->General_model->get_all_data('master_unit');
        $data['brands']     = $this->General_model->get_all_data('master_brand');

        // Validation
        $this->form_validation->set_rules(
            'item_name',
            'Item Name',
            'required|trim|is_unique[master_item.item_name]',
            [
                'required'  => 'Item name is required.',
                'is_unique' => 'This item name already exists.'
            ]
        );

        if ($this->form_validation->run() == FALSE) {
            // Validation failed → reload view
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master_data/item', $data);
            $this->load->view('templates/footer');
        } else {
            // ✅ Handle input
            $item_name   = $this->input->post('item_name');
            $category_id = $this->input->post('category_id');
            $unit_id     = $this->input->post('unit_id');
            $brand_id    = $this->input->post('brand_id');

            // If no brand is selected, set null
            $brand_id = !empty($brand_id) ? $brand_id : null;

            // ✅ Generate kode_item (brand-aware)
            $kode_item = $this->General_model->generate_kode_item($category_id, $brand_id);

            $newItem = [
                'item_name'   => $item_name,
                'category_id' => $category_id,
                'unit_id'     => $unit_id,
                'brand_id'    => $brand_id, // can be null
                'kode_item'   => $kode_item,
                'created_at'  => date('Y-m-d H:i:s'),
                'created_by'  => $data['user']['user_name']
            ];

            $insertedId = $this->General_model->insert_data('master_item', $newItem);

            if ($insertedId) {
                $this->session->set_flashdata(
                    'message',
                    '<div class="alert alert-success" role="alert">New item added successfully!</div>'
                );
            } else {
                $this->session->set_flashdata(
                    'message',
                    '<div class="alert alert-danger" role="alert">Failed to add new item.</div>'
                );
            }

            redirect('master/item');
        }
    }


    public function delete_item($id_item)
    {
        // Delete item by ID
        $this->General_model->delete_data('master_item', ['id_item' => $id_item]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Item deleted successfully!</div>');
        redirect('master/item'); // Redirect to the item page
    }

    public function upload_item()
    {
        $data['title'] = 'Upload Item';
        $data['user'] = $this->General_model->get_row_where('master_user', [
            'user_email' => $this->session->userdata('email')
        ]);
        $data['categories'] = $this->General_model->get_all_data('master_category');
        $data['units']      = $this->General_model->get_all_data('master_unit');
        $data['brands']     = $this->General_model->get_all_data('master_brand');
        $data['preview_data'] = [];

        // If "Preview" button clicked
        if ($this->input->post('preview')) {
            if (!empty($_FILES['file']['tmp_name'])) {
                // Load PhpSpreadsheet
                require_once FCPATH . 'vendor/autoload.php';
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['file']['tmp_name']);
                $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                $preview_data = [];
                $row_number = 1;

                foreach ($sheet as $row) {
                    // Skip header row
                    if ($row_number == 1) {
                        $row_number++;
                        continue;
                    }

                    $preview_data[] = [
                        'item_name'   => trim($row['A']),
                        'category_id' => trim($row['B']),
                        'unit_id'     => trim($row['C']),
                        'brand_id'    => isset($row['D']) ? trim($row['D']) : null
                    ];
                    $row_number++;
                }

                $data['preview_data'] = $preview_data;
            }
        }

        // If "Save" button clicked
        if ($this->input->post('save')) {
            $items = $this->input->post('items'); // items from hidden input in preview form

            if (!empty($items)) {
                foreach ($items as $item) {
                    $kode_item = $this->General_model->generate_kode_item(
                        $item['category_id'],
                        $item['brand_id']
                    );

                    $insert_data = [
                        'item_name'   => $item['item_name'],
                        'category_id' => $item['category_id'],
                        'unit_id'     => $item['unit_id'],
                        'brand_id'    => $item['brand_id'],
                        'kode_item'   => $kode_item,
                        'created_at'  => date('Y-m-d H:i:s'),
                        'created_by'  => $this->session->userdata('username')
                    ];

                    $this->General_model->insert_data('master_item', $insert_data);
                }

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Items uploaded successfully!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">No items to save.</div>');
            }

            redirect('master/item');
        }

        // Load view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('master_data/upload_item', $data);
        $this->load->view('templates/footer');
    }

    public function confirm_upload_item()
    {
        $item_names    = $this->input->post('item_name');
        $category_ids  = $this->input->post('category_id');
        $unit_ids      = $this->input->post('unit_id');
        $brand_ids     = $this->input->post('brand_id'); // ✅ make sure your upload form includes this

        $user = $this->General_model->get_row_where(
            'master_user',
            ['user_email' => $this->session->userdata('email')]
        );
        $created_by = $user ? $user['user_name'] : 'Unknown';

        $data = [];
        $count = count($item_names);

        for ($i = 0; $i < $count; $i++) {
            if (empty($item_names[$i]) || empty($category_ids[$i]) || empty($unit_ids[$i])) {
                continue;
            }

            // Key by category + brand to track counters separately
            $prefixKey = $category_ids[$i] . '-' . ($brand_ids[$i] ?: '0');

            if (!isset($counters[$prefixKey])) {
                $counters[$prefixKey] = $this->General_model->generate_kode_item($category_ids[$i], $brand_ids[$i]);
            }

            $nextNumber = ++$counters[$prefixKey];

            $kode_item = $this->General_model->format_kode_item($category_ids[$i], $brand_ids[$i], $nextNumber);

            $data[] = [
                'item_name'   => trim($item_names[$i]),
                'category_id' => $category_ids[$i],
                'unit_id'     => $unit_ids[$i],
                'brand_id'    => $brand_ids[$i],
                'kode_item'   => $kode_item,
                'created_at'  => date('Y-m-d H:i:s'),
                'created_by'  => $created_by
            ];
        }

        if (!empty($data)) {
            $this->db->insert_batch('master_item', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Items imported successfully!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">No valid data to import.</div>');
        }

        redirect('master/item');
    }

    public function download_item_template()
    {
        require_once FCPATH . 'vendor/autoload.php';
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header row
        $sheet->setCellValue('A1', 'Item Name');
        $sheet->setCellValue('B1', 'Category ID');
        $sheet->setCellValue('C1', 'Unit ID');
        $sheet->setCellValue('D1', 'Brand ID');

        // Example data row
        $sheet->setCellValue('A2', 'Example Item');
        $sheet->setCellValue('B2', '1'); // category_id
        $sheet->setCellValue('C2', '2'); // unit_id
        $sheet->setCellValue('D2', '3'); // brand_id

        // Autosize columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Output file to browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="item_upload_template.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function unit()
    {
        // Load the unit management view
        $data['title'] = 'Unit';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]);
        $data['units'] = $this->General_model->get_all_data('master_unit'); // Fetch all units

        $this->form_validation->set_rules('unit_name', 'Unit Name', 'required|trim|is_unique[master_unit.unit_name]', [
            'required' => 'Unit name is required.',
            'is_unique' => 'This unit name already exists.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, load the view with errors
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master_data/unit', $data);
            $this->load->view('templates/footer');
        } else {
            // If validation passes, insert new unit
            $unitData = [
                'unit_name' => $this->input->post('unit_name', true),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $data['user']['user_name'] // Assuming created_by is the email of the user
            ];
            $insertedId = $this->General_model->insert_data('master_unit', $unitData);

            if ($insertedId) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New unit added successfully!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed to add new unit.</div>');
            }
            redirect('master/unit'); // Redirect to the unit management page
        }
    }
    public function delete_unit($id_unit)
    {
        // Delete unit by ID
        $this->General_model->delete_data('master_unit', ['id_unit' => $id_unit]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Unit deleted successfully!</div>');
        redirect('master/unit'); // Redirect to the unit management page
    }

    public function brand()
    {
        // Load the brand management view
        $data['title'] = 'Brand';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]);
        $data['brands'] = $this->General_model->get_all_data('master_brand'); // Fetch all brands

        $this->form_validation->set_rules('brand_name', 'Brand Name', 'required|trim|is_unique[master_brand.brand_name]', [
            'required' => 'Brand name is required.',
            'is_unique' => 'This brand name already exists.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, load the view with errors
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master_data/brand', $data);
            $this->load->view('templates/footer');
        } else {
            // If validation passes, insert new brand
            $brandData = [
                'brand_name' => $this->input->post('brand_name', true),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $data['user']['user_name'] // Assuming created_by is the email of the user
            ];
            $insertedId = $this->General_model->insert_data('master_brand', $brandData);

            if ($insertedId) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New brand added successfully!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed to add new brand.</div>');
            }
            redirect('master/brand'); // Redirect to the brand management page
        }
    }

    public function delete_brand($id_brand)
    {
        // Delete brand by ID
        $this->General_model->delete_data('master_brand', ['id_brand' => $id_brand]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Brand deleted successfully!</div>');
        redirect('master/brand'); // Redirect to the brand management page
    }

    public function size($id_brand)
    {
        // Get brand
        $brand = $this->General_model->get_row_where('master_brand', ['id_brand' => $id_brand]);
        if (!$brand) {
            show_404();
        }

        $data['title'] = 'Manage Sizes';
        $data['brand'] = $brand;
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]);
        $data['sizes'] = $this->General_model->get_where('master_size', ['id_brand' => $id_brand]);

        $this->form_validation->set_rules('size_name', 'Size Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master_data/size', $data);
            $this->load->view('templates/footer');
        } else {
            $sizeData = [
                'id_brand'   => $id_brand,
                'size_name'  => $this->input->post('size_name', true),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $data['user']['user_name'] // Assuming created_by is the email of the user
            ];
            // Insert new size
            $this->General_model->insert_data('master_size', $sizeData);
            // Set success message
            $this->session->set_flashdata('message', '<div class="alert alert-success">New size added successfully!</div>');
            // Redirect to the sizes page for the brand
            redirect('master/size/' . $id_brand);
        }
    }
}
