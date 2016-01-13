<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Liabilities_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_liabilities');
			return $count;
		} else {
			 
			if (!empty($searchparam['liabilities_type_id'])) { 
				$this->db->where('liabilities_type_id', $searchparam['liabilities_type_id']);
			} 
			
			if (!empty($searchparam['liabilities_cause_id'])) { 
				$this->db->where('liabilities_cause_id', $searchparam['liabilities_cause_id']);
			}
			
			if (!empty($searchparam['liabilities_desc'])) {
				$this->db->like('liabilities_desc', $searchparam['liabilities_desc']);
			}
			
			
			/**
			if ($searchparam['bank_account_id'] != '') {
				$this->db->where('bank_account_id', $searchparam['bank_account_id']);
			}
			**/
			$count=$this->db->count_all_results('tb_liabilities');
			return $count;
		}
    }

    public function fetch_liabilities($limit, $start, $searchparam) {
        
		$this->db->select('*, option_type.option_desc AS type_option_desc, option_cause.option_desc AS cause_option_desc, ');
		$this->db->from('tb_liabilities');
		
		 
		if (!empty($searchparam['liabilities_type_id'])) { 
			$this->db->where('liabilities_type_id', $searchparam['liabilities_type_id']);
		} 
			
		if (!empty($searchparam['liabilities_cause_id'])) { 
			$this->db->where('liabilities_cause_id', $searchparam['liabilities_cause_id']);
		}
			
		if (!empty($searchparam['liabilities_desc'])) {
			$this->db->like('liabilities_desc', $searchparam['liabilities_desc']);
		}
		/**
		if (!empty($searchparam['bank_account_id'])) {
			$this->db->where('bank_account_id', $searchparam['bank_account_id']);
		}
		**/
		$this->db->join('tb_options AS option_type', 'tb_liabilities.liabilities_type_id = option_type.option_id','left');
		$this->db->join('tb_options AS option_cause', 'tb_liabilities.liabilities_cause_id = option_cause.option_id','left');
		$this->db->order_by("tb_liabilities.liabilities_date", "desc");
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