<?php 

class Dashboard extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();


		$this->data['page_title'] = 'Dashboard';
		
		$this->load->model('model_orders');
		$this->load->model('model_users');
		$this->load->model('model_stores');
	}

	public function index()
	{
		$user_id = $this->session->userdata('id');
		$is_admin = ($user_id == 1) ? true :false;
		
		date_default_timezone_set("Asia/Kolkata");
		$date = date('d-m-Y');
		$date=((string)$date);

		$store_id = $this->model_stores->getStoreid($user_id);
		
		if($is_admin == false){
			/*$this->data['total_store_unpaid_orders'] = $this->model_orders->countStoreUnPaidOrders($user_id,$date);
			$this->data['total_store_amount'] = $this->model_orders->countStorepayment($user_id,$date);
			$this->data['total_store_items_received'] = $this->model_orders->countStoreItemRec($store_id,$date);
			$this->data['total_store_unpaid_amount'] = $this->model_orders->countStoreUnPaidAmount($user_id,$date);
			$this->data['company_currency'] = $this->company_currency();*/
		}
		else{
			/*$this->data['total_unpaid_orders'] = $this->model_orders->countCurrentUnPaidOrders($date);
			$this->data['total_paid_amount'] = $this->model_orders->countCurrentpayment($date);
			$this->data['total_items_received'] = $this->model_orders->countTotalItemRec($date);
			$this->data['total_stores'] = $this->model_stores->countTotalStores();
			$this->data['total_unpaid_amount'] = $this->model_orders->countCurrentUnPaidAmount($date);
			$this->data['company_currency'] = $this->company_currency();*/
		}

		$this->data['is_admin'] = $is_admin;
		$this->render_template('dashboard', $this->data);
	}
}