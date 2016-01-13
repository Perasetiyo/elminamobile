<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Depreciation extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('depreciation_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->db->order_by('ppe_desc');
			$data['ppe'] = $this->db->get('tb_ppe')->result();
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/depreciation/lists");
			$config["total_rows"] = $this->depreciation_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_depreciation"] = $this->depreciation_model->fetch_depreciation($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "depreciationList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function depreciate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			
			$this->db->where('ppe_id', $this->input->post('ppe_id')); 
			$query = $this->db->get('tb_ppe');
			
	        if($query->num_rows == 1) {
				$row = $query->row();
				
				// update ppe table which ppe_id like never before :p
				$ppe_current_period = $row->ppe_current_period + $row->interval_in_month;
				$ppe_has_been_paid = $row->ppe_has_been_paid + $row->depreciation_nominal;
				$new_ppe = array(
					'ppe_current_period' => $ppe_current_period,
					'ppe_has_been_paid' => $ppe_has_been_paid,
					);
					
					$this->db->where('ppe_id', $this->input->post('ppe_id'));
					$this->db->update('tb_ppe', $new_ppe);
			
				$msg .= '<p>PPE price has been decreased '.$row->depreciation_nominal.'. NOW : '.$row->depreciation_nominal - $ppe_has_been_paid.' rupiahs!</p>';
				
				// insert into depreciation
				$depreciation = array(
					   'ppe_id' => $this->input->post('ppe_id'),
					   'depreciation_nominal' => $row->depreciation_nominal,
					   'depreciation_period' => $ppe_current_period,
					   'depreciation_date' =>  date('Y-m-d', strtotime($this->input->post('depreciation_date'))),
					);
				$this->db->insert('tb_depreciation', $depreciation);
				$depreciation_id = $this->db->insert_id();
				
				$msg .= '<p>Depreciation history has been created..!</p>';
				
				// insert into netppe
				$netppe = array(
					   'ppe_id' => $this->input->post('ppe_id'),
					   'depreciation_id' => $depreciation_id,
					   'netppe_date' =>  date('Y-m-d', strtotime($this->input->post('depreciation_date'))),
					   'netppe_nominal' => '-'.$row->depreciation_nominal,
					   'netppe_type_id' => 25 //id of depreciation
					);
				$this->db->insert('tb_netppe', $netppe);
				
				
				// decrease EARNING BY ADDING EXPENSE & DECREASED INCOME
				// increase EXPENSE
				$expense = array (
						'expense_type_id' => 25, // id of depreciation //$this->input->post('expense_type_id'),
						'expense_date' => date('Y-m-d', strtotime($this->input->post('depreciation_date'))),
						'expense_desc' => 'Depreciation of '.$row->ppe_desc.'. Period : '.$ppe_current_period.' of '.$row->ppe_age,
						'expense_nominal' => $row->depreciation_nominal,
					);
				
				$this->db->insert('tb_expense', $expense);
				$expense_id = $this->db->insert_id();
				$msg .= '<p>Expense ('.$row->ppe_desc.') has been added..!</p>';
			
				// decrease INCOME
				$income = array(
				   'income_type_id' => 25,
				   'income_date' => date('Y-m-d', strtotime($this->input->post('depreciation_date'))),
				   'income_desc' => 'Expense for Depreciation of '.$row->ppe_desc.'. Period : '.$ppe_current_period.' of '.$row->ppe_age,
				   'income_nominal' => "-".$row->depreciation_nominal,
				   'expense_id' => $expense_id
				);
				$this->db->insert('tb_income', $income);
				$msg .= '<p>Income decreased for '.'Depreciation of '.$row->ppe_desc.'. Period : '.$ppe_current_period.' of '.$row->ppe_age.' : '.$row->depreciation_nominal.'rupiahs!</p>';				
				$this->session->set_flashdata('success_message',$msg);
			
			} else {
				
			}
			
						
			redirect(site_url('depreciation/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// insert into depreciation
			$depreciation = array(
				   'depreciation_date' => date('Y-m-d', strtotime($this->input->post('depreciation_date'))),
				   'ppe_id' => $this->input->post('ppe_id'),
				   'depreciation_nominal' => $this->input->post('depreciation_nominal')
				);
			$this->db->insert('tb_depreciation', $depreciation);
			$msg .= '<p>Depreciation history has been added..!</p>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('depreciation/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating depreciation
			$depreciation = array(
				   'depreciation_date' => date('Y-m-d', strtotime($this->input->post('depreciation_date'))),
				   'ppe_id' => $this->input->post('ppe_id'),
				   'depreciation_nominal' => $this->input->post('depreciation_nominal')
				);
				
			$this->db->where('depreciation_id', $this->input->post('depreciation_id')); 
			$this->db->update('tb_depreciation', $depreciation);
			
			$msg .= '<p>Depreciation has been updated</p>';

			
			// insert into income --> depreciation DECREASE INCOME / EARNINGS / PROFIT
			$subtotal_income = $this->input->post('depreciation_nominal_cash') + $this->input->post('depreciation_nominal_credit');
			$income = array(
				   'income_type_id' => $this->input->post('depreciation_type_id'),
				   'income_date' => date('Y-m-d', strtotime($this->input->post('depreciation_date'))),
				   'income_desc' => 'Depreciation for : '.$this->input->post('depreciation_desc'),
				   'income_nominal' => "-".$subtotal_income,
				   'depreciation_id' => $this->input->post('depreciation_id')
				);
			$this->db->where('depreciation_id',$this->input->post('depreciation_id'));
			$query=$this->db->get('tb_income');
			
			if ($query->num_rows > 0) {
				$this->db->where('depreciation_id', $this->input->post('depreciation_id')); 
				$this->db->update('tb_income', $income);
			} else {
				$this->db->insert('tb_income', $income); 
			}
			
			$msg .= '<p>Income decreased '. $this->input->post('depreciation_nominal_cash') + $this->input->post('depreciation_nominal_credit') .'rupiahs!</p>';

			// updating credit / liabilities
			$liabilities = array (
					'liabilities_type_id' => $this->input->post('liabilities_type_id'),
					'liabilities_date' => date('Y-m-d', strtotime($this->input->post('depreciation_date'))),
					'liabilities_desc' => $this->input->post('depreciation_desc'),
					'liabilities_nominal' => $this->input->post('depreciation_nominal_credit'),
					'liabilities_cause_id' => $this->input->post('depreciation_type_id'),
					'depreciation_id' => $this->input->post('depreciation_id')
				);
			
			$this->db->where('depreciation_id',$this->input->post('depreciation_id'));
			$query=$this->db->get('tb_liabilities');
			if ($query->num_rows > 0) {
				$this->db->where('depreciation_id', $this->input->post('depreciation_id')); 
				$this->db->update('tb_liabilities', $liabilities); 
			} else {
				$this->db->insert('tb_liabilities', $liabilities); 
			}
			
			$msg .= '<p>Liabilities has been increased '. $this->input->post('depreciation_nominal_credit') .' rupiahs!</p>';
			
			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('depreciation/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_depreciation', array('depreciation_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_cash', array('depreciation_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_liabilities', array('depreciation_id' => $this->uri->segment(3))); 
			
			$msg = 'Depreciations was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('depreciation/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->where('option_type', 'EXP');
			$this->db->order_by('option_desc');
			$data['depreciation_type'] = $this->db->get('tb_options')->result();
			
			$this->db->where('option_type', 'LIABILITY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['page'] = "depreciationAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "depreciationUpdate";
			
			$this->db->where('option_type', 'EXP');
			$this->db->order_by('option_desc');
			$data['depreciation_type'] = $this->db->get('tb_options')->result();
			
			$this->db->where('option_type', 'LIABILITY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// QUERY depreciation
			$this->db->select('*, tb_depreciation.depreciation_id AS main_depreciation_id');
			$this->db->from('tb_depreciation');
			$this->db->join('tb_cash', 'tb_cash.depreciation_id = tb_depreciation.depreciation_id','left');
			$this->db->join('tb_liabilities', 'tb_liabilities.depreciation_id = tb_depreciation.depreciation_id','left');
			$this->db->where('tb_depreciation.depreciation_id',$this->uri->segment(3));
			$data['depreciation'] = $this->db->get()->result();
			
			$this->db->where('depreciation_id',$this->uri->segment(3));
			$query=$this->db->get('tb_depreciation');
			if ($query->num_rows == 0) {
				$msg = '<p>Depreciation not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('depreciation/lists'));
			}
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function history_per_ppe() {
		if($this->session->userdata('logged_in')) {
			
			$ppe_id = $this->uri->segment(3);
			
			$this->db->where('tb_depreciation.ppe_id',$ppe_id);
			$this->db->join('tb_ppe', 'tb_ppe.ppe_id = tb_depreciation.ppe_id','left');
			$data["list_history_per_ppe"] = $this->db->get('tb_depreciation')->result();
			
			$data['page'] = "depreciationHistoryPerPpe";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function search() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			$ppe_id = $this->uri->segment(3);
			$searchparam = null;
			if (!empty($ppe_id)) {
				$searchparam = array (
					'ppe_id' => $ppe_id
				);
			} else {			
				// Searching
				$searchparam = array(
				   'ppe_id' => $this->input->post('ppe_id'),
				   'depreciation_desc' => $this->input->post('depreciation_desc'),
				);
			}
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('depreciation/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */