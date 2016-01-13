<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cash_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_cash');
			return $count;
		} else {
			 
			if (!empty($searchparam['cash_type_id'])) {
				$this->db->where('cash_type_id', $searchparam['cash_type_id']);
			} 
			
			if (!empty($searchparam['period'])) {
				$this->db->where('MONTH(cash_date)', $searchparam['period']);
			}
			
			if (!empty($searchparam['cash_desc'])) {
				$this->db->like('cash_desc', $searchparam['cash_desc']);
			}
			
			if (!empty($searchparam['bank_account_id'])) {
				$this->db->where('bank_account_id', $searchparam['bank_account_id']);
			}
			
			$count=$this->db->count_all_results('tb_cash');
			return $count;
		}
    }

    public function fetch_cash($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_cash');
		
		if (!empty($searchparam['cash_type_id'])) {
			$this->db->where('cash_type_id', $searchparam['cash_type_id']);
		} 
			
		if (!empty($searchparam['period'])) {
			$this->db->where('MONTH(cash_date)', $searchparam['period']);
		}
			
		if (!empty($searchparam['cash_desc'])) {
			$this->db->like('cash_desc', $searchparam['cash_desc']);
		}
		
		if (!empty($searchparam['bank_account_id'])) {
			$this->db->where('bank_account_id', $searchparam['bank_account_id']);
		}
		
		if (!empty($searchparam['bank_account_id'])) {
			$this->db->where('bank_account_id', $searchparam['bank_account_id']);
		}
		
		if (!empty($searchparam['startdate']) && $searchparam['startdate'] != '1970-01-01' && 
			!empty($searchparam['enddate']) && $searchparam['enddate'] != '1970-01-01') {
			$this->db->where('cash_date >=',$searchparam['startdate']);
			$this->db->where('cash_date <=',$searchparam['enddate']);
		}
		
		$this->db->join('tb_options', 'tb_cash.cash_type_id = tb_options.option_id','left');
		$this->db->join('bank_account', 'tb_cash.bank_account_id = bank_account.id','left');
		$this->db->order_by("tb_cash.cash_date", "asc");
		//$this->db->limit($limit, $start);
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