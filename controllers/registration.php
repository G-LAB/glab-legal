<?php
class Registration extends CI_Controller {
	
	function __construct () {
		parent::__construct();
		$this->display->setPageTitle('New User Registration');
		$this->display->setStyleHeader('logo');
		$this->display->setViewSideRt('/sso/registration/side_rt');
	}
	
	function _remap () {
		$this->index();
	}
	
	function index () {
		$this->display->setViewBody('/sso/registration/step_1a');
	}

}
?>