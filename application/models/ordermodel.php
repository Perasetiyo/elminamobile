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
        if (!empty($searchparam['billing_name'])) {
			$this->db->like('billing.billing_name', $searchparam['billing_name']);
		}
		
		if (!empty($searchparam['billing_phone'])) {
			$this->db->like('billing.billing_phone', $searchparam['billing_phone']);
		}
		
		if (!empty($searchparam['billing_kec'])) {
			$this->db->like('billing.billing_kec', $searchparam['billing_kec']);
		}
		
		if (!empty($searchparam['billing_city'])) {
			$this->db->like('billing.billing_city', $searchparam['billing_city']);
		}
		
		if (!empty($searchparam['shipper_name'])) {
			$this->db->like('shipper.billing_name', $searchparam['shipper_name']);
		}
		
		$this->db->join('billing', 'orders.billing_id = billing.billing_id');
		$this->db->join('billing as shipper', 'orders.shipper_id = shipper.billing_id');
		$count=$this->db->count_all_results('orders');
		return $count;
    }

	public function fetch_orders_complete($limit, $start, $searchparam) {
		
		$session_data = $this->session->userdata('logged_in');
        if ($session_data['user_role'] <> 'member') { 
			$this->db->limit($limit/8, $start);
		} else {
		    $this->db->limit($limit, $start);
		}
	
		// ================= fetching order complete
		$ordercomplete = $this->db->select('*,  billing.billing_id as bill_id, billing.billing_name as bill_name, shipper.billing_name as shipper_name');
		$ordercomplete = $this->db->from('orders');
		$ordercomplete = $this->db->join('billing', 'orders.billing_id = billing.billing_id');
		$ordercomplete = $this->db->join('billing as shipper', 'orders.shipper_id = shipper.billing_id', 'left');
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
		
		if (!empty($searchparam['billing_city'])) {
			$ordercomplete = $this->db->like('billing.billing_city', $searchparam['billing_city']);
		}
		
		if (!empty($searchparam['shipper_name'])) {
			$ordercomplete = $this->db->like('shipper.billing_name', $searchparam['shipper_name']);
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
		
		$this->db->select('*, billing.billing_id as bill_id, billing.billing_name as bill_name, shipper.billing_name as shipper_name,
						billing.billing_kec as bill_kec,
						billing.billing_city as bill_city');
		$this->db->from('orders');
		$this->db->join('billing', 'orders.billing_id = billing.billing_id');
		$this->db->join('billing as shipper', 'orders.shipper_id = shipper.billing_id');
		$this->db->join('tb_options', 'orders.order_channel = tb_options.option_id', 'left');
		
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
		
		if (!empty($searchparam['billing_city'])) {
			$orderpending = $this->db->like('billing.billing_city', $searchparam['billing_city']);
		}
		
		if (!empty($searchparam['shipper_name'])) {
			$orderpending = $this->db->like('shipper.billing_name', $searchparam['shipper_name']);
		}
		
		$orderpending = $this->db->order_by("orders.order_date", "desc");
		$querypending=$orderpending->get();
		
		if ($querypending->num_rows() > 0) {
			foreach ($querypending->result() as $row) {
				$estimated_weight = $this->get_estimated_weight($row->id);
				$row->estimated_weight = $estimated_weight;

				// get cart
				$cart_table = '';
				$this->db->where('order_id', $row->id);
				$this->db->join('tb_stock', 'tb_stock.stock_id = tb_cart.stock_id');
				$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
														
				$queryCart = $this->db->get('tb_cart');
				$cart_in_html = '';
				$total_qty = 0;
				$subtotal = 0;
				if ($queryCart->num_rows > 0) {
					$cart = array(null);
					foreach ($queryCart->result() as $rowCart) {
						if ($rowCart->cart_qty > 0) {
							$cart_in_html = '<tr>
												<td >'.$rowCart->product_name.' - '.$rowCart->stock_desc.'</td>
												<td align="center">'.$rowCart->cart_qty.'</td>
												';
							if ($row->price_level == 1) {
								$cart_in_html .= '<td align="center">'.$rowCart->current_special_price.'</td>
													<td align="right">'.$rowCart->current_special_price * $rowCart->cart_qty.'</td>
											</tr>';
								 $subtotal += $rowCart->current_special_price * $rowCart->cart_qty;											
							} else if ($row->price_level == 2) {
								$cart_in_html .= '<td align="center">'.$rowCart->current_wholesale_price.'</td>
													 <td align="right">'.$rowCart->current_wholesale_price * $rowCart->cart_qty.'</td>
											</tr>';
								 $subtotal += $rowCart->current_wholesale_price * $rowCart->cart_qty;							
							} else {
								$cart_in_html .= '<td align="center">'.$rowCart->stock_price.'</td>
													 <td align="right">'.$rowCart->stock_price * $rowCart->cart_qty.'</td>
											 </tr>';
								 $subtotal += $rowCart->stock_price * $rowCart->cart_qty;											
							}
							
							array_push($cart, $cart_in_html);
																
							$total_qty = $total_qty + $rowCart->cart_qty;
															
						}
					}
					sort($cart);
					foreach ($cart as $sorted_cart) {
						$cart_table .= $sorted_cart;
					}
					$row->cart_table = $cart_table;
					$row->total_qty = $total_qty;
					$row->subtotal = $subtotal;
					//echo '<br/>--------------------'.$row->cart_table;
				}
				$data[] = $row;
			}
			return $data;
		}
		return false;
   }
   
   public function fetch_all_orders($limit, $start, $searchparam) {
        $this->db->limit($limit, $start);
        
		// ================= fetching order pending
		
		$allorder = $this->db->select('billing.*, orders.*, shipper.billing_name as shipper_name');
		$allorder = $this->db->from('orders');
		$allorder = $this->db->join('billing', 'orders.billing_id = billing.billing_id');
		$allorder = $this->db->join('billing as shipper', 'orders.shipper_id = shipper.billing_id','left');
		
		if (!empty($searchparam['billing_name'])) {
			$allorder = $this->db->like('billing.billing_name', $searchparam['billing_name']);
		}
		
		if (!empty($searchparam['billing_phone'])) {
			$allorder = $this->db->like('billing.billing_name', $searchparam['billing_phone']);
		}
		
		if (!empty($searchparam['billing_id'])) {
			$allorder = $this->db->where('orders.billing_id', $searchparam['billing_id']);
		}
		
		if (!empty($searchparam['shipper_name'])) {
			$allorder = $this->db->where('shipper.billing_name', $searchparam['shipper_name']);
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
