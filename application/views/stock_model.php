<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_stock');
			return $count;
		} else {
			
			if ($searchparam['stock_desc'] != '') {
				$this->db->like('stock_desc', $searchparam['stock_desc']);
			}
			
			$count=$this->db->count_all_results('tb_stock');
			return $count;
		}
    }

    public function fetch_stock($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_stock');
		
		if (!empty($searchparam['product_id'])) {
			$this->db->where('tb_product.product_id', $searchparam['product_id']);
		}
		
		if (!empty($searchparam['stock_desc'])) {
			$this->db->like('stock_desc', $searchparam['stock_desc']);
		}
		/**
		if (!empty($searchparam['bank_account_id'])) {
			$this->db->where('bank_account_id', $searchparam['bank_account_id']);
		}
		**/
		$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id','left');
		$this->db->order_by("tb_product.product_name", "desc");
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