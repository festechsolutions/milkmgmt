<?php 

class Model_reports extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*getting the total months*/
	private function months()
	{
		return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
	}

	/* getting the year of the orders */
	public function getOrderYear()
	{
		$sql = "SELECT * FROM orders WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		$result = $query->result_array();
		
		$return_data = array();
		foreach ($result as $k => $v) {
			$date = date('Y', $v['date']);
			$return_data[] = $date;
		}

		$return_data = array_unique($return_data);

		return $return_data;
	}

	// getting the order reports based on the year and moths
	public function getOrderData($year)
	{	
		if($year) {
			$months = $this->months();
			
			$sql = "SELECT * FROM orders WHERE paid_status = ?";
			$query = $this->db->query($sql, array(1));
			$result = $query->result_array();

			$final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;	

				$final_data[$get_mon_year][] = '';
				foreach ($result as $k => $v) {
					$month_year = date('Y-m', $v['date']);

					if($get_mon_year == $month_year) {
						$final_data[$get_mon_year][] = $v;
					}
				}
			}	

			return $final_data;
		}
	}

	

	public function getStoreWiseOrderData($year, $store)
	{
		if($year && $store) {
			$months = $this->months();
			
			$sql = "SELECT * FROM orders WHERE paid_status = ? AND store_id = ?";
			$query = $this->db->query($sql, array(1, $store));
			$result = $query->result_array();

			$final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;	

				$final_data[$get_mon_year][] = '';
				foreach ($result as $k => $v) {
					$month_year = date('Y-m', $v['date']);

					if($get_mon_year == $month_year) {
						$final_data[$get_mon_year][] = $v;
					}
				}
			}	
			
			return $final_data;
		}
	}

	public function countCurrentpayment($date)
	{
		$query = $this->db->query("SELECT * FROM orders WHERE paid_status = '1' && due_date= '$date'");
		$result = $query->result_array();
		$num_rows = $query->num_rows();
		$sum = 0;
		foreach($result as $data)
		{
		    $sum += $data['net_amount'];
		}		
		return $sum;
	}

	public function getStoreWiseItemData($date)
	{		
	    $sql = "SELECT SUM(qty) qtysum,product_name FROM order_items WHERE date= '$date' GROUP BY product_id ";
	    $query = $this->db->query($sql);
	    return $query->result_array();	
	}

	public function gettodaysItemData($date)
	{		
	    $sql = $this->db->query("SELECT SUM(qty)as sum FROM order_items WHERE date= '$date';")->row();
	    return $sql->sum;	
	}

	public function countStorepayment($user_id,$date)
	{
		$query = $this->db->query("SELECT * FROM orders WHERE paid_status = '1' && due_date= '$date' && store_id= $user_id");
		$result = $query->result_array();
		$num_rows = $query->num_rows();
		$sum = 0;
	    foreach($result as $data)
		{
		    $sum += $data['net_amount'];
		}	
        return $sum;
	}

	public function daywiseStorepayment($user_id,$user_date)
	{
		$query = $this->db->query("SELECT * FROM orders WHERE paid_status = '1' && paid_date = '$user_date' && store_id= $user_id");
		$result = $query->result_array();
		$num_rows = $query->num_rows();
		$sum = 0;
	    foreach($result as $data)
		{
		    $sum += $data['net_amount'];
		}	
        return $sum;
	}

	public function getTotalCount($order_id)
	{
		if($order_id) {
			$query = $this->db->query("SELECT SUM(qty) qtysum FROM order_items WHERE order_id = $order_id");
			$result = $query->result_array();
			$sum=0;
			foreach($result as $data)
			  $sum += $data['qtysum'];
			return $sum;
		}
	}

	public function getSareeCount($order_id)
	{
		if($order_id) {
			$query = $this->db->query("SELECT SUM(qty) qtysum FROM order_items WHERE order_id = $order_id && product_name LIKE '%Saree'");
			$result = $query->result_array();
			$sum=0;
			foreach($result as $data)
			  $sum += $data['qtysum'];
			return $sum;
		}
	}

	public function getBlouseCount($order_id)
	{
		if($order_id) {
			$query = $this->db->query("SELECT SUM(qty) qtysum FROM order_items WHERE order_id = $order_id && product_name LIKE '%Blouse'");
			$result = $query->result_array();
			$sum=0;
			foreach($result as $data)
			  $sum += $data['qtysum'];
			return $sum;
		}
	}

	public function getPantCount($order_id)
	{
		if($order_id) {
			$query = $this->db->query("SELECT SUM(qty) qtysum FROM order_items WHERE order_id = $order_id && product_name LIKE '%Pant'");
			$result = $query->result_array();
			$sum=0;
			foreach($result as $data)
			  $sum += $data['qtysum'];
			return $sum;
		}
	}

	public function getShirtCount($order_id)
	{
		if($order_id) {
			$query = $this->db->query("SELECT SUM(qty) qtysum FROM order_items WHERE order_id = $order_id && product_name LIKE '%Shirt'");
			$result = $query->result_array();
			$sum=0;
			foreach($result as $data)
			  $sum += $data['qtysum'];
			return $sum;
		}
	}
}