<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Material_Inventory_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_material_inventory');
			return $count;
		} else {
			 /**
			if (!empty($searchparam['product_id'])) {
				$this->db->where('tb_product.product_id', $searchparam['product_id']);
			}
		
			if ($searchparam['inventory_type_id'] != '') {
				$this->db->where('inventory_type_id', $searchparam['inventory_type_id']);
			} 
			
			if (!empty($searchparam['inventory_desc'])) {
				$this->db->like('inventory_desc', $searchparam['inventory_desc']);
			}
			
			$this->db->join('tb_stock', 'tb_stock.stock_id = tb_inventory.stock_id','left');
			$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
			**/
			
			if (!empty($searchparam['material_code'])) {
				$this->db->like('material_code', $searchparam['material_code']);
			}

			$count=$this->db->count_all_results('tb_material_inventory');
			return $count;
		}
    }

    public function fetch_material_inventory($limit, $start, $searchparam) {
        
		$this->db->select('*, mb.option_desc as material_bahan, mw.option_desc as material_warna');
		$this->db->from('tb_material_inventory');
		/*
		if (!empty($searchparam['product_id'])) {
			$this->db->where('tb_product.product_id', $searchparam['product_id']);
		}
		
		if (!empty($searchparam['inventory_type_id'])) {
			$this->db->where('inventory_type_id', $searchparam['inventory_type_id']);
		}
		*/
		if (!empty($searchparam['material_code'])) {
			$this->db->like('material_code', $searchparam['material_code']);
		}
		
		
		$this->db->join('tb_options mb', 'tb_material_inventory.material_bahan_id = mb.option_id','left');
		$this->db->join('tb_options mw ', 'tb_material_inventory.material_warna_id = mw.option_id','left');
		$this->db->join('tb_options mt', 'tb_material_inventory.material_type_id = mt.option_id','left');
		$this->db->order_by("tb_material_inventory.material_date_init", "desc");
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