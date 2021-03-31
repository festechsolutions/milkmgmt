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
		$this->load->model('model_reports');
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
			$this->data['todays_itemdata'] = $this->model_reports->gettodaysItemData($date);
			$this->data['total_subscribed_users'] = $this->model_users->countSubscribedUsers();
			$this->data['total_users'] = $this->model_users->countTotalUsers();
			$this->data['total_colonies'] = $this->model_stores->countTotalStores();
			$this->data['company_currency'] = $this->company_currency();
		}
		else{
			$this->data['todays_itemdata'] = $this->model_reports->gettodaysItemData($date);
			$this->data['total_subscribed_users'] = $this->model_users->countSubscribedUsers();
			$this->data['total_users'] = $this->model_users->countTotalUsers();
			$this->data['total_colonies'] = $this->model_stores->countTotalStores();
			$this->data['company_currency'] = $this->company_currency();
		}

		$this->data['is_admin'] = $is_admin;
		$this->render_template('dashboard', $this->data);
	}
}