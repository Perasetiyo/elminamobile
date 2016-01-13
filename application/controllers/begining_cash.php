<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Begining_cash extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('begining_cash_model');
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
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/begining_cash/lists");
			$config["total_rows"] = $this->begining_cash_model->record_count($searchterm);
			$config["per_page"] = 12;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_begining_cash"] = $this->begining_cash_model->fetch_begining_cash($config["per_page"], $page, $searchterm);
			
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "beginingCash";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// insert into beginingCash
			$query_bank_account = $this->db->get('bank_account');
			if ($query_bank_account->num_rows > 0) {
				foreach ($query_bank_account->result() as $row) {
					
					$begining_cash_nominal = $this->input->post('begining_cash_nominal'.$row->id);
					
					$begining_cash = array(
					   'begining_cash_period' => $this->input->post('period'),
					   'begining_cash_year' => $this->input->post('begining_cash_year'),
					   'begining_cash_nominal' => $begining_cash_nominal,
					   'bank_account_id' => $row->id
					);
					
					$this->db->insert('tb_begining_cash', $begining_cash); 
				}
				$msg .= '<p>Begining cash has been added..!</p>';
				$this->session->set_flashdata('success_message',$msg);
			} else {
				
			}
			
			redirect(site_url('begining_cash/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating beginingCash
			$query_bank_account = $this->db->get('bank_account');
			if ($query_bank_account->num_rows > 0) {
				foreach ($query_bank_account->result() as $row) {
					//$begining_cash_nominal = $this->input->post('begining_cash_nominal'.$row->id);
					//echo $begining_cash_nominal;
					$beginingCash = array(
					   'begining_cash_nominal' => $this->input->post('begining_cash_nominal'.$row->id)
					);
				
					$this->db->where('begining_cash_period', $this->input->post('period')); 
					$this->db->where('begining_cash_year', $this->input->post('year')); 
					$this->db->where('bank_account_id', $row->id);
					
					$this->db->update('tb_begining_cash', $beginingCash);
					//$msg .= '<p>Begining Cash has been updated'.$this->input->post('begining_cash_nominal'.$row->id).'.'.$this->input->post('period').'.'.$this->input->post('year').'.'.$row->bank_account_name.'</p>';
				}
				$msg .= '<p>Begining Cash has been updated</p>';
				$this->session->set_flashdata('success_message',$msg);	
			}
			redirect(site_url('begining_cash/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->delete('tb_begining_cash', 
						array(
							'begining_cash_period' => $this->uri->segment(3),
							'begining_cash_year' => $this->uri->segment(4)
			)); 
			
			$msg = '<p>Begining Cash was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('begining_cash/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			// get month list
			$where = "option_type = 'MON'";
			$this->db->where($where);
			$this->db->order_by('option_type');
			$data['months'] = $this->db->get('tb_options')->result();
			
			// get bank list
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			
			$data['page'] = "beginingCashAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "beginingCashUpdate";
			
			// get month list
			$where = "option_type = 'MON'";
			$this->db->where($where);
			$this->db->order_by('option_type');
			$data['months'] = $this->db->get('tb_options')->result();
			
			// get bank list
			$this->db->order_by('bank_account_name');
			$data['bank_account'] = $this->db->get('bank_account')->result();
			
			
			// QUERY BEGINING CASH
			$this->db->where('begining_cash_id', $this->uri->segment(3));
			$this->db->join('bank_account','bank_account.id = tb_begining_cash.bank_account_id');
			
			$data['begining_cash'] = $this->db->get('tb_begining_cash')->result();
			
			$this->db->where('begining_cash_id',$this->uri->segment(3));
			$this->db->join('bank_account','bank_account.id = tb_begining_cash.bank_account_id');
			
			$query=$this->db->get('tb_begining_cash');
			if ($query->num_rows == 0) {
				$msg = '<p>Begining Cash not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('begining_cash/lists'));
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
				   'begining_cash_period' => $this->input->post('period')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('begining_cash/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */