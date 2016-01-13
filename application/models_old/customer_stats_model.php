<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_stats_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('billing');
			return $count;
		} else {
		
			if (!empty($searchparam['billing_name'])) {
				$this->db->like('billing_name', $searchparam['billing_name']);
			}
			
			
			$count=$this->db->count_all_results('billing');
			return $count;
		}
    }

    public function fetch_customer_rank($limit, $start, $searchparam) {
        
		/**$sql = "select b.*, sum(total_amount - exp_cost) as nominal, count(o.billing_id) from orders o, billing b
					where b.billing_id = o.billing_id
					group by o.billing_id
					order by nominal desc";

			$query = $this->db->query($sql);
		*/
		
		$this->db->select('b.*, sum(total_amount - exp_cost) as nominal, count(o.billing_id) as purchase_count');
		$this->db->from('orders o');
		
		if (!empty($searchparam['billing_name'])) {
				$this->db->like('billing_name', $searchparam['billing_name']);
		}
		
		if (!empty($searchparam['purchase_period'])) {
				$this->db->where('MONTH(o.order_date)', $searchparam['purchase_period']);
		}
		
		$this->db->join('billing b', 'b.billing_id = o.billing_id','left');
		$this->db->group_by('o.billing_id');
		
		// ordering
		if (!empty($searchparam['order_column'])) {
			
			$this->db->order_by($searchparam['order_column'], $searchparam['order_type']);
		
		} else {
			//$this->db->order_by('nominal', 'desc');
		}
		
		$this->db->limit($limit, $start);
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
   }
   
}