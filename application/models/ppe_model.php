<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PPE_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_ppe');
			return $count;
		} else {
			/** 
			if ($searchparam['ppe_type_id'] != '') {
				$this->db->where('ppe_type_id', $searchparam['ppe_type_id']);
			} 
			**/
			
			if (!empty($searchparam['ppe_desc'])) {
				$this->db->like('ppe_desc', $searchparam['ppe_desc']);
			}
			
			if (!empty($searchparam['ppe_type_id'])) {
				$this->db->where('ppe_type_id', $searchparam['ppe_type_id']);
			}
			
			$count=$this->db->count_all_results('tb_ppe');
			return $count;
		}
    }

    public function fetch_ppe($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_ppe');
		/**
		if (!empty($searchparam['ppe_type_id'])) {
			$this->db->where('ppe_type_id', $searchparam['ppe_type_id']);
		}
		**/
		
		if (!empty($searchparam['ppe_desc'])) {
			$this->db->like('ppe_desc', $searchparam['ppe_desc']);
		}
		
		if (!empty($searchparam['ppe_type_id'])) {
			$this->db->where('ppe_type_id', $searchparam['ppe_type_id']);
		}
		
		$this->db->join('tb_options', 'tb_ppe.ppe_type_id = tb_options.option_id','left');
		$this->db->order_by("tb_ppe.ppe_date", "desc");
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