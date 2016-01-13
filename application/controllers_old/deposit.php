<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('deposit_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	function find_customer() {
		if($this->session->userdata('logged_in')) {	
			$billing_name = $this->input->post('billing_name');
			$billing_phone = $this->input->post('billing_phone');
			if (!empty($billing_name)) {
				$this->db->like('billing_name', $billing_name);
			}
				
			if (!empty($billing_phone)) {
					$this->db->like('billing_phone', $billing_phone);
			}
			$this->db->order_by('billing_id');
			$data['list_customer'] = $this->db->get('billing')->result();
			
			$data['page'] = "depositCustomerList";
			$this->load->view('dashboard',$data);
		
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->db->where('option_type','redirect(site_url(');
			$this->db->order_by('option_desc');
			$data['deposit_type'] = $this->db->get('tb_options')->result();
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/deposit/lists");
			$config["total_rows"] = $this->deposit_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_deposit"] = $this->deposit_model->fetch_deposit($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "depositList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// insert into deposit
			$deposit = array (
			
				   'deposit_date' => date('Y-m-d', strtotime($this->input->post('deposit_date'))),
				   'deposit_desc' => $this->input->post('deposit_desc'),
				   'deposit_nominal' => $this->input->post('deposit_nominal'),
				   'billing_id' => $this->uri->segment(3)
				   
				);
			$this->db->insert('tb_deposit', $deposit); 
			$msg += '<p>deposit ('.$this->input->post('deposit_desc').') has been added..!</p><br/>';
			$deposit_id = $this->db->insert_id();
			
			// updating cash if paid by cash
			if ($this->input->post('deposit_nominal_cash') > 0) {
				$cash = array (
					'cash_type_id' => 5, //$this->input->post('deposit_type_id'),
					'cash_date' => date('Y-m-d', strtotime($this->input->post('deposit_date'))),
					'cash_desc' => $this->input->post('deposit_desc'),
					'cash_nominal' => $this->input->post('deposit_nominal_cash'),
					'bank_account_id' => $this->input->post('bank_account_id'),
					'deposit_id' => $deposit_id
					
				);
				$this->db->insert('tb_cash', $cash); 
				$msg += '<p>Cash has been increased '. $this->input->post('deposit_nominal_cash') .' rupiahs!</p><br/>';
			}
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('deposit/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating deposit
			$deposit = array(
				   'deposit_date' => date('Y-m-d', strtotime($this->input->post('deposit_date'))),
				   'deposit_desc' => $this->input->post('deposit_desc'),
				   'deposit_nominal' => $this->input->post('deposit_nominal_cash'),
				   'billing_id' => $this->uri->segment(3)
				);
				
			$this->db->where('deposit_id', $this->input->post('deposit_id')); 
			$this->db->update('tb_deposit', $deposit);
			
			$msg .= '<p>deposit has been updated</p>';
			
			// updating cash
			$cash = array (
					'cash_type_id' => 5, //$this->input->post('deposit_type_id'),
					'cash_date' => date('Y-m-d', strtotime($this->input->post('deposit_date'))),
					'cash_desc' => $this->input->post('deposit_desc'),
					'cash_nominal' => $this->input->post('deposit_nominal_cash'),
					'bank_account_id' => $this->input->post('bank_account_id'),
					'deposit_id' => $this->input->post('deposit_id')
				);
			
			$this->db->where('deposit_id',$this->input->post('deposit_id'));
			$query=$this->db->get('tb_cash');
			if ($query->num_rows > 0) {
				$this->db->where('deposit_id', $this->input->post('deposit_id')); 
				$this->db->update('tb_cash', $cash);
				$msg .= '<p>Cash has been updated. There are '. $this->input->post('deposit_nominal_cash') .' rupiahs for investment!</p>';
			} else {
				$this->db->insert('tb_cash', $cash); 
				$msg .= '<p>Cash has been increased '. $this->input->post('deposit_nominal_cash') .' rupiahs!</p>';
			}
			
			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('/deposit/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_deposit', array('deposit_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_cash', array('deposit_id' => $this->uri->segment(3))); 
			
			$msg = 'deposit was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('/deposit/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			// get customer data
			$this->db->where('billing_id', $this->uri->segment(3));
			$querybilling = $this->db->get('billing');
			
			foreach ($querybilling->result() as $row) {
				$data['billing_name'] = $row->billing_name;
			}
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['page'] = "depositAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "depositUpdate";
			
			$this->db->where('option_type', 'deposit');
			$this->db->order_by('option_desc');
			$data['deposit_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// QUERY deposit
			$this->db->select('*, tb_deposit.deposit_id AS main_deposit_id');
			$this->db->from('tb_deposit');
			$this->db->join('tb_cash', 'tb_cash.deposit_id = tb_deposit.deposit_id','left');
			$this->db->where('tb_deposit.deposit_id',$this->uri->segment(3));
			$data['deposit'] = $this->db->get()->result();
			
			$this->db->where('deposit_id',$this->uri->segment(3));
			$query=$this->db->get('tb_deposit');
			if ($query->num_rows == 0) {
				$msg = '<p>deposit not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('deposit/lists'));
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
				   'deposit_type_id' => $this->input->post('deposit_type_id'),
				   'deposit_desc' => $this->input->post('deposit_desc'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'debet_credit' => $this->input->post('debet_credit')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('deposit/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */