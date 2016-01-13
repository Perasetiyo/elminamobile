<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Liabilities extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('liabilities_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			
			$this->db->where('option_type','LIABILITY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/liabilities/lists");
			$config["total_rows"] = $this->liabilities_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_liabilities"] = $this->liabilities_model->fetch_liabilities($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "liabilitiesList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
		
			$msg = "";
			// insert into liabilities
			$liabilities = array(
				   'liabilities_date' => date('Y-m-d', strtotime($this->input->post('liabilities_date'))),
				   'liabilities_desc' => $this->input->post('liabilities_desc'),
				   'liabilities_nominal' => $this->input->post('liabilities_nominal'),
				   'liabilities_type_id' => $this->input->post('liabilities_type_id'),
				   'liabilities_cause_id' => 3
				);
			$this->db->insert('tb_liabilities', $liabilities);
			$msg .= '<p>Liabilities ('.$this->input->post('liabilities_desc').') has been added..!</p>';
			$liabilities_id = $this->db->insert_id();
			
			// ADD INTO CASH
			$cash = array(
				   'cash_date' => date('Y-m-d', strtotime($this->input->post('liabilities_date'))),
				   'cash_desc' => $this->input->post('liabilities_desc'),
				   'cash_nominal' => $this->input->post('liabilities_nominal'),
				   'cash_type_id' => 3,//$this->input->post('liabilities_type_id'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'liabilities_id' => $liabilities_id
				);
			$this->db->insert('tb_cash', $cash);
			$msg .= '<p>Cash from ('.$this->input->post('liabilities_desc').') has been added..!</p>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('liabilities/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating liabilities
			$liabilities = array(
				   'liabilities_date' => date('Y-m-d', strtotime($this->input->post('liabilities_date'))),
				   'liabilities_desc' => $this->input->post('liabilities_desc'),
				   'liabilities_nominal' => $this->input->post('liabilities_nominal'),
				   'liabilities_type_id' => $this->input->post('liabilities_type_id')
				);
			$this->db->where('liabilities_id', $this->input->post('liabilities_id')); 
			$this->db->update('tb_liabilities', $liabilities);
			
			$msg .= '<p>Liabilities has been updated</p>';
			
			// ADDING CASH
			$cash = array(
				   'cash_date' => date('Y-m-d', strtotime($this->input->post('liabilities_date'))),
				   'cash_desc' => $this->input->post('liabilities_desc'),
				   'cash_nominal' => $this->input->post('liabilities_nominal'),
				   'cash_type_id' => 3,//$this->input->post('liabilities_type_id'),
				   'bank_account_id' => $this->input->post('bank_account_id')
				);
			
			$this->db->where('liabilities_id', $this->input->post('liabilities_id')); 			
			$this->db->update('tb_cash', $cash);
			$msg += '<p>Cash of ('.$this->input->post('liabilities_desc').') has been updated..!</p><br/>';
						
			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('liabilities/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_liabilities', array('liabilities_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_cash', array('liabilities_id' => $this->uri->segment(3))); 
			
			$msg = 'Liabilities was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('liabilities/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->where('option_type', 'LIABILITY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['page'] = "liabilitiesAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "liabilitiesUpdate";
			
			$this->db->where('option_type', 'LIABILITY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// QUERY liabilities
			$this->db->select('*, tb_liabilities.liabilities_id AS main_liabilities_id');
			$this->db->join('tb_cash','tb_cash.liabilities_id = tb_liabilities.liabilities_id','left');
			$this->db->from('tb_liabilities');
			$this->db->where('tb_liabilities.liabilities_id',$this->uri->segment(3));
			$data['liabilities'] = $this->db->get()->result();
			
			$this->db->where('liabilities_id',$this->uri->segment(3));
			$query=$this->db->get('tb_liabilities');
			if ($query->num_rows == 0) {
				$msg = '<p>Liabilities not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('liabilities/lists'));
			}
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function search() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			$liabilities_type_id = $this->uri->segment(3);
			$liabilities_cause_id = $this->uri->segment(4);
			
			$searchparam = null;
			
			if (!empty($liabilities_type_id)) {
				$searchparam = array(	   
						'liabilities_type_id' => $liabilities_type_id,
						'liabilities_cause_id' => $liabilities_cause_id
				);
			} else {			
				// Searching
				$searchparam = array(
					   'liabilities_type_id' => $this->input->post('liabilities_type_id'),
					   'liabilities_desc' => $this->input->post('liabilities_desc'),
					   'bank_account_id' => $this->input->post('bank_account_id'),
					   'debet_credit' => $this->input->post('debet_credit')
				);
			}
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('liabilities/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function money_repaid() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->where('option_code', 'MON_REP');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['page'] = "liabilitiesMoneyRepaid";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function updateRepaid() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "liabilitiesUpdateRepaid";
			
			$this->db->where('option_code', 'MON_REP');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// QUERY liabilities
			$this->db->select('*, tb_liabilities.liabilities_id AS main_liabilities_id');
			$this->db->join('tb_cash','tb_cash.liabilities_id = tb_liabilities.liabilities_id','left');
			$this->db->from('tb_liabilities');
			$this->db->where('tb_liabilities.liabilities_id',$this->uri->segment(3));
			$data['liabilities'] = $this->db->get()->result();
			
			$this->db->where('liabilities_id',$this->uri->segment(3));
			$query=$this->db->get('tb_liabilities');
			if ($query->num_rows == 0) {
				$msg = '<p>Liabilities not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('liabilities/lists'));
			}
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doRepaid() {
		if($this->session->userdata('logged_in')) {
		
			$msg = "";
			// REDUCE LIABILITY
			$liabilities = array(
				   'liabilities_date' => date('Y-m-d', strtotime($this->input->post('liabilities_date'))),
				   'liabilities_desc' => $this->input->post('liabilities_desc'),
				   'liabilities_nominal' => '-'.$this->input->post('liabilities_nominal'),
				   'liabilities_type_id' => $this->input->post('liabilities_type_id')
				);
			$this->db->insert('tb_liabilities', $liabilities);
			$msg .= '<p>Liabilities ('.$this->input->post('liabilities_desc').') has been repaid..!</p>';
			$liabilities_id = $this->db->insert_id();
			
			// REDUCE CASH
			$cash = array(
				   'cash_date' => date('Y-m-d', strtotime($this->input->post('liabilities_date'))),
				   'cash_desc' => $this->input->post('liabilities_desc'),
				   'cash_nominal' => '-'.$this->input->post('liabilities_nominal'),
				   'cash_type_id' => $this->input->post('liabilities_type_id'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'liabilities_id' => $liabilities_id
				);
			$this->db->insert('tb_cash', $cash);
			$msg .= '<p>Cash from ('.$this->input->post('liabilities_desc').') has been reduced for money repaid..!</p>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('liabilities/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdateRepaid() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating liabilities
			$liabilities = array(
				   'liabilities_date' => date('Y-m-d', strtotime($this->input->post('liabilities_date'))),
				   'liabilities_desc' => $this->input->post('liabilities_desc'),
				   'liabilities_nominal' => '-'.$this->input->post('liabilities_nominal'),
				   'liabilities_type_id' => $this->input->post('liabilities_type_id')
				);
			$this->db->where('liabilities_id', $this->input->post('liabilities_id')); 
			$this->db->update('tb_liabilities', $liabilities);
			
			$msg .= '<p>Liabilities has been updated</p>';
			
			// ADDING CASH
			$cash = array(
				   'cash_date' => date('Y-m-d', strtotime($this->input->post('liabilities_date'))),
				   'cash_desc' => $this->input->post('liabilities_desc'),
				   'cash_nominal' => '-'.$this->input->post('liabilities_nominal'),
				   'cash_type_id' => $this->input->post('liabilities_type_id'),
				   'bank_account_id' => $this->input->post('bank_account_id')
				);
			
			$this->db->where('liabilities_id', $this->input->post('liabilities_id')); 			
			$this->db->update('tb_cash', $cash);
			$msg += '<p>Cash of ('.$this->input->post('liabilities_desc').') has been updated..!</p><br/>';
						
			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('liabilities/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */