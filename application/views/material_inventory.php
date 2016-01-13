<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Material_Inventory extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('material_inventory_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	function push_button() {
		if ($_POST['action'] == 'Cetak Kode') {
			$this->cetak_kode();
		}
	}
	
	private function cetak_kode() {
		$this->load->library("Pdf");
		
		$pageLayout = array(300, 528); //  or array($height, $width) 
		$pdf = new TCPDF('p', 'px', $pageLayout, true, 'UTF-8', false);			
		
		//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
		
		$pdf->SetMargins(3, 2, PDF_MARGIN_RIGHT);
		
		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}   
	 
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);   
	 
		// Set font
		$pdf->SetFont('dejavusans', '', 11, '', true);   
	 
		// Add a page
		$pdf->AddPage(); 
	 
		
		// ====================== CONTENT LOGIC ================================================
		$html = '';
		$searchterm = $this->session->userdata('searchterm');
		$totalrow = $this->material_inventory_model->record_count($searchterm);
		
		for ($i = 0;$i<= $totalrow;$i++) {
			$ch = $this->input->post('ch'.$i);
			if ( isset($_POST['ch'.$i]) ) {
				$html .= $this->form_bahan_html($ch);
			}
		}
		
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);   
	 
		// ---------------------------------------------------------    
	 
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('example_001.pdf', 'I');
	}
	
	function form_bahan_html($material_id) {
		$this->db->where('material_id', $material_id);
		$query = $this->db->get('tb_material_inventory');
		
		foreach ($query->result() as $row) {
			$html =  ' 
						<table border="0px" width="290px" cellpadding="3px">
							<tr><td colspan="3"></td></tr>
							<tr>
								<td rowspan="3" width="30%" border="1"></td>
								<td width="23%"> Kode</td>
								<td> : '.$row->material_code.'</td>
							</tr>
							<tr>
								<td width="23%"> Tgl Masuk</td>
								<td> : '.date("d-M-Y", strtotime($row->material_date_init)).'</td>
							</tr>
							<tr>
								<td width="23%"> Ukuran </td>
								<td> : '.$row->material_qty_init.' yards</td>
							</tr>
						</table>	
						<br/>
						<hr/>
					';		
		}
		return $html;
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/materialInventory/lists");
			$config["total_rows"] = $this->material_inventory_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_material_inventory"] = $this->material_inventory_model->fetch_material_inventory($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "materialInventoryList";
			
			$this->load->view('dashboard',$data);
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {	
			$msg = "";
			$material_code = "";
			
			
			// SETTING MATERIAL CODE ...
			
			// query sequence of table
			$count=$this->db->count_all_results('tb_material_inventory');
			//echo $count;
			$material_sequence = $count + 1;
			
			// Query tipe bahan code
			$this->db->where('option_id', $this->input->post('material_bahan_id'));
			$this->db->limit(1);
			$option_material_bahan = $this->db->get('tb_options');
			$material_bahan_code = $option_material_bahan->row()->option_code;
			
			// query warna code
			$this->db->where('option_id', $this->input->post('material_warna_id'));
			$this->db->limit(1);
			$option_material_warna = $this->db->get('tb_options');
			$material_warna_code = $option_material_warna->row()->option_code;
			
			// BLENDING
			$material_code = $material_bahan_code.'-'.$material_warna_code.'-'.$material_sequence;
			
			//echo 'material code '.$material_code;
			
			$material_inventory = array (
				'material_code' => $material_code,
				'material_bahan_id' => $this->input->post('material_bahan_id'),
				'material_warna_id' => $this->input->post('material_warna_id'),
				'material_date_init' => date('Y-m-d', strtotime($this->input->post('material_date_init'))),
				'material_qty_init' => $this->input->post('material_qty_init'),
				'material_qty' => $this->input->post('material_qty_init'),
				'material_cogs' => $this->input->post('material_nominal_init'),
				'material_nominal_init' => $this->input->post('material_nominal_init') * $this->input->post('material_qty_init'),
				'material_nominal' => $this->input->post('material_nominal_init') * $this->input->post('material_qty_init'),
				'material_type_id' => 7 // inv purchase
			);
			
			$this->db->insert('tb_material_inventory', $material_inventory); 
			$msg .= '<p>Material Inv has been added..!</p>';
			$material_inventory_id = $this->db->insert_id();
			
			// add log
			$material_inventory_log = array (
				'material_inventory_log_date' => date('Y-m-d', strtotime($this->input->post('material_date_init'))),
				'material_inventory_id' => $material_inventory_id,
				'material_inventory_log_desc' => 'Bahan masuk Kode : '.$material_code,
				'material_inventory_log_used_qty' => 0,
				'material_inventory_log_last_qty' => $this->input->post('material_qty_init'),
				'material_inventory_log_used_nominal' => 0,
				'material_inventory_log_last_nominal' => $this->input->post('material_nominal_init') * $this->input->post('material_qty_init')
			);
			
			$this->db->insert('tb_material_inventory_log', $material_inventory_log); 
			$msg .= '<p>Material Inv Log has been added..!</p>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			// add to activity
			$session_data = $this->session->userdata('logged_in');
			$activity_desc = 'MATERIAL INVENTORY - Add New [DESC : <b>Bahan masuk Kode : '.$material_code.'</b> | 
								QTY : <b>'.$this->input->post('material_qty_init').'</b>]';
			$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
			
			redirect(site_url('material_inventory/lists'));
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {	
			// material bahan
			$this->db->where('option_type', 'MBAHAN');
			$this->db->order_by('option_desc');
			$data['material_bahan'] = $this->db->get('tb_options')->result();
			
			// material warna
			$this->db->where('option_type', 'MWARNA');
			$this->db->order_by('option_desc');
			$data['material_warna'] = $this->db->get('tb_options')->result();
			
			$data['page'] = "materialInventoryAdd";
			$this->load->view('dashboard',$data);
			
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function doCutting() {
		if($this->session->userdata('logged_in')) {	
			// query material
			$this->db->where('material_id', $this->input->post('material_id'));
			$this->db->limit(1);
			$material = $this->db->get('tb_material_inventory');
			$material_cogs = $material->row()->material_cogs;
			$material_qty = $material->row()->material_qty;
			$material_nominal = $material->row()->material_nominal;
		
			// new material inventory qty & nominal
			$material_last_qty = $material_qty - $this->input->post('material_used');
			$material_used_nominal = $this->input->post('material_used') * $material_cogs;
			$material_last_nominal = $material_nominal - $material_used_nominal;
		
			$cutting_material = array(
							'material_inventory_log_date' => date('Y-m-d', strtotime($this->input->post('cutting_date'))),
							'material_inventory_id' => $this->input->post('material_id'),
							'material_inventory_log_desc' => $this->input->post('cutting_desc'),
							'material_inventory_log_used_qty' => $this->input->post('material_used'),
							'material_inventory_log_last_qty' => $material_last_qty,
							'material_inventory_log_used_nominal' => $material_used_nominal,
							'material_inventory_log_last_nominal' => $material_last_nominal
			);
			
			$this->db->insert('tb_material_inventory_log', $cutting_material);
			$msg = '<p>Material was used. Material Inv Log has been added..!</p>';
			
			$this->update_material_balance($this->input->post('material_id'), $material_last_qty, $material_last_nominal);
			
			$this->session->set_flashdata('success_message',$msg);
						
			// add to activity
			$session_data = $this->session->userdata('logged_in');
			$activity_desc = 'MATERIAL INVENTORY - Cutting [DESC : <b>Cutting Bahan : '.$this->input->post('material_code').'</b> | 
								QTY : <b>'.$this->input->post('material_used').'</b>]';
			$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
			
			redirect(site_url('material_inventory/lists'));
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function cutting() {
		if($this->session->userdata('logged_in')) {	
			$this->db->where('material_id', $this->uri->segment(3));
			$this->db->limit(1);
			$data['material_inventory'] = $this->db->get('tb_material_inventory')->result();
			
			$data['page'] = "materialInventoryCutting";
			$this->load->view('dashboard',$data);
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function log_history() {
		if($this->session->userdata('logged_in')) {	
		
			$this->db->join('tb_material_inventory', 'tb_material_inventory.material_id = tb_material_inventory_log.material_inventory_id');
			$this->db->where('material_id', $this->uri->segment(3));
			$this->db->order_by('material_inventory_log_date');
			$material_inventory_log = $this->db->get('tb_material_inventory_log');
			$material_code = $material_inventory_log->row()->material_code;
			
			$data['material_code'] = $material_code;
			$data['list_material_inventory_log'] = $material_inventory_log->result();
			
			$data['page'] = "materialInventoryLogList";
			$this->load->view('dashboard',$data);
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function undo() {
	
		// get last used qty
		$this->db->where('material_inventory_log_id', $this->uri->segment(3));
		$this->db->limit(1);
		$material_inventory_log = $this->db->get('tb_material_inventory_log');
		$material_used_qty = $material_inventory_log->row()->material_inventory_log_used_qty;
			
		// add last used qty with last qty. and multiple with material cogs
		
		//delete inv log
		
		$this->update_material_balance($material_id, $last_qty, $last_nominal);
	
	}
	
	private function update_material_balance($material_id, $last_qty, $last_nominal) {
	
		$last_balance = array (
			'material_qty' => $last_qty,
			'material_nominal' => $last_nominal,
			);
			
		$this->db->where('material_id', $material_id);
		$this->db->update('tb_material_inventory', $last_balance);
	}
	
	public function search() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			// Searching
			$searchparam = array(
				   'material_code' => $this->input->post('material_code')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('material_inventory/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */