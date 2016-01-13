<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class fixed_cost extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('fixed_cost_model');
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
			$config["base_url"] = base_url("index.php/fixed_cost/lists");
			$config["total_rows"] = $this->fixed_cost_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_fixed_cost"] = $this->fixed_cost_model->fetch_fixed_cost($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "fixedCostList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// insert into fixed_cost
			$fixed_cost = array(
				   'fixed_cost_name' => $this->input->post('fixed_cost_name'),
				   'fixed_cost_nominal' => $this->input->post('fixed_cost_nominal'),
				   'fixed_cost_status' => $this->input->post('fixed_cost_status'),
				   'fixed_cost_type_id' => $this->input->post('fixed_cost_type_id'),
				   'fixed_cost_per_date' => date('Y-m-d', strtotime($this->input->post('fixed_cost_per_date')))
				);
			$this->db->insert('tb_fixed_cost', $fixed_cost); 
			$msg .= '<p>fixed_cost ('.$this->input->post('fixed_cost_name').') has been added..!</p>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('fixed_cost/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating fixed_cost
			$fixed_cost = array(
				   'fixed_cost_name' => $this->input->post('fixed_cost_name'),
				   'fixed_cost_nominal' => $this->input->post('fixed_cost_nominal'),
				   'fixed_cost_status' => $this->input->post('fixed_cost_status'),
				   'fixed_cost_type_id' => $this->input->post('fixed_cost_type_id'),
				   'fixed_cost_per_date' => date('Y-m-d', strtotime($this->input->post('fixed_cost_per_date')))
				);
				
			$this->db->where('fixed_cost_id', $this->input->post('fixed_cost_id')); 
			$this->db->update('tb_fixed_cost', $fixed_cost);
			
			$msg .= '<p>fixed_cost has been updated</p>';

			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('fixed_cost/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "fixedCostAdd";
			
			$this->db->where('option_type','EXP');
			$this->db->order_by('option_desc');
			$data['fixed_cost_type'] = $this->db->get('tb_options')->result();
			
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "fixedCostUpdate";
			
			$this->db->where('option_type','EXP');
			$this->db->order_by('option_desc');
			$data['fixed_cost_type'] = $this->db->get('tb_options')->result();
			
			// QUERY fixed_cost
			$this->db->where('fixed_cost_id',$this->uri->segment(3));
			$data['fixed_cost'] = $this->db->get('tb_fixed_cost')->result();
			
			
			$this->db->where('fixed_cost_id',$this->uri->segment(3));
			$query=$this->db->get('tb_fixed_cost');
			if ($query->num_rows == 0) {
				$msg = '<p>fixed_cost not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('fixed_cost/lists'));
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
				   'fixed_cost_name' => $this->input->post('fixed_cost_name'),
				   
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('fixed_cost/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */