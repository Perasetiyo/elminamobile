<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		$this->load->helper('url');
		
		$this->load->model('activity_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	}
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// insert into product
			$product = array(
				   'product_name' => $this->input->post('product_name'),
				   'current_cogs' => $this->input->post('current_cogs'),
				   'product_code' => $this->input->post('product_code'),
				   'product_weight' => $this->input->post('product_weight'),
				   'current_special_price' => $this->input->post('product_price_1'),
				   'current_wholesale_price' => $this->input->post('product_price_2'),
				   'status' => $this->input->post('status')
				);
			$this->db->insert('tb_product', $product); 
			$msg += '<p>Product ('.$this->input->post('product_desc').') has been added..!</p><br/>';
			
			$this->session->set_flashdata('success_message',$msg);
						
			redirect(site_url('stock/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {
			$msg = "";
			// updating product
			$product = array(
				   'product_name' => $this->input->post('product_name'),
				   'current_cogs' => $this->input->post('current_cogs'),
				   'product_code' => $this->input->post('product_code'),
				   'product_weight' => $this->input->post('product_weight'),
				   'current_special_price' => $this->input->post('product_price_1'),
				   'current_wholesale_price' => $this->input->post('product_price_2'),
				   'status' => $this->input->post('status')
				);
				
			$this->db->where('product_id', $this->input->post('product_id')); 
			$this->db->update('tb_product', $product);
			
			$msg .= '<p>Product has been updated</p>';
			
			redirect(site_url('stock/lists'));
		} else {
			 redirect(site_url('login'));
		}	
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {
			$this->db->delete('tb_product', array('product_id' => $this->uri->segment(3))); 
			
			$msg = 'Products was deleted.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('product/lists'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {
			
			$data['page'] = "productAdd";
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {
			$data['page'] = "productUpdate";
			
			// QUERY product
			$this->db->select('*');
			$this->db->from('tb_product');
			$this->db->where('product_id', $this->input->post('find_product_id'));
			$data['product'] = $this->db->get()->result();
			
			$this->db->where('product_id', $this->input->post('find_product_id'));
			$query=$this->db->get('tb_product');
			if ($query->num_rows == 0) {
				$msg = '<p>product not found.</p>';
				$this->session->set_flashdata('error_message',$msg);	
				redirect(site_url('stock/lists'));
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
				   'product_type_id' => $this->input->post('product_type_id'),
				   'product_desc' => $this->input->post('product_desc'),
				   'bank_account_id' => $this->input->post('bank_account_id'),
				   'debet_credit' => $this->input->post('debet_credit')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('productAction'));
		} else {
			 redirect(site_url('login'));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */