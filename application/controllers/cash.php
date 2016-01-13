<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cash extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('cash_model');
		$this->load->model('activity_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	public function pindahin() {
		if($this->session->userdata('logged_in')) {
			$cash_out = array(
				   'cash_date' => date('Y-m-d', strtotime($this->input->post('cash_date'))),
				   'cash_desc' => $this->input->post('cash_desc'),
				   'cash_nominal' => '-'.$this->input->post('cash_nominal'),
				   'cash_type_id' => 53,
				   'bank_account_id' => $this->input->post('bank_account_id_out')
				);
			$this->db->insert('tb_cash', $cash_out); 
			
			$cash_in = array(
				   'cash_date' => date('Y-m-d', strtotime($this->input->post('cash_date'))),
				   'cash_desc' => $this->input->post('cash_desc'),
				   'cash_nominal' => $this->input->post('cash_nominal'),
				   'cash_type_id' => 53,
				   'bank_account_id' => $this->input->post('bank_account_id_in')
				);
			$this->db->insert('tb_cash', $cash_in); 
			
			$msg = '<p>Cash ('.$this->input->post('cash_nominal').') has been moved..!</p>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('cash/lists'));
		} else {
			redirect(site_url('login'));
		}
	
	
	}
	
	public function pindahan() {
		if($this->session->userdata('logged_in')) {	
		
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['page'] = "cashMove";
			$this->load->view('dashboard',$data);
			
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->db->order_by('option_desc');
			$data['cash_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// SEARCHING TERMS
			$cash_type_id = '';
			$cash_desc = '';
			$bank_account_id = '';
			$startdate = '2015-01-01';
			
			$searchterm = $this->session->userdata('searchterm');
			if ($searchterm != null){
				
				if (!empty($searchterm['cash_type_id'])) {
					$cash_type_id = $searchterm['cash_type_id'];
				}
				
				if (!empty($searchterm['cash_desc'])) {
					$cash_desc = $searchterm['cash_desc'];
				}
				
				if (!empty($searchterm['bank_account_id'])) {
					$bank_account_id = $searchterm['bank_account_id'];
				}
				
				if (!empty($searchterm['startdate'])) {
					$startdate = $searchterm['startdate'];
				}
			}			
			
			$data['startdate'] = $startdate;
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/cash/lists");
			$config["total_rows"] = $this->cash_model->record_count($searchterm);
			$config["per_page"] = 100;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			// QUERY SALDO AWAL
			$this->db->select('sum(cash_nominal) as balance');
			$this->db->from('tb_cash');
			
			if ($cash_type_id != '') {
				$this->db->where('cash_type_id', $cash_type_id);
			}
			if ($cash_desc != '') {
				$this->db->like('cash_desc', $cash_desc);
			}
			if ($bank_account_id != '') {
				$this->db->where('bank_account_id', $bank_account_id);
			}
			if ($startdate != '') {
				$this->db->where('cash_date <', $startdate);
			}
			
			$this->db->limit(1);
			$cash = $this->db->get();
			$data['first_balance'] = $cash->row()->balance;
			
			$data["list_cash"] = $this->cash_model->fetch_cash($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "cashList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function search() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			
			$period = $this->uri->segment(3);
			$cash_type_id = $this->uri->segment(4);
			$searchparam = null;
			if (!empty($cash_type_id)) {
				$searchparam = array(	   
					'cash_type_id' => $cash_type_id,
					'period' => $period
				);
			} else {						
				// Searching
				$searchparam = array(
					   'cash_type_id' => $this->input->post('cash_type_id'),
					   'cash_desc' => $this->input->post('cash_desc'),
					   'bank_account_id' => $this->input->post('bank_account_id'),
					   'startdate' => date('Y-m-d', strtotime($this->input->post('startdate'))),
					   'enddate' => date('Y-m-d', strtotime($this->input->post('enddate'))),
				);
			}
			
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('cash/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {	
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$this->db->where('option_type', 'CASH');
			$this->db->order_by('option_desc');
			$data['production_options'] = $this->db->get('tb_options')->result();
			
			
			$data['page'] = "cashflowAdd";
			$this->load->view('dashboard',$data);
			
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {	
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$this->db->where('option_type', 'OTHER');
			$this->db->order_by('option_desc');
			$data['production_options'] = $this->db->get('tb_options')->result();
			
			// QUERY CASH
			$this->db->where('cash_id',$this->uri->segment(3));
			$data['cash'] = $this->db->get('tb_cash')->result();
			
			$this->db->where('cash_id',$this->uri->segment(3));
			$query=$this->db->get('tb_cash');
			if ($query->num_rows == 0) {
				$msg = '<p>Cash not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('expense/lists'));
			}
			
			$data['page'] = "cashflowUpdate";
			$this->load->view('dashboard',$data);
			
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
		
			$msg = 'Nothing Happen';
			
			if ($this->input->post('in_out') == '-') {
				$cash_nominal = $this->input->post('in_out').$this->input->post('cash_nominal');
			} else {
				$cash_nominal = $this->input->post('cash_nominal');
			}
				$cash_out = array(
					   'cash_date' => date('Y-m-d', strtotime($this->input->post('cash_date'))),
					   'cash_desc' => $this->input->post('cash_desc'),
					   'cash_nominal' => $cash_nominal,
					   'cash_type_id' => $this->input->post('cash_type_id'),
					   'bank_account_id' => $this->input->post('bank_account_id')
					);
				$this->db->insert('tb_cash', $cash_out); 
				
				$msg = '<p>Cash ('.$this->input->post('cash_nominal').') has been decreased..!</p>';
			

				// dont forget the activity
				// add to activity
				$session_data = $this->session->userdata('logged_in');
				$activity_desc = 'CASHFLOW - Add New [DESC : <b>'.$this->input->post('cash_desc').'</b> | 
									Nominal <b> '.$this->input->post('cash_nominal').'</b>]';
				$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
				
				$this->session->set_flashdata('success_message',$msg);
			
			
			redirect(site_url('cash/lists'));
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			
			
			if ($this->input->post('in_out') == '-') {
				$cash_nominal = $this->input->post('in_out').$this->input->post('cash_nominal');
			} else {
				$cash_nominal = $this->input->post('cash_nominal');
			}
			// updating cash
				$cash = array (
						'cash_type_id' => $this->input->post('cash_type_id'),
						'cash_date' => date('Y-m-d', strtotime($this->input->post('cash_date'))),
						'cash_desc' => $this->input->post('cash_desc'),
						'cash_nominal' => $cash_nominal,
						'bank_account_id' => $this->input->post('bank_account_id'),
					);
				
				$this->db->where('cash_id',$this->input->post('cash_id'));
				$query=$this->db->get('tb_cash');
				if ($query->num_rows > 0) {
					$this->db->where('cash_id', $this->input->post('cash_id')); 
					$this->db->update('tb_cash', $cash);
				} else {
					$this->db->insert('tb_cash', $cash); 
				}
				
				$msg .= '<p>Cash has been decreased '. $this->input->post('expense_nominal_cash') .' rupiahs!</p>';
							
				// add to activity
				$session_data = $this->session->userdata('logged_in');
				$activity_desc = 'CASHFLOW - Update [DESC : <b>'.$this->input->post('cash_desc').'</b> | 
									 Nominal <b> '.$this->input->post('cash_nominal').'</b>]';
				$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
				
				
				$this->session->set_flashdata('success_message',$msg);	
				redirect(site_url('cash/lists'));
			
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_cash', array('cash_id' => $this->uri->segment(3))); 
			
			$msg = 'Cash was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('cash/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */