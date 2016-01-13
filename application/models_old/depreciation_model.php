<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Depreciation_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_ppe');
			return $count;
		} else {
			/** 
			if ($searchparam['depreciation_type_id'] != '') {
				$this->db->where('depreciation_type_id', $searchparam['depreciation_type_id']);
			} 
			**/
			
			if (!empty($searchparam['ppe_desc'])) {
				$this->db->like('ppe_desc', $searchparam['ppe_desc']);
			}
			
			if (!empty($searchparam['ppe_id'])) {
				$this->db->where('ppe_id', $searchparam['ppe_id']);
			}
			
			$count=$this->db->count_all_results('tb_ppe');
			return $count;
		}
    }

    public function fetch_depreciation($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_ppe');
		
		if (!empty($searchparam['ppe_desc'])) {
			$this->db->like('ppe_desc', $searchparam['ppe_desc']);
		}
		
		if (!empty($searchparam['ppe_id'])) {
			$this->db->where('ppe_id', $searchparam['ppe_id']);
		}
		
		$this->db->order_by("tb_ppe.ppe_desc");
		$this->db->limit($limit, $start);
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				
				$this->db->where("ppe_id", $row->ppe_id);
				$this->db->order_by("depreciation_date", "desc");
				$this->db->limit(1);
				$query_depreciation = $this->db->get('tb_depreciation');
				
				
				$row->last_depreciation_date = $row->ppe_date;
				if ($query_depreciation->num_rows() > 0) {
					foreach ($query_depreciation->result() as $row2) {
						$row->last_depreciation_date = $row2->depreciation_date;
					}
				}
				$data[] = $row;
				
				
			}
			return $data;
		}
		return false;
   }
}