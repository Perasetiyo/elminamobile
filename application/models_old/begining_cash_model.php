<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Begining_cash_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_begining_cash');
			return $count;
		} else {
			
			if (!empty($searchparam['period'])) {
				
			}
			
			$count=$this->db->count_all_results('tb_begining_cash');
			return $count;
		}
    }

    public function fetch_begining_cash($limit, $start, $searchparam) {
        
		$this->db->select('begining_cash_id, begining_cash_period, begining_cash_year, sum(begining_cash_nominal) as total_nominal');
		$this->db->from('tb_begining_cash');
		
		if (!empty($searchparam['period'])) {
				
			}
			
		$this->db->group_by ('begining_cash_period');
		$this->db->group_by ('begining_cash_year');
		$this->db->limit($limit, $start);
		$this->db->order_by ('begining_cash_year','desc');
		$this->db->order_by ('begining_cash_period','desc');
		
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
   }
   
    public function fetch_nominal_per_bank($searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_begining_cash');
		$this->db->join('bank_account', 'tb_begining_cash.bank_account_id = bank_account.id');
		
		$this->db->where('begining_cash_period', $searchparam['period']);		
		$this->db->where('begining_cash_year', $searchparam['year']);
		
		$this->db->order_by ('bank_account_name');
		
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