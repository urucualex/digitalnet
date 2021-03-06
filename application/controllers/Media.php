<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends Generic_Controller {
	public $_item_name = 'media';
	public $_model = 'media_model';

	public function __construct() {
		parent::__construct();
	}

	private function getDurationFromFFmpegOutput($output) {
		if (!is_array($output)) {
			return '00:00:00';
		}
		
		foreach($output as $line) {
			if (preg_match('/Duration: ([0-9:]+)/', $line, $matches)) {
				return $matches[1];
			}
		}
		
		return '00:00:00';
	}
	
	private function getVideoDuration($file_path) {

		//$exec_str = 'ffmpeg -i "'.$file_path.'" 2>&1 | grep \'Duration\' | cut -d \' \' -f 4 | sed s/,//';
		$exec_str = 'ffmpeg -i "'.$file_path.'" 2>&1';		
		exec($exec_str, $output);
		
		$time = $this->getDurationFromFFmpegOutput($output);
		
		$duration = explode(":",$time);

		//debug('ffmpeg cmd', $exec_str);
		//debug('ffmpeg output', $output);

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

	public function playlist($playerId) {
		$date = iso_date_now();

		$params = $this->input->post(null, true);
		if (!empty($params)) {
			if (array_key_exists('date', $params)) {
				$date = $params['date'];
			}
		}

		$this->load->model('media_player_model');
		$this->data['medias'] = $this->media_player_model->playlist($playerId, $date);

		echo json_encode($this->data['medias']);
	}

	public function players($mediaId) {
		$this->load->model('media_player_model');

		$this->data['players'] = $this->media_player_model->players($mediaId);

		echo json_encode($this->data['players']);
	}

	public function items() {
		$params = $this->input->post();
		$data = [];
		if (!empty($params) and (array_key_exists('date', $params))) {
			$data = $this->media_model->getAllMediaOnDate($params['date']);
		}

		if ($this->input->is_ajax_request()) {
			echo json_encode($data);
		} else {
			$this->load->view('medias', $data);
		}
	}

	public function setOrder() {
		$mediaIds = $this->input->post('mediaIds', true);
		$mediaOrder = $this->input->post('order', true);

		$this->media_model->setPlaylistOrder($mediaIds, $mediaOrder);
	}

	public function removeFromPlayer($playerId) {
		$mediaIds = $this->input->post('mediaIds', true);

		if (!$this->media_player_model->removeMediaFromPlayers($mediaIds, $playerId)) {
			$this->output->set_status_header(404);
			return;
		}

		echo 'ok';
	}
}
