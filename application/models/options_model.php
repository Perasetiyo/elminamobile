<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class options_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_options');
			return $count;
		} else {
			
			if (!empty($searchparam['option_code'])) {
				$this->db->like('option_code', $searchparam['option_code']);
			}
			if (!empty($searchparam['option_type'])) {
				$this->db->like('option_type', $searchparam['option_type']);
			}
			if (!empty($searchparam['option_desc'])) {
				$this->db->like('option_desc', $searchparam['option_desc']);
			}
			
			if (!empty($searchparam['option_root_id'])) {
				$this->db->where('option_root_id', $searchparam['option_root_id']);
			}
			
			$count=$this->db->count_all_results('tb_options');
			return $count;
		}
    }

    public function fetch_options($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_options');
		
			if (!empty($searchparam['option_code'])) {
				$this->db->like('option_code', $searchparam['option_code']);
			}
			if (!empty($searchparam['option_type'])) {
				$this->db->like('option_type', $searchparam['option_type']);
			}
			if (!empty($searchparam['option_desc'])) {
				$this->db->like('option_desc', $searchparam['option_desc']);
			}
			
			if (!empty($searchparam['option_root_id'])) {
				$this->db->where('option_root_id', $searchparam['option_root_id']);
			}
			
		$this->db->order_by("option_id", "asc");
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