<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Balance_sheet extends CI_Controller {

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
			
			// : : : ASSET
			// cash
			$cash = $this->get_cash_list();
			$data["cash"] = $cash;
			
			// accrec
			$account_receivable = $this->get_account_receivable_list();
			$unfinish_order_nominal = $this->get_unfinish_order_nominal();
			
			$data["account_receivable"] = $account_receivable;
			$data["unfinish_order_nominal"] = $unfinish_order_nominal;
			
			// inventory
			$inventory_barang_mentah = $this->get_inventory_barang_mentah();
			$inventory_barang_setengah_jadi = 0;
			$inventory_barang_jadi = $this->get_inventory_barang_jadi();
			
			$data["inventory_barang_mentah"] = $inventory_barang_mentah;
			$data["inventory_barang_setengah_jadi"] = $inventory_barang_setengah_jadi;
			$data["inventory_barang_jadi"] = $inventory_barang_jadi;
			
			// ppe
			$list_ppe = $this->get_ppe_list();
			$data["list_ppe"] = $list_ppe;
			
			// LIABILITIES
			$account_payable = $this->get_account_payable_list();
			$money_repaid_nominal = $this->get_money_repaid_nominal();
			$wallet_nominal = $this->get_wallet_nominal();
			
			$data["account_payable"] = $account_payable;
			$data["money_repaid_nominal"] = $money_repaid_nominal;
			$data["wallet_nominal"] = $wallet_nominal;

			// : : : EQUITY
			$equities = $this->get_equity_list();
			$data["equities"] = $equities; 
			
			
			// Earning
			$sales_nominal = $this->get_sales_nominal();
			$cogs_nominal = $this->get_cogs_nominal();
			$ongkir_nominal = $this->get_ongkir_nominal();
			$expense_nominal = $this->get_expense_nominal();
			
			$earnings = $sales_nominal + $cogs_nominal - $expense_nominal;
			$data["earnings_nominal"] = $earnings; //$earnings;
			
			
			
			$data["page"] = "balanceSheet";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	private function get_equity_list() {
		$this->db->select('equity_type_id, option_desc, sum(equity_nominal) as equity_nominal');
		$this->db->from('tb_equity');
		
		$this->db->join('tb_options', 'tb_equity.equity_type_id = tb_options.option_id','left');
		$this->db->group_by('equity_type_id');
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
		
	}
	
	private function get_account_payable_list() {
		// range date
		
		$this->db->select('liabilities_type_id,liabilities_cause_id, option_desc, sum(liabilities_nominal) as liabilities_nominal');
		$this->db->from('tb_liabilities');
		
		$this->db->where('liabilities_type_id',8); // acc payable
		$this->db->join('tb_options', 'tb_liabilities.liabilities_cause_id = tb_options.option_id','left');
		$this->db->group_by('liabilities_cause_id');
		
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	private function get_money_repaid_nominal() {
		// range date
		
		$this->db->where_in('liabilities_type_id',84); // money repaid
		$query = $this->db->get('tb_liabilities');
		
		$sum_nominal = 0;
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + $item->liabilities_nominal;
			}
		}
		return $sum_nominal;			
	}
	
	private function get_wallet_nominal() {
		/**
		 *  select sum(wallet_trx_nominal) from tb_wallet, billing
			where billing.billing_id = tb_wallet.billing_id	
		 */
		 
		 $this->db->select('sum(wallet_trx_nominal) as wallet_nominal');
		 $this->db->from('tb_wallet');
		 $query=$this->db->get();
		 $wallet_nominal = $query->row()->wallet_nominal;
		 
		 return $wallet_nominal;
		 
	}
	
	private function get_ppe_list() {
		// range date
		
		$this->db->select('*, sum(ppe_nominal - ppe_has_been_paid) as ppe_nominal');
		$this->db->from('tb_ppe');
		
		$this->db->join('tb_options', 'tb_ppe.ppe_type_id = tb_options.option_id','left');
		$this->db->group_by('ppe_type_id');
		
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	private function get_account_receivable_list() {
		// range date
		
		$this->db->select('*, sum(acrec_nominal) as acrec_nominal');
		$this->db->from('tb_acrec');
		
		//$this->db->where('acrec_type_id',6); // 
		$this->db->join('tb_options', 'tb_acrec.acrec_type_id = tb_options.option_id','left');
		$this->db->group_by('acrec_type_id');
		
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	private function get_inventory_barang_mentah() {
		$this->db->select('sum(material_nominal) as nominal');
		$this->db->from('tb_material_inventory');
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$total_stock_nominal = $row->nominal;
			}
			return $total_stock_nominal;
		}
		return false;
	}
	
	private function get_inventory_barang_jadi() {
		$this->db->select('sum(tb_inventory.inventory_cogs * tb_inventory.inventory_qty) as nominal');
		$this->db->from('tb_inventory');
				
		$this->db->join('tb_stock', 'tb_stock.stock_id = tb_inventory.stock_id');
		$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
		$this->db->where('tb_product.status', 1);
				
		$query = $this->db->get();
			
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$total_free_nominal = $row->nominal;
			}
			return $total_free_nominal;
		}
		return false;
	}
	
	private function get_cash_list() {
		
		$this->db->select('bank_account_name, sum(cash_nominal) as nominal');
		$this->db->from('tb_cash');
		$this->db->join('bank_account','bank_account.id = tb_cash.bank_account_id');
		$this->db->group_by('bank_account_id');
				
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	private function get_sales_nominal() {
		// range date
		
		//$this->db->where_in('liabilities_type_id',84); // money repaid
		$query = $this->db->get('orders');
		
		$sum_nominal = 0;
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + ($item->product_amount - $item->discount_amount);
			}
		}
		return $sum_nominal;			
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
	
	private function get_unfinish_order_nominal() {
		
		$this->db->where_in('order_status',0); // INV_SOLD
		$query = $this->db->get('orders');
		
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $item) {
				$sum_nominal = $sum_nominal + $item->total_amount;
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
