<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acrec_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_acrec');
			return $count;
		} else {
			if (!empty($searchparam['acrec_type_id'])) {
				$this->db->where('acrec_type_id', $searchparam['acrec_type_id']);
			} 
			
			if (!empty($searchparam['acrec_desc'])) {
				$this->db->like('acrec_desc', $searchparam['acrec_desc']);
			}
			
			$count=$this->db->count_all_results('tb_acrec');
			return $count;
		}
    }

    public function fetch_acrec($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_acrec');
		$this->db->where('acrec_nominal >', 0);
		
		if (!empty($searchparam['acrec_type_id'])) {
			$this->db->where('acrec_type_id', $searchparam['acrec_type_id']);
		}
		
		if (!empty($searchparam['acrec_desc'])) {
			$this->db->like('acrec_desc', $searchparam['acrec_desc']);
		}
		
		/**
		if (!empty($searchparam['bank_account_id'])) {
			$this->db->where('bank_account_id', $searchparam['bank_account_id']);
		}
		**/
		
		$this->db->join('tb_options', 'tb_acrec.acrec_type_id = tb_options.option_id','left');
		$this->db->order_by("tb_acrec.acrec_date", "desc");
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