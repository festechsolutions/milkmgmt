<?php 

class Model_orders extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_users');
	}

	/* get the orders data */
	public function getOrdersData($id = null)
	{
		date_default_timezone_set("Asia/Kolkata");
		if($id) {
			$sql = "SELECT * FROM orders WHERE id = ? ORDER BY id DESC";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$user_id = $this->session->userdata('id');
		if($user_id == 1) {
			$sql = "SELECT * FROM orders ORDER BY id DESC";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
		else {
			$user_data = $this->model_users->getUserData($user_id);
			$sql = "SELECT * FROM orders ORDER BY id DESC";
			$query = $this->db->query($sql, array($user_data['store_id']));
			return $query->result_array();	
		}
	}

	// get the orders item data
	public function getOrdersItemData($order_id = null)
	{
		if(!$order_id) {
			return false;
		}

		$sql = "SELECT * FROM orders INNER JOIN order_items ON orders.id = order_items.order_id WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function getIfUserIsDelivered($store_id)
	{
		date_default_timezone_set("Asia/Kolkata");
		$date = date('d-m-Y');
		$sql = $this->db->query("SELECT DISTINCT orders.id,orders.user_id FROM orders INNER JOIN order_items ON orders.id = order_items.order_id WHERE order_items.is_subscribed ='1' && order_items.store_id ='$store_id' && order_items.date='$date'");
		return $sql->result_array();
	}

	public function checkIfOrderExists($user_id)
	{
		//$user_id = $this->input->post('user_id');
		date_default_timezone_set("Asia/Kolkata");
		$date = strtotime(date('d-m-Y'));
		$id = 0;
		$sql = $this->db->query("SELECT id FROM orders WHERE user_id ='$user_id' && date ='$date'");
		$res = $sql->row_array();
		$count = $sql->num_rows();
		if($count == 1){
			$id = $res['id'];
			return array('bool' => TRUE,'order_id' => $id);
		}else{
			return array('bool' => FALSE,'order_id' => $id);
		}
	}

	public function getUserDeliveriesData($store_id,$user_id,$month,$year)
	{
		if($store_id && $user_id && $month && $year)
		{
			date_default_timezone_set("Asia/Kolkata");
			$date = $month.'-'.$year;
			$sql = $this->db->query("SELECT order_items.product_name,order_items.qty,order_items.amount,order_items.date FROM orders INNER JOIN order_items ON orders.id = order_items.order_id WHERE orders.user_id ='$user_id' && order_items.store_id ='$store_id' && order_items.date LIKE '%$date'");
			return $sql->result_array();
		}
	}

	public function create($user_id,$category_id,$product_id,$product_name,$qty,$amount,$is_subscribed)
	{
		$user_data = $this->model_users->getUserData($user_id);
		$store_id = $user_data['store_id'];

		$bill_no = $this->generateBill($store_id);
		date_default_timezone_set("Asia/Kolkata");
		$date = strtotime(date('d-m-Y'));
		$date1 = date('d-m-Y');
		$time = date('h:i:sa');
		
		$get_company_data = $this->model_company->getCompanyData(1);
		$service_charge_amount = $get_company_data['service_charge_value'];

		$net_amount = number_format($amount, 2);

		$amount = number_format($amount, 2);
		
		$data = array(
    		'bill_no' => $bill_no,
			'date' => $date,
			'time' => $time,
			'net_amount' => $net_amount,
    		'paid_status' => 2,
    		'user_id' => $user_id,
			'store_id' => $store_id,
    	);

		$insert = $this->db->insert('orders', $data);
		$order_id = $this->db->insert_id();

		date_default_timezone_set("Asia/Kolkata");
		$items = array(
    		'order_id' => $order_id,
    		'category_id' => $category_id,
			'product_id' => $product_id,
			'product_name' => $product_name,
    		'qty' => $qty,
    		'amount' => $amount,
			'date' => $date1,
		    'store_id' => $store_id,
		    'is_subscribed' => $is_subscribed,
    	);

    	$this->db->insert('order_items', $items);

		return ($order_id) ? $order_id : false;
	}

	public function generateBill($store_id)
	{
		if($store_id)
		{
			$select = $this->db->query("SELECT code FROM stores WHERE id = $store_id");
			$query = $select->row_array();
			$result = $query['code'];
		    $i=0;
			$sql =  $this->db->query("SELECT orders_count FROM billno WHERE sno=$store_id");
			$row = $sql->row_array();
			$i=$row['orders_count']+1;
			$sqli = $this->db->query("UPDATE billno SET orders_count=$i WHERE sno=$store_id");
			date_default_timezone_set("Asia/Kolkata");
			$year = date('Y');
			$mon = date('m');
			$day = date('d');
			return $result.'-'.$year.$mon.$day.$i;
		}
	}

	public function countOrderItem($order_id)
	{
		if($order_id) {
			$sql = "SELECT * FROM order_items WHERE order_id = ?";
			$query = $this->db->query($sql, array($order_id));
			return $query->num_rows();
		}
	}

	public function update_order($order_id,$user_id,$category_id,$product_id,$product_name,$qty,$amount,$is_subscribed)
	{

		//$user_id = $this->input->post('id');
		// get store id from user id 
		$user_data = $this->model_users->getUserData($user_id);
		$store_id = $user_data['store_id'];
		// update the table info

		date_default_timezone_set("Asia/Kolkata");
		$date = strtotime(date('d-m-Y'));
		$date1 = date('d-m-Y');
	    $date_time = strtotime(date('d-m-Y h:i:sa'));
	    
	    $get_company_data = $this->model_company->getCompanyData(1);
		
		$select = $this->db->query("SELECT net_amount FROM orders WHERE id = $order_id");
		$query = $select->row_array();
		$existing_net = $query['net_amount'];

		$net_amount = $existing_net + $amount;
		$net_amount = number_format($net_amount, 2);

		$amount = number_format($amount, 2);
		
		$data = array(
			'net_amount' => $net_amount,
			'paid_status' => 2,
    		'modified_datetime' => $date_time,
    	);

		$this->db->where('id', $order_id);
		$update = $this->db->update('orders', $data);
		
		$items = array(
    		'order_id' => $order_id,
    		'category_id' => $category_id,
			'product_id' => $product_id,
			'product_name' => $product_name,
    		'qty' => $qty,
    		'amount' => $amount,
			'date' => $date1,
		    'store_id' => $store_id,
		    'is_subscribed' => $is_subscribed,
		);
		$this->db->insert('order_items', $items);

		return ($order_id) ? $order_id : false;
	}

	public function update($id)
	{
		if($id) {
			$user_id = $this->input->post('uedudwekb');
			$user_data = $this->model_users->getUserData($user_id);
			$store_id = $user_data['store_id'];
			// update the table info

			$order_data = $this->getOrdersData($id);
			
			date_default_timezone_set("Asia/Kolkata");
		    $date = strtotime(date('d-m-Y'));
		    $date1 = date('d-m-Y');
		    $date_time = strtotime(date('d-m-Y h:i:sa'));
			
			$data = array(
				'net_amount' => $this->input->post('net_amount'),
	    		'modified_datetime' => $date_time,
	    	);

			$this->db->where('id', $id);
			$update = $this->db->update('orders', $data);

			// now remove the order item data 
			$this->db->where('order_id', $id);
			$this->db->delete('order_items');
			
			$count_product = count($this->input->post('product'));
	    	for($x = 0; $x < $count_product; $x++) {
				$pid = $this->input->post('product')[$x];
				$category_id = $this->model_products->getCategoryID($pid);
			    $sql = $this->db->query("SELECT * FROM products where id=$pid");
			    $query = $sql->row_array();
	    		$items = array(
	    			'order_id' => $id,
	    			'category_id' => $category_id['category_id'],
					'product_id' => $this->input->post('product')[$x],
					'product_name' => $query['name'],
	    			'qty' => $this->input->post('qty')[$x],
	    			'amount' => $this->input->post('amount')[$x],
					'date' => $date1,
					'store_id' => $store_id,
					'is_subscribed' => 0,
	    		);
	    		$this->db->insert('order_items', $items);
	    	}
			return true;
		}
	}



	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('orders');

			$this->db->where('order_id', $id);
			$delete_item = $this->db->delete('order_items');
			return ($delete == true && $delete_item) ? true : false;
		}
	}

	public function remove_order($user_id)
	{
		if($user_id) {
			date_default_timezone_set("Asia/Kolkata");
		    $date = strtotime(date('d-m-Y'));
			$sql = $this->db->query("SELECT id FROM orders where user_id=$user_id && date='$date'");
			$query = $sql->row_array();
			$order_id = $query['id'];
			$this->db->where('id', $order_id);
			$delete = $this->db->delete('orders');

			$this->db->where('order_id', $order_id);
			$delete_item = $this->db->delete('order_items');
			return ($delete == true && $delete_item) ? true : false;
		}
	}

	public function countCurrentUnPaidOrders($date)
	{
	    $sql = "SELECT * FROM orders WHERE paid_status = '2' && due_date= '$date'";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

	public function countCurrentpayment($date)
	{
	    $query = $this->db->query("SELECT * FROM orders WHERE paid_status = '1' && paid_date= '$date'");
		$result = $query->result_array();
		$num_rows = $query->num_rows();
		$sum = 0;
		foreach($result as $data)
		{
		    $sum += $data['net_amount'];
		}		
		return $sum;
	}

	public function countStoreUnPaidOrders($user_id,$date)
	{
	    $sql = "SELECT * FROM orders WHERE paid_status = '2' && due_date= '$date' && user_id= '$user_id'";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

	public function countStorepayment($user_id,$date)
	{
	    $query = $this->db->query("SELECT * FROM orders WHERE paid_status = '1' && paid_date= '$date' && user_id= '$user_id'");
		$result = $query->result_array();
		$num_rows = $query->num_rows();
		$sum = 0;
	    foreach($result as $data)
		{
		    $sum += $data['net_amount'];
		}	
        return $sum;
	}
	
	public function countStoreItemRec($user_id,$date)
	{
	    $query = $this->db->query("SELECT * FROM order_items WHERE date= '$date' && store_id= $user_id");
		$result = $query->result_array();
		$num_rows = $query->num_rows();
		$qty = 0;
		foreach($result as $data)
		{
		    $qty += $data['qty'];
		}		
		return $qty;
	}

	public function countStoreUnPaidAmount($user_id,$date)
	{
		$query = $this->db->query("SELECT * FROM orders WHERE paid_status = '2' && paid_date= '$date' && user_id= '$user_id'");
		$result = $query->result_array();
		$num_rows = $query->num_rows();
		$sum = 0;
	    foreach($result as $data)
		{
		    $sum += $data['net_amount'];
		}	
        return $sum;
	}

	public function countCurrentUnPaidAmount($date)
	{
		$query = $this->db->query("SELECT * FROM orders WHERE paid_status = '2' ");
		$result = $query->result_array();
		$num_rows = $query->num_rows();
		$sum = 0;
	    foreach($result as $data)
		{
		    $sum += $data['net_amount'];
		}	
        return $sum;
	}

	public function countTotalItemRec($date)
	{
	    $query = $this->db->query("SELECT * FROM order_items WHERE date= '$date'");
		$result = $query->result_array();
		$num_rows = $query->num_rows();
		$qty = 0;
		foreach($result as $data)
		{
		    $qty += $data['qty'];
		}		
		return $qty;
	}
}