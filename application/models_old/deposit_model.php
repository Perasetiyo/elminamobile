<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_deposit');
			return $count;
		} else {
			
			if ($searchparam['deposit_desc'] != '') {
				$this->db->like('deposit_desc', $searchparam['deposit_desc']);
			}
			
			if ($searchparam['billing_name'] != '') {
				$this->db->like('billing_name', $searchparam['billing_name']);
			}
			
			$count=$this->db->count_all_results('tb_deposit');
			return $count;
		}
    }

    public function fetch_deposit($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_deposit');
		
		if (!empty($searchparam['deposit_desc'])) {
			$this->db->like('deposit_desc', $searchparam['deposit_desc']);
		}
		
		if (!empty($searchparam['billing_name'])) {
			$this->db->like('billing_name', $searchparam['billing_name']);
		}
		
		$this->db->join('billing', 'billing.billing_id = tb_deposit.billing_id');
		$this->db->order_by("tb_deposit.deposit_date", "desc");
		$this->db->limit($limit, $start);
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
   }
}