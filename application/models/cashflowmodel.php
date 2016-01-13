<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CashflowModel extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null || empty($searchparam['cashflow_type_id'])) {
			$count=$this->db->count_all_results('cashflow');
			return $count;
		} else {
			if ($searchparam['cashflow_type_id'] != '') {
				$this->db->where('cashflow_type_id', $searchparam['cashflow_type_id']);
			}
			
			if ($searchparam['cashflow_desc'] != '') {
				$this->db->like('cashflow_desc', $searchparam['cashflow_desc']);
			}
			
			if ($searchparam['bank_account_id'] != '') {
				$this->db->where('bank_account_id', $searchparam['bank_account_id']);
			}
			if ($searchparam['debet_credit'] != '') {
				$this->db->where('debet_credit', $searchparam['debet_credit']);
			}
			$count=$this->db->count_all_results('cashflow');
			return $count;
		}
    }

    public function fetch_cashflow($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('cashflow');
		if (!empty($searchparam['cashflow_type_id'])) {
			$this->db->where('cashflow_type_id', $searchparam['cashflow_type_id']);
		}
		
		if (!empty($searchparam['cashflow_desc'])) {
			$this->db->like('cashflow_desc', $searchparam['cashflow_desc']);
		}
		
		if (!empty($searchparam['bank_account_id'])) {
			$this->db->where('bank_account_id', $searchparam['bank_account_id']);
		}
		if (!empty($searchparam['debet_credit'])) {
			$this->db->where('debet_credit', $searchparam['debet_credit']);
		}
		
		$this->db->join('cashflow_type', 'cashflow.cashflow_type_id = cashflow_type.id');
		$this->db->join('bank_account', 'cashflow.bank_account_id = bank_account.id');
		$this->db->order_by("cashflow.cashflow_id", "desc");
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