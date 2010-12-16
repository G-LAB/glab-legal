<?php
class Login extends CI_Controller {
	
	public $brand;
	public $sessiondata;
	
	
	function _remap ($sid) {
		
		if ($sid == 'sso') {
			$this->brand = $this->load->api('brand',1);
		} else {
			$this->sessiondata = $this->load->api('auth','sid='.$sid);
			if (!$this->sessiondata) redirect ('http://glabstudios.com/');
		
			$this->brand = $this->load->api('brand',1);
		}
		
		// Load Form
		$this->form();
	}
	
	function form()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		// Validation Rules
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|callback_validate_login');
		
		// Validate Form Data
		$this->form_validation->run();
		
		// Else Show Login Form
		$this->display->setPageTitle('Login');
		$this->display->setStyleHeader('logo');
		$this->display->setStyleMasthead('login');
		$this->display->setStyleMain('blank');
	}
	
	function validate_login () {
		
		// Set Error Message
		$this->form_validation->set_message('validate_login', 'The email address or password entered is not valid.');
		
		// Bring in Data from Form
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$password_hash = sha1($password);
		
		// Check that an Email and Password were sent
		if (!$email || !$password) return FALSE;
		
		$data['email'] = $email;
		$data['hash'] = $password_hash;
		$data['sid'] = $this->uri->segment(2);
		
		$result = $this->load->api('auth_validate',$data,'post');
		
		// SUCCESS
		// Local Session
		$entity = $this->load->api('entity',$result['eid']);
		$session['eid'] = $entity['eid'];
		$session['name'] = $entity['name'];
		$this->session->set_userdata($session);
		
		// Redirect to Remote Session
		if ($this->uri->segment(2) == 'sso') redirect('account');
		elseif (isset($result['token'])) {
			$this->display->disable();
			redirect($this->sessiondata['returnURI'].'/'.$result['token'],'refresh');
		}
		
		return FALSE;
	}
}
?>