<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Earning extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	
	}
	
	public function search() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			// Searching
			$searchparam = array(
				   'period' => $this->input->post('period')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('earning/main'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function main() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// INCOME / OMSET
			
			$income = $this->get_income_list();
			$data["income"] = $income;
			
			// EARNING
			$retain_earning_nominal = 0;//$this->get_retain_earning_nominal();
			$earning_week_to_date = 0;//$this->get_earning_week_to_date_nominal();
			
			$data["retain_earning_nominal"] = $retain_earning_nominal;
			$data["earning_week_to_date_nominal"] = $earning_week_to_date;
			
			// SEDEKAH
			$supposedto_sedekah = 0;//$this->get_supposedto_sedekah();
			$current_sedekah = 0;//$this->get_current_sedekah();
			
			$data["current_sedekah_nominal"] = $current_sedekah;
			$data["supposedto_sedekah_nominal"] = $supposedto_sedekah;
			
			$data["page"] = "earningList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	private function get_income_list() {
		
		$this->db->select('tb_options.option_desc, sum(income_nominal) as income_nominal, income_type_id');
		$this->db->from('tb_income');
		$this->db->join('tb_options','tb_options.option_id = tb_income.income_type_id');
		$this->db->where('tb_income.income_nominal > 0');
		$this->db->group_by('income_type_id');
				
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	private function get_retain_earning_nominal() {
		
		return 0;			
	}
	
	private function get_ongkir_nominal() {
		
		$query = $this->db->get('orders');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + ($item->exp_cost);
			}
		}
		return $sum_nominal;			
	}
	
	private function get_cogs_nominal() {
		
		$this->db->where_in('inventory_type_id',24); // INV_SOLD
		$query = $this->db->get('tb_inventory');
		
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + $item->inventory_nominal;
			}
		}
		return $sum_nominal;			
	}
	
	private function get_expense_nominal() {
		
		$this->db->where('expense_type_id <>',43); // sedekah
		$query = $this->db->get('tb_expense');
		
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + $item->expense_nominal;
			}
		}
		return $sum_nominal;			
	}
	
	public function arrayToObject($d) {
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return (object) array_map(array($this,'arrayToObject'), $d);
		}
		else {
			// Return object
			return $d;
		}
	}
	
	public function expenses_detail_per_type_list() {
		if($this->session->userdata('logged_in')) {
			
			$period = $this->uri->segment(3);
			$expense_type_id = $this->uri->segment(4);
			
			$this->db->where('MONTH(expense_date)',$period);
			$this->db->where('expense_type_id',$expense_type_id);
			$this->db->join('tb_options', 'tb_expense.expense_type_id = tb_options.option_id','left');
			$this->db->join('bank_account', 'bank_account.id = tb_expense.bank_account_id','left');
			$data["list_expense_detail_per_type"] = $this->db->get('tb_expense')->result();
			
			$data['page'] = "expenseDetailPerTypeList";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
