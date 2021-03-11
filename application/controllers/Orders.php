<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends Admin_Controller 
{
	var $currency_code = '';

	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Orders';

		$this->load->model('model_orders');
		$this->load->model('model_products');
		$this->load->model('model_company');
		$this->load->model('model_stores');

		$this->currency_code = $this->company_currency();
	}

	/* 
	* It only redirects to the manage order page
	*/
	public function index()
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->render_template('orders/index', $this->data);		
	}

	public function current()
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Manage Orders';
		$this->render_template('orders/current', $this->data);		
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersData()
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$result = array('data' => array());

		$data = $this->model_orders->getOrdersData();

		foreach ($data as $key => $value) {

			$store_data = $this->model_stores->getStoresData($value['store_id']);

			$count_total_item = $this->model_orders->countOrderItem($value['id']);
			date_default_timezone_set("Asia/Kolkata");
			$date_time = date('d-m-Y', $value['date_time']);
			//$time = date('h:i a', $value['date_time']);

			//$date_time = $date . ' ' . $time;
			$due_date = $value['due_date'];

			// button
			$buttons = '';

			if(in_array('viewOrder', $this->permission)) {
				$buttons .= '<a target="__blank" href="'.base_url('orders/printDiv/'.$value['id']).'" style="border: 2px solid #008CBA" class="btn btn-default"><i class="fa fa-print style="color:#008CBA"></i></a>';
			}

			if(in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a href="'.base_url('orders/update/'.$value['id']).'" style="border: 2px solid #4CAF50" class="btn btn-default"><i class="fa fa-pencil" style="color:#4CAF50"></i></a>';
			}

			if(in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button type="button" style="border: 2px solid #f44336" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash" style="color:#f44336"></i></button>';
			}

			if($value['paid_status'] == 1) {
				$paid_status = '<span class="label label-success">Paid</span>';
			}
			else {
				$paid_status = '<span class="label label-warning">Not Paid</span>';
			}

			$result['data'][$key] = array(
				$value['bill_no'],
				$date_time,
				$due_date,
				$value['mobile_no'],
				$count_total_item,
				$value['net_amount'],
				$paid_status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		if(!in_array('createOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Add Order';

		$this->form_validation->set_rules('user_id', 'UserID', 'trim|required');
		$this->form_validation->set_rules('product_name', 'Product_name name', 'trim|required');
		$this->form_validation->set_rules('product_id', 'Product_name name', 'trim|required');
		$this->form_validation->set_rules('qty', 'Quantity', 'trim|required');
		$this->form_validation->set_rules('category_id', 'Category ID', 'trim|required');
		$this->form_validation->set_rules('store_id', 'Store ID', 'trim|required');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {

        	$check_order = $this->model_orders->checkIfOrderExists();

        	if($check_order == TRUE){

        		$order_id = $this->model_orders->update();

        		if($order_id) {
	        		$this->session->set_flashdata('success', 'Successfully created');
	        		redirect('orders/update/'.$order_id, 'refresh');
	        	}
	        	else {
	        		$this->session->set_flashdata('errors', 'Error occurred!!');
	        		redirect('orders/create/', 'refresh');
	        	}
        	}
        	else{

        		$order_id = $this->model_orders->create();
        	
	        	if($order_id) {
	        		$this->session->set_flashdata('success', 'Successfully created');
	        		redirect('orders/update/'.$order_id, 'refresh');
	        	}
	        	else {
	        		$this->session->set_flashdata('errors', 'Error occurred!!');
	        		redirect('orders/create/', 'refresh');
	        	}
        	}
        }
        else {
            // false case
            
			$this->data['products'] = $this->model_products->getActiveProductData();
			$this->data['store'] = $this->model_stores->getStoresData();

            $this->render_template('orders/create', $this->data);
        }
	}

	/*
	* It gets the product id passed from the ajax method.
	* It checks retrieves the particular product data from the product id 
	* and return the data into the json format.
	*/
	public function getProductValueById()
	{
		$product_id = $this->input->post('product_id');
		if($product_id) {
			$product_data = $this->model_products->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	/*
	* If the validation is not valid, then it redirects to the edit orders page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id)
	{
		if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		if(!$id) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Update Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	

        	$update = $this->model_orders->update($id);
        	
        	if($update == true) {
        		$this->session->set_flashdata('success', 'Successfully updated');
        		redirect('orders/update/'.$id, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('orders/update/'.$id, 'refresh');
        	}
        }
        else {
            // false case
        	
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$result = array();
        	$orders_data = $this->model_orders->getOrdersData($id);

        	if(empty($orders_data)) {
        		$this->session->set_flashdata('errors', 'The request data does not exists');
        		redirect('orders', 'refresh');
        	}

    		$result['order'] = $orders_data;
    		$orders_item = $this->model_orders->getOrdersItemData($orders_data['id']);

    		foreach($orders_item as $k => $v) {
    			$result['order_item'][] = $v;
    		}

    		$this->data['order_data'] = $result;

        	$this->data['products'] = $this->model_products->getActiveProductData();  
        	
        	$this->data['page_title'] = 'Manage Orders';
		    
            $this->render_template('orders/edit', $this->data);
        }
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if(!in_array('deleteOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$order_id = $this->input->post('order_id');

        $response = array();
        if($order_id) {
            $delete = $this->model_orders->remove($order_id);
            if($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response); 
	}

	/*
	* It gets the product id and fetch the order data. 
	* The order print logic is done here 
	*/
	public function printDiv($id)
	{
		if(!in_array('viewOrder', $this->permission)) {
          	redirect('dashboard', 'refresh');
  		}
        
		if($id) {
			$order_data = $this->model_orders->getOrdersData($id);
			$orders_items = $this->model_orders->getOrdersItemData($id);
			$company_info = $this->model_company->getCompanyData(1);
			$store_data = $this->model_stores->getStoresData($order_data['store_id']);

			$order_date = date('d-m-Y', $order_data['date_time']);
			$paid_status = ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";
			$discount = '0';
			$sum='0';

			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <link rel="stylesheet" type="text/css" media="print"/>
			  <title>Invoice</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			  
			    <style>
                
				* {
					  box-sizing: border-box;
					  float:none;
				}

				::-webkit-scrollbar {
					display: none;
				}

				@media print {
					.print {visibility:visible;}
					page-break-before: auto;
				}

				.mycolumn {
  					float: left;
  					width: 50%;
  					padding: 0px;
  					height: 700px; 
				}

				/* Clear floats after the columns */
				.myrow:after {
					width: 300px;
  					content: "";
  					display: table;
  					clear: both;
				}

				div {
					box-sizing: border-box;
				
				}

				div.c {
					text-transform: capitalize;
				  }

				th {
					background-color: #696969;
					color: white;
				  }

				tr:nth-child(even) {background-color: #D3D3D3;}
				@media print {
					md-content {
					  overflow: visible;
					}
				  }
				
				
				</style>
			</head>
			<body onload="window.print();">

			<div class="myrow">
			 <div class="mycolumn">
			  <section class="invoice">
			 	<div class="row">  
				    <h3 class="page-header" align="middle">
				          '.$company_info['company_name'].' <br>
				           <small><font size=2><i>'.$company_info['address'].'</i></font></small>
			        </h3>
					<b>&emsp;Bill No:  <font size=4>'.$order_data['bill_no'].'</font></b>
					<span class="pull-center">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
					    <b>Date:</b>'.$order_date.'</span>
				</div>
			    <div class="table-responsive">
			        <table class="table table-striped">
			          <thead>
					  <tr>
					    <th>Qty</th>
			            <th>Particulars</th>
						<th>Type</th>
			            <th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>'; 

			          foreach ($orders_items as $k => $v) {
			          	$product_data = $this->model_products->getProductData($v['product_id']); 
						$sum += $v['qty'];
						  $html .= '<tr>
						    <td>'.$v['qty'].'</td>
				            <td style="text-transform: uppercase" >'.$product_data['name'].'</td>
							<td style="text-transform: uppercase" >'.$v['type'].'</td>
				            <td>'.$v['amount'].'</td>
			          	</tr>';
					  }
					  $html .= '</tbody>
					  <tr>
					  <td><b>'.$sum.' - Total</b></td>
					  <td><b>-</b></td>
					  <td><b>-</b></td>
					  <td><b>'.$order_data['net_amount'].'</b></td>
					  </tr>
					</table>
			    </div>	      
				<center><font size=2><i>Article is accepted at owners risk.</i></font><br>
				<b>Delivery Date : '.$order_data['due_date'].'</b> <i>after 7 p.m.</i></center>
			    <br><b><font size=2>&nbsp;&nbsp;&nbsp;Customer Sign</font></b>
				<span class="pull-right"><b><font size=2>Signature</font></b></span>
				<br><b><font size=2>&nbsp;&nbsp;&nbsp;(+91 '.$order_data['mobile_no'].')</font></b></span>
			  </section>
			</div>
			<div class="mycolumn">
			  <section class="invoice">
			 	<div class="row">  
				    <h3 class="page-header" align="middle">
				         '.$company_info['company_name'].' <br>
				         <small><font size=2><i>'.$company_info['address'].'</i></font></small>
			        </h3>
					<b>&emsp;&emsp;Bill No:  <font size=4>'.$order_data['bill_no'].'</font></b>
					<span class="pull-center">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
					<b>Date:</b> '.$order_date.'</span>
				</div>
			    <div class="table-responsive">
			        <table class="table table-striped">
			          <thead>
					  <tr>
					    <th>Qty</th>
			            <th>Particulars</th>
						<th>Type</th>
			            <th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>'; 

					  $sum=0;
			          foreach ($orders_items as $k => $v) {
			          	$product_data = $this->model_products->getProductData($v['product_id']); 
						$sum += $v['qty'];
			          	$html .= '<tr>
							<td>'.$v['qty'].'</td>
							<td style="text-transform: uppercase" >'.$product_data['name'].'</td>
							<td style="text-transform: uppercase" >'.$v['type'].'</td>
				            <td>'.$v['amount'].'</td>
			          	</tr>';
					  }
					  $html .= '</tbody>
					  <tr>
					  <td><b>'.$sum.' - Total</b></td>
					  <td><b>-</b></td>
					  <td><b>-</b></td>
					  <td><b>'.$order_data['net_amount'].'</b></td>
					  </tr>
					</table>
			    </div>
			    <center><font size=2><i>Article is accepted at owners risk.</i></font><br>
				<b>Delivery Date : '.$order_data['due_date'].'</b> <i>after 7 p.m.</i></center>
			    <div class="footer">
				 <br><b><font size=2>&nbsp;&nbsp;&nbsp;Customer Sign</font></b>
				 <span class="pull-right"><b><font size=2>Signature</font></b></span>
				 <br><b><font size=2>&nbsp;&nbsp;&nbsp;(+91 '.$order_data['mobile_no'].')</font></b></span>
				</div> 
			  </section>
		     </div>
			</div>
		</body>
	</html>';
			  echo $html;
		}
	}
}