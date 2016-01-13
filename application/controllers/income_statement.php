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
				   'period' => $this->input->post('period'),
				   'year' => $this->input->post('year')
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
			
			
			$startdate 	= date('Y-m-d', strtotime($this->input->post('startdate')));
			$enddate 	= date('Y-m-d', strtotime($this->input->post('enddate')));
			
			$month_name = null;
			// all ids, merge into array, as a condition to summarize nominal in EACH ENTITY
			if (!empty($startdate) && !empty($enddate)) {
			
				//$this->db->where('option_code', $period);
				//$row = $this->db->get('tb_options')->row();
				$month_name = '';//$row->option_desc;
			
				$sales_nominal = $this->get_sales_nominal($startdate, $enddate);
				$cogs_nominal = $this->get_cogs_nominal($startdate, $enddate);
				$ongkir_nominal = $this->get_ongkir_nominal($startdate, $enddate);
				$ongkir_paid_nominal = $this->get_ongkir_paid_nominal($startdate, $enddate);
				
				$expenses = $this->get_expenses_list($startdate, $enddate);
				$sedekah_nominal = $this->get_sedekah_nominal($startdate, $enddate);
				
			} else {
				$sales_nominal = 0;
				$cogs_nominal = 0;
				$ongkir_nominal = 0;
				$ongkir_paid_nominal = 0;
				$expenses = null;
				$sedekah_nominal = 0;
			}			
			
			
			$results = array(
				'sales_nominal' => $sales_nominal,
				'cogs_nominal' => $cogs_nominal,
				'ongkir_nominal' => $ongkir_nominal,
				'ongkir_paid_nominal' => $ongkir_paid_nominal,
				'gp_nominal' => ($sales_nominal + $cogs_nominal),
				'sedekah_nominal' => $sedekah_nominal,
				'startdate' => $startdate,
				'enddate' => $enddate
				
			);
					
			$all_trx[] = $this->arrayToObject($results);
			$data["items"] = $all_trx;
			
			$data["expenses"] = $expenses;
			
			$data["page"] = "incomeStatement";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	private function get_sales_nominal($startdate, $enddate) {
		// range date
		
		$this->db->where('purchase_date >=',$startdate);
		$this->db->where('purchase_date <=',$enddate);
		
		$query = $this->db->get('orders');
		
		
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + ($item->product_amount - $item->discount_amount);
			}
		}
		return $sum_nominal;			
	}
	
	private function get_ongkir_nominal($startdate, $enddate) {
		// range date
		
		$this->db->where('purchase_date >=',$startdate);
		$this->db->where('purchase_date <=',$enddate);
		
		$query = $this->db->get('orders');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + ($item->exp_cost);
			}
		}
		return $sum_nominal;			
	}
	
	private function get_ongkir_paid_nominal($startdate, $enddate) {
		// range date
		
		$this->db->where('expense_date >=',$startdate);
		$this->db->where('expense_date <=',$enddate);
		$this->db->where('expense_type_id',33); // ongkir
		
		$query = $this->db->get('tb_expense');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + $item->expense_nominal;
			}
		}
		return $sum_nominal;			
	}
	
	private function get_cogs_nominal($startdate, $enddate) {
		// range date
		
		$this->db->where('purchase_date >=',$startdate);
		$this->db->where('purchase_date <=',$enddate);
		
		$this->db->join('orders', 'orders.id = tb_cart.order_id');
		$query = $this->db->get('tb_cart');
		
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal - $item->cart_cogs;
			}
		}
		return $sum_nominal;			
	}
	
	private function get_expenses_list($startdate, $enddate) {
		// range date
		
		$this->db->select('expense_type_id, option_desc, sum(expense_nominal) as exp_nominal');
		$this->db->from('tb_expense');
		
		$this->db->where('expense_date >=',$startdate);
		$this->db->where('expense_date <=',$enddate);
		$this->db->where('expense_type_id <>',43); // sedekah
		$this->db->where('expense_type_id <>',33); // ongkir
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
	
	private function get_sedekah_nominal($startdate, $enddate) {
		// range date
		
		$this->db->where('expense_date >=',$startdate);
		$this->db->where('expense_date <=',$enddate);
		
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
			
			$startdate = $this->uri->segment(3);
			$enddate = $this->uri->segment(4);
			$expense_type_id = $this->uri->segment(5);
			
			$this->db->where('expense_date >=',$startdate);
			$this->db->where('expense_date <=',$enddate);
			
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
