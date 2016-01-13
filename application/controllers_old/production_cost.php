<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Production_cost extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('production_cost_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->db->where('option_type','PROD');
			$this->db->where('option_root_id is not null');
			$this->db->order_by('option_desc');
			$data['prod_cost_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/production_cost/lists");
			$config["total_rows"] = $this->production_cost_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_production_cost"] = $this->production_cost_model->fetch_production_cost($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "productionCostList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// insert into production_cost
			$production_cost = array(
				   'production_cost_type_id' => $this->input->post('production_cost_type_id'),
				   'production_cost_date' => date('Y-m-d', strtotime($this->input->post('production_cost_date'))),
				   'production_cost_desc' => $this->input->post('production_cost_desc'),
				   'production_cost_nominal' => $this->input->post('production_cost_nominal_cash') + $this->input->post('production_cost_nominal_credit'),
				   'bank_account_id' => $this->input->post('bank_account_id')
				);
			$this->db->insert('tb_production_cost', $production_cost); 
			$msg .= '<p>Production Cost ('.$this->input->post('production_cost_desc').') has been added..!</p>';
			$production_cost_id = $this->db->insert_id();
			
			// updating cash if paid by cash
			if ($this->input->post('production_cost_nominal_cash') > 0) {
				$cash = array (
					'cash_type_id' => $this->input->post('production_cost_type_id'),
					'cash_date' => date('Y-m-d', strtotime($this->input->post('production_cost_date'))),
					'cash_desc' => $this->input->post('production_cost_desc'),
					'cash_nominal' => "-".$this->input->post('production_cost_nominal_cash'),
					'bank_account_id' => $this->input->post('bank_account_id'),
					'production_cost_id' => $production_cost_id
					
				);
				$this->db->insert('tb_cash', $cash); 
				$msg .= '<p>Cash has been decreased '. $this->input->post('production_cost_nominal_cash') .' rupiahs!</p>';
			}
			
			// updating liabilities if paid by credit
			if ($this->input->post('production_cost_nominal_credit') > 0 && $this->input->post('liabilities_type_id') != '') {
				$liabilities = array (
					'liabilities_type_id' => $this->input->post('liabilities_type_id'),
					'liabilities_date' => date('Y-m-d', strtotime($this->input->post('production_cost_date'))),
					'liabilities_desc' => $this->input->post('production_cost_desc'),
					'liabilities_nominal' => $this->input->post('production_cost_nominal_credit'),
					'liabilities_cause_id' => $this->input->post('production_cost_type_id'),
					'production_cost_id' => $production_cost_id
				);
				$this->db->insert('tb_liabilities', $liabilities); 
				$msg .= '<p>Liabilities has been increased '. $this->input->post('production_cost_nominal_credit') .' rupiahs!</p>';
			}
			
			$this->session->set_flashdata('success_message',$msg);
			
			// add to activity
			$session_data = $this->session->userdata('logged_in');
			$activity_desc = 'PRODUCTION COST - ADD [ <b>'.$this->input->post('production_cost_desc').' </b>]';
			$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
			
			redirect(site_url('production_cost/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating production_cost
			$production_cost = array(
				   'production_cost_date' => date('Y-m-d', strtotime($this->input->post('production_cost_date'))),
				   'production_cost_desc' => $this->input->post('production_cost_desc'),
				   'production_cost_nominal' => $this->input->post('production_cost_nominal_cash') + $this->input->post('production_cost_nominal_credit'),
				   'production_cost_type_id' => $this->input->post('production_cost_type_id'),
				   'bank_account_id' => $this->input->post('bank_account_id')
			);
				
			$this->db->where('production_cost_id', $this->input->post('production_cost_id')); 
			$this->db->update('tb_production_cost', $production_cost);
			
			$msg .= '<p>Production Cost has been updated</p>';

			// updating cash
			$cash = array (
					'cash_type_id' => $this->input->post('production_cost_type_id'),
					'cash_date' => date('Y-m-d', strtotime($this->input->post('production_cost_date'))),
					'cash_desc' => $this->input->post('production_cost_desc'),
					'cash_nominal' => "-".$this->input->post('production_cost_nominal_cash'),
					'bank_account_id' => $this->input->post('bank_account_id'),
					'production_cost_id' => $this->input->post('production_cost_id')					
			);
			
			$this->db->where('production_cost_id',$this->input->post('production_cost_id'));
			$query=$this->db->get('tb_cash');
			if ($query->num_rows > 0) {
				$this->db->where('production_cost_id', $this->input->post('production_cost_id')); 
				$this->db->update('tb_cash', $cash);
			} else {
				$this->db->insert('tb_cash', $cash); 
			}
			
			$msg .= '<p>Cash has been decreased '. $this->input->post('production_cost_nominal_cash') .' rupiahs!</p>';
			
			// updating credit / liabilities
			$liabilities = array (
					'liabilities_type_id' => $this->input->post('liabilities_type_id'),
					'liabilities_date' => date('Y-m-d', strtotime($this->input->post('production_cost_date'))),
					'liabilities_desc' => $this->input->post('production_cost_desc'),
					'liabilities_nominal' => $this->input->post('production_cost_nominal_credit'),
					'liabilities_cause_id' => $this->input->post('production_cost_type_id'),
					'production_cost_id' => $this->input->post('production_cost_id')
				);
			
			$this->db->where('production_cost_id',$this->input->post('production_cost_id'));
			$query=$this->db->get('tb_liabilities');
			if ($query->num_rows > 0) {
				$this->db->where('production_cost_id', $this->input->post('production_cost_id')); 
				$this->db->update('tb_liabilities', $liabilities); 
			} else {
				$this->db->insert('tb_liabilities', $liabilities); 
			}
			
			$msg .= '<p>Liabilities has been increased '. $this->input->post('production_cost_nominal_credit') .' rupiahs!</p>';
			
			// add to activity
			$session_data = $this->session->userdata('logged_in');
			$activity_desc = 'PRODUCTION COST - ADD [ <b>'.$this->input->post('production_cost_desc').' </b>]';
			$this->activity_model->add_activity($session_data['user_id'], $activity_desc);
			
			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('production_cost/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_production_cost', array('production_cost_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_cash', array('production_cost_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_liabilities', array('production_cost_id' => $this->uri->segment(3))); 
			
			$msg = 'Production Costs was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('production_cost/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->where('option_type', 'PROD');
			$this->db->where('option_root_id is not null');
			$this->db->order_by('option_desc');
			$data['production_cost_type'] = $this->db->get('tb_options')->result();
			
			$this->db->where('option_type', 'LIABILITY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['page'] = "productionCostAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "productionCostUpdate";
			
			$this->db->where('option_type', 'PROD');
			$this->db->where('option_root_id is not null');
			$this->db->order_by('option_desc');
			$data['production_cost_type'] = $this->db->get('tb_options')->result();
			
			$this->db->where('option_type', 'LIABILITY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// QUERY EXPENSE
			$this->db->select('*, tb_production_cost.production_cost_id AS main_production_cost_id');
			$this->db->from('tb_production_cost');
			$this->db->join('tb_cash', 'tb_cash.production_cost_id = tb_production_cost.production_cost_id','left');
			$this->db->join('tb_liabilities', 'tb_liabilities.production_cost_id = tb_production_cost.production_cost_id','left');
			$this->db->where('tb_production_cost.production_cost_id',$this->uri->segment(3));
			$data['production_cost'] = $this->db->get()->result();
			
			$this->db->where('production_cost_id',$this->uri->segment(3));
			$query=$this->db->get('tb_production_cost');
			if ($query->num_rows == 0) {
				$msg = '<p>Production Cost not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('production_cost/lists'));
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
				   'production_cost_type_id' => $this->input->post('production_cost_type_id'),
				   'production_cost_desc' => $this->input->post('production_cost_desc'),
				   'bank_account_id' => $this->input->post('bank_account_id')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('production_cost/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */