<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function record_count($searchparam) {
        if ($searchparam == null) {
			$count=$this->db->count_all_results('tb_activity');
			return $count;
		} else {
			if (!empty($searchparam['user_id'])) {
				$this->db->where('user_id', $searchparam['user_id']);
			} 
			
			if (!empty($searchparam['activity_desc'])) {
				$this->db->like('activity_desc', $searchparam['activity_desc']);
			}
			
			$count=$this->db->count_all_results('tb_activity');
			return $count;
		}
    }

    public function fetch_activity($limit, $start, $searchparam) {
        
		$this->db->select('*');
		$this->db->from('tb_activity');
		
		if (!empty($searchparam['user_id'])) {
			$this->db->where('user_id', $searchparam['user_id']);
		} 
			
		if (!empty($searchparam['activity_desc'])) {
			$this->db->like('activity_desc', $searchparam['activity_desc']);
		}
		
		$this->db->join('user', 'user.userid = tb_activity.user_id');
		$this->db->order_by("tb_activity.activity_date", "desc");
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
   
    public function add_activity($user_id, $activity_desc) {
		
		date_default_timezone_set('Asia/Bangkok');
		
		$activity = array(
						'user_id' => $user_id,
						'activity_desc' => $activity_desc,
						'activity_date' => date('Y-m-d H:i:s')
				);
		$this->db->insert('tb_activity', $activity); 
	}
}