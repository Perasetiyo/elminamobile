<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); 
class BillingAction extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }
	
	public function doAddBilling() {
		if($this->session->userdata('logged_in')) {
			$msg  = '';
			if ($this->input->post('is_ds') == true) {
				$dropshipper = array (
					'shipper_name' => $this->input->post('shipper_name'),
					'shipper_phone' => $this->input->post('shipper_phone'),
				);
				$this->db->insert('shipper', $dropshipper);
				$ds_id = $this->db->insert_id();
			
				$billing = array(
				   'billing_name' => $this->input->post('billing_name'),
				   'billing_street' => $this->input->post('billing_street'),
				   'billing_kec' => $this->input->post('billing_kec'),
				   'billing_prov' => $this->input->post('billing_prov'),
				   'billing_phone' => $this->input->post('billing_phone'),
				   'billing_kec' => $this->input->post('billing_kec'),
				   'billing_city' => $this->input->post('billing_city'),
				   'billing_country' => $this->input->post('billing_country'),
				   'is_reseller' => $this->input->post('is_reseller'),
				   'dropshipper_id' => $ds_id
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
				   'is_reseller' => $this->input->post('is_reseller')
				);
				$this->db->insert('billing', $billing); 
				$msg = '<p>List Building bertambah 1. Mantappp!</p>';
			
			}
			
			
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('billingAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function doUpdateBilling() {
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
				   'is_reseller' => $this->input->post('is_reseller'),
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
				   'is_reseller' => $this->input->post('is_reseller')
				);
				$this->db->where('billing_id', $this->uri->segment(3));
				$this->db->update('billing', $billing); 
				$msg = '<p>List Building dah diedit. Mantappp!</p>';
			
			}
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('billingAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function deleteBilling() {
		if($this->session->userdata('logged_in')) {	
			$this->db->delete('billing', array('billing_id' => $this->uri->segment(3))); 
			
			$msg = '<p>Billing berhasil dihapus dari muka bumi ini tapi tidak dengan dropshippernya.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('billingAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function addBilling() {
		if($this->session->userdata('logged_in')) {	
			$data['page'] = "addBilling";
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function updateBilling() {
		if($this->session->userdata('logged_in')) {	
			$data['page'] = "updateBilling";
			
			$data['billing']=$this->db->select('*');
			$data['billing']=$this->db->from('billing');
			$data['billing']=$this->db->join('shipper', 'billing.dropshipper_id = shipper.shipper_id','left');
			$data['billing']=$this->db->where('billing_id',$this->uri->segment(3));
			$data['billing']=$this->db->get()->result();
			
			$this->load->view('dashboard',$data);
		} else {
				 redirect('login', 'refresh');
		}
	}

	
	public function index() {	
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
				
			$data['listBilling']=$this->db->select('*');
			$data['listBilling']=$this->db->from('billing');
			$data['listBilling']=$this->db->join('shipper', 'billing.dropshipper_id = shipper.shipper_id','left');
			$data['listBilling']=$this->db->order_by('billing_name');
			$data['listBilling']=$this->db->get()->result();
			$data['page']="listBilling";
			
			$this->load->view('dashboard',$data);
		} else {
				 redirect('login', 'refresh');
		}	
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */