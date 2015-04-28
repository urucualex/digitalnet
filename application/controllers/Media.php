<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends Generic_Controller {
	public $_item_name = 'media';
	public $_model = 'media_model';

	public function __construct() {
		parent::__construct();
	}

	private function getVideoDuration($file_path) {

		$exec_str = 'ffmpeg -i "'.$file_path.'" 2>&1 | grep \'Duration\' | cut -d \' \' -f 4 | sed s/,//';
		$time = exec($exec_str, $output);   
		$duration = explode(":",$time);   

		debug('ffmpeg cmd', $exec_str);
		debug('ffmpeg output', $output);

		$duration_in_seconds = 0;

		if (count($duration) > 2) {
				$duration_in_seconds = $duration[0]*3600 + $duration[1]*60+ round($duration[2]);   
		} 

		return $duration_in_seconds;  		
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
		
			$result['duration'] = $this->getVideoDuration($this->media_file_model->path_to_file($result['file_name'], $result['path']));

			echo json_encode(array(
				'status' => 'ok',
				'file' => $result
			));			
		}
		return TRUE;
	}

	public function download($file_name) {
		$this->load->model('media_file_model');
		$this->media_file_model->serve_file($file_name);		
	}

	public function select() {
		$selectedMediaIds = $this->input->post('mediaId', TRUE);
		$this->load->library('session');		
		$this->session->set_userdata('selectedMediaIds', $selectedMediaIds);

		echo json_encode(array(
			'status' => 'ok',
			'messsage' => 'selection saved'
		));
	}
}
