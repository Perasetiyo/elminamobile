<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agent_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('billing');
			return $count;
		} else {
		
			if (!empty($searchparam['billing_name'])) {
				$this->db->like('billing.billing_name', $searchparam['billing_name']);
			}
				
			if (!empty($searchparam['billing_city'])) {
					$this->db->like('billing.billing_city', $searchparam['billing_city']);
			}
			
			if (!empty($searchparam['agen_status'])) {
					$this->db->where('billing.billing_flag1', $searchparam['agen_status']);
			}
			
			if (!empty($searchparam['billing_level'])) {
					$this->db->where('billing.billing_level', $searchparam['billing_level']);
			} else {			
				$this->db->where('billing.billing_level', 47); // level agent
				$this->db->or_where('billing.billing_level', 46); // level reseller
			}
			
			$count=$this->db->count_all_results('billing');
			return $count;
		}
    }

    public function fetch_agent($limit, $start, $searchparam) {
        
		$this->db->select( 'billing.*, 
							level.option_desc as level_desc, flag1.option_desc as flag1_desc, prov.option_desc as prov_desc, upline.billing_name as upline_name');
		$this->db->from('billing');
		
		if (!empty($searchparam['billing_name'])) {
				$this->db->like('billing.billing_name', $searchparam['billing_name']);
		}
		
		
		if (!empty($searchparam['billing_city'])) {
				$this->db->like('billing.billing_city', $searchparam['billing_city']);
		}
		
		
		if (!empty($searchparam['agen_status'])) {
				$this->db->where('billing.billing_flag1', $searchparam['agen_status']);
		}
		
		
		if (!empty($searchparam['billing_level'])) {
				$this->db->where('billing.billing_level', $searchparam['billing_level']);
		} else {			
			$this->db->where('billing.billing_level', 47); // level agent
			$this->db->or_where('billing.billing_level', 46); // level reseller
		}
		
		$this->db->join('billing as upline', 'upline.billing_id = billing.billing_upline','left');
		$this->db->join('tb_options as flag1', 'billing.billing_flag1 = flag1.option_id');
		$this->db->join('tb_options as level', 'billing.billing_level = level.option_id');
		$this->db->join('tb_options as prov', 'billing.billing_prov = prov.option_id', 'left');
		$this->db->order_by('billing.billing_name');
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
