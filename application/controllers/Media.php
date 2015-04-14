<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends Generic_Controller {
	public $_item_name = 'media';
	public $_model = 'media_model';

	public function __construct() {
		parent::__construct();
	}
}
