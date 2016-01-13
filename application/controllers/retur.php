<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Order extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('GenericModel');
		$this->load->model('OrderModel');
		$this->load->model('stock_model');
    } 
	function find_customer() {
		
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
		
			$this->db->where('order_id', $this->uri->segment(3));
			$query = $this->db->get('tb_cart');
			if ($query->num_rows > 0) {
				foreach ($query->result() as $row) {
					$this->db->where('stock_id', $row->stock_id);
					$stock = $this->db->get('tb_stock')->row();
					
					$new_stock_qty = $stock->stock_qty + $row->cart_qty;
					$refund_stock = array (
						'stock_qty' => $new_stock_qty,
						'stock_nominal' => $new_stock_qty * $stock->stock_cogs
					);
					
					$this->db->where('stock_id', $row->stock_id);
					$this->db->update('tb_stock',$refund_stock);
					
					// === UPDATE STOCK ON STORE VIA WEBSERVICE ===
					$this->stock_model->update_category_on_store($stock_id);
					// ==== === === === === === === === === === ===
				}
			}
			
			$this->db->delete('orders', array('id' => $this->uri->segment(3))); 
			$this->db->delete('tb_cash', array('order_id' => $this->uri->segment(3)));
			$this->db->delete('tb_cart', array('order_id' => $this->uri->segment(3)));
			$this->db->delete('tb_inventory', array('order_id' => $this->uri->segment(3)));
			$this->db->delete('tb_income', array('order_id' => $this->uri->segment(3)));
			$this->db->delete('tb_acrec', array('order_id' => $this->uri->segment(3)));
			
			$msg = '<p>Order has been deleted. Stock was refunded</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('order'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {	
				$this->load->helper('string');
				
				
				// BILLING		
				$billing = array(
							   'billing_name' => $this->input->post('billing_name'),
							   'billing_street' => $this->input->post('billing_street'),
							   'billing_kec' => $this->input->post('billing_kec'),
							   'billing_city' => $this->input->post('billing_city'),
							   'billing_prov' => $this->input->post('billing_prov'),
							   'billing_phone' => $this->input->post('billing_phone'),
							   'billing_country' => $this->input->post('billing_country'),
							   'billing_level' => $this->input->post('billing_level')
						);
				$this->db->insert('billing', $billing); 
				$billing_id = $this->db->insert_id();
				
				// SHIPPING
				$shipper = array(
							   'shipper_name' => $this->input->post('shipper_name'),
							   'shipper_street' => $this->input->post('shipper_street'),
							   'shipper_kecamatan' => $this->input->post('shipper_kecamatan'),
							   'shipper_city' => $this->input->post('shipper_city'),
							   'shipper_prov' => $this->input->post('shipper_prov'),
							   'shipper_phone' => $this->input->post('shipper_phone'),
						);
				$this->db->insert('shipper', $shipper); 
				$shipper_id = $this->db->insert_id();
				
				// ORDER ORDER
				//$invoice=
				$discount_amount = $this->input->post('discount_amount');
				$exp_cost = $this->input->post('exp_cost');
				
				$order = array(
							   'billing_id' => $billing_id,
							   'shipper_id' => $shipper_id,
							   'order_date' => date('Y-m-d', strtotime($this->input->post('order_date'))),
							   'expedition' => $this->input->post('expedition'),
							   'service' => $this->input->post('service'),
							   'exp_cost' => $exp_cost,
							   'discount_amount' => $discount_amount,
							   'bank_account_id' => $this->input->post('bank_account_id'),
							   'order_status' => $this->input->post('order_status'),
							   'package_status' => $this->input->post('package_status'),
							   'purchase_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),
							   'purchase_nominal_cash' => $this->input->post('purchase_nominal_cash'),
							   'purchase_nominal_credit' => $this->input->post('purchase_nominal_credit')
						);
				$this->db->insert('orders', $order); 
				$order_id = $this->db->insert_id();
				
				// CART
				
				// masukkan produk kedalam tb_cart
				$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
				$queryAllStock = $this->db->get('tb_stock');
				if ($queryAllStock->num_rows > 0) {
					foreach ($queryAllStock->result() as $rowStock) {
						
						$purchase_qty = $this->input->post('qty'.$rowStock->stock_id);
						
						// cart amount per stock
						$cartAmount = $purchase_qty * $rowStock->stock_price;
						$cogsAmount = $purchase_qty * $rowStock->stock_cogs;
						
						// UPDATE CART
						$this->add_to_cart($rowStock->stock_id, $order_id, $purchase_qty, $cartAmount);
					}
				}
				
				// ======== COLLECTING CART AMOUNT IN SAME ORDER ============
				$totalCartAmount = 0;
				$totalCogsAmount = 0;
				
				$this->db->where('order_id', $order_id);
				$this->db->join('tb_stock', 'tb_stock.stock_id = tb_cart.stock_id');
				$queryCart = $this->db->get('tb_cart');
				
				if ($queryCart->num_rows > 0) {
					foreach ($queryCart->result() as $rowCart) {
						// SALES >>>>
						$cartAmount = $rowCart->cart_amount;
						$totalCartAmount += $cartAmount;
						
						// COGS >>>>
						$cogsAmount = $rowCart->cart_qty * $rowCart->stock_cogs;
						$totalCogsAmount += $cogsAmount;
					}

				}
				
				// UPDATE ORDER - TOTAL CART AMOUNT & TOTAL AMOUNT
				$totalAmount = $totalCartAmount + $exp_cost - $discount_amount;
				
				$order = array(
							   'product_amount' => $totalCartAmount,
							   'total_amount' => $totalAmount
						);
				$this->db->where('id', $order_id);
				$this->db->update('orders', $order); 
				
				// ======== JIKA SUDAh BAYAR ==============
				// masukkan ke dalam income ,cash dan inventory bila order sudah dibayar
				if ($this->input->post('order_status') > 0) {
						
					// INCOME REVENUE
					$omset = array (
						'income_desc' => "Purchase Order by ".$this->input->post('billing_name'),
						'income_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),
						'income_nominal' => $totalAmount,
						'income_type_id' => 23,
						'order_id' => $order_id
					);
					$this->db->insert('tb_income', $omset);
							
					// INCOME COGS
					$incomecogs = array (
										
						'income_desc' => "Purchase Order by ".$this->input->post('billing_name'),
						'income_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),
						'income_nominal' => '-'.$totalCogsAmount,
						'income_type_id' => 24,
						'order_id' => $order_id
					);
					$this->db->insert('tb_income', $incomecogs);
							
					// CASH
					$cash = array (
						'cash_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),	
						'cash_desc' => "Purchase Order by ".$this->input->post('billing_name'),
						'cash_nominal' => $this->input->post('purchase_nominal_cash'),
						'cash_type_id' => 23,
						'bank_account_id' => $this->input->post('bank_account_id'),
						'order_id' => $order_id
					);
					$this->db->insert('tb_cash', $cash);
							
					if ($this->input->post('purchase_nominal_credit') > 0) {
						// ACC_RECEIVABLE
						$acc_receive = array (
							'acrec_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),	
							'acrec_desc' => "Purchase Order by ".$this->input->post('billing_name'),
							'acrec_nominal' => $this->input->post('purchase_nominal_credit'),
							'acrec_type_id' => 23,
							'order_id' => $order_id
						);
						$this->db->insert('tb_acrec', $acc_receive);
					}
					// INVENTORY
					$inv = array (
						'inventory_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),	
						'inventory_desc' => "Purchase Order by ".$this->input->post('billing_name'),
						'inventory_nominal' => '-'.$totalCogsAmount,
						'inventory_type_id' => 24,
						'order_id' => $order_id
					);
					$this->db->insert('tb_inventory', $inv);
						
				}
				
				
				$msg = '<p>Order successfully added.</p>'.$this->input->post('qty104');
				$this->session->set_flashdata('success_message',$msg);	
				redirect(site_url('order'));	
			
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
	if($this->session->userdata('logged_in')) {
				$this->load->helper('string');
				
				$order_id = $this->input->post('order_id');
				// masukkan billing info		
				$billing = array(
							   'billing_name' => $this->input->post('billing_name'),
							   'billing_street' => $this->input->post('billing_street'),
							   'billing_kec' => $this->input->post('billing_kec'),
							   'billing_prov' => $this->input->post('billing_prov'),
							   'billing_phone' => $this->input->post('billing_phone'),
							   'billing_kec' => $this->input->post('billing_kec'),
							   'billing_city' => $this->input->post('billing_city'),
							   'billing_country' => $this->input->post('billing_country'),
							   'billing_level' => $this->input->post('billing_level')
						);
						
				$this->db->where('billing_id', $this->input->post('billing_id'));
				$this->db->update('billing', $billing); 
				$billing_id = $this->input->post('billing_id');
				
				// masukkan shipping info		
				$shipper = array(
							   'shipper_name' => $this->input->post('shipper_name'),
							   'shipper_street' => $this->input->post('shipper_street'),
							   'shipper_kecamatan' => $this->input->post('shipper_kecamatan'),
							   'shipper_city' => $this->input->post('shipper_city'), 
							   'shipper_prov' => $this->input->post('shipper_prov'),
							   'shipper_phone' => $this->input->post('shipper_phone'),
						);
				$this->db->where('shipper_id', $this->input->post('shipper_id'));
				$this->db->update('shipper', $shipper); 
				$shipper_id = $this->input->post('shipper_id');
				
				// masukkan order
				//$invoice=
				$discount_amount = $this->input->post('discount_amount');
				$exp_cost = $this->input->post('exp_cost');
				
				$order = array(
							   'billing_id' => $billing_id,
							   'shipper_id' => $shipper_id,
							   'order_date' => date('Y-m-d', strtotime($this->input->post('order_date'))),
							   'expedition' => $this->input->post('expedition'),
							   'service' => $this->input->post('service'),
							   'exp_cost' => $this->input->post('exp_cost'),
							   'discount_amount' => $this->input->post('discount_amount'),
							   'bank_account_id' => $this->input->post('bank_account_id'),
							   'order_status' => $this->input->post('order_status'),
							   'package_status' => $this->input->post('package_status'),
							   'purchase_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),
							   'purchase_nominal_cash' => $this->input->post('purchase_nominal_cash'),
							   'purchase_nominal_credit' => $this->input->post('purchase_nominal_credit')
						);
				$this->db->where('id', $this->input->post('order_id'));
				$this->db->update('orders', $order); 
				
				// masukkan produk kedalam tb_cart
				$queryStock = $this->db->get('tb_stock');
						
				if ($queryStock->num_rows > 0) {
					
					foreach ($queryStock->result() as $rowStock) {
						
						$purchase_qty = $this->input->post('qty'.$rowStock->stock_id);
						
						// cart amount per stock
						$cartAmount = $purchase_qty * $rowStock->stock_price;
						$cogsAmount = $purchase_qty * $rowStock->stock_cogs;
						
						// UPDATE TB_CART
						$this->add_to_cart($rowStock->stock_id, $order_id, $purchase_qty, $cartAmount);
					}
				}
				
				// ======== COLLECTING CART AMOUNT IN SAME ORDER ============
				$totalCartAmount = 0;
				$totalCogsAmount = 0;
				
				$this->db->where('order_id', $order_id);
				$this->db->join('tb_stock', 'tb_stock.stock_id = tb_cart.stock_id');
				$queryCart = $this->db->get('tb_cart');
				
				if ($queryCart->num_rows > 0) {
					foreach ($queryCart->result() as $rowCart) {
						// SALES >>>>
						$cartAmount = $rowCart->cart_amount;
						$totalCartAmount += $cartAmount;
						
						// COGS >>>>
						$cogsAmount = $rowCart->cart_qty * $rowCart->stock_cogs;
						$totalCogsAmount += $cogsAmount;
					}

				}				
				
				// ========= JIKA SUDAH BAYAR =========
					
				$this->db->where('id', $order_id);
				$queryOrder = $this->db->get('orders');
				
				$row = $queryOrder->row();
				$totalAmount = $totalCartAmount + $row->exp_cost - $row->discount_amount;
				$data = array(
						'product_amount' => $totalCartAmount,
						'total_amount' => $totalAmount
				);
				$this->db->where('id', $order_id);
				$this->db->update('orders', $data);
					
				// masukkan ke dalam income ,cash dan inventory bila order sudah dibayar
				if ($row->order_status >= '1') {
					
					// INCOME REVENUE
					$this->db->where('income_type_id', 23);
					$this->db->where('order_id', $order_id);
					$query = $this->db->get('tb_income');
					
					$omset = array (
								
							'income_desc' => "Purchase Order by ".$this->input->post('billing_name'),
							'income_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),
							'income_nominal' => $totalAmount,
							'income_type_id' => 23,
							'order_id' => $order_id
					);
					
					if ($query->num_rows == 0) {
						$this->db->insert('tb_income', $omset);
					} else {
						$this->db->where('income_type_id', 23);
						$this->db->where('order_id', $order_id);
						$this->db->update('tb_income',$omset);
					}
					
					// INCOME COGS
					$this->db->where('income_type_id', 24);
					$this->db->where('order_id', $order_id);
					$query = $this->db->get('tb_income');
					
					$incomecogs = array (
							
						'income_desc' => "Purchase Order by ".$this->input->post('billing_name'),
						'income_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),
						'income_nominal' => '-'.$totalCogsAmount,
						'income_type_id' => 24,
						'order_id' => $order_id
					);
					
					if ($query->num_rows == 0) {
						$this->db->insert('tb_income', $incomecogs);
					} else {
						$this->db->where('income_type_id', 24);
						$this->db->where('order_id', $order_id);
						$this->db->update('tb_income',$incomecogs);
					}
					
					// CASH
					$this->db->where('order_id', $order_id);
					$query = $this->db->get('tb_cash');
					
					$cash = array (
						'cash_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),	
						'cash_desc' => "Purchase Order by ".$this->input->post('billing_name'),
						'cash_nominal' => $this->input->post('purchase_nominal_cash'),
						'cash_type_id' => 23,
						'bank_account_id' => $this->input->post('bank_account_id'),
						'order_id' => $order_id
					);
					
					if ($query->num_rows == 0) {
						$this->db->insert('tb_cash', $cash);
					} else {
						$this->db->where('order_id', $order_id);
						$this->db->update('tb_cash',$cash);
					}
					
					// ACC RECEIVE
					$this->db->where('order_id', $order_id);
					$query = $this->db->get('tb_acrec');
					$acc_receive = array (
						'acrec_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),	
						'acrec_desc' => "Purchase Order by ".$this->input->post('billing_name'),
						'acrec_nominal' => $this->input->post('purchase_nominal_credit'),
						'acrec_type_id' => 23,
						'order_id' => $order_id
					);
					
					if ($query->num_rows == 0) {
						$this->db->insert('tb_acrec', $acc_receive);
					} else {
						$this->db->where('order_id', $order_id);
						$this->db->update('tb_acrec',$acc_receive);
					}
					
					// INVENTORY
					$this->db->where('order_id', $order_id);
					$query = $this->db->get('tb_inventory');
					$inv = array (
						'inventory_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),	
						'inventory_desc' => "Purchase Order by ".$this->input->post('billing_name'),
						'inventory_nominal' => '-'.$totalCogsAmount,
						'inventory_type_id' => 24,
						'order_id' => $order_id
					);
					
					if ($query->num_rows == 0) {
						$this->db->insert('tb_inventory', $inv);
					} else {
						$this->db->where('order_id', $order_id);
						$this->db->update('tb_inventory', $inv);
					}
				}
					
				$msg = '<p>Order successfully added.</p>';
				$this->session->set_flashdata('success_message',$msg);	
				redirect(site_url('order'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {	
			$data['page'] = "returAdd";
			// get inventory
			$this->db->order_by('stock_desc');
			$this->db->where('stock_qty >',0);
			$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id','left');
			$data['stock']=$this->db->get('tb_stock')->result();
			
			// get billing level
			$this->db->where('option_type', 'BILL_LV');
			$this->db->order_by('option_desc');
			$data['billing_level'] = $this->db->get('tb_options')->result();
			
			// get bank account
			$this->db->order_by('bank_account_name');
			$data['bank_account']=$this->db->get('bank_account')->result();
			
			// get liabilities
			$this->db->where('option_code','ACC_PAY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {	
			$data['page'] = "orderUpdate";
			// get order data
			
			$data['orders']=$this->db->select('*, orders.id AS main_order_id');
			$data['orders']=$this->db->from('orders');
			$data['orders']=$this->db->join('billing', 'orders.billing_id = billing.billing_id','left');
			$data['orders']=$this->db->join('shipper', 'orders.shipper_id = shipper.shipper_id','left');
			$data['orders']=$this->db->join('bank_account', 'orders.bank_account_id = bank_account.id','left');
			$data['orders']=$this->db->join('tb_options AS liabilities_option', 'liabilities_option.option_id = orders.liabilities_type_id','left');
			$data['orders']=$this->db->where('orders.id',$this->uri->segment(3));
			$data['orders']=$this->db->get()->result();
			
			$this->db->where('id',$this->uri->segment(3));
			$query=$this->db->get('orders');
			$order = $query->row();
			
			if ($query->num_rows == 0) {
				$msg = '<p>Order tidak ditemukan.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('orderAction'));
			}
			
			
			$this->db->select('*');
			
			$this->db->where('tb_stock.stock_id in (select cart.stock_id from tb_cart as cart where cart.order_id = '.$order->id.') OR 
							  tb_stock.stock_qty > 0', NULL, false);
			//$this->db->where('tb_stock.stock_qty >',0);
			$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
			$this->db->order_by('tb_stock.stock_desc');
			$data['stock']=$this->db->get('tb_stock')->result();
			
			// get tb_cart
			$this->db->where('order_id',$order->id);
			$data['cart']=$this->db->get('tb_cart')->result();
			
			// get bank account
			$this->db->order_by('bank_account_name');
			$data['bank_account']=$this->db->get('bank_account')->result();
			
			// get billing level
			$this->db->where('option_type', 'BILL_LV');
			$this->db->order_by('option_desc');
			$data['billing_level'] = $this->db->get('tb_options')->result();
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function detail() {
		if($this->session->userdata('logged_in')) {	
			$data['page'] = "orderDetail";
			// get order data
			
			$data['orders']=$this->db->select('*');
			$data['orders']=$this->db->from('orders');
			$data['orders']=$this->db->join('billing', 'orders.billing_id = billing.billing_id');
			$data['orders']=$this->db->join('shipper', 'orders.shipper_id = shipper.shipper_id');
			$data['orders']=$this->db->where('orders.id',$this->uri->segment(3));
			$data['orders']=$this->db->get()->result();
			
			$this->db->where('id',$this->uri->segment(3));
			$query=$this->db->get('orders');
			$order = $query->row();
			
			if ($query->num_rows == 0) {
				$msg = '<p>Order tidak ditemukan.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('orderAction'));
			}
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function search() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			// Searching
			$searchparam = array(
				   'billing_name' => $this->input->post('billing_name'),
				   'billing_phone' => $this->input->post('billing_phone'),
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('order'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	public function index() {	
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
		
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/order/index");
			$config["total_rows"] = $this->OrderModel->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data['listOrderPending'] = $this->OrderModel->fetch_orders_pending($config["per_page"], $page, $searchterm);
			$data['listOrderComplete'] = $this->OrderModel->fetch_orders_complete($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			
			$data['page']="orderList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */