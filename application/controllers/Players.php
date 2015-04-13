<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Players extends Generic_Controller {
	public $_item_name = 'player';
	public $_model = 'player_model';

	public function __construct() {
		parent::__construct();
	}
}
