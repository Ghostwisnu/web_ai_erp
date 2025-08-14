<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Ensure the form_validation library is loaded
        // $this->load->library('form_validation');
        // $this->load->model('General_model');

    }

    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('user'); // Redirect to login if not logged in
        }
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('passowrd', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            // Load the login view if validation fails
            $data['title'] = 'Login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('passowrd');

        // Check if the user exists
        $user = $this->General_model->get_row_where('master_user', ['user_email' => $email]);

        // If user exists, verify password
        if ($user) {
            if ($user['user_is_active'] == 1) {
                if (password_verify($password, $user['user_password'])) {
                    // Set session data
                    $data = [
                        'email' => $user['user_email'],
                        'user_role_id' => $user['user_role_id'],
                        'is_logged_in' => true
                    ];
                    $this->session->set_userdata($data);
                    if ($user['user_role_id'] == 1) {
                        redirect('admin'); // Redirect to admin dashboard
                    } else {
                        redirect('user'); // Redirect to user dashboard
                    } // Redirect to the dashboard or home page
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This account is not active!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email not registered!</div>');
            redirect('auth');
        }
    }

    // private function _sendEmail($token, $type)
    // {
    //     // Gmail SMTP with App Password
    //     $config = [
    //         'protocol'  => 'smtp',
    //         'smtp_host' => 'ssl://smtp.googlemail.com',
    //         'smtp_user' => 'sendertoken0@gmail.com', // your Gmail address
    //         'smtp_pass' => 'cmzg wiea fndu boug', // your 16-char Gmail App Password
    //         'smtp_port' => 465,
    //         'mailtype'  => 'html',
    //         'charset'   => 'utf-8',
    //         'newline'   => "\r\n",
    //         'wordwrap'  => TRUE
    //     ];

    //     $this->load->library('email', $config);
    //     $this->email->set_newline("\r\n");
    //     $this->email->from('sendertoken0@gmail.com', 'Web AI ERP');
    //     $this->email->to($this->input->post('email'));

    //     if ($type == 'verify') {
    //         $this->email->subject('Account Verification');
    //         $this->email->message('Click this link to verify your account: <a href="'
    //             . base_url('auth/verify?email=' . urlencode($this->input->post('email')) . '&token=' . urlencode($token))
    //             . '">Activate</a>');
    //     } elseif ($type == 'forgot') {
    //         $this->email->subject('Password Reset');
    //         $this->email->message('Click this link to reset your password: <a href="'
    //             . base_url('auth/resetpassword?email=' . urlencode($this->input->post('email')) . '&token=' . urlencode($token))
    //             . '">Reset Password</a>');
    //     }

    //     if ($this->email->send()) {
    //         return true;
    //     } else {
    //         // Show detailed error if it fails
    //         echo $this->email->print_debugger(['headers']);
    //         return false;
    //     }
    // }



    public function register()
    {
        if ($this->session->userdata('email')) {
            redirect('user'); // Redirect to login if not logged in
        }
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[master_user.user_email]', [
            'is_unique' => 'This email has already been registered!'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'Password does not match!',
            'min_length' => 'Password too short!'
        ]);
        $this->form_validation->set_rules('password2', 'Repeat Password', 'required|trim|matches[password1]', [
            'matches' => 'Password does not match!'
        ]);
        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Register';
            // Validation failed, load the registration view with errors
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/register');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email', true);
            // Validation passed, proceed with registration
            $data = [
                'user_name' => htmlspecialchars($this->input->post('name', true)),
                'user_email' => htmlspecialchars($email),
                'user_image' => 'default.jpg',
                'user_password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'user_role_id' => 2,
                'user_is_active' => 0,
                'user_created_at' => date('Y-m-d H:i:s'),
            ];

            // Insert user data into the database
            // $token = base64_encode(random_bytes(32));
            // $user_token = [
            //     'user_email' => $email,
            //     'user_token' => $token,
            //     'user_token_created_at' => date('Y-m-d H:i:s')
            // ];
            $this->General_model->insert_data('master_user', $data);
            // $this->General_model->insert_data('user_token', $user_token);
            // $this->_sendEmail($token, 'verify');
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Registration successful! Please login.</div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        // Destroy the session to log out the user
        $this->session->sess_destroy();
        redirect('auth'); // Redirect to login page after logout
    }
}
