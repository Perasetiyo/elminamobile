<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GeneralEvent extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('general_event_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
		
	}
	
	public function lists() {
	
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->db->where('option_type','GE_TYPE');
			$this->db->order_by('option_desc');
			$data['general_event_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/generalevent/list");
			$config["total_rows"] = $this->general_event_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_general_event"] = $this->general_event_model->fetch_general_event($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data['page']="generalEventList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	
	}
	
	public function reporting(){
		if($this->session->userdata('logged_in')) {
		// ====== SEDEKAH ===========
			$this->db->select_sum('general_event_nominal');
			$this->db->where('general_event_type_id',2);
			$this->db->where('general_event_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('general_event_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_sedekah'] = $this->db->get('general_event')->result();
		
		// ====== BELANJA ===========
			$this->db->select_sum('general_event_nominal');
			$this->db->where('general_event_type_id',4);
			$this->db->where('general_event_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('general_event_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_belanja'] = $this->db->get('general_event')->result();
		
		// ====== PENGELUARAN RUTIN ===========
			$this->db->select_sum('general_event_nominal');
			$this->db->where('general_event_type_id',3);
			$this->db->where('general_event_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('general_event_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_expense'] = $this->db->get('general_event')->result();
		
		// ====== GAJI ===========
			$this->db->select_sum('general_event_nominal');
			$this->db->where('general_event_type_id',7);
			$this->db->where('general_event_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('general_event_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_gaji'] = $this->db->get('general_event')->result();
		
		// ====== HUTANG ===========
			$this->db->select_sum('general_event_nominal');
			$this->db->where('general_event_type_id',5);
			$this->db->where('general_event_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('general_event_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_hutang'] = $this->db->get('general_event')->result();
		
		// ====== PIUTANG ===========
			$this->db->select_sum('general_event_nominal');
			$this->db->where('general_event_type_id',6);
			$this->db->where('general_event_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('general_event_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_piutang'] = $this->db->get('general_event')->result();
		
		// ====== KAS ===========
			$this->db->select_sum('general_event_nominal');
			$this->db->where('general_event_type_id',8);
			$this->db->where('general_event_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('general_event_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_kas'] = $this->db->get('general_event')->result();
		// ====== MODAL ===========
			$this->db->select_sum('general_event_nominal');
			$this->db->where('general_event_type_id',9);
			$this->db->where('general_event_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('general_event_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_modal'] = $this->db->get('general_event')->result();
		// ====== INVESTASI ===========
			$this->db->select_sum('general_event_nominal');
			$this->db->where('general_event_type_id',10);
			$this->db->where('general_event_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('general_event_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_investasi'] = $this->db->get('general_event')->result();
			
		// ====== OMSET ===========
			$this->db->select_sum('general_event_nominal');
			$this->db->where('general_event_type_id',1);
			$this->db->where('general_event_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('general_event_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_omset'] = $this->db->get('general_event')->result();
			
			
			
			$data['page'] = "detailgeneral_event";
			$data['startdate'] = date('d-M-y', strtotime($this->input->post('startdate')));
			$data['enddate'] = date('d-M-y', strtotime($this->input->post('enddate')));
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function detail(){
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
			$data['page'] = "detailgeneral_event";
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$general_event = array(
				   'general_event_type_id' => $this->input->post('general_event_type_id'),
				   'general_event_date' => date('Y-m-d', strtotime($this->input->post('general_event_date'))),
				   'general_event_desc' => $this->input->post('general_event_desc'),
				   'general_event_nominal' => $this->input->post('general_event_nominal'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'general_event_is_cash' => $this->input->post('general_event_is_cash'),
				   'order_id' => null
				);
			$this->db->insert('general_event', $general_event); 
			
			$msg = '<p>Event ('.$this->input->post('general_event_desc').') has been added..!</p>';
			$this->session->set_flashdata('success_message',$msg);
						
			redirect('generalevent/list', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function doUpdategeneral_event() {
		if($this->session->userdata('logged_in')) {
			$general_event = array(
				   'general_event_type_id' => $this->input->post('general_event_type_id'),
				   'general_event_date' => date('Y-m-d', strtotime($this->input->post('general_event_date'))),
				   'general_event_desc' => $this->input->post('general_event_desc'),
				   'general_event_nominal' => $this->input->post('general_event_nominal'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'debet_credit' => $this->input->post('debet_credit')
				);
				
			$this->db->where('general_event_id', $this->uri->segment(3)); 
			$this->db->update('general_event', $general_event);
			
			$msg = '<p>Arus Kas '.$this->input->post('product_name').' berhasil diupdate.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('general_eventAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}	
	}
	
	public function deletegeneral_event() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('general_event', array('general_event_id' => $this->uri->segment(3))); 
			
			$msg = '<p>Arus Kas berhasil dihapus dari muka bumi ini.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('general_eventAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function addgeneral_event() {
		if($this->session->userdata('logged_in')) {
			$this->db->order_by('general_event_name');
			$data['general_event_type'] = $this->db->get('general_event_type')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['page'] = "addgeneral_event";
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function updategeneral_event() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "updategeneral_event";
			
			$this->db->order_by('general_event_name');
			$data['general_event_type'] = $this->db->get('general_event_type')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['general_event']=$this->db->where('general_event_id',$this->uri->segment(3));
			$data['general_event']=$this->db->get('general_event')->result();
				
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
				   'general_event_type_id' => $this->input->post('general_event_type_id'),
				   'general_event_desc' => $this->input->post('general_event_desc'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'debet_credit' => $this->input->post('debet_credit')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect('general_eventAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */