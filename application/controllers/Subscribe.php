<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscribe extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Subscribe';

		$this->load->model('model_products');
		$this->load->model('model_category');
        $this->load->model('model_users');
		$this->load->model('model_stores');
        $this->load->model('model_subscribe');
	}

    /* 
    * It only redirects to the manage product page
    */
	public function index()
	{
        if(!in_array('viewSubscription', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->render_template('subscribe/index', $this->data);	
	}

    /*
    * It Fetches the products data from the product table 
    * this function is called from the datatable ajax function
    */
	public function fetchSubscriptionData()
	{
        if(!in_array('viewSubscription', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
		$result = array('data' => array());

		$data = $this->model_subscribe->getSubscriptionData();

		foreach ($data as $key => $value) {

			// button
            $buttons = '';
            if(in_array('updateSubscription', $this->permission)) {
    			$buttons .= '<a href="'.base_url('subscribe/update/'.$value['id']).'" style="border: 2px solid #4CAF50" class="btn btn-default"><i class="fa fa-pencil" style="color:#4CAF50"></i></a>';
            }

            if(in_array('deleteSubscription', $this->permission)) {
    			$buttons .= ' <button type="button" style="border: 2px solid #f44336" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash" style="color:#f44336"></i></button>';
            }

            $store_id = $value['store_id'];
            $store_name =  $this->model_stores->getStoresData($store_id);
			
            $availability = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			$result['data'][$key] = array(
				$value['subscribe_no'],
                $store_name['name'],
                $value['net_amount'],
				$availability,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}	
    

	public function new()
	{
		if(!in_array('createSubscription', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('store_name', 'Store/Colony name', 'trim|required');
        $this->form_validation->set_rules('user_name', 'User name', 'trim|required');
		$this->form_validation->set_rules('product[]', 'Item name', 'trim|required');
        $this->form_validation->set_rules('qty[]', 'Quantity', 'trim|required');
        $this->form_validation->set_rules('amount[]', 'Amount', 'trim|required');
        $this->form_validation->set_rules('gross_amount', 'Total Amount', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {
            // true case

        	$order_id = $this->model_subscribe->create();
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
			$this->data['products'] = $this->model_products->getActiveProductData();        	
			
            $this->render_template('subscribe/new', $this->data);
        }	
	}

	public function update($id)
	{      
        if(!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('product[]', 'Item name', 'trim|required');
        $this->form_validation->set_rules('qty[]', 'Quantity', 'trim|required');
        $this->form_validation->set_rules('amount[]', 'Amount', 'trim|required');
        $this->form_validation->set_rules('gross_amount', 'Total Amount', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // true case

            $update = $this->model_subscribe->update($id);

            if($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('subscribe/', 'refresh');
            }
            else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('subscribe/update/'.$id, 'refresh');
            }
        }
        else {
            $result = array();
            $subscribe_data = $this->model_subscribe->getSubscriptionData($id);

            if(empty($subscribe_data)) {
                $this->session->set_flashdata('errors', 'The request data does not exists');
                redirect('subscribe', 'refresh');
            }

            $result['subscribe'] = $subscribe_data;
            $subscribed_item = $this->model_subscribe->getSubscribedItemData($subscribe_data['id']);

            foreach($subscribed_item as $k => $v) {
                $result['subscribe_item'][] = $v;
            }

            $this->data['subscribe_data'] = $result;
            $this->data['category'] = $this->model_category->getActiveCategory();
            $this->data['stores'] = $this->model_stores->getActiveStore();
            $this->data['users'] = $this->model_users->getUserData();

            $this->data['products'] = $this->model_products->getActiveProductData();  
            
            $this->data['page_title'] = 'Manage Subscriptions';
            
            $this->render_template('subscribe/edit', $this->data);
        }   
	}

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
	public function remove()
	{
        if(!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
        $product_id = $this->input->post('product_id');

        $response = array();
        if($product_id) {
            $delete = $this->model_products->remove($product_id);
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

}