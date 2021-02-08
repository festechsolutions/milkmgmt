<?php 

class Model_stores extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getStoresData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM stores WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM stores ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function create($data = array())
	{
		if($data) {
			$create = $this->db->insert('stores', $data);
			$store_id = $this->db->insert_id();
			$items = array(
			    'sno' => $store_id,
			    'count' => 0,
    		);

    		$bill = $this->db->insert('billno', $items); 
			return ($create == true && $bill == true) ? true : false;
		}
	}

	public function update($id = null, $data = array())
	{
		if($id && $data) {
			$this->db->where('id', $id);
			$update = $this->db->update('stores', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id = null)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('stores');
			return ($delete == true) ? true : false;
		}

		return false;
	}

	public function getCompanyData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM company WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

	public function getActiveStore()
	{
		$sql = "SELECT * FROM stores WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

    public function getStoreid($user_id)
	{
		$query = $this->db->query("SELECT * FROM users WHERE id = $user_id");
		$result = $query->result_array();
		foreach($result as $data){
		    $res = $data['store_id'];   
		}
		return $res;
	}
	
	public function countTotalStores()
	{
		$sql = "SELECT * FROM stores WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

	public function getStoresAmountData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM stores WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

}