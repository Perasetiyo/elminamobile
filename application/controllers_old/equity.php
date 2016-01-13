<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class equity extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('equity_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->db->where('option_type','EQUITY');
			$this->db->order_by('option_desc');
			$data['equity_type'] = $this->db->get('tb_options')->result();
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/equity/lists");
			$config["total_rows"] = $this->equity_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_equity"] = $this->equity_model->fetch_equity($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "equityList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function reporting(){
		if($this->session->userdata('logged_in')) {
		// ====== SEDEKAH ===========
			$this->db->select_sum('equity_nominal');
			$this->db->where('equity_type_id',2);
			$this->db->where('equity_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('equity_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_sedekah'] = $this->db->get('equity')->result();
		
		// ====== BELANJA ===========
			$this->db->select_sum('equity_nominal');
			$this->db->where('equity_type_id',4);
			$this->db->where('equity_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('equity_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_belanja'] = $this->db->get('equity')->result();
		
		// ====== PENGELUARAN RUTIN ===========
			$this->db->select_sum('equity_nominal');
			$this->db->where('equity_type_id',3);
			$this->db->where('equity_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('equity_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_equity'] = $this->db->get('equity')->result();
		
		// ====== GAJI ===========
			$this->db->select_sum('equity_nominal');
			$this->db->where('equity_type_id',7);
			$this->db->where('equity_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('equity_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_gaji'] = $this->db->get('equity')->result();
		
		// ====== HUTANG ===========
			$this->db->select_sum('equity_nominal');
			$this->db->where('equity_type_id',5);
			$this->db->where('equity_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('equity_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_hutang'] = $this->db->get('equity')->result();
		
		// ====== PIUTANG ===========
			$this->db->select_sum('equity_nominal');
			$this->db->where('equity_type_id',6);
			$this->db->where('equity_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('equity_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_piutang'] = $this->db->get('equity')->result();
		
		// ====== KAS ===========
			$this->db->select_sum('equity_nominal');
			$this->db->where('equity_type_id',8);
			$this->db->where('equity_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('equity_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_kas'] = $this->db->get('equity')->result();
		// ====== MODAL ===========
			$this->db->select_sum('equity_nominal');
			$this->db->where('equity_type_id',9);
			$this->db->where('equity_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('equity_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_modal'] = $this->db->get('equity')->result();
		// ====== INVESTASI ===========
			$this->db->select_sum('equity_nominal');
			$this->db->where('equity_type_id',10);
			$this->db->where('equity_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('equity_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_investasi'] = $this->db->get('equity')->result();
			
		// ====== OMSET ===========
			$this->db->select_sum('equity_nominal');
			$this->db->where('equity_type_id',1);
			$this->db->where('equity_date >=', date('Y-m-d', strtotime($this->input->post('startdate'))));
			$this->db->where('equity_date <=', date('Y-m-d', strtotime($this->input->post('enddate'))));
			$data['nominal_omset'] = $this->db->get('equity')->result();
			
			
			
			$data['page'] = "detailequity";
			$data['startdate'] = date('d-M-y', strtotime($this->input->post('startdate')));
			$data['enddate'] = date('d-M-y', strtotime($this->input->post('enddate')));
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function detail(){
		if($this->session->userdata('logged_in')) {
			$data['startdate'] = null;
			$data['enddate'] = null;
			$data['nominal_sedekah'] = null;
			$data['nominal_belanja'] = null;
			$data['nominal_equity'] = null;
			$data['nominal_gaji'] = null;
			$data['nominal_hutang'] = null;
			$data['nominal_piutang'] = null;
			$data['nominal_kas'] = null;
			$data['nominal_modal'] = null;
			$data['nominal_investasi'] = null;
			$data['nominal_omset'] = null;
			$data['page'] = "detailequity";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// insert into equity
			$equity = array(
				   'equity_type_id' => $this->input->post('equity_type_id'),
				   'equity_date' => date('Y-m-d', strtotime($this->input->post('equity_date'))),
				   'equity_desc' => $this->input->post('equity_desc'),
				   'equity_nominal' => $this->input->post('equity_nominal_cash')
				);
			$this->db->insert('tb_equity', $equity); 
			$msg += '<p>equity ('.$this->input->post('equity_desc').') has been added..!</p><br/>';
			$equity_id = $this->db->insert_id();
			
			// updating cash if paid by cash
			if ($this->input->post('equity_nominal_cash') > 0) {
				$cash = array (
					'cash_type_id' => $this->input->post('equity_type_id'),
					'cash_date' => date('Y-m-d', strtotime($this->input->post('equity_date'))),
					'cash_desc' => $this->input->post('equity_desc'),
					'cash_nominal' => $this->input->post('equity_nominal_cash'),
					'bank_account_id' => $this->input->post('bank_account_id'),
					'equity_id' => $equity_id
					
				);
				$this->db->insert('tb_cash', $cash); 
				$msg += '<p>Cash has been increased '. $this->input->post('equity_nominal_cash') .' rupiahs!</p><br/>';
			}
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('equity/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating equity
			$equity = array(
				   'equity_date' => date('Y-m-d', strtotime($this->input->post('equity_date'))),
				   'equity_desc' => $this->input->post('equity_desc'),
				   'equity_nominal' => $this->input->post('equity_nominal_cash'),
				   'equity_type_id' => $this->input->post('equity_type_id')
				   
				);
				
			$this->db->where('equity_id', $this->input->post('equity_id')); 
			$this->db->update('tb_equity', $equity);
			
			$msg .= '<p>Equity has been updated</p>';
			
			// updating cash
			$cash = array (
					'cash_type_id' => $this->input->post('equity_type_id'),
					'cash_date' => date('Y-m-d', strtotime($this->input->post('equity_date'))),
					'cash_desc' => $this->input->post('equity_desc'),
					'cash_nominal' => $this->input->post('equity_nominal_cash'),
					'bank_account_id' => $this->input->post('bank_account_id'),
					'equity_id' => $this->input->post('equity_id')
					
				);
			
			$this->db->where('equity_id',$this->input->post('equity_id'));
			$query=$this->db->get('tb_cash');
			if ($query->num_rows > 0) {
				$this->db->where('equity_id', $this->input->post('equity_id')); 
				$this->db->update('tb_cash', $cash);
				$msg .= '<p>Cash has been updated. There are '. $this->input->post('equity_nominal_cash') .' rupiahs for investment!</p>';
			} else {
				$this->db->insert('tb_cash', $cash); 
				$msg .= '<p>Cash has been increased '. $this->input->post('equity_nominal_cash') .' rupiahs!</p>';
			}
			
			
			
			/** updating credit / liabilities
			$liabilities = array (
					'liabilities_type_id' => $this->input->post('liabilities_type_id'),
					'liabilities_date' => date('Y-m-d', strtotime($this->input->post('equity_date'))),
					'liabilities_desc' => $this->input->post('equity_desc'),
					'liabilities_nominal' => $this->input->post('equity_nominal_credit'),
					'liabilities_cause_id' => $this->input->post('equity_type_id'),
					'equity_id' => $this->input->post('equity_id')
				);
			
			$this->db->where('equity_id',$this->input->post('equity_id'));
			$query=$this->db->get('tb_liabilities');
			if ($query->num_rows > 0) {
				$this->db->where('equity_id', $this->input->post('equity_id')); 
				$this->db->update('tb_liabilities', $liabilities); 
			} else {
				$this->db->insert('tb_liabilities', $liabilities); 
			}
			
			$msg .= '<p>Liabilities has been increased '. $this->input->post('equity_nominal_credit') .' rupiahs!</p>';
			**/
			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('/equity/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_equity', array('equity_id' => $this->uri->segment(3))); 
			$this->db->delete('tb_cash', array('equity_id' => $this->uri->segment(3))); 
			
			$msg = 'Equity was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('/equity/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->where('option_type', 'EQUITY');
			$this->db->order_by('option_desc');
			$data['equity_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			$data['page'] = "equityAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "equityUpdate";
			
			$this->db->where('option_type', 'EQUITY');
			$this->db->order_by('option_desc');
			$data['equity_type'] = $this->db->get('tb_options')->result();
			
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			// QUERY equity
			$this->db->select('*, tb_equity.equity_id AS main_equity_id');
			$this->db->from('tb_equity');
			$this->db->join('tb_cash', 'tb_cash.equity_id = tb_equity.equity_id','left');
			$this->db->where('tb_equity.equity_id',$this->uri->segment(3));
			$data['equity'] = $this->db->get()->result();
			
			$this->db->where('equity_id',$this->uri->segment(3));
			$query=$this->db->get('tb_equity');
			if ($query->num_rows == 0) {
				$msg = '<p>equity not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('equity/lists'));
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
				   'equity_type_id' => $this->input->post('equity_type_id'),
				   'equity_desc' => $this->input->post('equity_desc'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'debet_credit' => $this->input->post('debet_credit')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('equity/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */