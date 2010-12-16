<?php
class Account extends CI_Controller {
	
	function __construct ()
	{
		parent::__construct();
		$this->sso_server->requireAuth();	
		$this->display->setStyleHeader('logo');
		$this->display->setStyleMasthead('sso');
		$this->display->setStyleMain('blank');
		
		$this->load->library('form_validation');
	}
	
	function index () {
		$this->display->setViewSideRt('/sso/home');
	}
	
	function password () {
		
		$this->header->set('js','passwordStrengthMeter');
		
		$this->form_validation->set_rules('password_current', 'Current Password', 'required');
		$this->form_validation->set_rules('password_new', 'New Password', 'required');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required|matches[password_new]');
		
		if ($this->form_validation->run()) {
			$data['eid'] = eid();
			$data['old'] = $this->input->post('password_current');
			$data['new'] = $this->input->post('password_new');
			$this->load->api('entity_password',$data,'put');
		}
		
		$this->display->setViewSideRt('/sso/password');
	}
	
	function emails () {
		$this->load->helper('array');
		
		if ($this->input->post('action') == 'remove')
			$this->form_validation->set_rules('emid', 'email to remove', 'required');
		if ($this->input->post('action') == 'add')
			$this->form_validation->set_rules('email', 'new email address', 'required|valid_email');
		
		if ($this->form_validation->run()) {
			
			if ($this->input->post('action') == 'remove') 
				$this->load->api('email',array('emid'=>$this->input->post('emid')),'delete');
				
			if ($this->input->post('action') == 'add')
				$this->load->api('email',array('eid'=>eid(),'email'=>$this->input->post('email')),'put');
		}
		
		$emails = $this->load->api('entity_emails',eid());
		$this->display->setViewSideRt('/sso/emails',array('data'=>$emails));
	}
	
	function default_email () {
		
		if ( $this->input->post('emid') ) {
			$data['eid'] = eid();
			$data['emid'] = $this->input->post('emid');
			$this->load->api('email_default', $data,'put');
		}
		
		$emails = $this->load->api('entity_emails',eid());
		$this->display->setViewSideRt('/sso/default_email',array('data'=>$emails));
	}
	
	function close () {
	
	}

}
?>