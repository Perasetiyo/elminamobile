<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart_stats extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
		$this->load->helper('string');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('GenericModel');
		$this->load->model('cart_stats_model');
    }
	
	public function index() {
	
	}
	
	public function trends() {
		if($this->session->userdata('logged_in')) {
		
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// QUERY PRODUCT LIST
			$this->db->where('status', 1);
			$this->db->order_by('product_name');
			$data['products'] = $this->db->get('tb_product')->result();
		
			// QUERY STOCK LIST
			$this->db->where('status', 1);
			$this->db->order_by('product_name', 'stock_desc');
			$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
			$data['stocks'] = $this->db->get('tb_stock')->result();
		
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			$group_by = $this->input->post('group_by');
			$interval = $this->input->post('interval');
			$product_id = $this->input->post('product_id');
			$stock_id = $this->input->post('stock_id');
			$stockname = $this->input->post('stockname');
			$startdate = $this->input->post('startdate');
			$enddate = $this->input->post('enddate');
			
			// GET PARAM
			$numdays = $this->input->get('numdays');
			
			$searchterm['group_by'] = $group_by;
			$searchterm['interval'] = $interval;
			$searchterm['product_id'] = $product_id;
			$searchterm['stock_id'] = $stock_id;
			$searchterm['stockname'] = $stockname;
			$searchterm['startdate'] = $startdate;
			$searchterm['enddate'] = $enddate;
			
			// DEFINE TITLE
			$data['page_title'] = 'Stats Detail :<br/>';
			if (!empty($group_by)) {
				if ($group_by == 'PRODUCT') {
					$data["page_title"] .= 'Group by : <b>Product</b><br/>';
				} else if ($group_by == 'STOCK') {
					$data["page_title"] .= 'Group by : <b>Stock</b><br/>';
				}
			}
			
			if (!empty($interval)) {				
				if ($interval == 'MONTH') {
					$data["page_title"] .= 'Interval : <b>Monthly</b><br/>';
				} else if ($interval == 'DAY') {
					$data["page_title"] .= 'Interval : <b>Daily</b><br/>';
				} else if ($interval == 'WEEK') {
					$data["page_title"] .= 'Interval : <b>Weekly</b><br/>';
				}
			}
			
			if (!empty($product_id) && $product_id > 0) {
				$this->db->where('product_id', $product_id);
				$this->db->limit(1);
				$product_name = $this->db->get('tb_product')->row()->product_name;
				$data['page_title'] .= 'Product Name : <b>'.$product_name.'</b><br/>';
			}
			
			if (!empty($stock_id) && $stock_id > 0) {
				$this->db->where('tb_stock.stock_id', $stock_id);
				$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
				$this->db->limit(1);
				$stock = $this->db->get('tb_stock');
				$stock_desc = $stock->row()->stock_desc;
				$product_name = $stock->row()->product_name;
				$data['page_title'] .= 'Stock Name : <b>'.$product_name.' - '.$stock_desc.'</b><br/>';
			}
			
			if (!empty($stockname)) {
				$data['page_title'] .= 'Stock Name Like [<b>'.$stockname.'</b>]<br/>';
			}
			
			if (!empty($startdate) && !empty($enddate)) {
				$startdate = date('Y-m-d', strtotime($startdate));
				$enddate = date('Y-m-d', strtotime($enddate));
				
				$searchterm['startdate'] = $startdate;
				$searchterm['enddate'] = $enddate;
			
				$startdate2 = date('d M Y', strtotime($startdate));
				$enddate2 = date('d M Y', strtotime($enddate));
				
				$data['page_title'] .= 'Date Range : <b>'.$startdate2.' ~ '.$enddate2.'</b><br/>';
			}
			
			// AVG CART QTY
			
			
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/cart_stats/trends");
			
			$data["list_trends"] = $this->cart_stats_model->fetch_trends($searchterm);
			$data["interval"] = $interval;
			
			$data["list_top_cart"] = $this->cart_stats_model->fetch_top_cart($searchterm);
			
			$data["page"] = "cart_trends";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function main() {
		if($this->session->userdata('logged_in')) {	
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			// QUERY PRODUCT LIST
			$this->db->order_by('product_name');
			$data['products'] = $this->db->get('tb_product')->result();
			
			// COUNT READY STOCK QTY (FREE)
			$ready_qty = 0;
			
			if (!empty($searchterm['product_id'])) {
				$this->db->select('*');
				$this->db->from('tb_stock');
				
				$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
				$this->db->where('tb_product.product_id', $searchterm['product_id']);
				
				$query = $this->db->get();
				
				if ($query->num_rows() > 0) {
					
					foreach ($query->result() as $row2) {
						$ready_qty += $row2->stock_qty;
					}
				}
			}
			
			// COUNT READY STOCK QTY (KEEP)
			$list_monthly_cart = $this->Cart_stats_model->fetch_stock(12, 1, $searchterm);
			
			foreach ($list_monthly_cart as $row) {
				if ($row->package_status == 0) {
					$ready_qty += $row->total_qty;
				}
				
				if ($row->package_status == 1) {
					$sdata[] = $row;
				} 
			}
			
			$data["ready_qty"] = $ready_qty;
			$data["list_monthly_sold_cart"] = $sdata;
			$data["page"] = "cart_stats";
			
			$this->load->view('dashboard',$data);
		} else {
			 redirect(site_url('login'));
		}
	}
	
	public function sold_restock() {
		if($this->session->userdata('logged_in')) {	
			
			// SEARCHING TERMS
			$searchterm = $this->session->userdata('searchterm');
			
			$startdate = $this->input->post('startdate');
			$enddate = $this->input->post('enddate');
			
			if (!empty($startdate) && !empty($enddate)) {
				$startdate = date('Y-m-d', strtotime($startdate));
				$enddate = date('Y-m-d', strtotime($enddate));
				$searchterm['startdate'] = $startdate;
				$searchterm['enddate'] = $enddate;
			}
			
			// QUERY PRODUCT LIST
			$this->db->where('status',1);
			$this->db->order_by('product_name');
			$data['products'] = $this->db->get('tb_product')->result();
			
			// REAL TIME QTY OF PRODUCTS ( FREE, KEEPED, SOLD, RESTOCK)
			/*
			========================
			>>>>>>>>> FREE <<<<<<<<<
			========================
			
			// PER PRODUCT
			---------------------
			SELECT p.product_name, sum(i.inventory_cogs * i.inventory_qty), sum(i.inventory_qty) 
			FROM tb_inventory i, tb_stock s, tb_product p
			WHERE i.stock_id = s.stock_id
			AND s.product_id = p.product_id
			AND p.status = 1
			group by p.product_id

			// ALL
			---------------------
			SELECT sum(i.inventory_cogs * i.inventory_qty), sum(i.inventory_qty) 
			FROM tb_inventory i, tb_stock s, tb_product p
			WHERE i.stock_id = s.stock_id
			AND s.product_id = p.product_id
			AND p.status = 1
			*/
			
			$total_free_qty = 0;
			$total_free_nominal = 0;
			
			$this->db->select('sum(tb_inventory.inventory_cogs * tb_inventory.inventory_qty) as nominal, sum(tb_inventory.inventory_qty) as qty');
			$this->db->from('tb_inventory');
				
			$this->db->join('tb_stock', 'tb_stock.stock_id = tb_inventory.stock_id');
			$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
			$this->db->where('tb_product.status', 1);
				
			$query = $this->db->get();
				
			if ($query->num_rows() > 0) {
					
				foreach ($query->result() as $row) {
					$total_free_qty = $row->qty;
					$total_free_nominal = $row->nominal;
				}
			}
			
			$data["total_free_qty"] = $total_free_qty;
			$data["total_free_nominal"] = $total_free_nominal;
			
			/*
			========================
			>>>>>>>>> SOLD <<<<<<<<<
			========================
			*/
			
			$total_sold_qty = 0;
			$total_sold_nominal = 0;
			
			
			$this->db->select('sum(c.cart_qty) as total_qty, sum(cart_cogs) as total_cogs');
			$this->db->from('tb_cart c');
			$this->db->join('orders o', 'o.id = c.order_id','left');
			
			
			if (!empty($startdate) && !empty($enddate)) {
				$this->db->where('o.order_date >=', $searchterm['startdate']);
				$this->db->where('o.order_date <=', $searchterm['enddate']);
			}
			
			$query = $this->db->get();
			
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$total_sold_qty = $row->total_qty;
					$total_sold_nominal = $row->total_cogs;
				}
			}
			
			$data["total_sold_qty"] = $total_sold_qty;
			$data["total_sold_nominal"] = $total_sold_nominal;
			
			/*
			========================
			>>>>>>>>> RESTOCK <<<<<<<<<
			========================
			*/
			
			$total_restock_qty = 0;
			$total_restock_nominal = 0;
			
			$this->db->select('sum(inventory_nominal) as nominal, sum(inventory_qty_init) as qty');
			$this->db->from('tb_inventory');
			$this->db->where('inventory_type_id', 7);
			if (!empty($startdate) && !empty($enddate)) {
				$this->db->where('inventory_date >=', $searchterm['startdate']);
				$this->db->where('inventory_date <=', $searchterm['enddate']);
			}
			$query = $this->db->get();
			
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$total_restock_qty = $row->qty;
					$total_restock_nominal = $row->nominal;
				}
			}
			
			$data["total_restock_qty"] = $total_restock_qty;
			$data["total_restock_nominal"] = $total_restock_nominal;
			
			/**
			==============================
			: : : PRODUCT FREE TOP LIST
			==============================
			 
			 */ 
			
			$this->db->select('tb_product.product_name, sum(tb_inventory.inventory_cogs * tb_inventory.inventory_qty) as total_nominal, sum(tb_inventory.inventory_qty) as total_qty');
			$this->db->from('tb_inventory');
				
			$this->db->join('tb_stock', 'tb_stock.stock_id = tb_inventory.stock_id');
			$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
			$this->db->where('tb_product.status', 1);
			$this->db->order_by('total_qty desc');
			$this->db->group_by('tb_product.product_id');
				
			$query = $this->db->get();
				
			if ($query->num_rows() > 0) {
					
				foreach ($query->result() as $row) {
						$list_product_free[] = $row;
				}
			}
			
			$data['list_product_free'] = $list_product_free;
			
			
			/** 
			==============================
			: : : PRODUCT SOLD TOP LIST
			==============================
			*/
			
			/**
			SELECT tb_product.product_name , tb_stock.stock_desc, sum(cart_qty) as total_qty, sum(cart_cogs) as total_cogs
			FROM tb_cart, orders, tb_product, tb_stock
			WHERE tb_cart.stock_id = tb_stock.stock_id
			AND tb_product.product_id = tb_stock.product_id
			and tb_cart.order_id = orders.id
			AND orders.order_date BETWEEN '2015-01-01' AND '2015-08-31'
			GROUP by tb_cart.stock_id
			*/
			
			$list_product_sold = null;
			
			$this->db->select('p.product_id, p.product_name as product_name, s.stock_desc, sum(c.cart_qty) as total_qty, sum(cart_cogs) as total_cogs');
			$this->db->from('tb_cart c');
			$this->db->join('tb_stock s', 's.stock_id = c.stock_id','left');
			$this->db->join('tb_product p', 'p.product_id = s.product_id','left');
			$this->db->join('orders o', 'o.id = c.order_id','left');
			
			
			if (!empty($startdate) && !empty($enddate)) {
				$this->db->where('o.order_date >=', $searchterm['startdate']);
				$this->db->where('o.order_date <=', $searchterm['enddate']);
			}
			$this->db->group_by('s.product_id');
			$this->db->order_by('total_qty','desc');
			
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					if ($row->total_qty > 0) {
						$list_product_sold[] = $row;
					}
				}
			}
			
			$data['list_product_sold'] = $list_product_sold;
			
			/** 
			==============================
			: : : PRODUCT RESTOCK TOP LIST
			==============================
			*/
			
			$this->db->select('p.product_name, sum(i.inventory_nominal) as total_cogs, sum(i.inventory_qty_init) as total_qty');
			$this->db->from('tb_inventory i');
			$this->db->join('tb_stock s', 's.stock_id = i.stock_id','left');
			$this->db->join('tb_product p', 'p.product_id = s.product_id','left');
			
			$this->db->where('inventory_type_id', 7);
			if (!empty($startdate) && !empty($enddate)) {
				$this->db->where('inventory_date >=', $searchterm['startdate']);
				$this->db->where('inventory_date <=', $searchterm['enddate']);
			}
			
			$this->db->group_by('s.product_id');
			$this->db->order_by('total_qty','desc');
			
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					if ($row->total_qty > 0) {
						$list_product_restock[] = $row;
					}
				}
			}
			
			$data['list_product_restock'] = $list_product_restock;
			
			$data["startdate"] = $searchterm['startdate'];
			$data["enddate"] = $searchterm['enddate'];
			$data["page"] = "cart_sold_restock";
			
			$this->load->view('dashboard',$data);
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function search_sold_restock() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			// Searching
			$searchparam = array(
				   'option_id' => $this->input->post('option_id'),
				   'product_id' => $this->input->post('product_id')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('cart_stats/sold_restock'));
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function search_main() {
		if($this->session->userdata('logged_in')) {	
			$this->session->unset_userdata('searchterm');
			
			// Searching
			$searchparam = array(
				   'product_id' => $this->input->post('product_id')
			);
			
			$this->GenericModel->searchterm_handler($searchparam);
			
			redirect(site_url('cart_stats/main'));
		} else {
			redirect(site_url('login'));
		}
	}
	
	public function sold_per_stock() {
		if($this->session->userdata('logged_in')) {	
			
			$product_id = $this->uri->segment(3);
			
			/** 
			==============================
			: : : SOLD SOLD TOP LIST PER PRODUCT
			==============================
			*/
			
			/**
			 * 
			SELECT tb_product.product_name , tb_stock.stock_desc, sum(cart_qty) 
			FROM tb_cart, orders, tb_product, tb_stock
			WHERE tb_cart.stock_id = tb_stock.stock_id
			AND tb_product.product_id = tb_stock.product_id
			and tb_cart.order_id = orders.id
			AND orders.order_date BETWEEN '2015-01-01' AND '2015-08-31'
			GROUP by tb_cart.stock_id
			* 
			*/
			
			$this->db->select('p.product_name as product_name, s.stock_desc, sum(c.cart_qty) as total_qty');
			$this->db->from('tb_cart c');
			$this->db->join('tb_stock s', 's.stock_id = c.stock_id','left');
			$this->db->join('tb_product p', 'p.product_id = s.product_id','left');
			$this->db->join('orders o', 'o.id = c.order_id','left');
			
			$this->db->where('p.product_id', $product_id);
			
			if (!empty($startdate) && !empty($enddate)) {
				$this->db->where('o.order_date >=', $searchterm['startdate']);
				$this->db->where('o.order_date <=', $searchterm['enddate']);
			}
			
			$this->db->group_by('s.stock_id');
			$this->db->order_by('total_qty','desc');
			
			$query = $this->db->get();
			
			$list_stock_sold = null;
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					if ($row->total_qty > 0) {
						$list_stock_sold[] = $row;
					}
				}
			}
			
			$data['list_stock_sold'] = $list_stock_sold;
			$data["page"] = "cart_rank_per_product";
			
			$this->load->view('dashboard',$data);
		} else {
			redirect(site_url('login'));
		}
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
