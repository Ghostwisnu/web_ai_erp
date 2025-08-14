<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load necessary libraries and models
        $this->load->library('session');
        $this->load->model('General_model');

        // Check if user is logged in
        is_logged_in();
    }

    public function index()
    {
        // Load the user dashboard view
        $data['title'] = 'Dashboard';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]); // Get logged-in user's email

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates/footer');
    }

    public function role()
    {
        // Load the user dashboard view
        $data['title'] = 'Role Management';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]);
        $data['role'] = $this->General_model->get_all_data('master_user_role'); // Get logged-in user's email

        $this->form_validation->set_rules('role_name', 'Role Name', 'required|trim|is_unique[master_user_role.role_name]', [
            'required' => 'Role name is required.',
            'is_unique' => 'This role name already exists.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/role', $data);
            $this->load->view('templates/footer');
        } else {
            // If form validation passes, insert new menu
            $menuData = [
                'role_name' => $this->input->post('role_name', true)
            ];
            $insertedId = $this->General_model->insert_data('master_user_role', $menuData);

            if ($insertedId) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New menu added successfully!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed to add new menu.</div>');
            }
            redirect('admin/role');
        }
    }

    public function delete($id_role)
    {
        // Delete role by ID
        $this->General_model->delete_data('master_user_role', ['id_role' => $id_role]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Role deleted successfully!</div>');
        redirect('admin/role');
    }

    public function update()
    {
        // Update role
        $id_role = $this->input->post('id_role');
        $role_name = $this->input->post('role_name', true);

        $this->form_validation->set_rules('role_name', 'Role Name', 'required|trim', [
            'required' => 'Role name is required.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed to update role. Please check your input.</div>');
            redirect('admin/role');
        } else {
            $updateData = [
                'role_name' => $role_name
            ];
            $this->General_model->update_data(
                'master_user_role',
                ['id_role' => $id_role],
                $updateData
            );

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Role updated successfully!</div>');
            redirect('admin/role');
        }
    }

    public function roleaccess($id_role)
    {
        // Load role access management view
        $data['title'] = 'Role Access Management';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]);
        $data['role'] = $this->General_model->get_row_where('master_user_role', ['id_role' => $id_role]);
        $this->db->where('id_menu !=', 1);
        $data['menu'] = $this->General_model->get_all_data('master_menu');


        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role_access', $data);
        $this->load->view('templates/footer');
    }

    public function changeAccess()
    {
        // Change access for a role
        $roleId = $this->input->post('roleId');
        $menuId = $this->input->post('menuId');
        $isChecked = $this->input->post('isChecked');

        if ($isChecked) {
            // Grant access
            $data = [
                'id_role' => $roleId,
                'id_menu' => $menuId
            ];
            $this->General_model->insert_data('master_user_access_menu', $data);
        } else {
            // Revoke access
            $this->General_model->delete_data('master_user_access_menu', ['id_role' => $roleId, 'id_menu' => $menuId]);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Access updated successfully!</div>');
    }

    public function user_management()
    {
        $data['title'] = 'User Management';

        // Current logged-in user (for header/sidebar data)
        $data['user'] = $this->General_model->get_row_where(
            'master_user',
            ['user_email' => $this->session->userdata('email')]
        );

        // All users for listing
        $data['users'] = $this->General_model->get_all_data('master_user');

        // All roles for dropdown in modal
        $data['roles'] = $this->General_model->get_all_data('master_user_role');

        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/user_management', $data);
        $this->load->view('templates/footer');
    }

    public function update_user()
    {
        // Update user role and status
        $id_user = $this->input->post('id_user');
        $user_role_id = $this->input->post('user_role_id');
        $user_is_active = $this->input->post('user_is_active');

        $data = [
            'user_role_id' => $user_role_id,
            'user_is_active' => $user_is_active
        ];

        $this->General_model->update_data('master_user', ['id_user' => $id_user], $data);

        // Set success message and redirect
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User updated successfully!</div>');
        redirect('admin/user_management');
    }
}
