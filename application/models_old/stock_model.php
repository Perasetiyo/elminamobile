<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_model extends CI_Model {
    public function __construct() {
        parent::__construct();
		
		$this->config->load('webservice');
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_stock');
			return $count;
		} else {
			
			if (!empty($searchparam['stock_desc'])) {
				$this->db->like('stock_desc', $searchparam['stock_desc']);
			}
			
			if (!empty($searchparam['qty_minimum'])) {
				$this->db->where('stock_qty >=', $searchparam['qty_minimum']);
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
			$like = "(tb_product.product_name like '%".$searchparam['stock_desc']."%' || stock_desc like '%".$searchparam['stock_desc']."%')";
			$this->db->where($like);
		}
		
		if (!empty($searchparam['qty_minimum'])) {
			$this->db->where('stock_qty >=', $searchparam['qty_minimum']);
		}
			
		$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
		$this->db->order_by("tb_stock.stock_date", "desc");
		$this->db->limit($limit, $start);
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$this->db->select('*');
				$this->db->from('tb_cart');
				$this->db->join('orders', 'orders.id = tb_cart.order_id');
				$this->db->join('billing', 'orders.billing_id = billing.billing_id');
				$this->db->where('tb_cart.stock_id', $row->stock_id);
				$query_cart=$this->db->get();
				
				$keeped_qty = 0;
				$sold_qty = 0;
				$row->keeped_qty = $keeped_qty;
				$row->sold_qty = $sold_qty;
				
				if ($query_cart->num_rows() > 0) {
					foreach ($query_cart->result() as $row2) {
						if ($row2->package_status == 0) {
							$keeped_qty += $row2->cart_qty;//.', '.$row2->billing_name.'. <br>';
						}
						
						if ($row2->package_status == 1) {
							$sold_qty += $row2->cart_qty;
						}
					}
					$row->keeped_qty = $keeped_qty;
					$row->sold_qty = $sold_qty;
				}
				
				$this->db->where('tb_reject_cart.stock_id', $row->stock_id);
				$query_reject=$this->db->get('tb_reject_cart');
				
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
   
   public function fetch_stock_detail($limit, $start, $searchparam) {
        
		$now_date = date('Y-m-d H:i:s'); 
		$start_date = date ("Y-m-d", strtotime("-60 day", strtotime($now_date)));
		
		$this->db->order_by("tb_year_date.year_date_date", 'asc');		
		$this->db->where('year_date_date >=', $start_date);
		$this->db->where('year_date_date <=', $now_date);
		
		$query=$this->db->get('tb_year_date');
		
		if ($query->num_rows == 0) {
		} else {
			$free_qty = 0;
			foreach ($query->result() as $row) {
				//DATE
				$row->general_date = $row->year_date_date;
				
				// RESTOCK QTY
				$this->db->select('inventory_id, inventory_date, tb_inventory.inventory_nominal / tb_product.current_cogs as restock_qty');
				$this->db->from('tb_inventory');
				$this->db->join('tb_stock', 'tb_stock.stock_id = tb_inventory.stock_id','left');
				$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id','left');
				$this->db->where('tb_stock.stock_id', $searchparam['stock_id']);
				$this->db->where('tb_inventory.inventory_type_id', 7);
				$this->db->where('tb_inventory.inventory_date', $row->year_date_date);
				$query_restock=$this->db->get();

				$restock_qty = 0;
				$row->restock_qty = $restock_qty;
				if ($query_restock->num_rows() > 0) {
					foreach ($query_restock->result() as $row2) {
						$restock_qty += $row2->restock_qty;
					}
					$row->restock_qty = $restock_qty;
				}
				
				// REJECT QTY
				
				$this->db->join('tb_reject', 'tb_reject.reject_id = tb_reject_cart.reject_id','left');
				$this->db->where('tb_reject.reject_date', $row->year_date_date);
				$this->db->where('tb_reject_cart.stock_id', $searchparam['stock_id']);
				$query_reject=$this->db->get('tb_reject_cart');
						
				$reject_qty = 0;
				$row->reject_qty = $reject_qty;
				if ($query_reject->num_rows() > 0) {
					foreach ($query_reject->result() as $row2) {
						$reject_qty += $row2->reject_cart_qty;
					}
					$row->reject_qty = $reject_qty;
				}
				
				// ADJUST QTY
				
				$this->db->where('tb_adjust_stock.adjust_stock_date', $row->year_date_date);
				$this->db->where('tb_adjust_stock.stock_id', $searchparam['stock_id']);
				$query_adj=$this->db->get('tb_adjust_stock');
						
				$adj_qty = 0;
				$row->adj_qty = $adj_qty;
				if ($query_adj->num_rows() > 0) {
					foreach ($query_adj->result() as $row2) {
						$adj_qty += $row2->adjust_stock_qty;
					}
					$row->adj_qty = $adj_qty;
				}
				
				
				// SALES QTY
				$this->db->select('*');
				$this->db->from('tb_cart');
				$this->db->join('orders', 'orders.id = tb_cart.order_id');
				$this->db->join('billing', 'orders.billing_id = billing.billing_id');
				$this->db->where('tb_cart.stock_id', $searchparam['stock_id']);
				$this->db->where('orders.order_date', $row->year_date_date);
				
				$query_cart=$this->db->get();
					
				$keeped_qty = 0;
				$sold_qty = 0;
				$row->keeped_qty = $keeped_qty;
				$row->sold_qty = $sold_qty;
						
				if ($query_cart->num_rows() > 0) {
					foreach ($query_cart->result() as $row2) {
						if ($row2->package_status == 0) {
							$keeped_qty += $row2->cart_qty;//.', '.$row2->billing_name.'. <br>';
						}
						
						if ($row2->package_status == 1) {
							$sold_qty += $row2->cart_qty;
						}
					}
					$row->keeped_qty = $keeped_qty;
					$row->sold_qty = $sold_qty;
				}
				
				
				if ($row->restock_qty == 0 && $row->reject_qty == 0 && $row->adj_qty == 0 && $row->keeped_qty == 0 && $row->sold_qty == 0 ) {
				
				} else {
					$free_qty = $free_qty + $row->restock_qty - $row->reject_qty + $row->adj_qty - $row->keeped_qty - $row->sold_qty;
					$row->free_qty = $free_qty;
					$data[] = $row;
				}
			}
			return $data;
		}
	}
	
	public function update_category_on_store($stock_id) {
		
		//echo "UPDATE STOCK ON STORE VIA WEBSERVICE...!<br/>";
		// testing variable
		//$stock_id = 38;
		
		
		$this->db->where('stock_id', $stock_id);
		$this->db->limit(1);
		$query = $this->db->get('tb_stock');
		
		$store_id_product = $query->row()->store_id_product;
		$stock_qty = $query->row()->stock_qty;
		$default_category = $query->row()->store_id_category_default;
		$store_id_category = $query->row()->store_id_category;
		
		/**
		echo 'GET Stock with store id product ['.$store_id_product.']<br/>, 
				default category ['.$default_category.']<br/>
				store category ['.$store_id_category.']<br/>
				stock qty ['.$stock_qty.']<br/>';		
		**/
		
		//set POST variables
		if ($store_id_product == 0) {
			//echo 'stock not on the list on store<br/>';
			return false;
		}
		
		if ($stock_qty > 0 && $default_category == $store_id_category) {
			//echo 'stock available, default category is khimar, store id category either, on store also available<br/>';
			return false;
		} else if ($stock_qty == 0 && $default_category != $store_id_category) {
			//echo 'stock empty, default category is khimar, store id category not, on server already sold out<br/>';
			return false;
		} else {
			//echo '';
		}
		
		
		$host = $this->config->item('host');
		$usermail = $this->config->item('usermail');
		$cookie_key = $this->config->item('cookie_key');
		$password = $this->config->item('password');
		
		//echo 'POST TO WEBSERVICE...<br/>';
		$url = $host.'boutil/product-category-update.php';
		
		// HASH MD5
		$password_hash = md5($cookie_key.$password);
		
		//echo $password_hash;
		$fields = array(
			'usermail' => urlencode($usermail),
			'passwd' => urlencode($password_hash),
			'id_product' => urlencode($store_id_product),
			'stock_qty' => urlencode($stock_qty),
			'default_category' => urlencode($default_category),
		);

		//url-ify the data for the POST
		$fields_string = '';
		foreach($fields as $key=>$value) { 
			$fields_string .= $key.'='.$value.'&'; 
		}
		rtrim($fields_string, '&');
		//echo $fields_string;
		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		
		//execute post
		$response = curl_exec($ch);

		//echo '<br/>----------------------------------------------------';
		//echo '<br/>Client Got Response : '.$response;
		//echo '<br/>----------------------------------------------------';
		//close connection
		curl_close($ch);
		
		$response_splited = explode(';', $response);
		
		if ($response_splited[0] = "CONTINUE") {
			// updating stock
			$stock = array(
				   'store_id_category' => $response_splited[1]
			);
				
			$this->db->where('stock_id', $stock_id); 
			$this->db->update('tb_stock', $stock);
			
			//echo '<br/>OPERATION FINISH..!';
		} else {
			echo 'fail';
		}
	}
	
}
