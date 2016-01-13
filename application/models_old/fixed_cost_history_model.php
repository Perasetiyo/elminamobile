<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fixed_cost_history_model extends CI_Model {
    public function __construct() {
        parent::__construct();
		
		$this->config->load('webservice');
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_fixed_cost_history');
			return $count;
		} else {
			
			if (!empty($searchparam['fixed_cost_history_id'])) {
				$this->db->where('fixed_cost_history_id', $searchparam['fixed_cost_history_id']);
			}
		
			if (!empty($searchparam['month'])) {
				$this->db->where('fixed_cost_history_month', $searchparam['month']);
			}
			
			$count=$this->db->count_all_results('tb_fixed_cost_history');
			return $count;
		}
    }

    public function fetch_fixed_cost_history($limit, $start, $searchparam) {
        
		$this->db->select('*, sum(fixed_cost_history_nominal) as total_fixed_cost_history_nominal');
		$this->db->from('tb_fixed_cost_history');
		
		
		if (!empty($searchparam['fixed_cost_history_id'])) {
			$this->db->where('fixed_cost_history_id', $searchparam['fixed_cost_history_id']);
		}
		
		if (!empty($searchparam['fixed_cost_type_id'])) {
			$this->db->where('fixed_cost_type_id', $searchparam['fixed_cost_type_id']);
		}
			
		if (!empty($searchparam['fixed_cost_name'])) {
				$this->db->like('fixed_cost_name', $searchparam['fixed_cost_name']);
		}
			
		if (!empty($searchparam['month'])) {
			$this->db->where('fixed_cost_history_month', $searchparam['month']);
		}
			
		$this->db->join('tb_fixed_cost', 'tb_fixed_cost.fixed_cost_id = tb_fixed_cost_history.fixed_cost_id','right outer');
		$this->db->order_by("tb_fixed_cost_history.fixed_cost_history_month", "desc");
		$this->db->group_by("tb_fixed_cost.fixed_cost_id, tb_fixed_cost_history.fixed_cost_history_month");
		
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
