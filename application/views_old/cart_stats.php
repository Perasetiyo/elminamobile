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
					$data["page_title"] .= 'Group by : <b>Product</b>';
				} else if ($group_by == 'STOCK') {
					$data["page_title"] .= 'Group by : <b>Stock</b>';
				}
			}
			
			if (!empty($interval)) {
				
				if ($interval == 'MONTH') {
					$data["page_title"] .= ', Interval : <b>Monthly</b>';
				} else if ($interval == 'DAY') {
					$data["page_title"] .= ', Interval : <b>Daily</b>';
				}
			}
			
			if (!empty($product_id) && $product_id > 0) {
				$this->db->where('product_id', $product_id);
				$this->db->limit(1);
				$product_name = $this->db->get('tb_product')->row()->product_name;
				$data['page_title'] .= '<br/>Product Name : <b>'.$product_name.'</b>';
			}
			
			if (!empty($stock_id) && $stock_id > 0) {
				$this->db->where('tb_stock.stock_id', $stock_id);
				$this->db->join('tb_product', 'tb_stock.product_id = tb_product.product_id');
				$this->db->limit(1);
				$stock = $this->db->get('tb_stock');
				$stock_desc = $stock->row()->stock_desc;
				$product_name = $stock->row()->product_name;
				$data['page_title'] .= '<br/>Stock Name : <b>'.$product_name.' - '.$stock_desc.'</b>';
			}
			
			if (!empty($stockname)) {
				$data['page_title'] .= '<br/>Stock Name Like [<b>'.$stockname.'</b>]';
			}
			
			if (!empty($startdate) && !empty($enddate)) {
				$startdate = date('Y-m-d', strtotime($startdate));
				$enddate = date('Y-m-d', strtotime($enddate));
				$searchterm['startdate'] = $startdate;
				$searchterm['enddate'] = $enddate;
			
				$data['page_title'] .= '<br/>Date Range : <b>'.$startdate.' - '.$enddate.'</b>';
			}
			
			// Paging
			$config = array();
			$config["base_url"] = base_url("index.php/cart_stats/trends");
			
			$data["list_trends"] = $this->cart_stats_model->fetch_trends($searchterm);
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
			//$searchterm['product_id'] = null;
			// QUERY MONTH LIST
			//$this->db->where('option_type','MON');
			//$this->db->order_by('option_code');
			//$data['months'] = $this->db->get('tb_options')->result();
			
			// QUERY PRODUCT LIST
			$this->db->where('status',1);
			$this->db->order_by('product_name');
			$data['products'] = $this->db->get('tb_product')->result();
			
			// SOLD
			// COUNT SOLD STOCK QTY (FREE)
			$total_sold_qty = 0;
			$total_restock_qty = 0;
			
			$data["list_monthly_sold_cart"] = null;
			
			$startdate 	= date('Y-m-d', strtotime($this->input->post('startdate')));
			$enddate 	= date('Y-m-d', strtotime($this->input->post('enddate')));
			$product_id = $this->input->post('product_id');
			//echo $product_id;
			
			//$product_id = $searchterm['product_id'];
				
			$this->db->select('tb_stock.stock_id, tb_product.product_name, tb_stock.stock_desc, sum(tb_cart.cart_qty) as sold_qty, tb_stock.stock_qty');
			$this->db->from('tb_cart');
			$this->db->join('orders', 'orders.id = tb_cart.order_id','left');
			$this->db->join('tb_stock', 'tb_stock.stock_id = tb_cart.stock_id','left');
			$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id','left');
			//if ($period > 0) {
			//	$this->db->where('MONTH(orders.order_date)',$period);
			//}
			if (!empty($startdate) && !empty($enddate)) {
				$this->db->where('order_date >=',$startdate);
				$this->db->where('order_date <=',$enddate);
			}
			
			if ($product_id > 0) {
				$this->db->where('tb_product.product_id',$product_id);
			}
			
			$this->db->order_by('sold_qty','desc');
			$this->db->group_by('tb_cart.stock_id');
				
			$query = $this->db->get();
				
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$restock_qty = 0;
					$total_sold_qty += $row->sold_qty;
					
					$this->db->select('tb_inventory.stock_id, tb_product.product_name, tb_stock.stock_desc, 
									sum(tb_inventory.inventory_nominal / tb_stock.stock_cogs) as restock_qty');
					$this->db->from('tb_inventory');
					$this->db->join('tb_stock', 'tb_stock.stock_id = tb_inventory.stock_id','left');
					$this->db->join('tb_product', 'tb_product.product_id = tb_stock.product_id','left');
					//if ($period > 0) {
					//	$this->db->where('MONTH(tb_inventory.inventory_date)',$period);
					//}
					
					if (!empty($startdate) && !empty($enddate)) {
						$this->db->where('inventory_date >=',$startdate);
						$this->db->where('inventory_date <=',$enddate);
					}
			
					
					$this->db->where('tb_inventory.stock_id',$row->stock_id);
					$this->db->group_by('tb_inventory.stock_id');
					$this->db->order_by('restock_qty','desc');
						
					$query_restock = $this->db->get();
						
					if ($query_restock->num_rows() > 0) {
						foreach ($query_restock->result() as $row_restock) {
							$restock_qty += $row_restock->restock_qty;
						}
						$row->restock_qty = $restock_qty;
						
						$total_restock_qty += $row->restock_qty;
					} else {
						$row->restock_qty = 0;
						$total_restock_qty = 0;
					}
						
					$sdata[] = $row;
				}
				$data["list_monthly_sold_cart"] = $sdata;
			} else {
			
			}
			
			$data["total_sold_qty"] = $total_sold_qty;
			$data["total_restock_qty"] = $total_restock_qty;
			
			
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */