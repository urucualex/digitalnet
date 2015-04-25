<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends Generic_Controller {
	public $_item_name = 'media';
	public $_model = 'media_model';

	public function __construct() {
		parent::__construct();
	}

	public function upload() {
		$this->load->model('media_file_model');
		$result = $this->media_file_model->get_from_upload('file');

		if (!($result)) {
			echo json_encode(array(
				'status' => 'error',
				'message' => get_error('server error')
			));
			return FALSE;
		} else {
			echo json_encode(array(
				'status' => 'ok',
				'file' => $result
			));			
		}
		return TRUE;

	}
}
