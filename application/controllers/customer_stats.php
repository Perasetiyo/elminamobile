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
			
			// get 
			$this->db->where('billing_flag1',102);
			$this->db->order_by('billing_name');
			$data['billings'] = $this->db->get('billing')->result();
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			$data['startdate'] = '';
			$data['enddate'] = '';
			if ($searchterm != null) {
				if (!empty($searchterm['startdate']) && $searchterm['startdate'] != '1970-01-01') {
					$data['startdate'] = $searchterm['startdate'];
				}
				if (!empty($searchterm['enddate']) && $searchterm['enddate'] != '1970-01-01') {
					$data['enddate'] = $searchterm['enddate'];
				}
			}
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
	
	public function customer_rank_detail() {
		if($this->session->userdata('logged_in')) {	
			
			if (empty($searchterm['startdate'])) {
				$msg = '<p>Caution! Define Date Range</p>';
				$this->session->set_flashdata('error_message',$msg);	
		
				(site_url('customer_stats/customer_rank'));
			}
			
			$searchterm = $this->session->userdata('searchterm');
			$billing_id = $this->uri->segment(3);
			$all_total_qty = 0;
			
			// ================================================================
			// : : : BILLING ORDERS
			// ================================================================
			// QUERY ORDER
			/**
			 * SELECT o.id, o.order_date, sum(c.cart_qty), o.total_amount, i.inventory_nominal, 100 + i.inventory_nominal/o.total_amount*100 
			 * FROM tb_cart c, billing b, orders o, tb_inventory i
			where o.id = c.order_id

			and b.billing_id = o.billing_id
            and i.order_id = c.order_id
			and o.billing_id = 1274
            and o.order_date BETWEEN '2015-06-01' and '2015-06-28'

			group by o.id
			*/
			
			$margin_percentage = '(100+i.inventory_nominal/o.total_amount*100)';
			
			$this->db->select('b.billing_name, o.id as order_id, o.order_date as order_date, sum(c.cart_qty) as total_qty, 
								o.total_amount, i.inventory_nominal,
								'.$margin_percentage.' as margin_percentage');
			$this->db->from('tb_cart c');
			$this->db->join('orders o', 'o.id = c.order_id','left');
			$this->db->join('billing b', 'b.billing_id = o.billing_id','left');
			$this->db->join('tb_inventory i', 'i.order_id = c.order_id','left');
			 
			$this->db->where('o.billing_id', $billing_id);
			
			if ($searchterm <> null && !empty($searchterm['startdate'])) {
				$this->db->where('o.order_date >=', $searchterm['startdate']);
			}
			if ($searchterm <> null && !empty($searchterm['enddate'])) {
				$this->db->where('o.order_date <=', $searchterm['enddate']);
			}
			
			$this->db->group_by('o.id');
			$this->db->order_by('o.order_date','desc');
			
			$query = $this->db->get();
			
			$list_customer_detail = null;
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$list_customer_detail[] = $row;
					$all_total_qty = $all_total_qty + $row->total_qty;
				}
			}
			
			$data['list_customer_detail'] = $list_customer_detail;
			
			
			// ================================================================
			// : : : BILLING ORDERS
			// ================================================================
			// QUERY ORDER
			/**
			 * SELECT o.id, o.order_date, sum(c.cart_qty), o.total_amount, i.inventory_nominal, 100 + i.inventory_nominal/o.total_amount*100 
			 * FROM tb_cart c, billing b, orders o, tb_inventory i
			where o.id = c.order_id

			and b.billing_id = o.billing_id
            and i.order_id = c.order_id
			and o.billing_id = 1274
            and o.order_date BETWEEN '2015-06-01' and '2015-06-28'

			group by o.id
			*/
			
			$margin_percentage = '(100+i.inventory_nominal/o.total_amount*100)';
			
			$this->db->select('b.billing_name, o.id as order_id, o.order_date as order_date, sum(c.cart_qty) as total_qty, 
								o.total_amount, i.inventory_nominal,
								'.$margin_percentage.' as margin_percentage');
			$this->db->from('tb_cart c');
			$this->db->join('orders o', 'o.id = c.order_id','left');
			$this->db->join('billing b', 'b.billing_id = o.shipper_id','left');
			$this->db->join('tb_inventory i', 'i.order_id = c.order_id','left');
			 
			$this->db->where('o.shipper_id', $billing_id);
			
			
			if ($searchterm <> null && !empty($searchterm['startdate'])) {
				$this->db->where('o.order_date >=', $searchterm['startdate']);
			}
			if ($searchterm <> null && !empty($searchterm['enddate'])) {
				$this->db->where('o.order_date <=', $searchterm['enddate']);
			}
			
			$this->db->group_by('o.id');
			$this->db->order_by('o.order_date','desc');
			
			$query = $this->db->get();
			
			$list_customer_detail2 = null;
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$list_customer_detail2[] = $row;
					
					$all_total_qty = $all_total_qty + $row->total_qty;
				}
			}
			
			$data['list_customer_detail2'] = $list_customer_detail2;
			
			// =============================================================
			
			$data["startdate"] = date('d M Y', strtotime($searchterm['startdate']));
			$data["enddate"] = date('d M Y', strtotime($searchterm['enddate']));
			$data["all_total_qty"] = $all_total_qty;
			
			$data["page"] = "customer_rank_detail";
			
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
			
			$startdate 	= date('Y-m-d', strtotime($this->input->post('startdate')));
			$enddate 	= date('Y-m-d', strtotime($this->input->post('enddate')));
			
			// Searching
			$searchparam = array(
				   'billing_id' => $this->input->post('billing_id'),
				   'startdate' => $startdate,	
				   'enddate' => $enddate,	
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
