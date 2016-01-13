<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); 
class ProductAction extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }
	
	public function doAddProduct() {
		if($this->session->userdata('logged_in')) {	
			$product = array(
				   'product_code' => $this->input->post('product_code'),
				   'product_name' => $this->input->post('product_name'),
				   'product_price' => $this->input->post('product_price'),
				   'product_stock' => $this->input->post('product_stock')
				);
			$this->db->insert('product', $product); 
			
			$msg = '<p>Produk baru ('.$this->input->post('product_name').') berhasil ditambah.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('productAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function doUpdateProduct() {
		if($this->session->userdata('logged_in')) {	
			$product = array(
				   'product_code' => $this->input->post('product_code'),
				   'product_name' => $this->input->post('product_name'),
				   'product_price' => $this->input->post('product_price'),
				   'product_stock' => $this->input->post('product_stock')
				);
				
			$this->db->where('id', $this->uri->segment(3)); 
			$this->db->update('product', $product);
			
			$msg = '<p>Produk '.$this->input->post('product_name').' berhasil diupdate.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('productAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function deleteProduct() {
		if($this->session->userdata('logged_in')) {	
			$this->db->delete('product', array('id' => $this->uri->segment(3))); 
			
			$msg = '<p>Produk berhasil dihapus dari muka bumi ini.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('productAction', 'refresh');
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function addProduct() {
		if($this->session->userdata('logged_in')) {	
			$data['page'] = "addProduct";
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function updateProduct() {
		if($this->session->userdata('logged_in')) {	
			$data['page'] = "updateProduct";
			$data['product']=$this->db->where('id',$this->uri->segment(3));
			$data['product']=$this->db->get('product')->result();
				
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
	
	public function index() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$data['listProduct']=$this->db->get('product')->result();
			$data['page']="listProduct";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */