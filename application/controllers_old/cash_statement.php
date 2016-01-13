<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cash_statement extends CI_Controller {

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
			
			redirect(site_url('cash_statement/main'));
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
				$prod_costs = $this->get_prod_cost_list($period);
				$money_borrowed = $this->get_money_borrowed_nominal($period);
				$money_repaid = $this->get_money_repaid_nominal($period);
				$ppe_investment = $this->get_ppe_list($period);
				$original_investment = $this->get_original_investment_list($period);
				$expenses = $this->get_expenses_list($period);
				
				$begining_cash = $this->get_begining_cash_nominal($period, $year);
				
			} else {
				$sales_nominal = 0;
				$prod_costs = null;
				$money_borrowed = 0;
				$money_repaid = 0;
				$ppe_investment = null;
				$original_investment = null;
				$expenses = null;
				
				$begining_cash = 0;
			}			
			
			$results = array(
				'sales_nominal' => $sales_nominal,
				'begining_cash' => $begining_cash,
				'money_borrowed' => $money_borrowed,
				'money_repaid' => $money_repaid,
				'month' => $month_name,
				'month_id' => $period
			);
					
			$all_trx[] = $this->arrayToObject($results);
			$data["items"] = $all_trx;
			
			$data["prod_costs"] = $prod_costs;
			$data["equities"] = $original_investment;
			$data["expenses"] = $expenses;
			$data["ppes"] = $ppe_investment;
			
			$data["page"] = "cashStatement";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	private function get_sales_nominal($period) {
		// range date
		
		$this->db->where('MONTH(cash_date)',$period);
		$this->db->where('order_id is not null');
		$query = $this->db->get('tb_cash');
		$sum_nominal = 0;
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + ($item->cash_nominal);
			}
		}
		return $sum_nominal;			
	}
	
	private function get_begining_cash_nominal($period, $year) {
		// range date
		
		$this->db->where('begining_cash_period',$period);
		$this->db->where('begining_cash_year',$year);
		$query = $this->db->get('tb_begining_cash');
		$sum_nominal = 0;
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + $item->begining_cash_nominal;
			}
		}
		return $sum_nominal;			
	}
	
	private function get_prod_cost_list($period) {
		
		$this->db->select('cash_type_id, tb_options.option_desc, sum(cash_nominal) as cash_nominal');
		$this->db->from('tb_cash');
		
		$this->db->where('MONTH(cash_date)',$period);
		$this->db->where('tb_options.option_root_id',78); // 'PROD'
		
		$this->db->join('tb_options', 'tb_cash.cash_type_id = tb_options.option_id','left');
		$this->db->group_by('cash_type_id');
		
		$query=$this->db->get();
		
		
		$sum_nominal = '';	
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;			
	}
	
	private function get_money_borrowed_nominal($period) {
		// range date
		
		$this->db->where('MONTH(acrec_date)',$period);
		
		$query = $this->db->get('tb_acrec');
		
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal - $item->acrec_nominal;
			}
		}
		return $sum_nominal;			
	}
	
	private function get_money_repaid_nominal($period) {
		// range date
		
		$this->db->where('MONTH(liabilities_date)',$period);
		$this->db->where('liabilities_type_id',84); // Money Repaid
		$query = $this->db->get('tb_liabilities');
		
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + $item->liabilities_nominal;
			}
		}
		return $sum_nominal;			
	}
	
	private function get_ppe_list($period) {
		// range date
		
		$this->db->where('MONTH(cash_date)',$period);
		$this->db->join('tb_ppe', 'tb_cash.ppe_id = tb_ppe.ppe_id','left');
		$this->db->join('tb_options', 'tb_ppe.ppe_type_id = tb_options.option_id','left');
		$this->db->where_in('option_type','PPE'); // INV_SOLD
		$query = $this->db->get('tb_cash');
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	private function get_original_investment_list($period) {
		// range date
		
		$this->db->where('MONTH(cash_date)',$period);
		$this->db->join('tb_options', 'tb_cash.cash_type_id = tb_options.option_id','left');
		$this->db->where_in('option_type','EQUITY'); // INV_SOLD
		$query = $this->db->get('tb_cash');
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	private function get_expenses_list($period) {
		// range date
		
		$this->db->select('cash_type_id, option_desc, sum(cash_nominal) as cash_nominal');
		$this->db->where('MONTH(cash_date)',$period);
		$this->db->join('tb_options', 'tb_cash.cash_type_id = tb_options.option_id','left');		
		$this->db->where_in('option_type','EXP');
		$this->db->group_by('option_id');
		
		$query = $this->db->get('tb_cash');
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */