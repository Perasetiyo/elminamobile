<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_inventory');
			return $count;
		} else {
			/** 
			if ($searchparam['inventory_type_id'] != '') {
				$this->db->where('inventory_type_id', $searchparam['inventory_type_id']);
			} 
			**/
			
			if (!empty($searchparam['inventory_desc'])) {
				$this->db->like('inventory_desc', $searchparam['inventory_desc']);
			}
			
			$count=$this->db->count_all_results('tb_inventory');
			return $count;
		}
    }

    public function fetch_inventory($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_inventory');
		/**
		if (!empty($searchparam['inventory_type_id'])) {
			$this->db->where('inventory_type_id', $searchparam['inventory_type_id']);
		}
		**/
		
		if (!empty($searchparam['inventory_desc'])) {
			$this->db->like('inventory_desc', $searchparam['inventory_desc']);
		}
		
		$this->db->join('tb_options', 'tb_inventory.inventory_type_id = tb_options.option_id','left');
		$this->db->join('tb_stock', 'tb_stock.stock_id = tb_inventory.stock_id','left');
		$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id','left');
		$this->db->order_by("tb_inventory.inventory_date", "desc");
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