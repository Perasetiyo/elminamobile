<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('stock_model');
		$this->load->model('GenericModel');
		
		$this->config->load('webservice');
    }
	
	public function index() {
	}
	
	function push_button() {
		if ($_POST['action'] == 'Activate Stock') {
			$this->activate();
		} else if ($_POST['action'] == 'Move Stock into Trash') {
			$this->deactivate();
		}
	}
	
	function activate() {
		$searchterm = $this->session->userdata('searchterm');
		$totalrow = $this->stock_model->record_count($searchterm);
		$stockcount = 0;
		for ($i = 0;$i<= $totalrow;$i++) {
			$ch = $this->input->post('ch'.$i);
			if ( isset($_POST['ch'.$i]) ) {
				
				$this->db->where('stock_id', $ch);
				$querystock = $this->db->get('tb_stock');
				foreach ($querystock->result() as $row) {
					// UPDATE STATUS PENGIRIMAN
					$stock_status = array (
						'stock_status' => 1
						);
						
					$this->db->where('stock_id', $ch);
					$this->db->update('tb_stock', $stock_status);
					$stockcount++;
				}
			}
		}
		
		
		// add to activity
		$session_data = $this->session->userdata('logged_in');
		$activity_desc = 'STOCK - Activate [ <b>'.$stockcount.' Stock(s) </b>]';
		$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
		
		$msg = '<p>Stock has been activated</p>';
		$this->session->set_flashdata('success_message',$msg);	
						
		redirect(site_url('stock/lists')); 
	}
	
	function deactivate() {
		$searchterm = $this->session->userdata('searchterm');
		$totalrow = $this->stock_model->record_count($searchterm);
		$stockcount = 0;
		for ($i = 0;$i<= $totalrow;$i++) {
			$ch = $this->input->post('ch'.$i);
			if ( isset($_POST['ch'.$i]) ) {
				
				$this->db->where('stock_id', $ch);
				$querystock = $this->db->get('tb_stock');
				foreach ($querystock->result() as $row) {
					// UPDATE STATUS PENGIRIMAN
					$stock_status = array (
						'stock_status' => 2
						);
						
					$this->db->where('stock_id', $ch);
					$this->db->update('tb_stock', $stock_status);
					$stockcount++;
				}
			}
		}
		
		
		// add to activity
		$session_data = $this->session->userdata('logged_in');
		$activity_desc = 'STOCK - Deactivate [ <b>'.$stockcount.' Stock(s) </b>]';
		$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
		
		$msg .= '<p>Stock has been trashed</p>';
		$this->session->set_flashdata('success_message',$msg);	
						
		redirect(site_url('stock/lists')); 
	}
	
	
	public function insert_inventory_qty() {
		if($this->session->userdata('logged_in')) {
			// fetching stock
			$this->db->where('stock_qty >', 0);
			$this->db->where('stock_status', 1);
			
			$query = $this->db->get('tb_stock');
			
			if ($query->num_rows > 0) {
				foreach ($query->result() as $row) {
					$this->db->where('stock_id', $row->stock_id);
					$this->db->limit(1);
					$queryInv = $this->db->get('tb_inventory');
					
					foreach ($queryInv->result() as $rowInv) {
						
						$inv = array(
							   'inventory_qty' => $row->stock_qty,
							   'inventory_qty_init' => $row->stock_qty,
							   'inventory_cogs' => $row->stock_cogs,
							);
						
						$this->db->where('inventory_id', $rowInv->inventory_id); 
						$this->db->update('tb_inventory', $inv);
					}
				}
				echo 'done';
			}
		}
	}
	public function insert_date() {
		if($this->session->userdata('logged_in')) {
			// Set timezone
			date_default_timezone_set('UTC');
		 
			// Start date
			$date = '2014-01-01';
			// End date
			$end_date = '2020-12-31';
			$i = 0;
			while (strtotime($date) <= strtotime($end_date)) {
				$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
				
				$year_date = array(
					   'year_date_date' => $date
					);
				$this->db->insert('tb_year_date', $year_date);
				$i++;
			}
			echo 'done '.$i.' row';
		} else {
			echo 'who r u? get out!';
		}
	}
	
	public function mail_current_stock() {
		if($this->session->userdata('logged_in')) {	
			$this->load->library('email');

			// Email configuration
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'smtp.elminastore.com.',
				'smtp_port' => 465,
				'smtp_user' => 'admin@yourdomainname.com', // change it to yours
				'smtp_pass' => '******', // change it to yours
				'mailtype' => 'html',
				'charset' => 'iso-8859-1',
				'wordwrap' => TRUE
			);
		
			$this->email->from('no-reply@elminastore.com', 'Elmina Backoffice System');
			$this->email->to('pikun.112@gmail.com'); 
			$this->email->cc('diaz.prad@gmail.com'); 
			
			$this->email->subject('[ELMINA BACKOFFICE] CURRENT STOCK');
			$this->email->message($this->input->post('current_stock'));	

			$this->email->send();

			echo $this->email->print_debugger();
		
			// ============  Send email to Admin ==========================
			/*
			$subject = "[ELMINA BACKOFFICE] CURRENT STOCK ";
			$message = $this->input->post('current_stock');
			$from = "no-reply@elminastore.com";
			$replyto = "diaz.prad@gmail.com";
			$to = "pikun.112@gmail.com"; 
			$headers = "From:" . $from . "\r\n" .
					"Reply-To: " . $replyto;
			mail($to,$subject,$message,$headers);
			*/
			$msg = '<p>Email has been sent..!</p><br/>';
			$this->session->set_flashdata('success_message',$msg);
			
			$data['output'] = $this->input->post('current_stock');
			$data['page'] = "stockCurrent";
			$this->load->view('dashboard',$data);
		
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function view_stock() {
			//
			$count=$this->db->count_all_results('tb_product');
			$output= "UPDATE STOK - ".date('d M Y', strtotime('now'))."<br/>";
			//$output= "Untuk melihat keseluruhan katalog, 
			//			klik >> <a href=\"http://bit.ly/fotoprodukelmina\">http://bit.ly/fotoprodukelmina</a><br/>";
			
			for ($i = 0;$i<= $count;$i++) {
				$ch = $this->input->post('ch'.$i);
				if ( isset($_POST['ch'.$i]) ) {
					// fetching product name
					$this->db->where('product_id', $ch);
					$queryproduct = $this->db->get('tb_product');
					
					// fetching stock
					$this->db->where('stock_qty >', 0);
					$this->db->where('stock_status', 1);
					
					$this->db->where('tb_stock.product_id', $ch);
					$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
					$query = $this->db->get('tb_stock');
					
					if ($query->result() != null) {
						foreach ($queryproduct->result() as $row) {
							$output .= '--------------------<br/>';
							$output .= $row->product_name.'<br/>';
							$output .= '--------------------<br/>';
						}
					}	
					foreach ($query->result() as $row) {
						$output.= $row->product_name.' - '.$row->stock_desc;
						if ($row->stock_qty <= 10) {
							$output.=' ['.$row->stock_qty.' pcs]';
						} 
						
						if (!empty($row->stock_image)) {
							$output.='. <a href="'.$row->stock_image.'">Lihat Foto</a>';
						} else {
							$output.='. Foto N/A';
						}
						
						$output.='<br/>';
						
					}
					
				}
			}
			
			$data['output'] = $output;
			$data['page'] = "stockCurrent";
			$this->load->view('dashboard',$data);
		
			if (!empty($session_data['user_role'])) {
				// add to activity
				$session_data = $this->session->userdata('logged_in');
				$activity_desc = 'STOCK - View Current Stock';
				$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
			}
		
	}
	
	public function summary() {
			
			// FETCHING PRODUCTS
			$this->db->where('status',1);
			$this->db->order_by('product_name');
			$data['list_stock'] = $this->db->get('tb_product')->result();
			
			$data['page'] = "productChoose";
			$this->load->view('dashboard',$data);
		
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/stock/lists");
			$config["total_rows"] = $this->stock_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			
			$this->db->order_by('product_name');
			$data['products'] = $this->db->get('tb_product')->result();
			
			$data["list_stock"] = $this->stock_model->fetch_stock($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "stockList";
			
			$this->load->view('dashboard',$data);
			
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			
			// looking for inventory purchase type id
			$this->db->where('option_code', 'INV_PUR');
			$this->db->limit(1);
			$option_stock = $this->db->get('tb_options');
			$inventory_type_id = $option_stock->row()->option_id;
			
			// looking for products for define COGS of this stock
			
			$product_id = $this->input->post('product_id');
			$this->db->where('product_id', $product_id);
			$this->db->limit(1);
			$option_stock = $this->db->get('tb_product');
			
			$stock_cogs = $this->input->post('stock_cogs');
			
			// set up nominal of purchasing
			$nominal = $this->input->post('stock_qty') * $stock_cogs;
			
			$msg = "";
			// insert into stock
			$stock = array(
				   'stock_date' => date('Y-m-d', strtotime($this->input->post('stock_date'))),
				   'stock_desc' => $this->input->post('stock_desc'),
				   'stock_qty' => $this->input->post('stock_qty'),
				   'stock_cogs' => $stock_cogs,
				   'stock_price' => $this->input->post('stock_price'),
				   'stock_nominal' => $nominal,
				   'stock_status' => $this->input->post('stock_status'),
				   'stock_image' => $this->input->post('stock_image'),
				   'product_id' => $this->input->post('product_id'),
				   'store_id_product' => $this->input->post('store_id_product'),
				   'store_id_category_default' => $this->input->post('store_category')
				);
			$this->db->insert('tb_stock', $stock);
			$stock_id = $this->db->insert_id();
			$msg += '<p>Stock ('.$this->input->post('stock_desc').') has been added..!</p><br/>';
			
			// insert into inventory
			// SET DATE
			date_default_timezone_set('Asia/Bangkok');
			$inventory_date = date('Y-m-d', strtotime($this->input->post('stock_date')));
			
			if ($inventory_date == date('Y-m-d')) {
				$inventory_date = date('Y-m-d H:i:s');
			}
			
			$inventory = array(
				   'inventory_date' => $inventory_date,
				   'inventory_desc' => $this->input->post('stock_desc'),
				   'inventory_qty' => $this->input->post('stock_qty'),
				   'inventory_qty_init' => $this->input->post('stock_qty'),
				   'inventory_cogs' => $stock_cogs,
				   'inventory_nominal' => $nominal,
				   'inventory_type_id' => $inventory_type_id,
				   'stock_id' => $stock_id
				);
			$this->db->insert('tb_inventory', $inventory);
			$inventory_id = $this->db->insert_id();
			$msg += '<p>Inventory for ('.$this->input->post('stock_desc').') has been increased..!</p><br/>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			// add to activity
			$session_data = $this->session->userdata('logged_in');
			$activity_desc = 'STOCK - Add New [DESC : <b>'.$option_stock->row()->product_name.' - '.$this->input->post('stock_desc').'</b> | 
								QTY : <b>'.$this->input->post('stock_qty').'</b>]';
			$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
			
			redirect(site_url('stock/lists'));
			
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			
			// looking for inventory purchase type id
			$this->db->where('option_code', 'INV_PUR');
			$this->db->limit(1);
			$option_stock = $this->db->get('tb_options');
			$inventory_type_id = $option_stock->row()->option_id;
			
			/* looking for products for define COGS of this stock
			$product_id = $this->input->post('product_id');
			$this->db->where('product_id', $product_id);
			$this->db->limit(1);
			$option_stock = $this->db->get('tb_product');
			*/
			$stock_cogs = $this->input->post('stock_cogs');
			
			// set up nominal of purchasing
			$nominal = $this->input->post('stock_qty') * $stock_cogs;
			
			// looking for inventory
			$stock_id = $this->input->post('stock_id'); 
			$this->db->where('inventory_type_id', $inventory_type_id);
			$this->db->where('stock_id', $stock_id);
			$this->db->limit(1);
			$queryInventory = $this->db->get('tb_inventory');
			$inventory_row = $queryInventory->row();
			
			$msg = "";
			// updating stock
			$stock = array(
				   //'stock_date' => date('Y-m-d', strtotime($this->input->post('stock_date'))),
				   'stock_desc' => $this->input->post('stock_desc'),
				   //'stock_qty' => $this->input->post('stock_qty'),
				   //'stock_cogs' => $stock_cogs,
				   'stock_price' => $this->input->post('stock_price'),
				   'stock_status' => $this->input->post('stock_status'),
				   'stock_image' => $this->input->post('stock_image'),
				   //'stock_nominal' => $nominal,
				   'product_id' => $this->input->post('product_id'),
				   //'store_id_product' => $this->input->post('store_id_product'),
				   //'store_id_category_default' => $this->input->post('store_category')
				);
				
			$this->db->where('stock_id', $this->input->post('stock_id')); 
			$this->db->update('tb_stock', $stock);
			
			$msg .= '<p>Stock '.$this->input->post('stock_desc').' has been updated</p>';
			
			
			// updating inventory
			$inventory = array(
				   'inventory_desc' => 'Barang masuk : '.$this->input->post('stock_desc'),
				);
			
			$this->db->where('stock_id',$stock_id);
			$this->db->update('tb_inventory', $inventory);
			
			$msg += '<p>Inventory for ('.$this->input->post('stock_desc').') has been updated..!</p><br/>';
			
			$this->session->set_flashdata('success_message',$msg);	
			
			// add to activity
			$session_data = $this->session->userdata('logged_in');
			$activity_desc = 'STOCK - Update [DESC : <b>'.$this->input->post('product_id').' - '.$this->input->post('stock_desc').'</b> | 
								PRICE : <b>'.$this->input->post('stock_price').'</b>]';
			$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
			
			redirect(site_url('stock/lists'));
			
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function doRestock() {
		if($this->session->userdata('logged_in')) {
			
			// looking for products for define COGS of this stock
			$product_id = $this->input->post('product_id');
			$this->db->where('product_id', $product_id);
			$this->db->limit(1);
			$product = $this->db->get('tb_product');
			
			$stock_cogs = $this->input->post('stock_cogs');//$product->row()->current_cogs;
			
			// looking for inventory purchase type id
			$this->db->where('option_code', 'INV_PUR');
			$this->db->limit(1);
			$option_stock = $this->db->get('tb_options');
			$inventory_type_id = $option_stock->row()->option_id;
			//$nominal = $this->input->post('stock_nominal_cash') + $this->input->post('stock_nominal_credit');
			$nominal = $this->input->post('stock_qty') * $stock_cogs;
			
			// looking for inventory
			$stock_id = $this->input->post('stock_id'); 
			$this->db->where('inventory_type_id', $inventory_type_id);
			$this->db->where('stock_id', $stock_id);
			$this->db->limit(1);
			$queryInventory = $this->db->get('tb_inventory');
			$inventory_row = $queryInventory->row();
			
			// looking for stock
			$stock_id = $this->input->post('stock_id'); 
			$this->db->where('stock_id', $stock_id);
			$queryStock = $this->db->get('tb_stock');
			
			foreach ($queryStock->result() as $stock_row) {
				$msg = "";
				// updating stock
				$stock = array(
				   'stock_date' => date('Y-m-d', strtotime($this->input->post('stock_date'))),
				   'stock_qty' => $stock_row->stock_qty + $this->input->post('stock_qty'),
				   'stock_cogs' => $this->input->post('stock_cogs'),
				   'product_id' => $this->input->post('product_id'),
				   'stock_status' => $this->input->post('stock_status')
				);
			
				if ($nominal > 0) {
					$this->db->where('stock_id', $this->input->post('stock_id')); 
					$this->db->update('tb_stock', $stock);
				}
				
				// === UPDATE STOCK ON STORE VIA WEBSERVICE ===
				// $this->stock_model->update_category_on_store($stock_id);
				// ==== === === === === === === === === === ===
			
				$msg .= '<p>Stock '.$this->input->post('stock_desc').' has been updated</p>';
			}
			
			// insert into inventory
			// SET DATE
			date_default_timezone_set('Asia/Bangkok');
			$inventory_date = date('Y-m-d', strtotime($this->input->post('stock_date')));
			
			if ($inventory_date == date('Y-m-d')) {
				$inventory_date = date('Y-m-d H:i:s');
			}
			
			if ($nominal > 0) {
				$inventory = array(
					'inventory_date' => $inventory_date,
					'inventory_desc' => $this->input->post('stock_desc'),
					'inventory_qty' => $this->input->post('stock_qty'),
					'inventory_qty_init' => $this->input->post('stock_qty'),
					'inventory_cogs' => $stock_cogs,
					'inventory_nominal' => $nominal,
					'inventory_type_id' => $inventory_type_id,
					'stock_id' => $stock_id
				);
			
				$this->db->insert('tb_inventory', $inventory);
				$inventory_id = $this->db->insert_id();
				$msg += '<p>Inventory for ('.$this->input->post('stock_desc').') has been increased : '.$nominal.'..!</p><br/>';
			}
			
			$this->session->set_flashdata('success_message',$msg);	
			
			// add to activity
			$session_data = $this->session->userdata('logged_in');
			$activity_desc = 'STOCK - Restock [DESC : <b>'.$product->row()->product_name.' - '.$this->input->post('stock_desc').'</b> | 
								ADDED : <b>'.$this->input->post('stock_qty').'</b>]';
			$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
			
			redirect(site_url('stock/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function undo_restock() {
		if($this->session->userdata('logged_in')) {
			
			$stock_id = $this->input->post('stock_id');
			
			// looking for inventory
			$inventory_id = $this->uri->segment(3);
			$this->db->where('inventory_id', $inventory_id);
			$this->db->limit(1);
			$queryInventory = $this->db->get('tb_inventory');
			
			foreach ($queryInventory->result() as $inventory_row) {
			
			// looking for stock
			$stock_id = $inventory_row->stock_id; 
			$this->db->where('stock_id', $stock_id);
			$this->db->limit(1);
			$stock = $this->db->get('tb_stock');
			$product_id = $stock->row()->product_id;
			$stock_desc = $stock->row()->stock_desc;
			$last_stock_qty = $stock->row()->stock_qty;
			
			// looking for products for define COGS of this stock
			$this->db->where('product_id', $product_id);
			$this->db->limit(1);
			$product = $this->db->get('tb_product');
			$stock_cogs = $product->row()->current_cogs;
			$product_name = $product->row()->product_name;
			
			// updating stock
			$stock = array(
				   'stock_qty' => $last_stock_qty - $inventory_row->inventory_qty
				);
			$this->db->where('stock_id', $stock_id); 
			$this->db->update('tb_stock', $stock);
			
			/*
			// === UPDATE STOCK ON STORE VIA WEBSERVICE ===
			$this->stock_model->update_category_on_store($stock_id);
			// ==== === === === === === === === === === ===
			*/
			
			$this->db->delete('tb_inventory', array('inventory_id' => $inventory_id));
			
			// add to activity
			$session_data = $this->session->userdata('logged_in');
			$activity_desc = 'STOCK - Undo Restock [DESC : <b>'.$product_name.' - '.$stock_desc.' sejumlah '.$inventory_row->inventory_qty.'</b>]';
			$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
			
			
			}
			redirect(site_url('stock/restock_history/'.$stock_id));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->where('stock_id', $this->uri->segment(3));
			$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
			$this->db->limit(1);
			$stock = $this->db->get('tb_stock');
			
			
			$this->db->where('stock_id', $this->uri->segment(3));
			$queryInventory = $this->db->get('tb_inventory');
			
			foreach ($queryInventory->result() as $inventory_row) {
				$this->db->delete('tb_cash', array('inventory_id' => $inventory_row->inventory_id)); 
				$this->db->delete('tb_liabilities', array('inventory_id' => $inventory_row->inventory_id));
			}
			
			$this->db->delete('tb_stock', array('stock_id' => $this->uri->segment(3)));
			$this->db->delete('tb_inventory', array('stock_id' => $this->uri->segment(3)));
			$msg = 'Stock was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
			
			// add to activity
			$session_data = $this->session->userdata('logged_in');
			$activity_desc = 'STOCK - Delete [DESC : <b>'.$stock->row()->product_name.' - '.$stock->row()->stock_desc.'</b>]';
			$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
						
			redirect(site_url('stock/lists'));
			
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->order_by('product_name');
			$data['product_name'] = $this->db->get('tb_product')->result();
			
			$this->db->where('option_type', 'STOCK_STATUS');
			$this->db->order_by('option_desc');
			$data['stock_status'] = $this->db->get('tb_options')->result();
			
			/**
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			*/
			
			$data['page'] = "stockAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "stockUpdate";
			
			$this->db->order_by('product_name');
			$data['product_name'] = $this->db->get('tb_product')->result();
			
			$this->db->where('option_type', 'STOCK_STATUS');
			$this->db->order_by('option_desc');
			$data['stock_status'] = $this->db->get('tb_options')->result();
			
			// QUERY stock
			$this->db->select('*, tb_stock.stock_id AS main_stock_id, tb_inventory.inventory_id AS main_inventory_id');
			$this->db->from('tb_stock');
			$this->db->join('tb_inventory', 'tb_inventory.stock_id = tb_stock.stock_id');
			//$this->db->join('tb_cash', 'tb_cash.inventory_id = tb_inventory.inventory_id');
			//$this->db->join('tb_liabilities', 'tb_liabilities.inventory_id = tb_inventory.inventory_id');
			$this->db->where('tb_stock.stock_id',$this->uri->segment(3));
			$this->db->limit(1);
			$data['stock'] = $this->db->get()->result();
			
			$this->db->where('stock_id',$this->uri->segment(3));
			$query=$this->db->get('tb_stock');
			if ($query->num_rows == 0) {
				$msg = '<p>Stock not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('stock/lists'));
			}
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function restock() {
		if($this->session->userdata('logged_in')) {
			
			
			$this->db->where('option_type', 'STOCK_STATUS');
			$this->db->order_by('option_desc');
			$data['stock_status'] = $this->db->get('tb_options')->result();
			
			
			// QUERY stock
			$this->db->select('*');
			$this->db->from('tb_stock');
			$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
			$this->db->where('tb_stock.stock_id',$this->uri->segment(3));
			$data['stock'] = $this->db->get()->result();
			
			$this->db->where('stock_id',$this->uri->segment(3));
			$query=$this->db->get('tb_stock');
			if ($query->num_rows == 0) {
				$msg = '<p>Stock not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('stock/lists'));
			}
			
			$data['page'] = "stockRestock";
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
				   'stock_desc' => $this->input->post('stock_desc'),
				   'product_id' => $this->input->post('product_id'),
				   'qty_minimum' => $this->input->post('qty_minimum'),
				   'stock_status' => $this->input->post('stock_status')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('stock/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function who_keep() {
		if($this->session->userdata('logged_in')) {	
			$this->db->select('*');
			$this->db->from('tb_cart');
			$this->db->join('orders', 'orders.id = tb_cart.order_id','left');
			$this->db->join('billing', 'billing.billing_id = orders.billing_id');
			$this->db->where('tb_cart.stock_id', $this->uri->segment(3));
			$this->db->where('orders.package_status', 0);
			$query_cart=$this->db->get();
			
			$data['list_cart'] = $query_cart->result();
			
			$data['page'] = "stockKeepedList";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function sold_history() {
		if($this->session->userdata('logged_in')) {	
			$this->db->select('*');
			$this->db->from('tb_cart');
			$this->db->join('orders', 'orders.id = tb_cart.order_id','left');
			$this->db->join('billing', 'billing.billing_id = orders.billing_id');
			$this->db->where('tb_cart.stock_id', $this->uri->segment(3));
			$this->db->where('orders.package_status', 1);
			$this->db->order_by('orders.order_date', 'desc');
			$query_cart=$this->db->get();
			
			$data['list_cart'] = $query_cart->result();
			
			
			$data['page'] = "stockSoldList";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function restock_history() {
		if($this->session->userdata('logged_in')) {	
			$this->db->select('*');
			$this->db->from('tb_inventory');
			$this->db->join('tb_stock', 'tb_stock.stock_id = tb_inventory.stock_id','left');
			$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id','left');
			$this->db->where('tb_stock.stock_id', $this->uri->segment(3));
			$this->db->where('tb_inventory.inventory_type_id', 7);
			$query_cart=$this->db->get();
			
			$data['list_cart'] = $query_cart->result();
			
			$data['page'] = "stockRestockList";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function stock_detail() {
		if($this->session->userdata('logged_in')) {	
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/stock/stock_detail");
			$config["total_rows"] = $this->stock_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(4);
			
			// GET STOCK INFORMATION
			$this->db->where('stock_id', $this->uri->segment(3));
			$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
			$this->db->limit(1);
			$data["stock"] = $this->db->get('tb_stock')->result();;
			
			// GET DATA ON THE LIST
			$searchterm['stock_id'] = $this->uri->segment(3);
			$data["list_stock_detail"] = $this->stock_model->fetch_stock_detail($config["per_page"], $page, $searchterm);
			
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "stockDetail";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdjust() {
		if($this->session->userdata('logged_in')) {
		
			//masukin ke table adjust
			$adjust = array(
				'adjust_stock_date' => date('Y-m-d', strtotime($this->input->post('general_date'))),
				'adjust_stock_qty' => $this->input->post('adj_qty'),
				'stock_id' => $this->input->post('stock_id'),
				);
			
			$this->db->insert('tb_adjust_stock', $adjust);
			
			// adjust (tambah / kurang) stok
			// looking for stock
			$stock_id = $this->input->post('stock_id'); 
			$this->db->where('stock_id', $stock_id);
			$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
			$this->db->limit(1);
			$queryStock = $this->db->get('tb_stock');
			
			foreach ($queryStock->result() as $row) {
				$msg = "";
				// updating stock
				$stock_qty = $row->stock_qty + $this->input->post('adj_qty');
				$stock = array(
				   'stock_qty' => $stock_qty
				);
			
				$this->db->where('stock_id', $this->input->post('stock_id')); 
				$this->db->update('tb_stock', $stock);
				
				// === UPDATE STOCK ON STORE VIA WEBSERVICE ===
				$this->stock_model->update_category_on_store($stock_id);
				// ==== === === === === === === === === === ===
				
				$msg .= '<p>Stock '.$row->product_name.' - '.$row->stock_desc.' has been adjusted</p>';
				
				// add to activity
				$session_data = $this->session->userdata('logged_in');
				$activity_desc = 'STOCK - Adjust Stock [DESC : <b>'.$row->product_name.' - '.$row->stock_desc.'</b>]';
				$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
			
			}
		
			redirect(site_url('stock/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function addDate() {
	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
