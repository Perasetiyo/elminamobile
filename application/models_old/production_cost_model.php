<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Production_cost_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_production_cost');
			return $count;
		} else {
			if (!empty($searchparam['production_cost_type_id'])) {
				$this->db->where('production_cost_type_id', $searchparam['production_cost_type_id']);
			} 
			
			if (!empty($searchparam['production_cost_desc'])) {
				$this->db->like('production_cost_desc', $searchparam['production_cost_desc']);
			}
			
			if (!empty($searchparam['bank_account_id'])) {
				$this->db->where('bank_account_id', $searchparam['bank_account_id']);
			}
			
			$count=$this->db->count_all_results('tb_production_cost');
			return $count;
		}
    }

    public function fetch_production_cost($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_production_cost');
		
		if (!empty($searchparam['production_cost_type_id'])) {
			$this->db->where('production_cost_type_id', $searchparam['production_cost_type_id']);
		}
		
		if (!empty($searchparam['production_cost_desc'])) {
			$this->db->like('production_cost_desc', $searchparam['production_cost_desc']);
		}
		
		if (!empty($searchparam['bank_account_id'])) {
			$this->db->where('bank_account_id', $searchparam['bank_account_id']);
		}
		
		$this->db->join('tb_options', 'tb_production_cost.production_cost_type_id = tb_options.option_id','left');
		$this->db->join('bank_account', 'tb_production_cost.bank_account_id = bank_account.id','left');
		$this->db->order_by("tb_production_cost.production_cost_date", "desc");
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