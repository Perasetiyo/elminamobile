<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Login_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    public function validate(){
        // grab user input
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = sha1($this->security->xss_clean($this->input->post('password')));
        
        // Prep the query
        $this->db->where('user_name', $username);
        $this->db->where('user_password', $password);
        
        // Run the query
        $query = $this->db->get('user');
        // Let's check if there are any results
        if($query->num_rows == 1) {
			// If there is a user, then create session data
			$row = $query->row();
			$data = array(
					'username' => $row->user_name,
					'password' => $row->user_password,
					'userid' => sha1(md5($row->userid)),
					'user_id' => $row->userid,
					'user_role' => $row->user_role,
					'billing_id' => $row->billing_id,
					);
			$this->session->set_userdata('logged_in',$data);
			return "1";
		}
        // If the previous process did not validate
        // then return false.
        return "0";
    }
}
?>
