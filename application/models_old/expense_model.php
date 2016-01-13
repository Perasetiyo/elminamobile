<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expense_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_expense');
			return $count;
		} else {
			if (!empty($searchparam['expense_type_id'])) {
				$this->db->where('expense_type_id', $searchparam['expense_type_id']);
			} 
			
			if (!empty($searchparam['expense_desc'])) {
				$this->db->like('expense_desc', $searchparam['expense_desc']);
			}
			
			if (!empty($searchparam['bank_account_id'])) {
				$this->db->where('bank_account_id', $searchparam['bank_account_id']);
			}
			
			$count=$this->db->count_all_results('tb_expense');
			return $count;
		}
    }

    public function fetch_expense($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_expense');
		
		if (!empty($searchparam['expense_type_id'])) {
			$this->db->where('expense_type_id', $searchparam['expense_type_id']);
		}
		
		if (!empty($searchparam['expense_desc'])) {
			$this->db->like('expense_desc', $searchparam['expense_desc']);
		}
		
		if (!empty($searchparam['bank_account_id'])) {
			$this->db->where('bank_account_id', $searchparam['bank_account_id']);
		}
		
		$this->db->join('tb_options', 'tb_expense.expense_type_id = tb_options.option_id','left');
		$this->db->join('bank_account', 'tb_expense.bank_account_id = bank_account.id','left');
		$this->db->order_by("tb_expense.expense_date", "desc");
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