<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OrderModel extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }
	
	public function get_estimated_weight($order_id) {
		// get estimated weight for cart
		$this->db->where('order_id',$order_id);
		$this->db->join('tb_stock', 'tb_stock.stock_id = tb_cart.stock_id');
		$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
		$queryCart=$this->db->get('tb_cart');
			
		$total_weight = 0;
		if ($queryCart->num_rows > 0) {
			foreach ($queryCart->result() as $row) {
				$weight = $row->product_weight * $row->cart_qty;
				$total_weight = $total_weight + $weight;
			}
		}
		return $total_weight;
	}		

    public function record_count($searchparam) {
        if ($searchparam != null && !empty($searchparam['billing_name'])) {
			$this->db->like('billing.billing_name', $searchparam['billing_name']);
		}
		
		$this->db->join('billing', 'orders.billing_id = billing.billing_id');
		$this->db->join('shipper', 'orders.shipper_id = shipper.shipper_id');
		$count=$this->db->count_all_results('orders');
		return $count;
    }

	public function fetch_orders_complete($limit, $start, $searchparam) {
        $this->db->limit($limit, $start);
	
	
		// ================= fetching order complete
		$ordercomplete = $this->db->select('*');
		$ordercomplete = $this->db->from('orders');
		$ordercomplete = $this->db->join('billing', 'orders.billing_id = billing.billing_id');
		$ordercomplete = $this->db->join('shipper', 'orders.shipper_id = shipper.shipper_id');
		$ordercomplete = $this->db->where('orders.order_status', 2);
		$ordercomplete = $this->db->where('orders.package_status', 1);
		
		if (!empty($searchparam['billing_name'])) {
			$ordercomplete = $this->db->like('billing.billing_name', $searchparam['billing_name']);
		}
		
		if (!empty($searchparam['billing_phone'])) {
			$ordercomplete = $this->db->like('billing.billing_phone', $searchparam['billing_phone']);
		}
		
		if (!empty($searchparam['billing_kec'])) {
			$ordercomplete = $this->db->like('billing.billing_kec', $searchparam['billing_kec']);
		}
		
		$ordercomplete = $this->db->order_by("orders.order_date", "desc"); 
		$querycomplete=$ordercomplete->get();
		
		if ($querycomplete->num_rows() > 0) {
			foreach ($querycomplete->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
    public function fetch_orders_pending($limit, $start, $searchparam) {
        $this->db->limit($limit, $start);
        
		// ================= fetching order pending
		$wherepending = '(orders.order_status < 2 OR orders.package_status=0)';
		
		$orderpending = $this->db->select('*');
		$orderpending = $this->db->from('orders');
		$orderpending = $this->db->join('billing', 'orders.billing_id = billing.billing_id');
		$orderpending = $this->db->join('shipper', 'orders.shipper_id = shipper.shipper_id');
		$orderpending = $this->db->join('tb_options', 'orders.order_channel = tb_options.option_id', 'left');
		
		$orderpending = $this->db->where($wherepending);
		if (!empty($searchparam['billing_name'])) {
			$orderpending = $this->db->like('billing.billing_name', $searchparam['billing_name']);
		}
		
		if (!empty($searchparam['billing_phone'])) {
			$orderpending = $this->db->like('billing.billing_phone', $searchparam['billing_phone']);
		}
		
		if (!empty($searchparam['billing_kec'])) {
			$orderpending = $this->db->like('billing.billing_kec', $searchparam['billing_kec']);
		}
		
		$orderpending = $this->db->order_by("orders.order_date", "desc");
		$querypending=$orderpending->get();
		
		if ($querypending->num_rows() > 0) {
			foreach ($querypending->result() as $row) {
				$estimated_weight = $this->get_estimated_weight($row->id);
				$row->estimated_weight = $estimated_weight;
				$data[] = $row;
			}
			return $data;
		}
		return false;
   }
   
   public function fetch_all_orders($limit, $start, $searchparam) {
        $this->db->limit($limit, $start);
        
		// ================= fetching order pending
		
		$allorder = $this->db->select('*');
		$allorder = $this->db->from('orders');
		$allorder = $this->db->join('billing', 'orders.billing_id = billing.billing_id');
		$allorder = $this->db->join('shipper', 'orders.shipper_id = shipper.shipper_id');
		
		if (!empty($searchparam['billing_name'])) {
			$allorder = $this->db->like('billing.billing_name', $searchparam['billing_name']);
		}
		
		$allorder = $this->db->order_by("orders.order_date", "desc");
		$query = $allorder->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
   }
}