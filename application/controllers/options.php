<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Options extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('options_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->db->where(array('option_root_id' => NULL));
			$this->db->order_by('option_desc');
			$data['root_type'] = $this->db->get('tb_options')->result();
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/options/lists");
			$config["total_rows"] = $this->options_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_options"] = $this->options_model->fetch_options($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "optionList";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// insert into expense
			$option = array(
				   'option_type' => $this->input->post('option_type'),
				   'option_code' => $this->input->post('option_code'),
				   'option_desc' => $this->input->post('option_desc'),
				   'option_root_id' => $this->input->post('option_root_id')
				);
				
			$this->db->insert('tb_options', $option); 
			$msg .= '<p>Options ('.$this->input->post('option_desc').') has been added..!</p>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('options/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating expense
			$option = array(
				   'option_type' => $this->input->post('option_type'),
				   'option_code' => $this->input->post('option_code'),
				   'option_desc' => $this->input->post('option_desc'),
				   'option_root_id' => $this->input->post('option_root_id')
				);
				
			$this->db->where('option_id', $this->input->post('option_id')); 
			$this->db->update('tb_options', $option);
			
			$msg .= '<p>Options has been updated</p>';

			$this->session->set_flashdata('success_message',$msg);	
			redirect(site_url('options/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_options', array('option_id' => $this->uri->segment(3))); 
			
			$msg = 'Option was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('options/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			$this->db->where(array('option_root_id' => NULL));
			$this->db->order_by('option_desc');
			$data['root_type'] = $this->db->get('tb_options')->result();
			
			$data['page'] = "optionAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "optionUpdate";
			
			$this->db->where(array('option_root_id' => NULL));
			$this->db->order_by('option_desc');
			$data['root_type'] = $this->db->get('tb_options')->result();
			
			// QUERY option
			$this->db->where('tb_options.option_id',$this->uri->segment(3));
			$data['option'] = $this->db->get('tb_options')->result();
			
			$this->db->where('option_id',$this->uri->segment(3));
			$query=$this->db->get('tb_options');
			if ($query->num_rows == 0) {
				$msg = '<p>option not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('options/lists'));
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
					'option_root_id' => $this->input->post('option_root_id'),
					'option_code' => $this->input->post('option_code'),
				   'option_desc' => $this->input->post('option_desc'),
				   'option_type' => $this->input->post('option_type'),
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('options/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */