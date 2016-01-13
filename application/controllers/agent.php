<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agent extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('agent_model');
		$this->load->model('GenericModel');
    }
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg  = '';
			
			$billing = array(
			   'billing_level' => $this->input->post('billing_level'),
			   'billing_name' => $this->input->post('billing_name'),
			   'billing_street' => $this->input->post('billing_street'),
			   'billing_kec' => $this->input->post('billing_kec'),
			   'billing_prov' => $this->input->post('billing_prov'),
			   'billing_kec' => $this->input->post('billing_kec'),
			   'billing_city' => $this->input->post('billing_city'),
			   'billing_country' => $this->input->post('billing_country'),
				
			   'billing_phone' => $this->input->post('billing_phone'),
			   'billing_whatsapp' => $this->input->post('billing_whatsapp'),
			   'billing_bbm' => $this->input->post('billing_bbm'),
			   'billing_facebook' => $this->input->post('billing_facebook'),
			   'billing_fanpage' => $this->input->post('billing_fanpage'),
			   'billing_twitter' => $this->input->post('billing_twitter'),
			   'billing_instagram' => $this->input->post('billing_instagram'),
			   'billing_web' => $this->input->post('billing_web'),
			   
			   'billing_postal_code' => $this->input->post('billing_postal_code'),
			   'billing_email' => $this->input->post('billing_email'),	
			   'billing_upline' => $this->input->post('billing_upline'),	
			   
			);
			$this->db->insert('billing', $billing); 
			$msg = '<p>List Building bertambah 1. Mantappp!</p>';
			
			$this->session->set_flashdata('success_message',$msg);	
			
			redirect(site_url('agent/lists'));
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {		
			$msg  = '';

			$billing = array(
			   'billing_level' => $this->input->post('billing_level'),
			   'billing_name' => $this->input->post('billing_name'),
			   
			   'billing_street' => $this->input->post('billing_street'),
			   'billing_kec' => $this->input->post('billing_kec'),
			   'billing_prov' => $this->input->post('billing_prov'),			   
			   'billing_kec' => $this->input->post('billing_kec'),
			   'billing_city' => $this->input->post('billing_city'),
			   'billing_country' => $this->input->post('billing_country'),
			   
			   'billing_phone' => $this->input->post('billing_phone'),
			   'billing_whatsapp' => $this->input->post('billing_whatsapp'),
			   'billing_bbm' => $this->input->post('billing_bbm'),
			   'billing_facebook' => $this->input->post('billing_facebook'),
			   'billing_fanpage' => $this->input->post('billing_fanpage'),
			   'billing_twitter' => $this->input->post('billing_twitter'),
			   'billing_instagram' => $this->input->post('billing_instagram'),
			   'billing_web' => $this->input->post('billing_web'),
			   
			   'billing_flag1' => $this->input->post('agen_status'),
			   
			   'billing_postal_code' => $this->input->post('billing_postal_code'),
			   'billing_email' => $this->input->post('billing_email'),	
			   'billing_upline' => $this->input->post('billing_upline'),	
			   
			   //'billing_level' => 47
			);
				
			$this->db->where('billing_id', $this->uri->segment(3));
			$this->db->update('billing', $billing); 

			// get agent by area
			/**$this->db->where('billing_prov', $this->input->post('billing_prov'));
			$count_billing_per_area=$this->db->count_all_results('billing');
			
			// get area name
			/** $this->db->where('tb_options.option_id', $this->input->post('billing_prov'));
			$this->db->join('tb_options as root', 'tb_options.option_root_id = root.option_id','left');
			$options = $this->db->get('tb_options');
			$area_code = $options->row()->option_code;
			**/
			// analyzing agent code
			//$code_1 =  $this->uri->segment(3);
			//$code_2 = $area_code;
			//$code_3 = $count_billing_per_area+1;
			
			$msg = '<p>Success update Billing';// (Code : '.$code_1.'-'.$code_2.'-'.$code_3.')</p>';

			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('agent/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {	
			$this->db->delete('billing', array('billing_id' => $this->uri->segment(3))); 
			
			$msg = '<p>Billing berhasil dihapus dari muka bumi ini tapi tidak dengan dropshippernya.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('agent/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {	

			// looking for level
			$this->db->where('option_type', 'BILL_LV');
			$this->db->order_by('option_desc');
			$data['billing_levels'] = $this->db->get('tb_options')->result();

			// looking for status
			$this->db->where('option_type', 'AGEN_STATUS');
			$this->db->order_by('option_desc');
			$data['agen_status'] = $this->db->get('tb_options')->result();
		
			// looking for province
			$this->db->where('option_type', 'PROVINCE');
			$this->db->order_by('option_desc');
			$data['province'] = $this->db->get('tb_options')->result();
			
			// looking for agent
			$this->db->where('billing_level', 47);
			$this->db->where('billing_flag1', 102);
			$this->db->order_by('billing_name');
			$data['billing_upline'] = $this->db->get('billing')->result();
		
			
			$data['page'] = "agentAdd";
			
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
			$data['billing_levels'] = $this->db->get('tb_options')->result();

			// looking for status
			$this->db->where('option_type', 'AGEN_STATUS');
			$this->db->order_by('option_desc');
			$data['agen_status'] = $this->db->get('tb_options')->result();
		
			// looking for province
			$this->db->where('option_type', 'PROVINCE');
			$this->db->order_by('option_desc');
			$data['province'] = $this->db->get('tb_options')->result();
			
			// looking for agent
			$this->db->where('billing_level', 47);
			$this->db->where('billing_flag1', 102);
			$this->db->order_by('billing_name');
			$data['billing_upline'] = $this->db->get('billing')->result();
		

			$data['page'] = "agentUpdate";
			
			$data['billing']=$this->db->select('billing.*');
			$data['billing']=$this->db->from('billing');
			$data['billing']=$this->db->join('billing as upline', 'upline.billing_id = billing.billing_upline','left');
			$data['billing']=$this->db->join('tb_options as flag1', 'billing.billing_flag1 = flag1.option_id','left');
			$data['billing']=$this->db->join('tb_options as prov', 'billing.billing_prov = prov.option_id','left');
			$data['billing']=$this->db->where('billing.billing_id',$this->uri->segment(3));
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
			$config["base_url"] = base_url("index.php/agent/lists");
			$config["total_rows"] = $this->agent_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			// looking for level
			$this->db->where('option_type', 'BILL_LV');
			$this->db->order_by('option_desc');
			$data['billing_levels'] = $this->db->get('tb_options')->result();

			// looking for agent status
			$this->db->where('option_type', 'AGEN_STATUS');
			$this->db->order_by('option_desc');
			$data['agen_status'] = $this->db->get('tb_options')->result();
			
			$data["list_billing"] = $this->agent_model->fetch_agent($config["per_page"], $page, $searchterm);
			$data["total_list_billing"] = $this->agent_model->record_count($searchterm);
			
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "agentList";
			
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
				   'billing_city' => $this->input->post('billing_city'),
				   'billing_level' => $this->input->post('billing_level'),
				   'agen_status' => $this->input->post('agen_status')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('agent/lists'));
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
