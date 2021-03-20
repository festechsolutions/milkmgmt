<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Payments';

		$this->load->model('model_payments');
		$this->load->model('model_products');
		$this->load->model('model_category');
        $this->load->model('model_users');
		$this->load->model('model_stores');
        $this->load->model('model_subscribe');
	}

	public function index()
	{
        if(!in_array('viewPayments', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->render_template('payments/index', $this->data);	
	}

	public function create()
	{
		if(!in_array('createPayments', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('store_name', 'Store/Colony name', 'trim|required');
        $this->form_validation->set_rules('user_name', 'User name', 'trim|required');
		$this->form_validation->set_rules('month', 'Month', 'trim|required');
        $this->form_validation->set_rules('year', 'Year', 'trim|required');
	
        if ($this->form_validation->run() == TRUE) {
            // true case

        	$order_id = $this->model_payments->create();
        	if($order_id) {
        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('subscribe/', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('subscribe/new', 'refresh');
        	}
        }
        else {
            // false case

        	$this->data['category'] = $this->model_category->getActiveCategory();
            $this->data['stores'] = $this->model_stores->getActiveStore();
			
            $this->render_template('payments/create', $this->data);
        }	
	}

	public function fetch()
	{
		if(!in_array('viewPayments', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
		
        $store_id = $this->input->post('store_name');
        $user_id = $this->input->post('user_id');
        $month = $this->input->post('month');
        $year = $this->input->post('year');

    	$order_data = $this->model_orders->getUserDeliveriesData($store_id,$user_id,$month,$year);
		$this->data['report_years'] = $this->model_reports->getOrderYear();

		$final_order_data = array();
		foreach ($order_data as $k => $v) {
			
			if(count($v) > 1) {
				$total_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if($v2) {
						$total_amount_earned[] = $v2['net_amount'];						
					}
				}
				$final_order_data[$k] = array_sum($total_amount_earned);	
			}
			else {
				$final_order_data[$k] = 0;
			}
			
		}
		
		$this->data['selected_year'] = $today_year;
		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $final_order_data;

		$this->render_template('payments/create', $this->data);
	}


}