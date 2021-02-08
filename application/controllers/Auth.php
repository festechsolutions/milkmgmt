<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Admin_Controller 
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_auth');
	}

	/* 
		Check if the login form is submitted, and validates the user credential
		If not submitted it redirects to the login page
	*/
	public function login()
	{

		$this->logged_in();

		$this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == TRUE) {
            // true case
           	$username_exists = $this->model_auth->check_username($this->input->post('username'));

           	if($username_exists == TRUE) {
           		$login = $this->model_auth->login($this->input->post('username'), $this->input->post('password'));

           		if($login) {

           			$logged_in_sess = array(
           				'id' => $login['id'],
				        'username'  => $login['username'],
				        'logged_in' => TRUE
					);

					$this->session->set_userdata($logged_in_sess);
           			redirect('dashboard', 'refresh');
           		}
           		else {
           			$this->data['errors'] = "<div class='alert alert-danger alert-dismissible' role='alert'>
					   <button type='button' class='close' data-dismiss='alert' aria-label='close'><span aria-hidden='true'>&times;</span></button>
					   <strong>Invalid Username or Password</strong></div>";
           			$this->load->view('login', $this->data);
           		}
           	}
           	else {
           		$this->data['errors'] = "<div class='alert alert-danger alert-dismissible' role='alert'>
				   <button type='button' class='close' data-dismiss='alert' aria-label='close'><span aria-hidden='true'>&times;</span></button>
					<strong>Username does not exist</strong></div>";

           		$this->load->view('login', $this->data);
           	}	
        }
        else {
            // false case
            $this->load->view('login');
        }	
	}

	/*
		clears the session and redirects to login page
	*/
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('', 'refresh');
	}

	public function reset_password()
	{
		$email = 'mattapalliswakhil@gmail.com';
		$this->send_reset_password_email($email);
		$this->load->view('login/view_reset_password_sent',array('email' => $email));
	}

	private function send_reset_password_email($email)
	{
		$this->load->library('email');
		$email_code = md5($this->config->item('salt').'swakhil');

		$this->email->set_mailtype('html');
		$this->email->from($this->config->item('bot_email'),'FES');
		$this->email->to($email);
		$this->email->subject('Please reset your password');

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html>
		<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		</head><body>';
		$message .= '<p> Dear Swakhil </p>';
		$message .= '<p>We want to help you reset your password! Please <strong><a href="' . base_url() .'login/reset_password_form/' . $email . '/'.
		$email_code . '">click here</a></strong> to reset your password</p>';
		$message .= '<p>Thank You!</p>';
		$message .= '</body></html>';

		$email->email->message($message);
		$email->email->send();
	}
}
