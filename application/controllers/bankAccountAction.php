<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); 
class BankAccountAction extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }
	
	public function doAddBankAccount() {
		if($this->session->userdata('logged_in')) {	
			$ba = array(
				   'bank_account_name' => $this->input->post('bank_account_name'),
				   'ba_saldo' => $this->input->post('ba_saldo')
				);
			$this->db->insert('bank_account', $ba); 
			
			$msg = '<p>Bank Account ('.$this->input->post('bank_account_name').') berhasil ditambah.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('bankAccountAction', 'refresh');
		} else {
				 redirect('login', 'refresh');
		}
	}
	
	public function doUpdateBankAccount() {
		if($this->session->userdata('logged_in')) {	
			$ba = array(
				   'bank_account_name' => $this->input->post('bank_account_name'),
				   'ba_saldo' => $this->input->post('ba_saldo')
				);	
			$this->db->where('id', $this->uri->segment(3)); 
			$this->db->update('bank_account', $ba);
			
			$msg = '<p>Bank Account '.$this->input->post('product_name').' berhasil diupdate.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('bankAccountAction', 'refresh');
		} else {
				 redirect('login', 'refresh');
		}
	}
	
	public function deleteBankAccount() {
		if($this->session->userdata('logged_in')) {	
			$this->db->delete('bank_account', array('id' => $this->uri->segment(3))); 
			
			$msg = '<p>BA berhasil dihapus dari muka bumi ini.</p>';
			$this->session->set_flashdata('success_message',$msg);	
						
			redirect('bankAccountAction', 'refresh');
		} else {
				 redirect('login', 'refresh');
		}
	}
	
	public function addBankAccount() {
		if($this->session->userdata('logged_in')) {	
			$data['page'] = "addBankAccount";
			$this->load->view('dashboard',$data);
		} else {
				 redirect('login', 'refresh');
		}
	}
	
	public function updateBankAccount() {
		if($this->session->userdata('logged_in')) {	
			$data['page'] = "updateBankAccount";
			$data['bank_account']=$this->db->where('id',$this->uri->segment(3));
			$data['bank_account']=$this->db->get('bank_account')->result();
				
			$this->load->view('dashboard',$data);
		} else {
				 redirect('login', 'refresh');
		}
	}
	
	public function index() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$data['listBankAccount']=$this->db->get('bank_account')->result();
			$data['page']="listBankAccount";
			
			$listBA = $this->db->get('bank_account')->result();
			foreach($listBA as $item):
			
				$this->db->select_sum('cashflow_nominal', 'nominal');
				$this->db->from('cashflow');
				$this->db->where('debet_credit',"DEBET");
				$this->db->where('bank_account_id',$item->id);
				$result = $this->db->get()->result();
				$debet = $result[0]->nominal;
				
				$this->db->select_sum('cashflow_nominal', 'nominal');
				$this->db->from('cashflow');
				$this->db->where('debet_credit',"KREDIT");
				$this->db->where('bank_account_id',$item->id);
				$result = $this->db->get()->result();
				$kredit = $result[0]->nominal;
				
				$saldo = $debet - $kredit;
				
				if ($saldo != $item->ba_saldo) {
					$ba_saldo = array('ba_saldo' => $saldo);
				
					$this->db->where('id',$item->id);
					$this->db->update('bank_account',$ba_saldo);
				}
				
			endforeach;
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect('login', 'refresh');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */