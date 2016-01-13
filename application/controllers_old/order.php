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
		$this->load->model('Stock_model');
		
		$this->load->model('stock_model');
		
    } 
	
	function choose_customer() {
		if($this->session->userdata('logged_in')) {	
			
			// get customer data
			$this->db->where('billing_id', $this->uri->segment(3));
			$data['billing']=$this->db->get('billing')->result();
			
			// get last order
			$this->db->where('billing_id', $this->uri->segment(3));
			$this->db->order_by('order_date', 'desc');
			$this->db->limit(1);
			$data['last_order'] = $this->db->get('orders')->result();
			
			
			// get inventory
			$this->db->order_by('stock_desc');
			$this->db->where('stock_qty >',0);
			$this->db->where('tb_product.status',1);
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
			
			// get order channel
			$this->db->where('option_type', 'ORD_CHANNEL');
			$this->db->order_by('option_desc');
			$data['order_channel'] = $this->db->get('tb_options')->result();
			
			$data['page'] = "orderAdd";
			$this->load->view('dashboard',$data);
			
		} else {
			redirect(site_url('login'));
		}
	}
	
	function find_customer() {
		if($this->session->userdata('logged_in')) {	
			$billing_name = $this->input->post('billing_name');
			$billing_phone = $this->input->post('billing_phone');
			
			// looking for existing customer
			if (!empty($billing_name)) {
				$this->db->like('billing_name', $billing_name);
			}
				
			if (!empty($billing_phone)) {
					$this->db->like('billing_phone', $billing_phone);
			}
			
			$this->db->join('tb_options', 'tb_options.option_id = billing.billing_level','left');
			$this->db->order_by('billing_id');
			$data['list_customer'] = $this->db->get('billing')->result();
			
			// looking for order history if customer exist
			// SEARCHING TERMS
			$this->session->unset_userdata('searchterm');
			
			// Searching
			$searchparam = array(
				   'billing_name' => $this->input->post('billing_name'),
				   'billing_phone' => $this->input->post('billing_phone'),
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/order/find_customer");
			$config["total_rows"] = $this->OrderModel->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data['listOrderPending'] = $this->OrderModel->fetch_orders_pending($config["per_page"], $page, $searchterm);
			$data['list_history_order'] = $this->OrderModel->fetch_all_orders($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			
			
			$data['page'] = "orderCustomerList";
			$this->load->view('dashboard',$data);
			
		} else {
			redirect(site_url('login'));
		}
	}
	
	function push_button_order_form() {
		if ($_POST['action'] == 'CETAK NOTA') {
			$this->cetak_nota();
		} else if ($_POST['action'] == 'UPDATE') {
			$this->doUpdate();
		}
	}
	
	function push_button() {
		if ($_POST['action'] == 'CETAK ALAMAT') {
			$this->to_pdf();
		} else if ($_POST['action'] == 'SUDAH DIKIRIM') {
			$this->sudah_dikirim();
		} else if ($_POST['action'] == 'SUDAH BAYAR') {
			$this->sudah_bayar();
		}
	}
	
	function sudah_dikirim() {
		$searchterm = $this->session->userdata('searchterm');
		$totalrow = $this->OrderModel->record_count($searchterm);
		$ordercount = 0;
		for ($i = 0;$i<= $totalrow;$i++) {
			$ch = $this->input->post('ch'.$i);
			if ( isset($_POST['ch'.$i]) ) {
				
				$this->db->where('id', $ch);
				$queryorder = $this->db->get('orders');
				foreach ($queryorder->result() as $row) {
					// UPDATE STATUS PENGIRIMAN
					$sudah_terkirim = array (
						'package_status' => 1
						);
						
					$this->db->where('id', $ch);
					$this->db->update('orders', $sudah_terkirim);
					$ordercount++;
				}
			}
		}
		
		
		// add to activity
		$session_data = $this->session->userdata('logged_in');
		$activity_desc = 'ORDER - Sudah dikirim [ <b>'.$ordercount.' Order(s) </b>]';
		$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
		
		$msg = '<p>Order has been updated</p>';
		$this->session->set_flashdata('success_message',$msg);	
						
		redirect(site_url('order')); 
	}
	
	function cetak_nota() {
		$this->load->library("Pdf");
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
		
		$pdf->SetMargins(3, 2, PDF_MARGIN_RIGHT);
		
		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}   
	 
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);   
	 
		// Set font
		$pdf->SetFont('dejavusans', '', 9, '', true);   
	 
		// Add a page
		$pdf->AddPage(); 
	 
		// ====================== CONTENT LOGIC ================================================
		$subtotal = 0;
		$diskon = $this->input->post('discount_amount');
		$ongkir = $this->input->post('exp_cost');
		$order_id = $this->input->post('order_id');
		$billing_name = $this->input->post('billing_name');
		$order_date = $this->input->post('order_date');
		
		$html = '
		<table>
			<tr>
				<td colspan="3"></td>
				<td colspan="1">Nama : '.$billing_name.'</td>
			</tr>	
			<tr>
				<td colspan="3">Nota No : '.$order_id.'</td>
				<td colspan="1">Tanggal : '.$order_date.'</td>
			</tr>	
		</table>
		
		<table border="1px" cellpadding="5px;">
					<tr align="center">
						<td width="10%">Kode</td>
						<td width="50%" colspan="2">Nama Barang</td>
						
						<td width="20%">Harga Satuan</td>
						<td width="20%">Subtotal</td>
					</tr>';
		// masukkan produk kedalam tb_cart
		$queryStock = $this->db->get('tb_stock');
		if ($queryStock->num_rows > 0) {
			$total_qty = 0;
			$cart = array(null);
			foreach ($queryStock->result() as $rowStock) {
				$purchase_qty = $this->input->post('qty'.$rowStock->stock_id);
				if ($purchase_qty > 0) {
					$this->db->where('product_id', $rowStock->product_id);
					$queryproduct = $this->db->get('tb_product');
					
					foreach ($queryproduct->result() as $rowProduct) {
						$subtotal += $rowStock->stock_price * $purchase_qty;
						$cart_in_html = '<tr>
									<td align="center">'.$rowProduct->product_code.'</td>
									<td colspan="2">'.$purchase_qty.' PCS '.$rowProduct->product_name.' - '.$rowStock->stock_desc.'</td>
									<td align="center">'.$rowStock->stock_price.'</td>
									<td align="right">'.$rowStock->stock_price * $purchase_qty.'</td>
								  </tr>';
								  
						array_push($cart, $cart_in_html);
					}
					
					$total_qty = $total_qty + $purchase_qty;
				}
			}
			
			sort($cart);
			foreach ($cart as $row) {
				$html .= $row;
			}
		}
		// space
		$html .= '<tr>
					<td colspan="5"></td>
				  </tr>';
				  
		// Subtotal
		$html .= '<tr>
					<td colspan="2">'.'Subtotal'.'</td>
					<td align="center">'.$total_qty.' PCS</td>
					<td align="center">'.''.'</td>
					<td align="right"> '.$subtotal.'</td>
				  </tr>';
		
		// Ongkir
		$html .= '<tr>
					<td colspan="2">'.'Ongkos Kirim'.'</td>
					<td align="center">'.''.'</td>
					<td align="center">'.''.'</td>
					<td align="right"> '.$ongkir.'</td>
				  </tr>';
		
		// Diskon jika ada
		$html .= '<tr>
					<td colspan="2">'.'Diskon'.'</td>
					<td align="center">'.''.'</td>
					<td align="center">'.''.'</td>
					<td align="right"> ('.$diskon.')</td>
				  </tr>';
		

		// space
		$html .= '<tr>
					<td colspan="5"></td>
				  </tr>';		
				  
		
		// TOTAL 
		$total = $subtotal - $diskon + $ongkir;
		$html .= '<tr>
					<td colspan="2">'.'TOTAL'.'</td>
					<td align="center">'.''.'</td>
					<td align="center">'.''.'</td>
					<td align="right">'.$total.'</td>
				  </tr>';		  
				  
		$html .= '</table>';
		
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);   
	 
		// ---------------------------------------------------------    
	 
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('example_001.pdf', 'I');    
	}
	
	function alamat_html($order_id) {
	
		$html = '';
		$this->db->where('id', $order_id);
		$this->db->join('billing', 'billing.billing_id = orders.billing_id');
		$this->db->join('shipper', 'shipper.shipper_id = orders.shipper_id');
		$queryorder = $this->db->get('orders');
		foreach ($queryorder->result() as $row) {
			$s_street = '';
			$s_kec = '';
			$s_city = '';
			$s_prov = '';
			$s_country = '';
			if (!empty($row->shipper_street)) { 
				$s_street = $row->shipper_street.'<br/>';
			}
			if (!empty($row->shipper_kecamatan)) { 
				$s_kec = 'Kec. '.$row->shipper_kecamatan.'. ';
			}
			if (!empty($row->shipper_city)) { 
				$s_city = $row->shipper_city.'<br/>';
			}
			if (!empty($row->shipper_prov)) { 
				$s_prov = $row->shipper_prov.'. ';
			}
			if (!empty($row->shipper_country)) { 
				$s_country = $row->shipper_country;
			}
						
			$b_street = '';
			$b_kec = '';
			$b_city = '';
			$b_prov = '';
			$b_country = '';
			if (!empty($row->billing_street)) { 
				$b_street = $row->billing_street.'<br/>';
			}
			if (!empty($row->billing_kec)) { 
				$b_kec = 'Kec. '.$row->billing_kec.'. ';
			}
			if (!empty($row->billing_city)) { 
				$b_city = $row->billing_city.'<br/>';
			}
			if (!empty($row->billing_prov)) { 
				$b_prov = $row->billing_prov.'. ';
			}
			if (!empty($row->billing_country)) { 
				$b_country = $row->billing_country;
			}
		
			$html .=  '
						<table border="0px" width="290px" cellpadding="3px">
	<tr>
		<td colspan="1">Ekspedisi :</td>
		<td colspan="3">'.$row->expedition.' / '.$row->service.' &nbsp;(Tarif : '.$row->exp_cost.')</td>		
	</tr>
	
	<tr>
		<td  colspan="1">Penerima :</td>
		<td colspan="3"><b>'.$row->billing_name.'</b></td>
	</tr>
	<tr>
		<td  colspan="1">d/A :</td>
		<td colspan="3">'.
			$b_street.$b_kec.$b_city.$b_prov.$b_country.
			'
		</td>
	</tr>
	<tr>
		<td  colspan="1">Telp :</td>
		<td colspan="3">'.$row->billing_phone.'</td>
	</tr>
</table>
<hr/>
<table border="0px" width="270px" cellpadding="3px">
	<tr>
		<td colspan="1">Pengirim :</td>
		<td colspan="3"><b>'.$row->shipper_name.'</b></td>
	</tr>
	<tr>
		<td colspan="1"></td>
		<td colspan="3">
		'.$s_street.$s_kec.$s_city.$s_prov.$s_country.
		'
		</td>
	</tr>
	<tr>
		<td colspan="1">Telp :</td>
		<td colspan="3">'.$row->shipper_phone.'</td>
	</tr>
</table>
<br/><br/>
		';
		}
		return $html;
	}
	
	function to_pdf() {
		$this->load->library("Pdf");
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
		
		$pdf->SetMargins(3, 2, PDF_MARGIN_RIGHT);
		
		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}   
	 
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);   
	 
		// Set font
		$pdf->SetFont('dejavusans', '', 9, '', true);   
	 
		// Add a page
		$pdf->AddPage(); 
	 
		
		// ====================== CONTENT LOGIC ================================================
		$html = '';
		$searchterm = $this->session->userdata('searchterm');
		$totalrow = $this->OrderModel->record_count($searchterm);
		
		for ($i = 0;$i<= $totalrow;$i++) {
			$ch = $this->input->post('ch'.$i);
			if ( isset($_POST['ch'.$i]) ) {
				$html .= $this->alamat_html($ch);
				
			}
		}
		
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);   
	 
		// ---------------------------------------------------------    
	 
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('example_001.pdf', 'I');    
	}
	
	function add_to_cart($stock_id, $order_id, $qty, $amount) {
		
		// UPDATE STOCK
		$this->db->where('stock_id', $stock_id);
		$purchasedStock = $this->db->get('tb_stock');
		
		foreach ($purchasedStock->result() as $rowPurchasedStock) {
			if ($rowPurchasedStock->stock_id == $stock_id) {
				
				// LOOKING FOR STOCK IN CART OF THIS ORDER
				$this->db->where('stock_id', $stock_id);
				$this->db->where('order_id', $order_id);
				$cartContainThisStock = $this->db->get('tb_cart');
				if ($cartContainThisStock->num_rows > 0) {
					// IF THEY HAS BEEN PURCHASE BEFORE
					foreach ($cartContainThisStock->result() as $rowCart) {
						// get previous qty
						$previousPurchasedStockQty = $rowCart->cart_qty;
						$undoStockQty = $previousPurchasedStockQty + $rowPurchasedStock->stock_qty;
					}
					
					// new qty : undo stock - current stock purchased qty
					$new_stock_qty = $undoStockQty - $qty;
					$new_stock_nominal = $new_stock_qty * $rowPurchasedStock->stock_cogs; 
					
				} else {
					//IF THIS STOCK IS A NEW PURCHASE
					$new_stock_qty = $rowPurchasedStock->stock_qty - $qty;
					$new_stock_nominal = $new_stock_qty * $rowPurchasedStock->stock_cogs; 
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
				
				
				// TB_CART
				$new_cart = array(
					'stock_id' => $stock_id,
					'order_id' => $order_id,
					'cart_qty' => $qty,
					'cart_amount' => $amount
				);
				$this->db->where('stock_id', $stock_id);
				$this->db->where('order_id', $order_id);
				$query = $this->db->get('tb_cart');
									
				if ($query->num_rows > 0 && $qty <= 0) {
					$this->db->delete('tb_cart', array('stock_id' => $stock_id, 'order_id' => $order_id));
				} else if ($query->num_rows > 0 && $qty > 0) {
					$this->db->where('stock_id', $stock_id);
					$this->db->where('order_id', $order_id);
					$this->db->update('tb_cart', $new_cart);
				} else if ($query->num_rows == 0 && $qty > 0) {
					$this->db->insert('tb_cart', $new_cart);
				}
			}
		}
		
	}
	
	function update_stock($stock_id, $qty) {
		
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			
			// search billing id
			$this->db->where('id', $this->uri->segment(3));
			$this->db->join('billing', 'billing.billing_id = orders.billing_id');
			
			$querybilling = $this->db->get('orders');
			
			$billing_name = $querybilling->row()->billing_name;
			
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
					$this->stock_model->update_category_on_store($row->stock_id);
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
						
			
			// add to activity
			$session_data = $this->session->userdata('logged_in');
			$activity_desc = 'ORDER - Delete [Billing Name : <b>'.$billing_name.'</b>]';
			$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
				
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
						
				$billing_id = $this->input->post('billing_id');
				
				if (!empty($billing_id)) {
					$this->db->where('billing_id', $billing_id);
					$this->db->update('billing', $billing); 
				} else {
					$this->db->insert('billing', $billing); 
					$billing_id = $this->db->insert_id();
				}
				
				
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
							   'purchase_nominal_credit' => $this->input->post('purchase_nominal_credit'),
							   'order_channel' => $this->input->post('order_channel')
						);
				$this->db->insert('orders', $order); 
				$order_id = $this->db->insert_id();
				
				// CART
				
				// masukkan produk kedalam tb_cart
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
				
				// add to activity
				$session_data = $this->session->userdata('logged_in');
				$nominal_sales = $totalCartAmount - $discount_amount;
				$activity_desc = 'ORDER - Add New [ORDER ID : <b>'.$order_id.'</b> | TO <b>'.$this->input->post('billing_name').'</b> FROM <b>'.$this->input->post('shipper_name').'</b> | 
									NOMINAL : <b>'.$nominal_sales.'</b>]';
				$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
				
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
							   'purchase_nominal_credit' => $this->input->post('purchase_nominal_credit'),
							   'order_channel' => $this->input->post('order_channel')
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
					
					if ($this->input->post('purchase_nominal_credit') > 0) {
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
				
				
				// add to activity
				$session_data = $this->session->userdata('logged_in');
				$nominal_sales = $totalCartAmount - $discount_amount;
				$activity_desc = 'ORDER - Update [ORDER ID : <b>'.$order_id.'</b> | TO <b>'.$this->input->post('billing_name').'</b> FROM <b>'.$this->input->post('shipper_name').'</b> | 
									NOMINAL : <b>'.$nominal_sales.'</b>]';
				$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
				
				redirect(site_url('order'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {	
			
			// get inventory
			$this->db->order_by('stock_desc');
			$this->db->where('tb_product.status',1);
			$this->db->where('stock_qty >',0);
			$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id','left');
			$data['stock']=$this->db->get('tb_stock')->result();
			
			// get billing level
			$this->db->where('option_type', 'BILL_LV');
			$this->db->order_by('option_desc');
			$data['billing_level'] = $this->db->get('tb_options')->result();
			
			// get order channel
			$this->db->where('option_type', 'ORD_CHANNEL');
			$this->db->order_by('option_desc');
			$data['order_channel'] = $this->db->get('tb_options')->result();
			
			// get bank account
			$this->db->order_by('bank_account_name');
			$data['bank_account']=$this->db->get('bank_account')->result();
			
			// get liabilities
			$this->db->where('option_code','ACC_PAY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			// get zero billing
			$data['billing'] = null;
			$data['last_order'] = null;
			
			$data['page'] = "orderAdd";
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
			$this->db->where('tb_product.status',1);
			$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
			$this->db->order_by('tb_stock.stock_desc');
			$data['stock']=$this->db->get('tb_stock')->result();
			
			// get estimated weight of cart
			$data['total_weight']=$this->OrderModel->get_estimated_weight($order->id);
			
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
			
			// get order channel
			$this->db->where('option_type', 'ORD_CHANNEL');
			$this->db->order_by('option_desc');
			$data['order_channel'] = $this->db->get('tb_options')->result();
			
			
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
				   'billing_kec' => $this->input->post('billing_kec')
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
			$config["per_page"] = 40;
			
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
	
	function cetak_nota_dan_alamat() {
		$this->load->library("Pdf");
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
		
		
		$pdf->SetMargins(3, 2, PDF_MARGIN_RIGHT);
		
		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}   
	 
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);   
	 
		// Set font
		$pdf->SetFont('dejavusans', '', 9, '', true);   
	 
		// Add a page
		$pdf->AddPage(); 
	 
		// FETCHING DATA
		$order_id = $this->uri->segment(3);
		$this->db->where('id', $order_id);
		$this->db->join('billing', 'billing.billing_id = orders.billing_id');
		$this->db->limit(1);
		$queryorder = $this->db->get('orders');
		
		$subtotal = 0;
		$diskon = $queryorder->row()->discount_amount;
		$ongkir = $queryorder->row()->exp_cost;
		$order_date = $queryorder->row()->order_date;
		$order_id = $order_id;
		$billing_name = $queryorder->row()->billing_name;
		//$order_date = date("Y-m-d", strtotime(($$queryorder->row()->order_date)));
		
		// ====================== CONTENT LOGIC ================================================
		
		/** $subtotal = 0;
		$diskon = $this->input->post('discount_amount');
		$ongkir = $this->input->post('exp_cost');
		$order_id = $this->input->post('order_id');
		$billing_name = $this->input->post('billing_name');
		$order_date = $this->input->post('order_date');
		**/
		$html= '';
		
		$html .= $this->alamat_html($order_id);
		
		$html .= '
		<table border="1px" width="270px" cellpadding="3px" style="margin-top: 5px;">
			<tr><td colspan="4" align="right">'.$billing_name.'</td></tr>
			<tr>
				<td colspan="2">Nota No : '.$order_id.'</td>
				<td colspan="2" align="right">Tanggal : '.date('d-M-Y', strtotime($order_date)).'</td>
			</tr>	
		
			<tr align="center">
				<td width="20%">Kode</td>
				<td width="80%" colspan="2">Nama Barang</td>
			</tr>';
		
		
		// masukkan produk kedalam tb_cart
		$this->db->where('order_id', $order_id);
		$this->db->join('tb_stock', 'tb_stock.stock_id = tb_cart.stock_id');
		$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id');
		
		$queryCart = $this->db->get('tb_cart');
		
		if ($queryCart->num_rows > 0) {
			$total_qty = 0;
			$subtotal = 0;
			$cart = array(null);
			foreach ($queryCart->result() as $rowCart) {
				if ($rowCart->cart_qty > 0) {
					$cart_in_html = '<tr>
						<td align="center">'.$rowCart->product_code.'</td>
						<td colspan="2">'.$rowCart->cart_qty.' PCS &nbsp;'.$rowCart->product_name.' - '.$rowCart->stock_desc.'</td>
						</tr>';
								  
					array_push($cart, $cart_in_html);
				
					$total_qty = $total_qty + $rowCart->cart_qty;
				}
			}
			sort($cart);
			foreach ($cart as $row) {
				$html .= $row;
			}
		}
		
		
				  
		// Subtotal
		$html .= '<tr>
					<td colspan="2">'.'Total Barang '.'</td>
					<td align="center"><font size="12">'.$total_qty.'</font></td>
					
				  </tr>';
				  
		$html .= '</table>';
		
		
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);   
		// ---------------------------------------------------------    
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('example_002.pdf', 'I');    
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
