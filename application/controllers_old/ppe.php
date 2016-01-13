<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PPE extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('ppe_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->db->where('option_type','PPE');
			$this->db->order_by('option_desc');
			$data['ppe_type'] = $this->db->get('tb_options')->result();
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/ppe/lists");
			$config["total_rows"] = $this->ppe_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_ppe"] = $this->ppe_model->fetch_ppe($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "ppeList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// insert into ppe
			$ppe_nominal = $this->input->post('ppe_nominal_cash') + $this->input->post('ppe_nominal_credit');
			$ppe = array(
				   'ppe_date' => date('Y-m-d', strtotime($this->input->post('ppe_date'))),
				   'ppe_desc' => $this->input->post('ppe_desc'),
				   'ppe_nominal' => $ppe_nominal,
				   'ppe_type_id' => $this->input->post('ppe_type_id'),
				   'depreciation_nominal' => $this->input->post('depreciation_nominal'),
				   'ppe_age' => $this->input->post('ppe_age'),
				   'interval_in_month' => $this->input->post('interval_in_month'),
				);
				$this->db->insert('tb_ppe', $ppe); 
			$msg .= '<p>PPE ('.$this->input->post('ppe_desc').') has been added..!</p>';
			$ppe_id = $this->db->insert_id();
			
			
			// insert into netppe
			$netppe = array(
				   'ppe_id' => $ppe_id,
				   'netppe_nominal' => $ppe_nominal,
				   'netppe_type_id' => $this->input->post('ppe_type_id'),
				   'netppe_date' => date('Y-m-d', strtotime($this->input->post('ppe_date'))),
				);
			$this->db->insert('tb_netppe', $netppe);
				
			// updating cash if paid by cash
			if ($this->input->post('ppe_nominal_cash') > 0) {
				$cash = array (
					'cash_type_id' => $this->input->post('ppe_type_id'),
					'cash_date' => date('Y-m-d', strtotime($this->input->post('ppe_date'))),
					'cash_desc' => $this->input->post('ppe_desc'),
					'cash_nominal' => "-".$this->input->post('ppe_nominal_cash'),
					'bank_account_id' => $this->input->post('bank_account_id'),
					'ppe_id' => $ppe_id
					
				);
				$this->db->insert('tb_cash', $cash); 
				$msg .= '<p>Cash has been decreased '. $this->input->post('ppe_nominal_cash') .' rupiahs!</p>';
			}
			
			// updating liabilities if paid by credit
			if ($this->input->post('ppe_nominal_credit') > 0 && $this->input->post('liabilities_type_id') != '') {
				$liabilities = array (
					'liabilities_type_id' => $this->input->post('liabilities_type_id'),
					'liabilities_date' => date('Y-m-d', strtotime($this->input->post('ppe_date'))),
					'liabilities_desc' => $this->input->post('ppe_desc'),
					'liabilities_nominal' => $this->input->post('ppe_nominal_credit'),
					'liabilities_cause_id' => $this->input->post('ppe_type_id'),
					'ppe_id' => $ppe_id
				);
				$this->db->insert('tb_liabilities', $liabilities); 
				$msg .= '<p>Liabilities has been increased '. $this->input->post('ppe_nominal_credit') .' rupiahs!</p>';
			}
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('ppe/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating ppe
			$ppe_nominal = $this->input->post('ppe_nominal_cash') + $this->input->post('ppe_nominal_credit');
			$ppe = array(
				   'ppe_date' => date('Y-m-d', strtotime($this->input->post('ppe_date'))),
				   'ppe_desc' => $this->input->post('ppe_desc'),
				   'ppe_nominal' => $ppe_nominal,
				   'ppe_type_id' => $this->input->post('ppe_type_id'),
				   'depreciation_nominal' => $this->input->post('depreciation_nominal'),
				   'ppe_age' => $this->input->post('ppe_age'),
				   'interval_in_month' => $this->input->post('interval_in_month'),
				);
				
			$this->db->where('ppe_id', $this->input->post('ppe_id')); 
			$this->db->update('tb_ppe', $ppe);
			
			$msg .= '<p>PPE has been updated</p>';
			
			// updating into netppe
			$netppe = array(
				   'netppe_nominal' => $ppe_nominal,
				   'netppe_type_id' => $this->input->post('ppe_type_id'),
				   'netppe_date' => date('Y-m-d', strtotime($this->input->post('ppe_date')))
				);
			$this->db->where('ppe_id', $this->input->post('ppe_id'));
			$this->db->where('netppe_type_id', $this->input->post('ppe_type_id'));
			$this->db->update('tb_netppe', $netppe);
			
			// updating cash
			$cash = array (
					'cash_type_id' => $this->input->post('ppe_type_id'),
					'cash_date' => date('Y-m-d', strtotime($this->input->post('ppe_date'))),
					'cash_desc' => $this->input->post('ppe_desc'),
					'cash_nominal' => "-".$this->input->post('ppe_nominal_cash'),
					'bank_account_id' => $this->input->post('bank_account_id'),
					'ppe_id' => $this->input->post('ppe_id')
					
				);
			
			$this->db->where('ppe_id',$this->input->post('ppe_id'));
			$query=$this->db->get('tb_cash');
			if ($query->num_rows > 0) {
				$this->db->where('ppe_id', $this->input->post('ppe_id')); 
				$this->db->update('tb_cash', $cash);
			} else {
				$this->db->insert('tb_cash', $cash); 
			}
			
			$msg .= '<p>Cash has been decreased '. $this->input->post('ppe_nominal_cash') .' rupiahs!</p>';
			
			// updating credit / liabilities
			$liabilities = array (
					'liabilities_type_id' => $this->input->post('liabilities_type_id'),
					'liabilities_date' => date('Y-m-d', strtotime($this->input->post('ppe_date'))),
					'liabilities_desc' => $this->input->post('ppe_desc'),
					'liabilities_nominal' => $this->input->post('ppe_nominal_credit'),
					'liabilities_cause_id' => $this->input->post('ppe_type_id'),
					'ppe_id' => $this->input->post('ppe_id')
				);
			
			$this->db->where('ppe_id',$this->input->post('ppe_id'));
			$query=$this->db->get('tb_liabilities');
			if ($query->num_rows > 0) {
				$this->db->where('ppe_id', $this->input->post('ppe_id')); 
				$this->db->update('tb_liabilities', $liabilities); 
			} else {
				$this->db->insert('tb_liabilities', $liabilities); 
			}
			
			$msg .= '<p>Liabilities has been increased '. $this->input->post('ppe_nominal_credit') .' rupiahs!</p>';
			
			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('ppe/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_ppe', array('ppe_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_cash', array('ppe_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_liabilities', array('ppe_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_depreciation', array('ppe_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_netppe', array('ppe_id' => $this->uri->segment(3))); 
			
			$msg = '{PPE was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('ppe/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->where('option_type', 'PPE');
			$this->db->order_by('option_desc');
			$data['ppe_type'] = $this->db->get('tb_options')->result();
			
			$this->db->where('option_type', 'LIABILITY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['page'] = "ppeAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "ppeUpdate";
			
			$this->db->where('option_type', 'PPE');
			$this->db->order_by('option_desc');
			$data['ppe_type'] = $this->db->get('tb_options')->result();
			
			$this->db->where('option_type', 'LIABILITY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// QUERY ppe
			$this->db->select('*, tb_ppe.ppe_id AS main_ppe_id');
			$this->db->from('tb_ppe');
			$this->db->join('tb_cash', 'tb_cash.ppe_id = tb_ppe.ppe_id','left');
			$this->db->join('tb_liabilities', 'tb_liabilities.ppe_id = tb_ppe.ppe_id','left');
			$this->db->where('tb_ppe.ppe_id',$this->uri->segment(3));
			$data['ppe'] = $this->db->get()->result();
			
			$this->db->where('ppe_id',$this->uri->segment(3));
			$query=$this->db->get('tb_ppe');
			if ($query->num_rows == 0) {
				$msg = '<p>PPE not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('ppe/lists'));
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
				   'ppe_type_id' => $this->input->post('ppe_type_id'),
				   'ppe_desc' => $this->input->post('ppe_desc'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'debet_credit' => $this->input->post('debet_credit')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('ppe/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */