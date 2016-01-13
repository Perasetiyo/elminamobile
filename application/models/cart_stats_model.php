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
	
	public function fetch_trends($searchparam) {	
		/**
		select o.order_date, sum(c.cart_qty) 
		from tb_cart c, tb_stock s, tb_product p, orders o
		where c.stock_id = s.stock_id
		and s.product_id = p.product_id
		and c.order_id = o.id
		and tb_stock.stock_id = 236
		and o.order_date >= '2014-02-01'
		and o.order_date <= '2015-02-28'
		group by o.order_date
		order by o.order_date
		*/
		$data = null;
		
		$begin = new DateTime($searchparam['startdate']);
		$end = new DateTime($searchparam['enddate']);

		/**
		$interval = DateInterval::createFromDateString('1 day');
		
		if (!empty($searchparam['interval'])) {
			if($searchparam['interval'] == 'MONTH') {
				$interval = DateInterval::createFromDateString('1 month');
			} else if($searchparam['interval'] == 'WEEK') {
				$interval = DateInterval::createFromDateString('1 week');
			}
		}
		**/
		
		//$period = new DatePeriod($begin, $interval, $end);

		//foreach ($period as $dt) {
			
			$this->db->select('o.order_date as dates,  sum(cart_qty) as total_qty, sum(cart_amount) as total_omset');
			
			$this->db->from('tb_cart');
						
			$this->db->join('tb_stock', 'tb_cart.stock_id = tb_stock.stock_id');
			$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
			$this->db->join('orders as o', 'tb_cart.order_id = o.id');
			
			if (!empty($searchparam['product_id']) && $searchparam['product_id'] > 0) {
				$this->db->where('tb_product.product_id', $searchparam['product_id']);
			} else if (!empty($searchparam['stock_id']) && $searchparam['stock_id'] > 0) {			
				$this->db->where('tb_stock.stock_id', $searchparam['stock_id']);
			}
			
			if (!empty($searchparam['stockname'])) {
				$this->db->like('tb_stock.stock_desc', $searchparam['stockname']);
			}
			
			// order date
			//$dt->dates = $dt->format("Y-m-d");
			//$this->db->where('order_date',$dt->dates);
			$this->db->where('order_date >=',$searchparam['startdate']);
			$this->db->where('order_date <=',$searchparam['enddate']);
			
			if (!empty($searchparam['interval'])) {
				if($searchparam['interval'] == 'MONTH') {
					$this->db->group_by('MONTH(order_date)');
				} else if($searchparam['interval'] == 'WEEK') {
					$this->db->group_by('WEEK(order_date)');
				} else {
					$this->db->group_by('order_date');
				}
			} else {
					$this->db->group_by('order_date');
			}
			
			$this->db->order_by('order_date', 'asc');
			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					//$startdate 	= date('Y-m-d', strtotime($this->input->post('startdate')));
					
					if ($searchparam['interval'] == 'MONTH') {
						$this->db->where('MONTH(goal_date)',date('m', strtotime($row->dates)));
						$this->db->where('YEAR(goal_date)',date('Y', strtotime($row->dates)));
						
						$query_goal = $this->db->get('tb_goal');
						if ($query_goal->num_rows() > 0) {
							$row->target_monthly = $query_goal->row()->goal_nominal;
						} else {
							$row->target_monthly = 0;
						}
						
					}
					
					$row->dates = date('d-M-Y', strtotime($row->dates));
					
					$data[] = $row;
				}
			}
		
		return $data;
	
		//return false;
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
   
   public function fetch_top_cart($searchparam) {
        
		// 
		/**
		SELECT p.product_name, s.stock_desc, sum(c.cart_qty) as sold_qty
		FROM `tb_cart` c, tb_product p, tb_stock s, orders o
		where o.id = c.order_id
		AND c.stock_id = s.stock_id
		AND s.product_id = p.product_id
		AND c.order_id in (
			SELECT id FROM `orders` 
			where order_date >= DATE(NOW() - INTERVAL 7 DAY)
			)
		group by c.stock_id
		order by sold_qty desc
					
		*/
		$this->db->select('tb_product.product_name, tb_stock.stock_desc, tb_stock.stock_id , sum(cart_qty) as sold_qty, tb_stock.stock_date');
		$this->db->from('tb_cart');
					
		$this->db->join('orders', 'orders.id = tb_cart.order_id');
		$this->db->join('tb_stock', 'tb_stock.stock_id = tb_cart.stock_id');
		$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
			
		$this->db->where('tb_cart.order_id IN (
									SELECT id FROM orders
									where order_date BETWEEN \''.$searchparam['startdate'].'\' and \''.$searchparam['enddate'].'\')', NULL, FALSE);
		
		$this->db->group_by('tb_cart.stock_id');
		$this->db->order_by('sold_qty', 'desc');
		
		$query_top_cart = $this->db->get();
		
		if ($query_top_cart->num_rows() > 0) {
			
			foreach ($query_top_cart->result() as $row) {
				
				$this->db->select('*');
				$this->db->from('tb_stock');
				
				$this->db->where('tb_stock.stock_id', $row->stock_id);
				
				$query_stock=$this->db->get();
				$stock_qty = $query_stock->row()->stock_qty;
				
				$row->stock_qty = $stock_qty;
				
				$data[] = $row;
			}
			return $data;
		}
		return false;
   }
}
