<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->data['page_title'] = 'Reports';
		$this->load->model('model_reports');
		$this->load->model('model_stores');
	}

	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	public function index()
	{
		if(!in_array('viewReport', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
		
		$today_year = date('Y');

		if($this->input->post('select_year')) {
			$today_year = $this->input->post('select_year');
		}

		$order_data = $this->model_reports->getOrderData($today_year);
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

		$this->render_template('reports/index', $this->data);
	}

	public function dailystoreamount()
	{
		if(!in_array('viewReport', $this->permission)) {
            redirect('dashboard', 'refresh');
		}
		
		$today = date('d-m-Y');

		$store_data = $this->model_stores->getStoresData();
		$store_id = $store_data[0]['id'];
		
		date_default_timezone_set("Asia/Kolkata");
		$date = date('d-m-Y');
		$date=((string)$date);

		$this->data['company_currency'] = $this->company_currency();
		$this->data['total_paid_amount'] = $this->model_reports->countCurrentpayment($date);
		$this->data['malkajgiri_store_amount'] = $this->model_reports->countStorepayment('1',$date);
		//$this->data['tarnaka_store_amount'] = $this->model_reports->countStorepayment('2',$date);
		//$this->data['nacharam_store_amount'] = $this->model_reports->countStorepayment('3',$date);

		$this->render_template('reports/storeamount', $this->data);		
	}

	public function daywise()
	{
		if(!in_array('viewReport', $this->permission)) {
            redirect('dashboard', 'refresh');
		}
	
		$date = $this->input->post('user_date');
		$new_date = date("d-m-Y", strtotime($date));
		$this->data['user_date'] = $new_date;
		$date=((string)$new_date);

		$store_data = $this->model_stores->getStoresData();
		$store_id = $store_data[0]['id'];

		$this->data['company_currency'] = $this->company_currency();
		$this->data['total_paid_amount'] = $this->model_reports->daywiseStorepayment('1',$date)+$this->model_reports->daywiseStorepayment('2',$date);
		$this->data['malkajgiri_store_amount'] = $this->model_reports->daywiseStorepayment('1',$date);
		//$this->data['tarnaka_store_amount'] = $this->model_reports->daywiseStorepayment('2',$date);
		

		$this->render_template('reports/daywise', $this->data);		
	}

	public function todayitemwise()
	{
		if(!in_array('viewReport', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

        date_default_timezone_set("Asia/Kolkata");
		$date = date('d-m-Y');
		$date=((string)$date);
		$order_data = $this->model_reports->getStoreWiseItemData($date);
        
		$this->data['order_data'] = $order_data;
		$this->data['date'] = $date;
		$this->data['company_currency'] = $this->company_currency();

		$this->render_template('reports/todayitemwise', $this->data);
	}
	
	public function todaystoreitemwise()
	{
		if(!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

        $store_data = $this->model_stores->getStoresData();
		//$store_id = $store_data[0]['id'];
		
		$user_id = $this->session->userdata('id');
		$store_id = $this->model_stores->getStoreid($user_id);
		
		date_default_timezone_set("Asia/Kolkata");
		$date = date('d-m-Y');
		$date=((string)$date);
		$order_data = $this->model_reports->getStoreWiseItemData($store_id,$date);
        
		$this->data['selected_store'] = $store_id;
		$this->data['store_data'] = $store_data;
		$this->data['order_data'] = $order_data;
		$this->data['company_currency'] = $this->company_currency();

		$this->render_template('reports/todayitemwise', $this->data);
	}

	public function storewise()
	{

		if(!in_array('viewReport', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
		$today_year = date('Y');


		$store_data = $this->model_stores->getStoresData();
		

		$store_id = $store_data[0]['id'];

		if($this->input->post('select_store')) {
			$store_id = $this->input->post('select_store');
		}

		if($this->input->post('select_year')) {
			$today_year = $this->input->post('select_year');
		}

		$order_data = $this->model_reports->getStoreWiseOrderData($today_year, $store_id);
		$this->data['report_years'] = $this->model_reports->getOrderYear();
		

		$final_parking_data = array();
		foreach ($order_data as $k => $v) {
			
			if(count($v) > 1) {
				$total_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if($v2) {
						$total_amount_earned[] = $v2['net_amount'];						
					}
				}
				$final_parking_data[$k] = array_sum($total_amount_earned);	
			}
			else {
				$final_parking_data[$k] = 0;	
			}
			
		}

		$this->data['selected_store'] = $store_id;
		$this->data['store_data'] = $store_data;
		$this->data['selected_year'] = $today_year;
		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $final_parking_data;
		
		$this->render_template('reports/storewise', $this->data);
	}

	public function fetchSummary()
	{
		if(!in_array('viewReport', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$result = array('data' => array());

		$data = $this->model_reports->getOrdersData();
		$saree=0;
		$blouse=0;
		$pant=0;
		$shirt=0;
		$total=0;
		$others=0;

		foreach ($data as $key => $value) {

			$saree=$this->model_reports->getSareeCount($value['id']);
			$blouse=$this->model_reports->getBlouseCount($value['id']);
			$pant=$this->model_reports->getPantCount($value['id']);
			$shirt=$this->model_reports->getShirtCount($value['id']);
			$total=$this->model_reports->getTotalCount($value['id']);
			$others=$total-$saree-$blouse-$pant-$shirt;

			$result['data'][$key] = array(
				$value['bill_no'],
				$saree,
				$blouse,
				$pant,
				$shirt,
				$others,
				$value['net_amount'],
			);
		} // /foreach

		echo json_encode($result);
	}

	public function summary()
	{
		if(!in_array('viewReport', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Daywise Reports';

		$result = array('data' => array());

		$data = $this->model_reports->getOrdersData();
		$saree=0;
		$blouse=0;
		$pant=0;
		$shirt=0;
		$total=0;
		$others=0;
		$total_amt=0;

		foreach ($data as $key => $value) {

			$saree+=$this->model_reports->getSareeCount($value['id']);
			$blouse+=$this->model_reports->getBlouseCount($value['id']);
			$pant+=$this->model_reports->getPantCount($value['id']);
			$shirt+=$this->model_reports->getShirtCount($value['id']);
			$total+=$this->model_reports->getTotalCount($value['id']);
			$others=$total-$saree-$blouse-$pant-$shirt;

			$result['data'][$key] = array(
				$total_amt +=$value['net_amount'],
			);
		}

		$this->data['saree_qty'] = $saree;
		$this->data['blouse_qty'] = $blouse;
		$this->data['pant_qty'] = $pant;
		$this->data['shirt_qty'] = $shirt;
		$this->data['others_qty'] = $others;
		$this->data['total_amount'] = $total_amt;

		$this->render_template('reports/summary', $this->data);		
	}

	public function printDiv($store_id)
	{
		if(!in_array('viewOrder', $this->permission)) {
          	redirect('dashboard', 'refresh');
  		}
        
		if($store_id) {
			date_default_timezone_set("Asia/Kolkata");
		    $date = date('d-m-Y');
		    $date=((string)$date);
			$order_data = $this->model_reports->getStoreWiseItemData($store_id,$date);
			$company_info = $this->model_stores->getCompanyData(1);
			$store_data = $this->model_stores->getStoresData($store_id);
			
			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			  
			  <meta name="viewport" content="width=device-width, initial-scale=1">
			</head>
			<body onload="window.print();">
			 <div class="row">  
			    <h3 class="page-header" align="middle">
				  '.$company_info['company_name'].' <br>
				   <small><font size=2><i>'.$company_info['address'].'</i></font></small>
			    </h3>
			    <b>&emsp;Shop Name:  <font size=3>'.$store_data['name'].'</font></b>
			    <span class="pull-center">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
				<b>Date:</b>'.$date.'</span>
		     </div>
			 <div class="box">
			  <div class="box-header">
			   <h3 class="box-title">Total Items - Report</h3>
			  </div>
			  <div class="box-body">
				<table id="datatables" class="table table-bordered">
				  <thead>
				  <tr>
					  <th>Item Name</th>
					  <th>Quantity</th>
				  </tr>
				  </thead>
				  <tbody>';
					$sum=0;
					 foreach ($order_data as $k => $v){
					 $html .= '<tr>
					  <td>'.$v['product_name'].'</td>
					  <td>'.$v['qtysum'].'</td>
					 </tr>';
					  $sum+=$v['qtysum'];
					 }
					  
				  $html .= '</tbody>
				  <tbody>
					<tr>
					 <th>Total Quantity</th>
					 <th>
						'.$sum.'
					 </th>
					</tr>
				  </tbody>
				</table>
			  </div>
		     </div>
	        </body>
	       </html>';
			  echo $html;
		}
	}
}	