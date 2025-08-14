<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
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
        $data['title'] = 'My Profile';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]); // Get logged-in user's email

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/profile', $data);
        $this->load->view('templates/footer');
    }

    public function edit()
    {
        // Load the edit profile view
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]);

        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer');
        } else {
            // Process the form submission
            $name = $this->input->post('name');
            $email = $this->session->userdata('email');

            // Handle file upload if an image is selected
            $upload_image = $_FILES['image']['name'];
            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048'; // 2MB
                $config['upload_path'] = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['user']['user_image'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }
                    $new_image = $this->upload->data('file_name');
                    $this->General_model->update_data('master_user', ['user_email' => $email], ['user_name' => $name, 'user_image' => $new_image]);
                } else {
                    // Handle upload error
                    echo $this->upload->display_errors();
                }
            } else {
                // Update without changing the image
                $this->General_model->update_data('master_user', ['user_email' => $email], ['user_name' => $name]);
            }

            // Set success message and redirect
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Profile updated successfully!</div>');
            redirect('user');
        }
    }

    public function changepassword()
    {
        // Load the user dashboard view
        $data['title'] = 'Change Password';
        $data['user'] = $this->General_model->get_row_where('master_user', ['user_email' => $this->session->userdata('email')]); // Get logged-in user's email

        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[3]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Repeat Password', 'required|trim|matches[new_password1]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/change_password', $data);
            $this->load->view('templates/footer');
        } else {
            // Process the password change
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');

            // Verify current password
            if (!password_verify($current_password, $data['user']['user_password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Current password is incorrect!</div>');
                redirect('user/changepassword');
            } else {
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">New password cannot be the same as current password!</div>');
                    redirect('user/changepassword');
                } else {
                    // Update the password
                    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $this->General_model->update_data('master_user', ['user_email' => $this->session->userdata('email')], ['user_password' => $hashed_new_password]);

                    // Set success message and redirect
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password changed successfully!</div>');
                    redirect('user');
                }
            }
        }
    }
}
