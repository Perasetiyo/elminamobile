<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_stats_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('billing');
			return $count;
		} else {
		
		if (!empty($searchparam['billing_name'])) {
			$this->db->like('billing_name', $searchparam['billing_name']);
		}
			
			
			$count=$this->db->count_all_results('billing');
			return $count;
		}
    }

    public function fetch_customer_rank($limit, $start, $searchparam) {
        
		/**$sql = "select b.*, sum(total_amount - exp_cost) as nominal, count(o.billing_id) from orders o, billing b
					where b.billing_id = o.billing_id
					group by o.billing_id
					order by nominal desc";

			$query = $this->db->query($sql);
			
			// NEW VERSION 20150323
			select billing_id, billing_name, 
			(select sum(total_amount - exp_cost) as nominal from orders where billing_id = 771) as billing_nominal, 
			(select count(*) from orders where billing_id = 771) as billing_count, 
			(select sum(total_amount - exp_cost) as nominal from orders where shipper_id = 771) as dropship_nominal,
			(select count(*) from orders where shipper_id = 771) as shipper_count
			from billing 
			where billing_id = 771

		*/
		
		if (!empty($searchparam['billing_id'])) {		
			$this->db->where('billing_id', $searchparam['billing_id']);
		}
		$this->db->where('billing_level',47);
		$this->db->where('billing_flag1',102);
		//$this->db->limit($limit, $start);
		$billings = $this->db->get('billing');
		
		if ($billings->num_rows() > 0) {
			foreach ($billings->result() as $billing) {
				// BILLING NOMINAL
				$where_billing_nominal = 'select sum(total_amount - exp_cost) as nominal 
									from orders 
									where billing_id = '.$billing->billing_id.' ';
				if (!empty($searchparam['startdate']) && $searchparam['startdate'] != '1970-01-01' && 
					!empty($searchparam['enddate']) && $searchparam['enddate'] != '1970-01-01') {
					$where_billing_nominal .='and order_date >= "'.$searchparam['startdate'].'"
									  and order_date <= "'.$searchparam['enddate'].'"';
				}
					
				// BILLING COUNT
				$where_billing_count = 'select count(*) 
									from orders 
									where billing_id = '.$billing->billing_id.' ';
				if (!empty($searchparam['startdate']) && $searchparam['startdate'] != '1970-01-01' && 
				!empty($searchparam['enddate'])&& $searchparam['enddate'] != '1970-01-01') {
					$where_billing_count .='and order_date >= "'.$searchparam['startdate'].'"
									  and order_date <= "'.$searchparam['enddate'].'"';
				}
				// DROPSHIP NOMINAL
				$where_dropship_nominal = 'select sum(total_amount - exp_cost) as nominal 
									from orders 
									where shipper_id = '.$billing->billing_id.' ';
				if (!empty($searchparam['startdate']) && $searchparam['startdate'] != '1970-01-01' && 
					!empty($searchparam['enddate']) && $searchparam['enddate'] != '1970-01-01') {
					$where_dropship_nominal .='and order_date >= "'.$searchparam['startdate'].'"
									  and order_date <= "'.$searchparam['enddate'].'"';
				}
					
				// DROPSHIP COUNT
				$where_dropship_count = 'select count(*) 
									from orders 
									where shipper_id = '.$billing->billing_id.' ';
				if (!empty($searchparam['startdate']) && $searchparam['startdate'] != '1970-01-01' && 
				!empty($searchparam['enddate'])&& $searchparam['enddate'] != '1970-01-01') {
					$where_dropship_count .='and order_date >= "'.$searchparam['startdate'].'"
									  and order_date <= "'.$searchparam['enddate'].'"';
				}
				
				// RESELLER NOMINAL
				$where_reseller_nominal = 'select sum(total_amount - exp_cost) as nominal 
									from orders, billing as shipper
									where orders.shipper_id = shipper.billing_id 
									and shipper.billing_upline = '.$billing->billing_id.' ';
				if (!empty($searchparam['startdate']) && $searchparam['startdate'] != '1970-01-01' && 
					!empty($searchparam['enddate']) && $searchparam['enddate'] != '1970-01-01') {
					$where_reseller_nominal .='and order_date >= "'.$searchparam['startdate'].'"
									  and order_date <= "'.$searchparam['enddate'].'"';
				}
					
				// RESELLER COUNT
				$where_reseller_count = 'select count(*) 
									from orders, billing as shipper
									where orders.shipper_id = shipper.billing_id 
									and shipper.billing_upline = '.$billing->billing_id.' ';
				if (!empty($searchparam['startdate']) && $searchparam['startdate'] != '1970-01-01' && 
				!empty($searchparam['enddate'])&& $searchparam['enddate'] != '1970-01-01') {
					$where_reseller_count .='and order_date >= "'.$searchparam['startdate'].'"
									  and order_date <= "'.$searchparam['enddate'].'"';
				}	
				/**
				// PRODUCT_SOLD_QTY
				$all_order_id = 'select id
									from orders, billing as shipper
									where orders.shipper_id = shipper.billing_id 
									and shipper.billing_upline = '.$billing->billing_id.' 
									or orders.shipper_id = '.$billing->billing_id.' 
									or orders.billing_id = '.$billing->billing_id.' ';
				if (!empty($searchparam['startdate']) && $searchparam['startdate'] != '1970-01-01' && 
				!empty($searchparam['enddate'])&& $searchparam['enddate'] != '1970-01-01') {
					$all_order_id .='and order_date >= "'.$searchparam['startdate'].'"
									  and order_date <= "'.$searchparam['enddate'].'"';
				}
				*/
				
				$product_sold_qty = 'select sum(cart_qty)
						from tb_cart 
						where order_id in (
							SELECT `orders`.`id` FROM (`orders`) LEFT JOIN `billing` as shipper ON `shipper`.`billing_id` = `orders`.`shipper_id` 
							WHERE (
								orders.billing_id = '.$billing->billing_id.' 
								or orders.shipper_id = '.$billing->billing_id.' 
								or shipper.billing_upline = '.$billing->billing_id.' 
							)';
							
				
				if (!empty($searchparam['startdate']) && $searchparam['startdate'] != '1970-01-01' && 
				!empty($searchparam['enddate'])&& $searchparam['enddate'] != '1970-01-01') {
					$product_sold_qty .= ' AND orders.order_date >= " '.$searchparam['startdate'].'" 
							AND orders.order_date <= "'.$searchparam['enddate'].'"';
				}
				
				$product_sold_qty .= ')';
				
				$this->db->select('b.billing_id, b.billing_name,  
								 ('.$where_billing_nominal.')  as billing_nominal,
								 ('.$where_billing_count.') as billing_count,
								 ('.$where_dropship_nominal.')  as dropship_nominal,
								 ('.$where_dropship_count.') as dropship_count,
								 ('.$where_reseller_nominal.')  as reseller_nominal,
								 ('.$where_reseller_count.') as reseller_count,
								 ('.$product_sold_qty.') as product_sold_qty'
								 );
								 
				$this->db->from('billing b');
				$this->db->where('b.billing_id', $billing->billing_id);
			
				$query=$this->db->get();
				
				if ($query->num_rows() > 0) {
					foreach ($query->result() as $row) {
						$data[] = $row;
					}
				}
			}
			return $data;
		}
		return false;
   }
   
}
