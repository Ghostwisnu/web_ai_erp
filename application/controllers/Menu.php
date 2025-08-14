<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
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
        // Load the user dashboard view
        $data['title'] = 'Menu Management';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]);
        $data['menu'] = $this->General_model->get_all_data('master_menu'); // Get logged-in user's email

        $this->form_validation->set_rules('menu', 'Menu Name', 'required|trim|is_unique[master_menu.menu_name]', [
            'required' => 'Menu name is required.',
            'is_unique' => 'This menu name already exists.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master_data/menu', $data);
            $this->load->view('templates/footer');
        } else {
            // If form validation passes, insert new menu
            $menuData = [
                'menu_name' => $this->input->post('menu', true)
            ];
            $insertedId = $this->General_model->insert_data('master_menu', $menuData);

            if ($insertedId) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New menu added successfully!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed to add new menu.</div>');
            }
            redirect('menu');
        }
    }

    public function update()
    {
        $id_menu = $this->input->post('id_menu');
        $menu_name = $this->input->post('menu_name');

        $update = $this->General_model->update_data('master_menu', ['id_menu' => $id_menu], ['menu_name' => $menu_name]);

        if ($update) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu updated successfully!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed to update menu.</div>');
        }
        redirect('menu');
    }


    public function delete($id_menu)
    {
        // Delete menu by ID
        $this->load->model('General_model');

        if ($this->General_model->delete_data('master_menu', ['id_menu' => $id_menu])) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu deleted successfully!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed to delete menu.</div>');
        }

        redirect('menu');
    }

    public function submenu()
    {
        $data['title'] = 'Sub Menu Management';
        $data['user'] = $this->General_model->get_row_where(
            'master_user',
            ['user_email' => $this->session->userdata('email')]
        );
        $data['submenu'] = $this->General_model->getSubMenu();
        $data['menu'] = $this->General_model->get_all_data('master_menu');

        // Form validation rules
        $this->form_validation->set_rules('submenu_name', 'menu name', 'required|trim');
        $this->form_validation->set_rules('id_menu', 'Menu', 'required');
        $this->form_validation->set_rules('menu_url', 'URL', 'required|trim');
        $this->form_validation->set_rules('menu_icon', 'Icon', 'required|trim');

        if ($this->form_validation->run() == false) {
            // Load add submenu view
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master_data/submenu', $data);
            $this->load->view('templates/footer');
        } else {
            // Insert new submenu into DB
            $dataInsert = [
                'submenu_name'     => $this->input->post('submenu_name'),
                'id_menu'   => $this->input->post('id_menu'),
                'menu_url'       => $this->input->post('menu_url'),
                'menu_icon'      => $this->input->post('menu_icon'),
                'menu_is_active' => $this->input->post('menu_is_active')
            ];

            $this->General_model->insert_data('master_sub_menu', $dataInsert);

            $this->session->set_flashdata('message', '<div class="alert alert-success">New submenu added!</div>');
            redirect('menu/submenu');
        }
    }
    public function editsubmenu()
    {
        $id_submenu = $this->input->post('id_submenu');
        $submenu_name = $this->input->post('submenu_name');
        $id_menu = $this->input->post('id_menu');
        $menu_url = $this->input->post('menu_url');
        $menu_icon = $this->input->post('menu_icon');
        $menu_is_active = $this->input->post('menu_is_active') ? 1 : 0;

        // Update submenu data
        $updateData = [
            'submenu_name' => $submenu_name,
            'id_menu' => $id_menu,
            'menu_url' => $menu_url,
            'menu_icon' => $menu_icon,
            'menu_is_active' => $menu_is_active
        ];

        if ($this->General_model->update_data('master_sub_menu', ['id_submenu' => $id_submenu], $updateData)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Sub menu updated successfully!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to update sub menu.</div>');
        }
        redirect('menu/submenu');
    }
}
