<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Income_statement extends CI_Controller {

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
			
			redirect(site_url('income_statement/main'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function main() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// get month list
			$where = "option_type = 'MON'";
			$this->db->where($where);
			$this->db->order_by('option_type');
			$data['months'] = $this->db->get('tb_options')->result();
			
			$period 	= $this->input->post('period');
			$year 	= $this->input->post('year');
			$month_name = null;
			// all ids, merge into array, as a condition to summarize nominal in EACH ENTITY
			if (!empty($period)) {
			
				$this->db->where('option_code', $period);
				$row = $this->db->get('tb_options')->row();
				$month_name = $row->option_desc;
			
				$sales_nominal = $this->get_sales_nominal($period);
				$cogs_nominal = $this->get_cogs_nominal($period);
				$ongkir_nominal = $this->get_ongkir_nominal($period);
				$expenses = $this->get_expenses_list($period);
				$fixed_cost = $this->get_fixed_cost_list($period, $year);
				$sedekah_nominal = $this->get_sedekah_nominal($period);
				
			} else {
				$sales_nominal = 0;
				$cogs_nominal = 0;
				$ongkir_nominal = 0;
				$expenses = null;
				$fixed_cost = null;
				$sedekah_nominal = 0;
			}			
			
			
			$results = array(
				'sales_nominal' => $sales_nominal,
				'cogs_nominal' => $cogs_nominal,
				'ongkir_nominal' => $ongkir_nominal,
				'gp_nominal' => ($sales_nominal + $cogs_nominal),
				'sedekah_nominal' => $sedekah_nominal,
				'month_id' => $period,
				'month' => $month_name,
				
				
			);
					
			$all_trx[] = $this->arrayToObject($results);
			$data["items"] = $all_trx;
			
			$data["expenses"] = $expenses;
			$data["fixed_cost_history"] = $fixed_cost;
			
			$data["page"] = "incomeStatement";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	private function get_sales_nominal($period) {
		// range date
		
		$this->db->where('MONTH(order_date)',$period);
		$query = $this->db->get('orders');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + ($item->product_amount - $item->discount_amount);
			}
		}
		return $sum_nominal;			
	}
	
	private function get_ongkir_nominal($period) {
		// range date
		
		$this->db->where('MONTH(order_date)',$period);
		
		$query = $this->db->get('orders');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + ($item->exp_cost);
			}
		}
		return $sum_nominal;			
	}
	
	private function get_cogs_nominal($period) {
		// range date
		
		$this->db->where('MONTH(inventory_date)',$period);
		
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
	
	private function get_expenses_list($period) {
		// range date
		
		$this->db->select('expense_type_id, option_desc, sum(expense_nominal) as exp_nominal');
		$this->db->from('tb_expense');
		
		$this->db->where('MONTH(expense_date)',$period);
		$this->db->where('expense_type_id <>',43); // sedekah
		$this->db->join('tb_options', 'tb_expense.expense_type_id = tb_options.option_id','left');
		$this->db->group_by('expense_type_id');
		
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	private function get_fixed_cost_list($period, $year) {
		// range date
		
		$this->db->select('option_desc, fixed_cost_type_id, sum(fixed_cost_nominal) fixed_cost_nominal');
		$this->db->from('tb_fixed_cost');
		$this->db->join('tb_options', 'tb_options.option_id = tb_fixed_cost.fixed_cost_type_id');
		$this->db->where('YEAR(fixed_cost_per_date) <= ',$year);
		$this->db->where('MONTH(fixed_cost_per_date) <= ',$period);
		
		$this->db->group_by('fixed_cost_per_date');
		
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	private function get_sedekah_nominal($period) {
		// range date
		
		$this->db->where('MONTH(expense_date)',$period);
		$this->db->where_in('expense_type_id',43); // sedekah
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