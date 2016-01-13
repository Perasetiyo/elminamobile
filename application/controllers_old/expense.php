<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expense extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('expense_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->db->where('option_type','EXP');
			$this->db->order_by('option_desc');
			$data['expense_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/expense/lists");
			$config["total_rows"] = $this->expense_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_expense"] = $this->expense_model->fetch_expense($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "expenseList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// insert into expense
			$expense = array(
				   'expense_type_id' => $this->input->post('expense_type_id'),
				   'expense_date' => date('Y-m-d', strtotime($this->input->post('expense_date'))),
				   'expense_desc' => $this->input->post('expense_desc'),
				   'expense_nominal' => $this->input->post('expense_nominal_cash') + $this->input->post('expense_nominal_credit'),
				   'bank_account_id' => $this->input->post('bank_account_id')
				);
			$this->db->insert('tb_expense', $expense); 
			$msg .= '<p>Expense ('.$this->input->post('expense_desc').') has been added..!</p>';
			$expense_id = $this->db->insert_id();
			
			// insert into income --> EXPENSE DECREASE INCOME / EARNINGS / PROFIT
			$subtotal_income = $this->input->post('expense_nominal_cash') + $this->input->post('expense_nominal_credit');
			$income = array(
				   'income_type_id' => $this->input->post('expense_type_id'),
				   'income_date' => date('Y-m-d', strtotime($this->input->post('expense_date'))),
				   'income_desc' => 'Expense for : '.$this->input->post('expense_desc'),
				   'income_nominal' => "-".$subtotal_income,
				   'expense_id' => $expense_id
				);
			$this->db->insert('tb_income', $income); 
			$msg .= '<p>Income decreased for ('.$this->input->post('expense_desc').') : '. $this->input->post('expense_nominal_cash') + $this->input->post('expense_nominal_credit') .'rupiahs!</p>';
			
			// updating cash if paid by cash
			if ($this->input->post('expense_nominal_cash') > 0) {
				$cash = array (
					'cash_type_id' => $this->input->post('expense_type_id'),
					'cash_date' => date('Y-m-d', strtotime($this->input->post('expense_date'))),
					'cash_desc' => $this->input->post('expense_desc'),
					'cash_nominal' => "-".$this->input->post('expense_nominal_cash'),
					'bank_account_id' => $this->input->post('bank_account_id'),
					'expense_id' => $expense_id
					
				);
				$this->db->insert('tb_cash', $cash); 
				$msg .= '<p>Cash has been decreased '. $this->input->post('expense_nominal_cash') .' rupiahs!</p>';
			}
			
			// updating liabilities if paid by credit
			if ($this->input->post('expense_nominal_credit') > 0 && $this->input->post('liabilities_type_id') != '') {
				$liabilities = array (
					'liabilities_type_id' => $this->input->post('liabilities_type_id'),
					'liabilities_date' => date('Y-m-d', strtotime($this->input->post('expense_date'))),
					'liabilities_desc' => $this->input->post('expense_desc'),
					'liabilities_nominal' => $this->input->post('expense_nominal_credit'),
					'liabilities_cause_id' => $this->input->post('expense_type_id'),
					'expense_id' => $expense_id
				);
				$this->db->insert('tb_liabilities', $liabilities); 
				$msg .= '<p>Liabilities has been increased '. $this->input->post('expense_nominal_credit') .' rupiahs!</p>';
			}
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('expense/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating expense
			$expense = array(
				   'expense_date' => date('Y-m-d', strtotime($this->input->post('expense_date'))),
				   'expense_desc' => $this->input->post('expense_desc'),
				   'expense_nominal' => $this->input->post('expense_nominal_cash') + $this->input->post('expense_nominal_credit'),
				   'expense_type_id' => $this->input->post('expense_type_id'),
				   'bank_account_id' => $this->input->post('bank_account_id')
				);
				
			$this->db->where('expense_id', $this->input->post('expense_id')); 
			$this->db->update('tb_expense', $expense);
			
			$msg .= '<p>Expense has been updated</p>';

			
			// insert into income --> EXPENSE DECREASE INCOME / EARNINGS / PROFIT
			$subtotal_income = $this->input->post('expense_nominal_cash') + $this->input->post('expense_nominal_credit');
			$income = array(
				   'income_type_id' => $this->input->post('expense_type_id'),
				   'income_date' => date('Y-m-d', strtotime($this->input->post('expense_date'))),
				   'income_desc' => 'Expense for : '.$this->input->post('expense_desc'),
				   'income_nominal' => "-".$subtotal_income,
				   'expense_id' => $this->input->post('expense_id')
				);
			$this->db->where('expense_id',$this->input->post('expense_id'));
			$query=$this->db->get('tb_income');
			
			if ($query->num_rows > 0) {
				$this->db->where('expense_id', $this->input->post('expense_id')); 
				$this->db->update('tb_income', $income);
			} else {
				$this->db->insert('tb_income', $income); 
			}
			
			$msg .= '<p>Income decreased '. $this->input->post('expense_nominal_cash') + $this->input->post('expense_nominal_credit') .'rupiahs!</p>';

			// updating cash
			$cash = array (
					'cash_type_id' => $this->input->post('expense_type_id'),
					'cash_date' => date('Y-m-d', strtotime($this->input->post('expense_date'))),
					'cash_desc' => $this->input->post('expense_desc'),
					'cash_nominal' => "-".$this->input->post('expense_nominal_cash'),
					'bank_account_id' => $this->input->post('bank_account_id'),
					'expense_id' => $this->input->post('expense_id')
					
				);
			
			$this->db->where('expense_id',$this->input->post('expense_id'));
			$query=$this->db->get('tb_cash');
			if ($query->num_rows > 0) {
				$this->db->where('expense_id', $this->input->post('expense_id')); 
				$this->db->update('tb_cash', $cash);
			} else {
				$this->db->insert('tb_cash', $cash); 
			}
			
			$msg .= '<p>Cash has been decreased '. $this->input->post('expense_nominal_cash') .' rupiahs!</p>';
			
			// updating credit / liabilities
			$liabilities = array (
					'liabilities_type_id' => $this->input->post('liabilities_type_id'),
					'liabilities_date' => date('Y-m-d', strtotime($this->input->post('expense_date'))),
					'liabilities_desc' => $this->input->post('expense_desc'),
					'liabilities_nominal' => $this->input->post('expense_nominal_credit'),
					'liabilities_cause_id' => $this->input->post('expense_type_id'),
					'expense_id' => $this->input->post('expense_id')
				);
			
			$this->db->where('expense_id',$this->input->post('expense_id'));
			$query=$this->db->get('tb_liabilities');
			if ($query->num_rows > 0) {
				$this->db->where('expense_id', $this->input->post('expense_id')); 
				$this->db->update('tb_liabilities', $liabilities); 
			} else {
				$this->db->insert('tb_liabilities', $liabilities); 
			}
			
			$msg .= '<p>Liabilities has been increased '. $this->input->post('expense_nominal_credit') .' rupiahs!</p>';
			
			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('expense/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_expense', array('expense_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_cash', array('expense_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_liabilities', array('expense_id' => $this->uri->segment(3))); 
			
			$msg = 'Expenses was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('expense/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->where('option_type', 'EXP');
			$this->db->order_by('option_desc');
			$data['expense_type'] = $this->db->get('tb_options')->result();
			
			$this->db->where('option_type', 'LIABILITY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['page'] = "expenseAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "expenseUpdate";
			
			$this->db->where('option_type', 'EXP');
			$this->db->order_by('option_desc');
			$data['expense_type'] = $this->db->get('tb_options')->result();
			
			$this->db->where('option_type', 'LIABILITY');
			$this->db->order_by('option_desc');
			$data['liabilities_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// QUERY EXPENSE
			$this->db->select('*, tb_expense.expense_id AS main_expense_id');
			$this->db->from('tb_expense');
			$this->db->join('tb_cash', 'tb_cash.expense_id = tb_expense.expense_id','left');
			$this->db->join('tb_liabilities', 'tb_liabilities.expense_id = tb_expense.expense_id','left');
			$this->db->where('tb_expense.expense_id',$this->uri->segment(3));
			$data['expense'] = $this->db->get()->result();
			
			$this->db->where('expense_id',$this->uri->segment(3));
			$query=$this->db->get('tb_expense');
			if ($query->num_rows == 0) {
				$msg = '<p>Expense not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('expense/lists'));
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
				   'expense_type_id' => $this->input->post('expense_type_id'),
				   'expense_desc' => $this->input->post('expense_desc'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'debet_credit' => $this->input->post('debet_credit')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('expense/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */