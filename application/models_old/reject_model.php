<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reject_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_reject');
			return $count;
		} else {
			
			$count=$this->db->count_all_results('tb_reject');
			return $count;
		}
    }

    public function fetch_reject($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_reject');
		$this->db->order_by('reject_date','desc');
		
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