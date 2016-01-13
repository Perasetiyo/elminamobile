<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		$this->load->model('GenericModel');
    }
	
	public function index($msg=NULL)
	{
		$data['msg'] = $msg;
		$this->load->view('login',$data);
	}
	
	public function validate(){
        
		$this->load->model('login_model');
        $result = $this->login_model->validate();
        $session_data = $this->session->userdata('logged_in');
		
		if($result == "0"){
            $msg = '<font color=red>Invalid Email and/or Password.</font><br />';
            $this->index($msg);
        } else if($result == "1"){
			$data['page'] = "welcome";
			$this->load->view('dashboard',$data);
			
			// add to activity
			$this->activity_model->add_activity($session_data['user_id'], 'Login Backoffice');
			
		} else {
			redirect('login');
		}
	}

	public function logout(){
		$this->session->sess_destroy();
        $msg = 'Kamu sudah logout';
        redirect('login');
    }
}
/* End of file loginmember.php */
/* Location: ./application/controllers/loginmember.php */