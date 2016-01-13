<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity extends CI_Controller {

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
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->db->order_by('option_desc');
			$data['activity_type'] = $this->db->get('tb_options')->result();
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/activity/lists");
			$config["total_rows"] = $this->activity_model->record_count($searchterm);
			$config["per_page"] = 30;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$this->db->order_by('user_name');
			$data['users'] = $this->db->get('user')->result();
			
			$data["list_activity"] = $this->activity_model->fetch_activity($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "activityList";
			
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
				   'user_id' => $this->input->post('user_id'),
				   'activity_desc' => $this->input->post('activity_desc'),				  
				   'activity_date' => $this->input->post('activity_date')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('activity/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */