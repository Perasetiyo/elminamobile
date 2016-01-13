<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fixed_cost_history extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('fixed_cost_history_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// get month list
			$where = "option_type = 'MON'";
			$this->db->where($where);
			$this->db->order_by('option_type');
			$data['months'] = $this->db->get('tb_options')->result();
			
			// get bank list
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// get expense list
			$this->db->where('option_type','EXP');
			$this->db->order_by('option_desc');
			$data['fixed_cost_type'] = $this->db->get('tb_options')->result();
			
			
			// SEARCHING TERMS
			
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/fixed_cost_history/lists");
			$config["total_rows"] = $this->fixed_cost_history_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_fixed_cost_history"] = $this->fixed_cost_history_model->fetch_fixed_cost_history($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "fixedCostHistoryList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() { 
		if($this->session->userdata('logged_in')) {
			$msg = "";
			
			$fixed_cost_history_month = date('m', strtotime($this->input->post('fixed_cost_history_date')));			
			$pay_nominal = $this->input->post('pay_nominal');
			$bank_account_id = $this->input->post('bank_account_id');
			
			// QUERY FIXED COST DATA
			$this->db->where('fixed_cost_id', $this->input->post('fixed_cost_id'));
			$this->db->join('tb_options', 'tb_options.option_id = tb_fixed_cost.fixed_cost_type_id');
			$this->db->limit(1);
			$fixed_cost = $this->db->get('tb_fixed_cost');
			
			$fixed_cost_name = $fixed_cost->row()->fixed_cost_name;
			$fixed_cost_type_id = $fixed_cost->row()->fixed_cost_type_id;
			
			// QUERY MONTH / PERIOD
			$this->db->where('option_code', $fixed_cost_history_month);
			$this->db->where('option_type', 'MON');
			$this->db->limit(1);
			$month = $this->db->get('tb_options');
			$month_name = $month->row()->option_desc;
			
			// check total nominal fixed_cost_history of this month
			$this->db->select('*, sum(fixed_cost_history_nominal) as total_fixed_cost_history_nominal');
			$this->db->from('tb_fixed_cost_history h');
			$this->db->join('tb_fixed_cost f', 'f.fixed_cost_id = h.fixed_cost_id');
			$this->db->where('h.fixed_cost_history_month', $fixed_cost_history_month);
			$this->db->where('h.fixed_cost_id', $this->input->post('fixed_cost_id'));
			
			$query=$this->db->get();
			echo 'found fixed_cost_history : '.$query->num_rows;
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					if ($pay_nominal > ($row->fixed_cost_nominal * 2)) {
						$msg = '<p>Nominal too high, twice than monthly nominal!</p>';
						$this->session->set_flashdata('error_message',$msg);
					} else { 
						if (($row->total_fixed_cost_history_nominal + $pay_nominal) > $row->fixed_cost_nominal) {
						
							// nmsn = 900 + 150 - 1000 = 50
							$next_month_pay_nominal = ($row->total_fixed_cost_history_nominal + $pay_nominal) - $row->fixed_cost_nominal;
							
							// cmsn = 150 - 50 = 100
							$current_month_pay_nominal = $pay_nominal - $next_month_pay_nominal;
							
							// insert fixed_cost_history twice
							// 1. current month
							$fixed_cost_history1 = array(
								   'fixed_cost_id' => $this->input->post('fixed_cost_id'),
								   'fixed_cost_history_nominal' => $current_month_pay_nominal,
								   'fixed_cost_history_month' => $fixed_cost_history_month,
								   'fixed_cost_history_date' => date('Y-m-d', strtotime($this->input->post('fixed_cost_history_date'))),
								   'bank_account_id' => $bank_account_id
								);
							$this->db->insert('tb_fixed_cost_history', $fixed_cost_history1); 
							
							// 2. next month
							$fixed_cost_history2 = array(
								   'fixed_cost_id' => $this->input->post('fixed_cost_id'),
								   'fixed_cost_history_nominal' => $next_month_pay_nominal,
								   'fixed_cost_history_month' => $fixed_cost_history_month + 1,
								   'fixed_cost_history_date' => date('Y-m-d', strtotime($this->input->post('fixed_cost_history_date'))),
								   'bank_account_id' => $bank_account_id
								);
							$this->db->insert('tb_fixed_cost_history', $fixed_cost_history2); 
							
							$msg .= '<p>fixed_cost_history has been added twice..!</p>';
							$this->session->set_flashdata('success_message',$msg);
						} else {
							// 1. current month
							$fixed_cost_history1 = array(
								   'fixed_cost_id' => $this->input->post('fixed_cost_id'),
								   'fixed_cost_history_nominal' => $pay_nominal,
								   'fixed_cost_history_month' => $fixed_cost_history_month,
								   'fixed_cost_history_date' => date('Y-m-d', strtotime($this->input->post('fixed_cost_history_date'))),
								   'bank_account_id' => $bank_account_id
								);
							$this->db->insert('tb_fixed_cost_history', $fixed_cost_history1); 
							
							$msg .= '<p>fixed_cost_history has been added..!</p>'.$fixed_cost_history_month;
							$this->session->set_flashdata('success_message',$msg);
						}
									
						// updating cash if paid by cash
						if ($pay_nominal > 0) {
							$cash = array (
								'cash_type_id' => $fixed_cost_type_id,
								'cash_date' => date('Y-m-d', strtotime($this->input->post('fixed_cost_history_date'))),
								'cash_desc' => $fixed_cost_name.' in '.$month_name,
								'cash_nominal' => "-".$pay_nominal,
								'bank_account_id' => $this->input->post ('bank_account_id'),
								
							);
							$this->db->insert('tb_cash', $cash); 
							$msg .= '<p>Cash has been decreased '. $this->input->post('expense_nominal_cash') .' rupiahs!</p>';
						}

					}
				}
			}
		
			
			redirect(site_url('fixed_cost_history/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			$msg .= '<p>fixed_cost_history has been updated</p>';

			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('fixed_cost_history/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_fixed_cost_history', array('fixed_cost_history_id' => $this->uri->segment(3))); 
			
			$msg = 'fixed_cost_historys was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('fixed_cost_history/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function detail() {
		if($this->session->userdata('logged_in')) {
			$data["page"] = "fixedCostHistoryDetail";
			
			$period = $this->uri->segment(3);
			$fixed_cost_id = $this->uri->segment(4);
			
			
			$this->db->where('fixed_cost_id',$fixed_cost_id);
			$this->db->limit(1);
			$fixed_cost = $this->db->get('tb_fixed_cost');
			$data["fixed_cost_name"] = $fixed_cost->row()->fixed_cost_name;
			
			if ($period < 10) {
				$period = '0'.$period;
			}
			$this->db->where('option_code',$period);
			$this->db->where('option_type','MON');
			$this->db->limit(1);
			$month = $this->db->get('tb_options');
			$data["month"] = $month->row()->option_desc;
			
			$this->db->where('tb_fixed_cost_history.fixed_cost_history_month',$period);
			$this->db->where('tb_fixed_cost.fixed_cost_id',$fixed_cost_id);
			$this->db->join('tb_fixed_cost', 'tb_fixed_cost.fixed_cost_id = tb_fixed_cost_history.fixed_cost_id');
			$data["list_fixed_cost_history_detail"] = $this->db->get('tb_fixed_cost_history')->result();
			
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			
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
				   'fixed_cost_name' => $this->input->post('fixed_cost_name'),
				   'month' => $this->input->post('period'),
				   'fixed_cost_type_id' => $this->input->post('fixed_cost_type_id'),
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('fixed_cost_history/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */