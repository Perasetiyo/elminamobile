<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('billing');
			return $count;
		} else {
			
			if (!empty($searchparam['level'])) {
				$this->db->where('billing.level', $searchparam['level']);
			}
		
			if (!empty($searchparam['billing_name'])) {
				$this->db->like('billing_name', $searchparam['billing_name']);
			}
				
			if (!empty($searchparam['billing_phone'])) {
					$this->db->like('billing_phone', $searchparam['billing_phone']);
			}
			
			$count=$this->db->count_all_results('billing');
			return $count;
		}
    }

    public function fetch_customer($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('billing');
		
		if (!empty($searchparam['billing_level'])) {
			$this->db->where('billing.billing_level', $searchparam['billing_level']);
		}
		
		if (!empty($searchparam['billing_name'])) {
				$this->db->like('billing_name', $searchparam['billing_name']);
		}
		
		if (!empty($searchparam['billing_phone'])) {
				$this->db->like('billing_phone', $searchparam['billing_phone']);
		}
		
		$this->db->join('shipper', 'billing.dropshipper_id = shipper.shipper_id','left');
		$this->db->join('tb_options', 'billing.billing_level = tb_options.option_id', 'left');
		$this->db->order_by('billing_name');
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