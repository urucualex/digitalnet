<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Players extends CI_Controller {
	public function __construct() {
		$this->data = [
			'current_page' => 'players'
		];
	}

	public function index() {
		$this->load->view('players');
	}

	public function item() {
		$this->load->view('player');
	}
}
