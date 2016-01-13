<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roseta_stone extends CI_Controller {

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
				   'startdate' => $this->input->post('startdate'),
				   'enddate' => $this->input->post('enddate'),
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('roseta_stone/main'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function main() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$where = "roseta_sequence != ''";
			$this->db->where($where);
			$this->db->order_by('roseta_sequence');
			$query = $this->db->get('tb_options');
			
			$startdate 	= date("Y-m-d", strtotime($this->input->post('startdate')));
			$enddate 	= date("Y-m-d", strtotime($this->input->post('enddate')));
			
			
			$all_trx = array();
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
				
					// looking for cash total
					// first, looking for options where type = row->
					$this->db->where('option_id',$row->option_id);
					$trx_type = $this->db->get('tb_options');
					
					foreach ($trx_type->result() AS $item) {
						$ids = $item->option_id;
						$cash_type_ids[] = $ids;
					}
					
					// all ids, merge into array, as a condition to summarize nominal in EACH ENTITY
					if (!empty($startdate) && !empty($enddate)) {
					
						$sum_cash = $this->get_cash_nominal($ids, $startdate, $enddate);
						$sum_bca = $this->get_bca_nominal($ids, $startdate, $enddate);
						$sum_mandiri = $this->get_mandiri_nominal($ids, $startdate, $enddate);
						$sum_bri = $this->get_bri_nominal($ids, $startdate, $enddate);
						$sum_bni = $this->get_bni_nominal($ids, $startdate, $enddate);
						$sum_rusydi = $this->get_rusydi_nominal($ids, $startdate, $enddate);
						$sum_diaz = $this->get_diaz_nominal($ids, $startdate, $enddate);
						
						$sum_acc_rec = $this->get_acc_rec_nominal($ids, $startdate, $enddate);
						$sum_inventory = $this->get_inventory_nominal($ids, $startdate, $enddate);
						$sum_ppe = $this->get_ppe_nominal($ids, $startdate, $enddate);
						
						$sum_account_pay = $this->get_account_pay_nominal($ids, $startdate, $enddate);
						$sum_notes_pay = $this->get_notes_pay_nominal($ids, $startdate, $enddate);
						$sum_tax_pay = $this->get_tax_pay_nominal($ids, $startdate, $enddate);
						
						$sum_rusydi_pay = $this->get_rusydi_pay_nominal($ids, $startdate, $enddate);
						$sum_diaz_pay = $this->get_diaz_pay_nominal($ids, $startdate, $enddate);
						
						$sum_equity = $this->get_equity_nominal($ids, $startdate, $enddate);
						$sum_income = $this->get_income_nominal($ids, $startdate, $enddate);
					} else {
						$sum_cash = 0;
						$sum_acc_rec = 0;
						$sum_inventory = 0;
						$sum_ppe = 0;
						$sum_account_pay = 0;
						$sum_notes_pay = 0;
						$sum_tax_pay = 0;
						
						$sum_rusydi_pay = 0;
						$sum_diaz_pay = 0;
						
						$sum_equity = 0;
						$sum_income = 0;
					}
					
					// prepare variable to assign into array / roseta stone table
					$option_desc = $row->option_desc;
					
					$cash_subtotal = $sum_cash;
					$bca_subtotal = $sum_bca;
					$mandiri_subtotal = $sum_mandiri;
					$bri_subtotal = $sum_bri;
					$bni_subtotal = $sum_bni;
					$rusydi_subtotal = $sum_rusydi;
					$diaz_subtotal = $sum_diaz;
					
					$acc_rec_subtotal = $sum_acc_rec;
					$inventory_subtotal = $sum_inventory;
					$ppe_subtotal = $sum_ppe;
					$account_pay_subtotal = $sum_account_pay;
					$notes_pay_subtotal = $sum_notes_pay;
					$tax_pay_subtotal = $sum_tax_pay;
					
					$rusydi_pay_subtotal = $sum_rusydi_pay;
					$diaz_pay_subtotal = $sum_diaz_pay;
					
					$equity_subtotal = $sum_equity;
					$income_subtotal = $sum_income;
					
					
					// checking has root or not
					if ($row->option_root_id !='') {
						$option_desc = '- '.$row->option_desc;
					} else {
						$option_desc = '<strong>'.$row->option_desc.'</strong>';
					}
					
					$results = array(
							'option_desc' => $option_desc,
							'cash' => $cash_subtotal,
							
							'bca' => $bca_subtotal,
							'mandiri' => $mandiri_subtotal,
							'bri' => $bri_subtotal,
							'bni' => $bni_subtotal,
							'rusydi' => $rusydi_subtotal,
							'diaz' => $diaz_subtotal,
							
							'acc_rec' => $acc_rec_subtotal,
							'inventory' => $inventory_subtotal,
							'ppe' => $ppe_subtotal,
							'account_pay' => $account_pay_subtotal,
							'notes_pay' => $notes_pay_subtotal,
							'tax_pay' => $tax_pay_subtotal,
							
							'rusydi_pay' => $rusydi_pay_subtotal,
							'diaz_pay' => $diaz_pay_subtotal,
							
							'equity' => $equity_subtotal,
							'income' => $income_subtotal,
							);
					
					$all_trx[] = $this->arrayToObject($results);
				}
				
				$data["transactions"] = $all_trx;
			}
			
			$data["rows"] = $query->num_rows;
			$data["page"] = "rosetaStone";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	private function get_cash_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('cash_date >=', $startdate);
		$this->db->where('cash_date <=', $enddate);
		
		$this->db->where_in('cash_type_id',$ids);
		$query = $this->db->get('tb_cash');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->cash_nominal;
			}
		}
		
		return $sum_nominal;			
	}
	
	private function get_bca_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('cash_date >=', $startdate);
		$this->db->where('cash_date <=', $enddate);
		$this->db->where('bank_account_id', 1);
		
		$this->db->where_in('cash_type_id',$ids);
		$query = $this->db->get('tb_cash');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->cash_nominal;
			}
		}
		
		return $sum_nominal;
	}
	
	private function get_mandiri_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('cash_date >=', $startdate);
		$this->db->where('cash_date <=', $enddate);
		$this->db->where('bank_account_id', 2);
		
		$this->db->where_in('cash_type_id',$ids);
		$query = $this->db->get('tb_cash');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->cash_nominal;
			}
		}
		
		return $sum_nominal;
	}
	
	private function get_bri_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('cash_date >=', $startdate);
		$this->db->where('cash_date <=', $enddate);
		$this->db->where('bank_account_id', 3);
		
		$this->db->where_in('cash_type_id',$ids);
		$query = $this->db->get('tb_cash');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->cash_nominal;
			}
		}
		
		return $sum_nominal;
	}
	
	private function get_bni_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('cash_date >=', $startdate);
		$this->db->where('cash_date <=', $enddate);
		$this->db->where('bank_account_id', 7);
		
		$this->db->where_in('cash_type_id',$ids);
		$query = $this->db->get('tb_cash');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->cash_nominal;
			}
		}
		
		return $sum_nominal;
	}
	
	private function get_rusydi_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('cash_date >=', $startdate);
		$this->db->where('cash_date <=', $enddate);
		$this->db->where('bank_account_id', 5);
		
		$this->db->where_in('cash_type_id',$ids);
		$query = $this->db->get('tb_cash');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->cash_nominal;
			}
		}
		
		return $sum_nominal;
	}
	
	private function get_diaz_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('cash_date >=', $startdate);
		$this->db->where('cash_date <=', $enddate);
		$this->db->where('bank_account_id', 6);
		
		$this->db->where_in('cash_type_id',$ids);
		$query = $this->db->get('tb_cash');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->cash_nominal;
			}
		}
		
		return $sum_nominal;
	}
	
	private function get_acc_rec_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('acrec_date >=', $startdate);
		$this->db->where('acrec_date <=', $enddate);
		
		$this->db->where_in('acrec_type_id',$ids);
		$query = $this->db->get('tb_acrec');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->acrec_nominal;
			}
		}
		
		return $sum_nominal;			
	}
	
	private function get_inventory_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('inventory_date >=', $startdate);
		$this->db->where('inventory_date <=', $enddate);
		
		$this->db->where_in('inventory_type_id',$ids);
		$query = $this->db->get('tb_inventory');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->inventory_nominal;
			}
		}
		return $sum_nominal;			
		
	}
	
	private function get_account_pay_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('liabilities_date >=', $startdate);
		$this->db->where('liabilities_date <=', $enddate);
		
		$this->db->where_in('liabilities_cause_id',$ids);
		$this->db->where_in('liabilities_type_id',8);
		$query = $this->db->get('tb_liabilities');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->liabilities_nominal;
			}
		}
		return $sum_nominal;
	}
	
	private function get_notes_pay_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('liabilities_date >=', $startdate);
		$this->db->where('liabilities_date <=', $enddate);
		
		$this->db->where_in('liabilities_cause_id',$ids);
		$this->db->where_in('liabilities_type_id',9);
		$query = $this->db->get('tb_liabilities');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->liabilities_nominal;
			}
		}
		return $sum_nominal;
	}
	
	private function get_rusydi_pay_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('liabilities_date >=', $startdate);
		$this->db->where('liabilities_date <=', $enddate);
		
		$this->db->where_in('liabilities_cause_id',$ids);
		$this->db->where_in('liabilities_type_id',49);
		$query = $this->db->get('tb_liabilities');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->liabilities_nominal;
			}
		}
		return $sum_nominal;
	}
	
	private function get_diaz_pay_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('liabilities_date >=', $startdate);
		$this->db->where('liabilities_date <=', $enddate);
		
		$this->db->where_in('liabilities_cause_id',$ids);
		$this->db->where_in('liabilities_type_id',50);
		$query = $this->db->get('tb_liabilities');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->liabilities_nominal;
			}
		}
		return $sum_nominal;
	}
	
	private function get_tax_pay_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('liabilities_date >=', $startdate);
		$this->db->where('liabilities_date <=', $enddate);
		
		$this->db->where_in('liabilities_cause_id',$ids);
		$this->db->where_in('liabilities_type_id',10);
		$query = $this->db->get('tb_liabilities');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->liabilities_nominal;
			}
		}
		return $sum_nominal;
	}
	
	private function get_income_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('income_date >=', $startdate);
		$this->db->where('income_date <=', $enddate);
		
		$this->db->where_in('income_type_id',$ids);
		$query = $this->db->get('tb_income');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->income_nominal;
			}
		}
		return $sum_nominal;	
	}
	
	private function get_ppe_nominal($ids, $startdate, $enddate) {
		// range date
		$this->db->where('netppe_date >=', $startdate);
		$this->db->where('netppe_date <=', $enddate);
		
		$this->db->where_in('netppe_type_id',$ids);
		$query = $this->db->get('tb_netppe');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->netppe_nominal;
			}
		}
		return $sum_nominal;	
	}
	
	private function get_equity_nominal($ids, $startdate, $enddate) {
		// range date
		
		$this->db->where('equity_date >=', $startdate);
		$this->db->where('equity_date <=', $enddate);
		
		$this->db->where_in('equity_type_id',$ids);
		$query = $this->db->get('tb_equity');
		$sum_nominal = '';
		if ($query->num_rows > 0) {
			foreach ($query->result() AS $trx) {
				$sum_nominal = $sum_nominal + $trx->equity_nominal;
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */