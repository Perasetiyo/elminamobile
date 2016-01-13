<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Equity_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_equity');
			return $count;
		} else {
			 
			if ($searchparam['equity_type_id'] != '') {
				$this->db->where('equity_type_id', $searchparam['equity_type_id']);
			} 
			
			if ($searchparam['equity_desc'] != '') {
				$this->db->like('equity_desc', $searchparam['equity_desc']);
			}
			
			$count=$this->db->count_all_results('tb_equity');
			return $count;
		}
    }

    public function fetch_equity($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_equity');
		
		if (!empty($searchparam['equity_type_id'])) {
			$this->db->where('equity_type_id', $searchparam['equity_type_id']);
		}
		
		
		if (!empty($searchparam['equity_desc'])) {
			$this->db->like('equity_desc', $searchparam['equity_desc']);
		}
		
		/**
		if (!empty($searchparam['bank_account_id'])) {
			$this->db->where('bank_account_id', $searchparam['bank_account_id']);
		}
		**/
		
		$this->db->join('tb_options', 'tb_equity.equity_type_id = tb_options.option_id','left');
		$this->db->order_by("tb_equity.equity_date", "desc");
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