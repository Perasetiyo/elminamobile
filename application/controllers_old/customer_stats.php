<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_stats extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
		$this->load->helper('string');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('customer_stats_model');
		$this->load->model('GenericModel');
    }
	
	public function index() {
	
	}
	
	public function customer_rank() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// get month list
			$where = "option_type = 'MON'";
			$this->db->where($where);
			$this->db->order_by('option_type');
			$data['months'] = $this->db->get('tb_options')->result();
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/customer_stats/customer_rank");
			$config["total_rows"] = $this->customer_stats_model->record_count($searchterm);
			$config["per_page"] = 20;
			
			$this->pagination->initialize($config);
			$page = $this->uri->segment(3);
			
			$data["list_customer_rank"] = $this->customer_stats_model->fetch_customer_rank($config["per_page"], $page, $searchterm);
			$data["links"] = $this->pagination->create_links();
			$data["row"] = 1+$page;
			$data["page"] = "customerRankStats";
			
			$this->load->view('dashboard',$data);
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function clean_customer() {
		if($this->session->userdata('logged_in')) {	
			
			$sql = 'SELECT  `billing_name` , billing_phone, COUNT( * ) AS duplikat
					FROM  `billing` 
					GROUP BY  `billing_name`, billing_phone 
					HAVING duplikat >1';
			
			$query = $this->db->query($sql);
			$duplicate_cust = $query->result();
			foreach ($duplicate_cust as $cust) {
					
					$this->db->where('billing_name',$cust->billing_name);
					$this->db->where('billing_phone',$cust->billing_phone);
					$this->db->order_by('billing_id', 'desc');
					$this->db->limit(1);
					$billing = $this->db->get('billing');
			
					$bill_id = 0;
					foreach ($billing->result() as $bill2) {
						$bill_id = $bill2->billing_id;
					}
					
					$sqlOrder = 'SELECT o.id, o.order_date, o.billing_id, b.billing_name
							FROM orders o, billing b
							WHERE o.billing_id = b.billing_id
							AND b.billing_name =  "'.$cust->billing_name.'"
							AND b.billing_phone =  "'.$cust->billing_phone.'"
							ORDER BY b.billing_id';
					
					$cust_order = $this->db->query($sqlOrder);
								
					
					foreach ($cust_order->result() as $row) {
						// UPDATE BILLING ID ORDER
						$billing_update = array (
							'billing_id' => $bill_id
							);
							
						$this->db->where('id', $row->id);
						$this->db->update('orders', $billing_update);
					}
					
					$sql_junk_billing = 'SELECT * 
											FROM billing
											WHERE billing_id NOT 
											IN (
												SELECT DISTINCT billing_id
												FROM orders
											)';
											
					$junk_billing = $this->db->query($sql_junk_billing);
					
					foreach ($junk_billing->result() as $row) {
						// REMOVE DUPLICATE BILLING
						$this->db->delete('billing', array('billing_id' => $row->billing_id)); 
					}
			}
				print_r ('OK');
		} else {
			 redirect(site_url('login'));
		}
	
	}
	
	
	public function search() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			$this->session->unset_userdata('orderterm');
			
			// Searching
			$searchparam = array(
				   'billing_name' => $this->input->post('billing_name'),
				   'purchase_period' => $this->input->post('purchase_period'),
				   'order_column' => $this->input->post('order_column'),
				   'order_type' => $this->input->post('order_type')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('customer_stats/customer_rank'));
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function main() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$startdate 	= date("Y-m-d", strtotime($this->input->post('startdate')));
			$enddate 	= date("Y-m-d", strtotime($this->input->post('enddate')));
			$top 		= $this->input->post('top');
			
			if (empty($top)) {
				$top = 10;
			}
			
			$sql = "SELECT o.option_desc as option_desc, count(*) as total_cust
					FROM  `billing` b, tb_options o
					WHERE b.billing_level = o.option_id 
					AND o.option_type = 'BILL_LV'
					GROUP BY b.billing_level
					ORDER BY o.option_desc";
					
			$query = $this->db->query($sql);
			
			$data["customer_stats"] = $query->result();
			
			$data["rows"] = $query->num_rows;
			$data["page"] = "customer_stats";
			
			$data["startdate"] = $startdate;
			$data["enddate"] = $enddate;
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */