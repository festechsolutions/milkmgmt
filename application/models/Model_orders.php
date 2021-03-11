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
		$date = date('d-m-Y');
		$date=((string)$date);
		if($id) {
			$sql = "SELECT * FROM orders WHERE id = ? && paid_status='2' ";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$user_id = $this->session->userdata('id');
		if($user_id == 1) {
			$sql = "SELECT * FROM orders  WHERE paid_status='2' ORDER BY id DESC";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
		else {
			$user_data = $this->model_users->getUserData($user_id);
			$sql = "SELECT * FROM orders WHERE store_id = ? && paid_status='2' ORDER BY id DESC";
			$query = $this->db->query($sql, array($user_data['store_id']));
			return $query->result_array();	
		}
	}

	public function getCurrentOrdersData($id = null)
	{
	    date_default_timezone_set("Asia/Kolkata");
		$date = date('d-m-Y');
		$date=((string)$date);
		if($id) {
			$sql = "SELECT * FROM orders WHERE paid_status='2' && due_date= '$date'";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
		$user_id = $this->session->userdata('id');
		if($user_id == 1) {
			$sql = "SELECT * FROM orders WHERE due_date= '$date' ORDER BY id DESC";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
		else {
			$user_data = $this->model_users->getUserData($user_id);
			$sql = "SELECT * FROM orders WHERE store_id = ? && due_date= '$date' ORDER BY id DESC";
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

		$sql = "SELECT * FROM order_items WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function checkIfOrderExists()
	{
		$user_id = $this->input->post('user_id');
		date_default_timezone_set("Asia/Kolkata");
		$date = date('d-m-Y');

		$sql = $this->db->query("SELECT * FROM orders WHERE user_id = $user_id && date = '$date'");
		$count = $sql->num_rows();
		if($count == 1){
			return true;
		}else{
			return false;
		}
	}

	public function create()
	{
		$user_id = $this->input->post('id');
		// get store id from user id 
		$user_data = $this->model_users->getUserData($user_id);
		$store_id = $user_data['store_id'];

		$bill_no = $this->generateBill($store_id);
		date_default_timezone_set("Asia/Kolkata");
		$date = date('d-m-Y');
		$date =((string)$date);
		$time = date('h:i:sa');
		$time = ((string)$time);

		$get_company_data = $this->model_company->getCompanyData(1);
		$service_charge_amount = $get_company_data['service_charge_amount'];

		$gross_amount = $this->input->post('amount');

		$net_amount = $gross_amount + $service_charge_amount;
		
		$data = array(
    		'bill_no' => $bill_no,
			'date' => $date,
			'time' => $time,
			'gross_amount' => $gross_amount,
			'service_charge_amount' => $service_charge_amount,
			'net_amount' => $net_amount,
    		'paid_status' => 2,
    		'user_id' => $user_id,
			'store_id' => $store_id,
    	);

		$insert = $this->db->insert('orders', $data);
		$order_id = $this->db->insert_id();

		date_default_timezone_set("Asia/Kolkata");
		$date = date('d-m-Y');
		$items = array(
    		'order_id' => $order_id,
    		'category_id' => $this->input->post('category_id'),
			'product_id' => $this->input->post('product_id'),
			'product_name' => $this->input->post('product_name'),
    		'qty' => $this->input->post('qty'),
    		'amount' => $this->input->post('amount'),
			'date' => $date,
		    'store_id' => $store_id,
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
			$i=$row['count']+1;
			$sqli = $this->db->query("UPDATE billno SET orders_count=$i WHERE sno=$store_id");
			$l=strlen((string)$i);
			$sum='';
			for($j=0;$j<5-$l;$j++)
			    $sum.='0';
			return $result.'-'.$sum.$i;
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

	public function update()
	{

		$user_id = $this->input->post('id');
		// get store id from user id 
		$user_data = $this->model_users->getUserData($user_id);
		$store_id = $user_data['store_id'];
		// update the table info

		date_default_timezone_set("Asia/Kolkata");
	    $date_time = date('d-m-Y h:i:sa');
	    $date_time =((string)$date_time);

	    $get_company_data = $this->model_company->getCompanyData(1);
		$service_charge_amount = $get_company_data['service_charge_amount'];

		$gross_amount = $this->input->post('amount');

		$net_amount = $gross_amount + $service_charge_amount;
		
		$data = array(
			'gross_amount' => $gross_amount,
			'service_charge_amount' => $service_charge_amount,
			'net_amount' => $net_amount,
			'paid_status' => 2,
    		'modified_datetime' => $date_time,
    	);

		$this->db->where('id', $id);
		$update = $this->db->update('orders', $data);

		// now remove the order item data 
		$this->db->where('order_id', $id);
		$this->db->delete('order_items');
		
		$items = array(
    		'order_id' => $order_id,
    		'category_id' => $this->input->post('category_id'),
			'product_id' => $this->input->post('product_id'),
			'product_name' => $this->input->post('product_name'),
    		'qty' => $this->input->post('qty'),
    		'amount' => $this->input->post('amount'),
			'date' => $date,
		    'store_id' => $store_id,
		);
		$this->db->insert('order_items', $items);

		return ($order_id) ? $order_id : false;
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