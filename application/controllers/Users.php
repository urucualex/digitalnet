<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->data = [
			'current_page' => 'users'
		];
	}

	public function index() {
		$this->load->view('user', $this->data);
	}

}
