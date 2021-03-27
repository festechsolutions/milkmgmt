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
        $this->load->model('model_orders');
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

	public function main()
	{
		if(!in_array('createPayments', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->data['category'] = $this->model_category->getActiveCategory();
        $this->data['stores'] = $this->model_stores->getActiveStore();
        $this->data['company_currency'] = $this->company_currency();
        $this->data['company_data'] = $this->model_company->getCompanyData(1);
		$this->render_template('payments/main', $this->data);
	}

	public function fetch()
	{

        $store_id = $this->input->post('store_name');
        $user_id = $this->input->post('user_id');
        $month = $this->input->post('month');
        $year = $this->input->post('year');

    	$orders_data = $this->model_orders->getUserDeliveriesData($store_id,$user_id,$month,$year);
    	echo json_encode($orders_data);
	}

}