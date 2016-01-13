<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CashflowAction extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('GenericModel');
		$this->load->model('CashflowModel');
    }
	
	public function reporting(){
		if($this->session->userdata('logged_in')) {
		// ====== SEDEKAH ===========
			$this->db->select_sum('cashflow_nominal');
			$this->db->where('cashflow_type_id',2);
			$this->db->where('cashflow_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('cashflow_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_sedekah'] = $this->db->get('cashflow')->result();
		
		// ====== BELANJA ===========
			$this->db->select_sum('cashflow_nominal');
			$this->db->where('cashflow_type_id',4);
			$this->db->where('cashflow_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('cashflow_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_belanja'] = $this->db->get('cashflow')->result();
		
		// ====== PENGELUARAN RUTIN ===========
			$this->db->select_sum('cashflow_nominal');
			$this->db->where('cashflow_type_id',3);
			$this->db->where('cashflow_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('cashflow_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_expense'] = $this->db->get('cashflow')->result();
		
		// ====== GAJI ===========
			$this->db->select_sum('cashflow_nominal');
			$this->db->where('cashflow_type_id',7);
			$this->db->where('cashflow_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('cashflow_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_gaji'] = $this->db->get('cashflow')->result();
		
		// ====== HUTANG ===========
			$this->db->select_sum('cashflow_nominal');
			$this->db->where('cashflow_type_id',5);
			$this->db->where('cashflow_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('cashflow_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_hutang'] = $this->db->get('cashflow')->result();
		
		// ====== PIUTANG ===========
			$this->db->select_sum('cashflow_nominal');
			$this->db->where('cashflow_type_id',6);
			$this->db->where('cashflow_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('cashflow_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_piutang'] = $this->db->get('cashflow')->result();
		
		// ====== KAS ===========
			$this->db->select_sum('cashflow_nominal');
			$this->db->where('cashflow_type_id',8);
			$this->db->where('cashflow_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('cashflow_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_kas'] = $this->db->get('cashflow')->result();
		// ====== MODAL ===========
			$this->db->select_sum('cashflow_nominal');
			$this->db->where('cashflow_type_id',9);
			$this->db->where('cashflow_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('cashflow_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_modal'] = $this->db->get('cashflow')->result();
		// ====== INVESTASI ===========
			$this->db->select_sum('cashflow_nominal');
			$this->db->where('cashflow_type_id',10);
			$this->db->where('cashflow_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('cashflow_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_investasi'] = $this->db->get('cashflow')->result();
			
		// ====== OMSET ===========
			$this->db->select_sum('cashflow_nominal');
			$this->db->where('cashflow_type_id',1);
			$this->db->where('cashflow_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('cashflow_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_omset'] = $this->db->get('cashflow')->result();
			
			
			
			$data['page'] = "detailCashflow";
			$data['startdate'] = date('d-M-y', strtotime($this->input->post('startdate')));
			$data['enddate'] = date('d-M-y', strtotime($this->input->post('enddate')));
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function detailCashflow(){
		if($this->session->userdata('logged_in')) {
			$data['startdate'] = null;
			$data['enddate'] = null;
			$data['nominal_sedekah'] = null;
			$data['nominal_belanja'] = null;
			$data['nominal_expense'] = null;
			$data['nominal_gaji'] = null;
			$data['nominal_hutang'] = null;
			$data['nominal_piutang'] = null;
			$data['nominal_kas'] = null;
			$data['nominal_modal'] = null;
			$data['nominal_investasi'] = null;
			$data['nominal_omset'] = null;
			$data['page'] = "detailCashflow";
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function doAddCashflow() {
		if($this->session->userdata('logged_in')) {
			$cashflow = array(
				   'cashflow_type_id' => $this->input->post('cashflow_type_id'),
				   'cashflow_date' => date('Y-m-d', strtotime($this->input->post('cashflow_date'))),
				   'cashflow_desc' => $this->input->post('cashflow_desc'),
				   'cashflow_nominal' => $this->input->post('cashflow_nominal'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'debet_credit' => $this->input->post('debet_credit'),
				   'order_id' => null
				);
			$this->db->insert('cashflow', $cashflow); 
			
			$msg = '<p>Arus kas ('.$this->input->post('cashflow_desc').') berhasil ditambah.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('cashflowAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function doUpdateCashflow() {
		if($this->session->userdata('logged_in')) {
			$cashflow = array(
				   'cashflow_type_id' => $this->input->post('cashflow_type_id'),
				   'cashflow_date' => date('Y-m-d', strtotime($this->input->post('cashflow_date'))),
				   'cashflow_desc' => $this->input->post('cashflow_desc'),
				   'cashflow_nominal' => $this->input->post('cashflow_nominal'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'debet_credit' => $this->input->post('debet_credit')
				);
				
			$this->db->where('cashflow_id', $this->uri->segment(3)); 
			$this->db->update('cashflow', $cashflow);
			
			$msg = '<p>Arus Kas '.$this->input->post('product_name').' berhasil diupdate.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('cashflowAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}	
	}
	
	public function deleteCashflow() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('cashflow', array('cashflow_id' => $this->uri->segment(3))); 
			
			$msg = '<p>Arus Kas berhasil dihapus dari muka bumi ini.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('cashflowAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function addCashflow() {
		if($this->session->userdata('logged_in')) {
			$this->db->order_by('cashflow_name');
			$data['cashflow_type'] = $this->db->get('cashflow_type')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['page'] = "addCashflow";
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function updateCashflow() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "updateCashflow";
			
			$this->db->order_by('cashflow_name');
			$data['cashflow_type'] = $this->db->get('cashflow_type')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['cashflow']=$this->db->where('cashflow_id',$this->uri->segment(3));
			$data['cashflow']=$this->db->get('cashflow')->result();
				
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function search() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			// Searching
			$searchparam = array(
				   'cashflow_type_id' => $this->input->post('cashflow_type_id'),
				   'cashflow_desc' => $this->input->post('cashflow_desc'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'debet_credit' => $this->input->post('debet_credit')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect('cashflowAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	
	public function index() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->db->order_by('cashflow_name');
			$data['cashflow_type'] = $this->db->get('cashflow_type')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
		  
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/cashflowAction/index");
			$config["total_rows"] = $this->CashflowModel->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["listCashflow"] = $this->CashflowModel->fetch_cashflow($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data['page']="listCashflow";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */