<?php 

class Model_subscribe extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_users');
	}

	public function getSubscriptionData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM subscribe WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$user_id = $this->session->userdata('id');
		if($user_id == 1) {
			$sql = "SELECT * FROM subscribe ORDER BY id DESC";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
		else {
			$user_data = $this->model_users->getUserData($user_id);
			$sql = "SELECT * FROM subscribe WHERE store_id = ? ORDER BY id DESC";
			$query = $this->db->query($sql, array($user_data['store_id']));
			return $query->result_array();	
		}
	}

	public function getSubscribedItemData($order_id = null)
	{
		if(!$order_id) {
			return false;
		}

		$sql = "SELECT * FROM subscribed_items WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function create()
	{
		$store_id = $this->input->post('store_name');
		$subscription_no = $this->generateBill($store_id);
		date_default_timezone_set("Asia/Kolkata");
		$date = date('h:m:s d-m-Y');
		$date=((string)$date);
		
		$data = array(
    		'subscribe_no' => $subscription_no,
			'store_id' => $store_id,
            'user_id' => $this->input->post('user_name'),
            'net_amount' => $this->input->post('gross_amount'),
            'last_modified' => $date,
            'active' => 1,
    	);

		$subscribe = $this->db->insert('subscribe', $data);
		$order_id = $this->db->insert_id();

		$count_product = count($this->input->post('product'));
		for($x = 0; $x < $count_product; $x++) {
			 $pid = $this->input->post('product')[$x];
			 $sql = $this->db->query("SELECT * FROM products where id=$pid");
			 $query = $sql->row_array();
    		$items = array(
    			'order_id' => $order_id,
				'product_id' => $pid,
				'product_name' => $query['name'],
    			'qty' => $this->input->post('qty')[$x],
    			'amount' => $this->input->post('amount')[$x],
    		);

    		$this->db->insert('subscribed_items', $items);
    	}

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
			$sql =  $this->db->query("SELECT count FROM billno WHERE sno=$store_id");
			$row = $sql->row_array();
			$i=$row['count']+1;
			$sqli = $this->db->query("UPDATE billno SET count=$i WHERE sno=$store_id");
			$l=strlen((string)$i);
			$sum='';
			for($j=0;$j<5-$l;$j++)
			    $sum.='0';
			return $result.'/'.$sum.$i;
		}
	}

	public function update($id)
	{
		if($id) {
			$store_id = $this->input->post('store_name');
			date_default_timezone_set("Asia/Kolkata");
			$date = date('h:m:s d-m-Y');
			$date=((string)$date);
			
			$data = array(
	    		'net_amount' => $this->input->post('gross_amount'),
	            'last_modified' => $date,
	            'active' => $this->input->post('active'),
	    	);

			$this->db->where('id', $id);
			$update = $this->db->update('subscribe', $data);

			// now remove the order item data 
			$this->db->where('order_id', $id);
			$this->db->delete('subscribed_items');
			
			$count_product = count($this->input->post('product'));
	    	for($x = 0; $x < $count_product; $x++) {
				$pid = $this->input->post('product')[$x];
			    $sql = $this->db->query("SELECT * FROM products where id=$pid");
			    $query = $sql->row_array();
	    		$items = array(
					'order_id' => $id,
					'product_id' => $pid,
					'product_name' => $query['name'],
	    			'qty' => $this->input->post('qty')[$x],
	    			'amount' => $this->input->post('amount')[$x],
	    		);
	    		$this->db->insert('subscribed_items', $items);
	    	}
			return true;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('subscribe');

			$this->db->where('order_id', $id);
			$delete_item = $this->db->delete('subscribed_items');
			return ($delete == true && $delete_item) ? true : false;
		}
	}
}