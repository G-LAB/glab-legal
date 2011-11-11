<?php
class Sign extends CI_Controller {
	
	function __construct ()
	{
		parent::__construct();
		$this->display->setStyleHeader('logo');
		//$this->display->setStyleMasthead('blank');
		$this->display->setStyleMain('blank');
		$this->display->setStyleFooter('blank');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('agreement');
		$this->load->model('profile');
	}
	
	function _remap($str) {
		$this->index($str);
	}
	
	function index ($key=false) {
		
		if ($key == 'index') {
			show_error('URL must contain unique key.');
		}

		if ($this->input->post('agree')) {
			$this->form_validation->set_rules('signature', 'signature', 'trim|required|min_length[7]|max_length[64]');
		}
		
		if ($this->form_validation->run()) {
			
			$signature = $this->agreement->sign(
				$key,
				$this->input->post('agrvid'),
				$this->input->post('signature')
			);
			
			if ($signature) $this->display->setViewBody('/legal-temp/confirmation');
			else show_error('Could not save signature.  Please contact your account manager for assistance.');
			
		} else {
			
			$request = $this->agreement->getRequest($key);
			
			if (count($request)) {
				
				$data['agreement'] = $this->agreement->getLatest($request['agid']);
				$data['profile'] = $this->profile->get($request['pid']);
				$data['profile_signer'] = $this->profile->get($request['pid_signer']);
				
				$this->display->setViewBody('/legal-temp/form', $data);
				
			} else {
				show_error('The request for signature could not be located.  Please call your account manager for assistance.');
			}
		}
	}

}
?>