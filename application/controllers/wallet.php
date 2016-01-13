<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wallet extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('wallet_model');
		$this->load->model('GenericModel');
    }
	
	public function index() { 
	}
	
	function doPayment() {
		if($this->session->userdata('logged_in')) {
		
			// UPDATING WALLET
			// finding last balance of wallet account
			$this->db->select_sum('wallet_trx_nominal');
			$this->db->where('wallet_id <>', $this->input->post('wallet_id'));
			$this->db->where('billing_id', $this->input->post('billing_id'));
			$this->db->group_by('billing_id');
			$this->db->limit(1);
			$wallet = $this->db->get('tb_wallet');
			//echo 'before : '.$wallet_trx_nominal;
			$wallet_trx_nominal = $wallet->row()->wallet_trx_nominal;
			//echo '<br/>after : '.$wallet_trx_nominal;
			if ($wallet_trx_nominal < $this->input->post('order_total_nominal')) {
				$msg = '<p>Saldo Kurang bro. Isi dulu ya</p> ';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('wallet/add/'));
			} else {
			
				// updating wallet
				$wallet = array(
					   'wallet_trx_nominal' => '-'.$this->input->post('order_total_nominal'),
					   'wallet_balance' => $wallet_trx_nominal - $this->input->post('order_total_nominal'),
					   'wallet_trx_date' => date('Y-m-d H:i:s'),
					   'wallet_status' => 1,
					);
					
				$this->db->where('wallet_id', $this->input->post('wallet_id')); 
				$this->db->update('tb_wallet', $wallet);
				
				$msg = '<p>Wallet has been updated</p> '.$wallet_trx_nominal;
				$this->session->set_flashdata('success_message',$msg);	
				// updating order
				$order = array(
						'order_status' => 2,
						'purchase_date' =>  date('Y-m-d'),
					);
				$this->db->where('id', $this->input->post('order_id')); 
				$this->db->update('orders', $order);
				
				// updating inventory
				$inv = array (
						'inventory_date' => date('Y-m-d')
						);
				$this->db->where('order_id', $this->input->post('order_id')); 
				$this->db->update('tb_inventory', $inv);
				
				redirect(site_url('wallet/lists'));
			}
		} else {
		
		}
	}
	
	function payment() {
		if($this->session->userdata('logged_in')) {	
			$wallet_id = $this->uri->segment(3);
			
			$this->db->select('billing.*, orders.*, tb_wallet.*, tb_wallet.billing_id as wallet_billing_id');
			$this->db->where('wallet_id', $wallet_id); 
			$this->db->join('billing', 'tb_wallet.billing_id = billing.billing_id');
			$this->db->join('orders', 'tb_wallet.order_id = orders.id');
			
			$data['wallet'] = $this->db->get('tb_wallet')->result();
			
			$data["page"] = "walletPayment";
			
			$this->load->view('dashboard',$data);
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// SEARCHING TERMS
			$billing_name = 'Seluruh Agen';
			$startdate = '01-Jan-2015';
			$searchterm = $this->session->userdata('searchterm');
			
			if ($searchterm != null){
				
				if (!empty($searchterm['billing_name'])) {
					$billing_name = $searchterm['billing_name'];
				}
				if (!empty($searchterm['billing_id'])) {
					$billing_id = $searchterm['billing_id'];
				}
				if (!empty($searchterm['startdate'])) {
					$startdate = $searchterm['startdate'];
				}
			}
			
			$data['billing_name'] = $billing_name;
			$data['startdate'] =  date('d-M-Y', strtotime($startdate));
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/wallet/lists");
			$config["total_rows"] = $this->wallet_model->record_count($searchterm);
			
			
			$this->pagination->initialize($config);
			//$page = $this->uri->segment(3);
			
			// QUERY SALDO AWAL
			$this->db->select('sum(wallet_trx_nominal) as balance');
			$this->db->join('billing', 'billing.billing_id = tb_wallet.billing_id');
			$this->db->from('tb_wallet');
			if ($startdate != '') {
				$this->db->where('wallet_trx_date <', $startdate);
			}
			
			if ($billing_name != 'Seluruh Agen') {
				$this->db->like('billing_name', $billing_name);
			}
			
			if (!empty($billing_id)) {
				$this->db->where('tb_wallet.billing_id', $billing_id);
			}
			
			
			$this->db->limit(1);
			$cash = $this->db->get();
			
			$data['first_balance'] = $cash->row()->balance;
			
			$data["list_wallet"] = $this->wallet_model->fetch_wallet($searchterm);
			//$data["links"] = $this->pagination->create_links();
			$data["row"] = 1;
			$data["page"] = "walletList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function users() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/wallet/users");
			$config["total_rows"] = $this->wallet_model->record_count_users($searchterm);
			
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_wallet_users"] = $this->wallet_model->fetch_wallet_users($searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1;
			$data["page"] = "walletUsers";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
		
			// search billing
			$this->db->where('billing_id',$this->input->post('billing_id'));
			$billing = $this->db->get('billing');
			$billing_name = $billing->row()->billing_name;

			// finding last balance of wallet account
			$this->db->select_sum('wallet_trx_nominal');
			$this->db->where('billing_id', $this->input->post('billing_id')); 
			$this->db->group_by('billing_id');
			$wallet = $this->db->get('tb_wallet');
			$wallet_trx_nominal = 0;
			if ($wallet->num_rows > 0) {
				$wallet_trx_nominal = $wallet->row()->wallet_trx_nominal;
			}
			
			// insert into wallet
			$wallet = array (
				'billing_id' => $this->input->post('billing_id'),
				'wallet_trx_nominal' => $this->input->post('wallet_trx_nominal'),
				'wallet_balance' => $wallet_trx_nominal + $this->input->post('wallet_trx_nominal'),
				'wallet_trx_date' => date('Y-m-d', strtotime($this->input->post('wallet_trx_date'))),
				'wallet_trx_desc' => $this->input->post('wallet_trx_desc'),
				'bank_account_id' => $this->input->post('bank_account_id'),
				'wallet_status' => $this->input->post('wallet_status')
			);
			$this->db->insert('tb_wallet', $wallet); 
			$wallet_id = $this->db->insert_id();
					
			$msg += '<p>Wallet ('.'Tambah saldo : '.$billing_name.') has been added..!</p><br/>';
					
			// insert into cash
			$cash = array (
				'cash_type_id' => 5, //$this->input->post('wallet_type_id'),
				'cash_date' => date('Y-m-d', strtotime($this->input->post('wallet_trx_date'))),
				'cash_desc' => $this->input->post('wallet_trx_desc'),
				'cash_nominal' => $this->input->post('wallet_trx_nominal'),
				'bank_account_id' => $this->input->post('bank_account_id'),
				'wallet_id' => $wallet_id
			);
			
			$this->db->insert('tb_cash', $cash); 
			$msg += '<p>Cash has been increased '. $this->input->post('wallet_trx_nominal').' rupiahs!</p><br/>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('wallet/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doReduce() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
		
			// search billing
			$this->db->where('billing_id',$this->input->post('billing_id'));
			$billing = $this->db->get('billing');
			$billing_name = $billing->row()->billing_name;

			// finding last balance of wallet account
			$this->db->select_sum('wallet_trx_nominal');
			$this->db->where('billing_id', $this->input->post('billing_id')); 
			$this->db->group_by('billing_id');
			$wallet = $this->db->get('tb_wallet');
			$wallet_trx_nominal = 0;
			if ($wallet->num_rows > 0) {
				$wallet_trx_nominal = $wallet->row()->wallet_trx_nominal;
			}
			
			// insert into wallet
			$wallet = array (
				'billing_id' => $this->input->post('billing_id'),
				'wallet_trx_nominal' => '-'.$this->input->post('wallet_trx_nominal'),
				'wallet_balance' => $wallet_trx_nominal - $this->input->post('wallet_trx_nominal'),
				'wallet_trx_date' => date('Y-m-d', strtotime($this->input->post('wallet_trx_date'))),
				'wallet_trx_desc' => $this->input->post('wallet_trx_desc'),
				'bank_account_id' => $this->input->post('bank_account_id'),
				'wallet_status' => $this->input->post('wallet_status')
			);
			$this->db->insert('tb_wallet', $wallet); 
			$wallet_id = $this->db->insert_id();
					
			$msg += '<p>Wallet ('.'Pengurangan saldo : '.$billing_name.') has been added..!</p><br/>';
					
			// insert into cash
			$cash = array (
				'cash_type_id' => 5, //$this->input->post('wallet_type_id'),
				'cash_date' => date('Y-m-d', strtotime($this->input->post('wallet_trx_date'))),
				'cash_desc' => $this->input->post('wallet_trx_desc'),
				'cash_nominal' => '-'.$this->input->post('wallet_trx_nominal'),
				'bank_account_id' => $this->input->post('bank_account_id'),
				'wallet_id' => $wallet_id
			);
			
			$this->db->insert('tb_cash', $cash); 
			$msg += '<p>Cash has been decreased '. $this->input->post('wallet_trx_nominal').' rupiahs!</p><br/>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('wallet/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			
			// finding last balance of wallet account
			$this->db->select_sum('wallet_trx_nominal');
			$this->db->where('billing_id', $this->input->post('billing_id')); 
			$this->db->where('wallet_id <>', $this->input->post('wallet_id')); 
			$this->db->group_by('billing_id');
			$wallet = $this->db->get('tb_wallet');
			$wallet_trx_nominal = $wallet->row()->wallet_trx_nominal;
			
			// updating wallet
			$wallet = array(
				   'wallet_trx_date' => date('Y-m-d', strtotime($this->input->post('wallet_trx_date'))),
				   'wallet_trx_nominal' => $this->input->post('wallet_trx_nominal'),
				   'wallet_balance' => $wallet_trx_nominal + $this->input->post('wallet_trx_nominal'),
				   'wallet_status' => $this->input->post('wallet_status'),
				   'billing_id' => $this->input->post('billing_id')
				);
				
			$this->db->where('wallet_id', $this->input->post('wallet_id')); 
			$this->db->update('tb_wallet', $wallet);
			
			$msg .= '<p>wallet has been updated</p> '.$wallet_trx_nominal;
			
			// updating cash
			$cash = array (
					'cash_type_id' => 5, //$this->input->post('wallet_type_id'),
					'cash_date' => date('Y-m-d', strtotime($this->input->post('wallet_date'))),
					'cash_desc' => $this->input->post('wallet_desc'),
					'cash_nominal' => $this->input->post('wallet_nominal_cash'),
					'bank_account_id' => $this->input->post('bank_account_id'),
					'wallet_id' => $this->input->post('wallet_id')
				);
			
			$this->db->where('wallet_id',$this->input->post('wallet_id'));
			$query=$this->db->get('tb_cash');
			if ($query->num_rows > 0) {
				$this->db->where('wallet_id', $this->input->post('wallet_id')); 
				$this->db->update('tb_cash', $cash);
				$msg .= '<p>Cash has been updated. There are '. $this->input->post('wallet_nominal_cash') .' rupiahs for investment!</p>';
			} else {
				$this->db->insert('tb_cash', $cash); 
				$msg .= '<p>Cash has been increased '. $this->input->post('wallet_nominal_cash') .' rupiahs!</p>';
			}
			
			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('/wallet/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_wallet', array('wallet_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_cash', array('wallet_id' => $this->uri->segment(3))); 
			
			$msg = 'wallet was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('/wallet/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function reduce() {
		if($this->session->userdata('logged_in')) {
			
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
		
			// get agent list
			$this->db->where('billing_level', 47);
			$this->db->order_by('billing_name');
			$data['billing'] = $this->db->get('billing')->result();
			
			$data['page'] = "walletReduce";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
		
			// get agent list
			$this->db->where('billing_level', 47);
			$this->db->order_by('billing_name');
			$data['billing'] = $this->db->get('billing')->result();
			
			$data['page'] = "walletAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "walletUpdate";
			
			// get agent list
			$this->db->where('billing_level', 47);
			$this->db->order_by('billing_name');
			$data['billing'] = $this->db->get('billing')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// QUERY wallet
			$this->db->select('*');
			$this->db->from('tb_wallet');
			$this->db->where('tb_wallet.wallet_id',$this->uri->segment(3));
			$data['wallet'] = $this->db->get()->result();
			
			$this->db->where('wallet_id',$this->uri->segment(3));
			$query=$this->db->get('tb_wallet');
			if ($query->num_rows == 0) {
				$msg = '<p>wallet not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('wallet/lists'));
			}
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function search() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			$billing_id = $this->uri->segment(3);
			//echo $billing_id;
			$searchparam = null;
			
			if (!empty($billing_id)) {
				//echo 'masuk';
				$searchparam = array(	   
						'billing_id' => $billing_id,
				);
			} else {			

				// Searching
				$searchparam = array(
					'billing_name' => $this->input->post('billing_name'),
				  	'startdate' => date('Y-m-d', strtotime($this->input->post('startdate'))),
					'enddate' => date('Y-m-d', strtotime($this->input->post('enddate')))
				);
			}
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('wallet/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function search_users() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			$searchparam = null;
			
			// Searching
			$searchparam = array(
				'billing_name' => $this->input->post('billing_name')
			);
		
			
			$this->GenericModel->searchterm_handler($searchparam);
		
			redirect(site_url('wallet/users'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
