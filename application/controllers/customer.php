<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('customer_model');
		$this->load->model('GenericModel');
    }
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg  = '';
			if ($this->input->post('is_ds') == true) {
				
				$billing = array(
				   'billing_name' => $this->input->post('billing_name'),
				   'billing_street' => $this->input->post('billing_street'),
				   'billing_kec' => $this->input->post('billing_kec'),
				   'billing_prov' => $this->input->post('billing_prov'),
				   'billing_phone' => $this->input->post('billing_phone'),
				   'billing_kec' => $this->input->post('billing_kec'),
				   'billing_city' => $this->input->post('billing_city'),
				   'billing_country' => $this->input->post('billing_country'),
				   'billing_level' => $this->input->post('billing_level'),
				   'dropshipper_id' => $this->input->post('agent_id')
				);
				$this->db->insert('billing', $billing); 
				$msg = '<p>List Building bertambah 1 beserta dropshippernya. Mantappp!</p>';
			} else {
				$billing = array(
				   'billing_name' => $this->input->post('billing_name'),
				   'billing_street' => $this->input->post('billing_street'),
				   'billing_kec' => $this->input->post('billing_kec'),
				   'billing_prov' => $this->input->post('billing_prov'),
				   'billing_phone' => $this->input->post('billing_phone'),
				   'billing_kec' => $this->input->post('billing_kec'),
				   'billing_city' => $this->input->post('billing_city'),
				   'billing_country' => $this->input->post('billing_country'),
				   'billing_level' => $this->input->post('billing_level')
				);
				$this->db->insert('billing', $billing); 
				$msg = '<p>List Building bertambah 1. Mantappp!</p>';
			
			}
			
			
			$this->session->set_flashdata('success_message',$msg);	
			
			redirect(site_url('customer/lists'));
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {		
			$msg  = '';
			$ds_id = 0;
			if ($this->input->post('is_ds') == true) {
				$dropshipper = array (
					'shipper_name' => $this->input->post('shipper_name'),
					'shipper_phone' => $this->input->post('shipper_phone'),
				);
				
				$this->db->where('shipper_id', $this->input->post('ds_id'));
				$queryDS = $this->db->get('shipper');
				if($queryDS->num_rows == 1) {
					$this->db->where('shipper_id', $this->input->post('ds_id'));
					$this->db->update('shipper', $dropshipper);
					$dsRow = $queryDS->row();
					$ds_id = $dsRow->shipper_id;
				} else {
					$this->db->insert('shipper', $dropshipper);
					$ds_id = $this->db->insert_id();
				}
				
				$billing = array(
				   'billing_name' => $this->input->post('billing_name'),
				   'billing_street' => $this->input->post('billing_street'),
				   'billing_kec' => $this->input->post('billing_kec'),
				   'billing_prov' => $this->input->post('billing_prov'),
				   'billing_phone' => $this->input->post('billing_phone'),
				   'billing_kec' => $this->input->post('billing_kec'),
				   'billing_city' => $this->input->post('billing_city'),
				   'billing_country' => $this->input->post('billing_country'),
				   'billing_level' => $this->input->post('billing_level'),
				   'dropshipper_id' => $ds_id
				);
				
				$this->db->where('billing_id', $this->uri->segment(3));
				$this->db->update('billing', $billing); 
				$msg = '<p>List Building diedit beserta dropshippernya. Mantappp!</p>';
			} else {
				$billing = array(
				   'billing_name' => $this->input->post('billing_name'),
				   'billing_street' => $this->input->post('billing_street'),
				   'billing_kec' => $this->input->post('billing_kec'),
				   'billing_prov' => $this->input->post('billing_prov'),
				   'billing_phone' => $this->input->post('billing_phone'),
				   'billing_kec' => $this->input->post('billing_kec'),
				   'billing_city' => $this->input->post('billing_city'),
				   'billing_country' => $this->input->post('billing_country'),
				   'billing_level' => $this->input->post('billing_level')
				);
				$this->db->where('billing_id', $this->uri->segment(3));
				$this->db->update('billing', $billing); 
				$msg = '<p>List Building dah diedit. Mantappp!</p>';
			
			}
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('customer/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {	
			$this->db->delete('billing', array('billing_id' => $this->uri->segment(3))); 
			
			$msg = '<p>Billing berhasil dihapus dari muka bumi ini tapi tidak dengan dropshippernya.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('customer/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {	
			// looking for level
			$this->db->where('option_type', 'BILL_LV');
			$this->db->order_by('option_desc');
			$data['billing_level'] = $this->db->get('tb_options')->result();
			
			// looking for agent
			$this->db->where('billing_level', 47);
			$this->db->order_by('billing_name');
			$data['agents'] = $this->db->get('billing')->result();
			
			
			$data['page'] = "customerAdd";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {	
			
			// looking for level
			$this->db->where('option_type', 'BILL_LV');
			$this->db->order_by('option_desc');
			$data['billing_level'] = $this->db->get('tb_options')->result();
		
			$data['page'] = "customerUpdate";
			
			$data['billing']=$this->db->select('*');
			$data['billing']=$this->db->from('billing');
			$data['billing']=$this->db->join('shipper', 'billing.dropshipper_id = shipper.shipper_id','left');
			$data['billing']=$this->db->join('tb_options', 'billing.billing_level = tb_options.option_id','left');
			$data['billing']=$this->db->where('billing_id',$this->uri->segment(3));
			$data['billing']=$this->db->get()->result();
			
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
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/customer/lists");
			$config["total_rows"] = $this->customer_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			// looking for level
			$this->db->where('option_type', 'BILL_LV');
			$this->db->order_by('option_desc');
			$data['billing_level'] = $this->db->get('tb_options')->result();
			
			$data["list_billing"] = $this->customer_model->fetch_customer($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "customerList";
			
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
				   'billing_name' => $this->input->post('billing_name'),
				   'billing_phone' => $this->input->post('billing_phone'),
				   'billing_level' => $this->input->post('billing_level')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('customer/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function index() {	
		if($this->session->userdata('logged_in')) {	
			
		} else {
				 redirect(site_url('login'));
		}	
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */