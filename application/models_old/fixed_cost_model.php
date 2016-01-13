<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fixed_cost_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_fixed_cost');
			return $count;
		} else {
			
			if (!empty($searchparam['fixed_cost_name'])) {
				$this->db->like('fixed_cost_name', $searchparam['fixed_cost_name']);
			}
			
			$count=$this->db->count_all_results('tb_fixed_cost');
			return $count;
		}
    }

    public function fetch_fixed_cost($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_fixed_cost');
		
		if (!empty($searchparam['fixed_cost_name'])) {
			$this->db->like('fixed_cost_name', $searchparam['fixed_cost_name']);
		}
		
		$this->db->order_by("tb_fixed_cost.fixed_cost_name", "asc");
		$this->db->join('tb_options','tb_options.option_id = tb_fixed_cost.fixed_cost_type_id','left');
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