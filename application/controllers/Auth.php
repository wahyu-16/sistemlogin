<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'WAS Login Page';
            $this->load->view("templates/auth_header", $data);
            $this->load->view("auth/login");
            $this->load->view("templates/auth_footer");
        } else {
            $this->_login();
        }
    }
    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        //jika user ada
        if ($user) {
            //jika user aktive
            if ($user['is_active'] == 1) {
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id'],
                    ];
                    $this->session->set_userdata($data);
                    if ($user['role_id'] == 1) {
                        redirect('Admin');
                    } else {
                        redirect('user');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    password is wrong !</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                email has been is not active!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            email not registered!</div>');
            redirect('auth');
        }
    }
    public function registration()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'this email has already registered!'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[5]|matches[password2]', [
            'matches' => 'Password dont match!',
            'min_length' => 'Password to short!'
        ]);
        $this->form_validation->set_rules('password2', 'Password2', 'required|trim|matches[password]');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'WAS registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/register');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email', true);
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()
            ];

            //siapkan token
            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            ];


            $this->db->insert('user', $data);
            $this->db->insert('user_token', $user_token);

            $this->_sendEmail($token, 'verify');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            registration is succes! please activate your account</div>');
            redirect('auth');
        }
    }

    private function _sendEmail($token, $type)
    {

        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'waslogin1@gmail.com',
            'smtp_pass' => 'Waslogin1..',
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->email->initialize($config);

        $this->email->from('waslogin1@gmail.com', 'WAS');
        $this->email->to($this->input->post('email'));
        if ($type == 'verify') {
            $this->email->subject('Account Verification');
            $this->email->message('Click this link to verify your account : 
            <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>');
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Click this link to Reset your password : 
            <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }
    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                if (time() - $user_token['date_created'] < 86400) {
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('user');
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    ' . $email . ' has been activated! Please login</div>');
                    redirect('auth');
                } else {
                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Account Activation Failed ! Token Expired</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Account Activation Failed ! Token Invalid</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Account Activation Failed ! Wrong Email</div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            you have been logout!</div>');
        redirect('auth');
    }

    public function block()
    {
        $data['title'] = 'Access Blocked';
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('auth/blocked');
        $this->load->view('templates/footer');
    }
    public function forgotpassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Forgot Password';
            $this->load->view("templates/auth_header", $data);
            $this->load->view("auth/forgot_password");
            $this->load->view("templates/auth_footer");
        } else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();
            if ($user) {
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];
                $this->db->insert('user_token', $user_token);
                $this->_sendEmail($token, 'forgot');
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Please check your email to reset your password!</div>');
                redirect('auth');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Email is not registered or activated!</div>');
                redirect('auth/forgotpassword');
            }
        }
    }
    public function resetpassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                if (time() - $user_token['date_created'] < 86400) {
                    $this->session->set_userdata('reset_email', $email);
                    $this->changepassword();
                } else {
                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Reset Password Failed ! Token Expired</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Reset Password Failed ! Token Invalid</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Reset Password Failed ! Wrong Email</div>');
            redirect('auth');
        }
    }
    public function changepassword()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('auth');
        }
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[5]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Repeat Password', 'required|trim|min_length[5]|matches[password]');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Forgot Password';
            $this->load->view("templates/auth_header", $data);
            $this->load->view("auth/change_password");
            $this->load->view("templates/auth_footer");
        } else {
            $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');
            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->unset_userdata('reset_email');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Password has been changed! Pleas login</div>');
            redirect('auth');
        }
    }
}
