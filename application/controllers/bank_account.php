<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); 
class Bank_account extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }
	
	public function doAdd() {
		if($this->session->userdata('logged_in')) {	
			$ba = array(
				   'bank_account_name' => $this->input->post('bank_account_name'),
				   'ba_saldo' => $this->input->post('ba_saldo')
				);
			$this->db->insert('bank_account', $ba); 
			
			$msg = '<p>Bank Account ('.$this->input->post('bank_account_name').') berhasil ditambah.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('bank_account/lists', 'refresh'));
		} else {
				 redirect(site_url('login', 'refresh'));
		}
	}
	
	public function doUpdate() {
		if($this->session->userdata('logged_in')) {	
			$ba = array(
				   'bank_account_name' => $this->input->post('bank_account_name'),
				   'ba_saldo' => $this->input->post('ba_saldo')
				);	
			$this->db->where('id', $this->uri->segment(3)); 
			$this->db->update('bank_account', $ba);
			
			$msg = '<p>Bank Account '.$this->input->post('product_name').' berhasil diupdate.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('bank_account/lists', 'refresh'));
		} else {
				 redirect(site_url('login', 'refresh'));
		}
	}
	
	public function delete() {
		if($this->session->userdata('logged_in')) {	
			$this->db->delete('bank_account', array('id' => $this->uri->segment(3))); 
			
			$msg = '<p>Bank Account berhasil dihapus</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect(site_url('bank_account/lists', 'refresh'));
		} else {
				 redirect(site_url('login', 'refresh'));
		}
	}
	
	public function add() {
		if($this->session->userdata('logged_in')) {	
			$data['page'] = "bankAccountAdd";
			$this->load->view('dashboard',$data);
		} else {
				 redirect(site_url('login', 'refresh'));
		}
	}
	
	public function update() {
		if($this->session->userdata('logged_in')) {	
			$data['page'] = "bankAccountUpdate";
			$data['bank_account']=$this->db->where('id',$this->uri->segment(3));
			$data['bank_account']=$this->db->get('bank_account')->result();
				
			$this->load->view('dashboard',$data);
		} else {
				 redirect(site_url('login', 'refresh'));
		}
	}
	
	public function lists() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			//$data['listBankAccount']=$this->db->get('bank_account')->result();
			$data['page']="bankAccountList";
			
			$query_ba = $this->db->get('bank_account')->result();
			foreach($query_ba as $item):
			
				$this->db->select('bank_account_name, sum(cash_nominal) as nominal');
				$this->db->from('tb_cash');
				$this->db->join('bank_account','bank_account.id = tb_cash.bank_account_id');
				$this->db->group_by('bank_account_id');
				$data['listBankAccount'] = $this->db->get()->result();
				
			endforeach;
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login', 'refresh'));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */