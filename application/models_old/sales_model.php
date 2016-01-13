<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sales_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_sales');
			return $count;
		} else {
			
			if ($searchparam['sales_desc'] != '') {
				$this->db->like('sales_desc', $searchparam['sales_desc']);
			}
			
			$count=$this->db->count_all_results('tb_sales');
			return $count;
		}
    }

    public function fetch_sales($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_sales');
		
		if (!empty($searchparam['sales_desc'])) {
			$this->db->like('sales_desc', $searchparam['sales_desc']);
		}
		
		$this->db->join('tb_options', 'tb_sales.sales_type_id = tb_options.option_id','left');
		$this->db->join('orders', 'tb_sales.order_id = order.id','left');
		$this->db->order_by("tb_sales.sales_date", "desc");
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