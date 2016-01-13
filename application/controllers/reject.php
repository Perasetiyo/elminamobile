<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reject extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('reject_model');
		$this->load->model('GenericModel');
		
		$this->load->model('stock_model');
		
		$this->load->model('cart_logic');
    }
	
	public function index() {
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/reject/lists");
			$config["total_rows"] = $this->reject_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$this->db->order_by('product_name');
			$data['products'] = $this->db->get('tb_product')->result();
			
			$data["list_reject"] = $this->reject_model->fetch_reject($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "rejectList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			
			// looking for inventory purchase type id
			$this->db->where('option_code', 'INV_REJ');
			$this->db->limit(1);
			$option_reject = $this->db->get('tb_options');
			$inventory_type_id = $option_reject->row()->option_id;
			
			$msg = "";
			// insert into reject
			$reject = array(
				   'reject_date' => date('Y-m-d', strtotime($this->input->post('reject_date'))),
				);
			$this->db->insert('tb_reject', $reject);
			$reject_id = $this->db->insert_id();
				
			// set up nominal of rejection
			// masukkan produk kedalam tb_cart
			
			$total_cogs_amount = 0;
			$totalRejectQty = 0;
			
			$queryAllStock = $this->db->get('tb_stock');
			if ($queryAllStock->num_rows > 0) {
				foreach ($queryAllStock->result() as $rowStock) {
					
					$reject_qty = $this->input->post('qty'.$rowStock->stock_id);
					
					// cart amount per stock
					$rejectAmount = $reject_qty * $rowStock->stock_cogs;
					
					// UPDATE CART
					$this->cart_logic->add_to_cart(
							$rowStock->stock_id, 
							$reject_id, 
							$reject_qty, 
							$rejectAmount, 
							date('Y-m-d', strtotime($this->input->post('reject_date'))), 
							'Reject Inventory', 
							$total_cogs_amount,
							$totalRejectQty,
							'reject');
					
				}
			}
			
			// UPDATE QTY reject
			$reject = array(
				   'reject_qty' => $totalRejectQty
				);
				
			$this->db->where('reject_id', $reject_id); 
			$this->db->update('tb_reject', $reject);
			
			// ADD EXPENSE
			$expense = array(
				   'expense_date' => date('Y-m-d', strtotime($this->input->post('reject_date'))), 
				   'expense_desc' => 'Reject Inventory',
				   'expense_nominal' => $total_cogs_amount,
				   'expense_type_id' => 54,
				   'reject_id' => $reject_id,
				);
				
			
			$this->db->insert('tb_expense', $expense);
			
			$msg += '<p>Reject product has been added..!</p><br/>';
			
			
			$this->session->set_flashdata('success_message',$msg);			
			
			redirect(site_url('reject/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			
			$reject_id = $this->input->post('reject_id');
			// looking for inventory purchase type id
			$this->db->where('option_code', 'INV_REJ');
			$this->db->limit(1);
			$option_reject = $this->db->get('tb_options');
			$inventory_type_id = $option_reject->row()->option_id;
			
			// masukkan produk kedalam tb_cart
			
			$total_cogs_amount = 0;
			$totalRejectQty = 0;
			$queryAllStock = $this->db->get('tb_stock');
			if ($queryAllStock->num_rows > 0) {
				foreach ($queryAllStock->result() as $rowStock) {
					
					$reject_qty = $this->input->post('qty'.$rowStock->stock_id);
					
					// cart amount per stock
					$rejectAmount = $reject_qty * $rowStock->stock_cogs;
					
					// UPDATE CART
					$this->cart_logic->add_to_cart(
							$rowStock->stock_id, 
							$reject_id, 
							$reject_qty, 
							$rejectAmount, 
							date('Y-m-d', strtotime($this->input->post('reject_date'))), 
							'Reject Inventory', 
							$totalRejectQty, 
							$total_cogs_amount,
							'reject');
					
				}
			}
			
			
			$msg = "";
			
			// UPDATE REKECT
			// : : : looking for inventory_cogs
			$this->db->where('reject_id', $reject_id);
			$this->db->limit(1);
			$reject_inv = $this->db->get('tb_inventory');
			$reject_qty = $reject_inv->row()->inventory_qty;
			$reject_nominal = $reject_inv->row()->inventory_nominal;
			
			$reject = array(
				   'reject_date' => date('Y-m-d', strtotime($this->input->post('reject_date'))),
				   'reject_qty' => $reject_qty,
				   'reject_nominal' => -$reject_nominal
				);
		
			$this->db->where('reject_id', $reject_id); 
			$this->db->update('tb_reject', $reject);
			
			// UPDATE EXPENSE
			$expense = array(
				   'expense_date' => date('Y-m-d', strtotime($this->input->post('reject_date'))), 
				   'expense_desc' => 'Reject Inventory',
				   'expense_nominal' => -$reject_nominal,
				   'expense_type_id' => 54
				);
				
			$this->db->where('reject_id', $reject_id); 
			$this->db->update('tb_expense', $expense);
			
			
			$msg .= '<p>Rejection has been updated '.$total_qty.'</p>';
			
			$this->session->set_flashdata('success_message',$msg);			
			
			redirect(site_url('reject/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if ($this->session->userdata('logged_in')) {
			$this->db->where('reject_id', $this->uri->segment(3));
			$queryInventory = $this->db->get('tb_inventory');
			
			foreach ($queryInventory->result() as $inventory_row) {
				$this->db->delete('tb_cash', array('inventory_id' => $inventory_row->inventory_id)); 
				$this->db->delete('tb_liabilities', array('inventory_id' => $inventory_row->inventory_id));
			}
			
			$this->db->delete('tb_reject', array('reject_id' => $this->uri->segment(3)));
			$this->db->delete('tb_inventory', array('reject_id' => $this->uri->segment(3)));
			$this->db->delete('tb_reject_cart', array('reject_id' => $this->uri->segment(3)));
			$msg = 'Reject was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('reject/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->order_by('product_name');
			$data['product_name'] = $this->db->get('tb_product')->result();
			
			// get inventory
			$this->db->order_by('stock_desc');
			$this->db->where('stock_qty >',0);
			$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id','left');
			$data['stock']=$this->db->get('tb_stock')->result();
			
			$data['page'] = "rejectAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "rejectUpdate";
			$reject_id = $this->uri->segment(3);
			
			$this->db->select('*');
			$this->db->where('tb_stock.stock_id in (select cart.stock_id from tb_reject_cart as cart where cart.reject_id = '.$reject_id.') OR 
							  tb_stock.stock_qty > 0', NULL, false);
			$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
			$this->db->order_by('tb_stock.stock_desc');
			$data['stock']=$this->db->get('tb_stock')->result();
			
			// get tb_cart
			$this->db->where('reject_id',$reject_id);
			$data['cart']=$this->db->get('tb_reject_cart')->result();
			
			// QUERY reject
			$this->db->select('*, tb_reject.reject_id AS main_reject_id, tb_inventory.inventory_id AS main_inventory_id');
			$this->db->from('tb_reject');
			$this->db->join('tb_inventory', 'tb_inventory.reject_id = tb_reject.reject_id','left');
			$this->db->where('tb_reject.reject_id',$reject_id);
			$data['reject'] = $this->db->get()->result();
			
			$this->db->where('reject_id',$reject_id);
			$query=$this->db->get('tb_reject');
			if ($query->num_rows == 0) {
				$msg = '<p>Reject not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('reject/lists'));
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
				   'reject_desc' => $this->input->post('reject_desc'),
				   'product_id' => $this->input->post('product_id')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('reject/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	function add_to_cart($stock_id, $reject_id, $qty, $amount) {
		
		// UPDATE STOCK
		$this->db->where('stock_id', $stock_id);
		$rejectedStock = $this->db->get('tb_stock');
		
		foreach ($rejectedStock->result() as $rowRejectedStock) {
			if ($rowRejectedStock->stock_id == $stock_id) {
				
				// LOOKING FOR STOCK IN CART OF THIS ORDER
				$this->db->where('stock_id', $stock_id);
				$this->db->where('reject_id', $reject_id);
				$cartContainThisStock = $this->db->get('tb_reject_cart');
				if ($cartContainThisStock->num_rows > 0) {
					// IF THEY HAS BEEN REJECTED BEFORE
					foreach ($cartContainThisStock->result() as $rowCart) {
						// get previous qty
						$previousrRejectedStockQty = $rowCart->reject_cart_qty;
						$undoStockQty = $previousrRejectedStockQty + $rowRejectedStock->stock_qty;
					}
					
					// new qty : undo stock - current stock purchased qty
					$new_stock_qty = $undoStockQty - $qty;
					$new_stock_nominal = $new_stock_qty * $rowRejectedStock->stock_cogs; 
					
				} else {
					//IF THIS STOCK IS A NEW REJECT
					$new_stock_qty = $rowRejectedStock->stock_qty - $qty;
					$new_stock_nominal = $new_stock_qty * $rowRejectedStock->stock_cogs; 
				}
				
				// UPDATE STOCK
				$new_stock = array (
				'stock_qty' => $new_stock_qty,
				'stock_nominal' => $new_stock_nominal
				);
									
				$this->db->where('stock_id', $stock_id);
				$this->db->update('tb_stock', $new_stock);
				
				// === UPDATE STOCK ON STORE VIA WEBSERVICE ===
				$this->stock_model->update_category_on_store($stock_id);
				// ==== === === === === === === === === === ===
					
				// TB_REJECTED_CART
				$new_cart = array(
					'stock_id' => $stock_id,
					'reject_id' => $reject_id,
					'reject_cart_qty' => $qty,
					'reject_cart_amount' => $amount
				);
				$this->db->where('stock_id', $stock_id);
				$this->db->where('reject_id', $reject_id);
				$query = $this->db->get('tb_reject_cart');
									
				if ($query->num_rows > 0 && $qty <= 0) {
					$this->db->delete('tb_reject_cart', array('stock_id' => $stock_id, 'reject_id' => $reject_id));
				} else if ($query->num_rows > 0 && $qty > 0) {
					$this->db->where('stock_id', $stock_id);
					$this->db->where('reject_id', $reject_id);
					$this->db->update('tb_reject_cart', $new_cart);
				} else if ($query->num_rows == 0 && $qty > 0) {
					$this->db->insert('tb_reject_cart', $new_cart);
				}
			}
		}		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
