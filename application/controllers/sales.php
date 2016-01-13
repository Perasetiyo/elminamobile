<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('sales_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/sales/lists");
			$config["total_rows"] = $this->sales_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_sales"] = $this->sales_model->fetch_sales($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "salesList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login'));
		}
	}
	
	public function search() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			// Searching
			$searchparam = array(
				   'sales_type_id' => $this->input->post('sales_type_id'),
				   'sales_desc' => $this->input->post('sales_desc'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'debet_credit' => $this->input->post('debet_credit')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect('sales/lists'));
		} else {
			 redirect('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */