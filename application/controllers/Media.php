<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends CI_Controller {
	public function __construct() {
		$this->data = [
			'current_page' => 'media'
		];
	}

	public function index() {
		$this->load->view('media');
	}

	public function item() {
		$this->load->view('media_item');
	}
}
