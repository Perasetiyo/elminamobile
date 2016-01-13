<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart_stats_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count = 12;
			return $count;
		} else {
			$count = 12;
			return $count;
		}
    }

    public function fetch_stock($limit, $start, $searchparam) {
        
		// MONTHLY
		/**
		select tb_product.product_id, MONTH(order_date), sum(cart_qty), sum(`cart_amount`), orders.package_status from tb_cart
		join orders, tb_stock, tb_product
		where orders.id = tb_cart.order_id
		and tb_stock.stock_id = tb_cart.stock_id
		and tb_stock.product_id = tb_product.product_id
		and tb_product.product_id = 39
		
		group by MONTH(order_date), orders.package_status
					
		*/
		$this->db->select('tb_product.product_name, MONTH(order_date) as month, 
							sum(cart_qty) as total_qty, sum(cart_amount) as total_amount,
							orders.package_status');
		$this->db->from('tb_cart');
					
		$this->db->join('orders', 'orders.id = tb_cart.order_id','left');
		$this->db->join('tb_stock', 'tb_stock.stock_id = tb_cart.stock_id');
		$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
					
		if (!empty($searchparam['product_id'])) {
			$this->db->where('tb_product.product_id', $searchparam['product_id']);
		}
		
		//$this->db->where('orders.package_status', 1);
		$this->db->group_by('MONTH(order_date), orders.package_status');
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			
			foreach ($query->result() as $row) {
			
				$this->db->select('*');
				$this->db->from('tb_reject_cart');
				$this->db->join('tb_reject', 'tb_reject.reject_id = tb_reject_cart.reject_id');
				$this->db->join('tb_stock', 'tb_stock.stock_id = tb_reject_cart.stock_id');
				$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
		
				if (!empty($searchparam['product_id'])) {
					$this->db->where('tb_product.product_id', $searchparam['product_id']);
				}
				$this->db->where('MONTH(reject_date)', $row->month);
				$query_reject=$this->db->get();
				
				$reject_qty = 0;
				$row->reject_qty = $reject_qty;
				if ($query_reject->num_rows() > 0) {
					foreach ($query_reject->result() as $row2) {
						$reject_qty += $row2->reject_cart_qty;
					}
					$row->reject_qty = $reject_qty;
				}
				$data[] = $row;
			}
			
				
			return $data;
		}
		return false;
   }
}