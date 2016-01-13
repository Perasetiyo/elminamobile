<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class acrec extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('acrec_model');
		$this->load->model('activity_model');
		$this->load->model('GenericModel');
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
			$config["base_url"] = base_url("index.php/acrec/lists");
			$config["total_rows"] = $this->acrec_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_acrec"] = $this->acrec_model->fetch_acrec($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "acrecList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// insert into acrec
			$acrec = array(
				   'acrec_type_id' => $this->input->post('acrec_type_id'),
				   'acrec_date' => date('Y-m-d', strtotime($this->input->post('acrec_date'))),
				   'acrec_desc' => $this->input->post('acrec_desc'),
				   'acrec_nominal' => $this->input->post('acrec_nominal')
				);
			$this->db->insert('tb_acrec', $acrec);
			$acrec_id = $this->db->insert_id();
			$msg += '<p>Account receivable ('.$this->input->post('acrec_desc').') has been added..!</p><br/>';
			
			// insert into cash / decrease cash nominal
			$cash = array(
				   'cash_type_id' => $this->input->post('acrec_type_id'),
				   'cash_date' => date('Y-m-d', strtotime($this->input->post('acrec_date'))),
				   'cash_desc' => $this->input->post('acrec_desc'),
				   'cash_nominal' => '-'.$this->input->post('acrec_nominal'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'acrec_id' => $acrec_id
				);
			$this->db->insert('tb_cash', $cash); 
			$msg += '<p>Cash has beed decreased ('.$this->input->post('acrec_desc').')!</p><br/>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('acrec/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating acrec
			$acrec = array(
				   'acrec_type_id' => $this->input->post('acrec_type_id'),
				   'acrec_date' => date('Y-m-d', strtotime($this->input->post('acrec_date'))),
				   'acrec_desc' => $this->input->post('acrec_desc'),
				   'acrec_nominal' => $this->input->post('acrec_nominal')
				);
				
			$this->db->where('acrec_id', $this->input->post('acrec_id')); 
			$this->db->update('tb_acrec', $acrec);
			
			$msg .= '<p>Account receivable has been updated</p>';
			
			// insert into cash / decrease cash nominal
			$cash = array(
				   'cash_type_id' => $this->input->post('acrec_type_id'),
				   'cash_date' => date('Y-m-d', strtotime($this->input->post('acrec_date'))),
				   'cash_desc' => $this->input->post('acrec_desc'),
				   'cash_nominal' => '-'.$this->input->post('acrec_nominal'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'acrec_id' => $this->input->post('acrec_id')
				);
				
			$this->db->where('acrec_id', $this->input->post('acrec_id')); 
			$this->db->update('tb_cash', $cash); 
			$msg += '<p>Cash has beed decreased ('.$this->input->post('acrec_desc').')!</p><br/>';
			
			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('/acrec/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_acrec', array('acrec_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_cash', array('acrec_id' => $this->uri->segment(3))); 
			
			$msg = 'acrec was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('/acrec/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->where('option_type', 'ACT_REC');
			$this->db->order_by('option_desc');
			$data['acrec_type'] = $this->db->get('tb_options')->result();
			
			
			// get bank account
			$this->db->order_by('bank_account_name');
			$data['bank_account']=$this->db->get('bank_account')->result();
			
			$data['page'] = "acrecAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "acrecUpdate";
			
			// get bank account
			$this->db->order_by('bank_account_name');
			$data['bank_account']=$this->db->get('bank_account')->result();
			
			$this->db->where('option_type', 'ACT_REC');
			$this->db->order_by('option_desc');
			$data['acrec_type'] = $this->db->get('tb_options')->result();
			
			// QUERY acrec
			$this->db->where('tb_acrec.acrec_id',$this->uri->segment(3));
			$this->db->join('tb_cash', 'tb_cash.acrec_id = tb_acrec.acrec_id','left');
			$data['acrec'] = $this->db->get('tb_acrec')->result();
			
			$this->db->where('acrec_id',$this->uri->segment(3));
			$query=$this->db->get('tb_acrec');
			if ($query->num_rows == 0) {
				$msg = '<p>Acrec not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('acrec/lists'));
			}
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function search() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			$acrec_type_id = $this->uri->segment(3);
			$searchparam = null;
			if (!empty($acrec_type_id)) {
				$searchparam = array(	   
					'acrec_type_id' => $acrec_type_id
				);
			} else {			
				// Searching
				$searchparam = array(
					   'acrec_type_id' => $this->input->post('acrec_type_id'),
					   'acrec_desc' => $this->input->post('acrec_desc')
				);
			}
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('acrec/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */