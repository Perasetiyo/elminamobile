<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GenericModel extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

	public function searchterm_handler($searchterm) {
		if ($this->session->userdata('searchterm')) {
			$session_searchterm = $this->session->userdata('searchterm');
			return $session_searchterm;
		} else {
			$session_searchterm = $this->session->set_userdata('searchterm', $searchterm);
			return $session_searchterm;
		}
	}
	
	public function adv_searchterm_handler($searchterm, $orderterm) {
		
		if ($this->session->userdata('searchterm')) {
			$session_searchterm = $this->session->userdata('searchterm');
			return $session_searchterm;
		} else {
			$session_searchterm = $this->session->set_userdata('searchterm', $searchterm);
			return $session_searchterm;
		}
		
		if ($this->session->userdata('orderterm')) {
			$session_orderterm = $this->session->userdata('orderterm');
			return $session_orderterm;
		} else {
			$session_orderterm = $this->session->set_userdata('orderterm', $orderterm);
			return $session_orderterm;
		}
	}
}