<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Players extends Generic_Controller {
	public $_item_name = 'player';
	public $_model = 'player_model';

	public function __construct() {
		parent::__construct();
	}

	public function select() {
		$this->load->view('players_select');
	}

	public function addMedia() {
		$mediaIds = $this->input->post('mediaIds', true);
		$playerIds = $this->input->post('playerIds', true);		
		// For every playerId add mediaIds to it's playlist
		$this->load->model('media_player_model');
		foreach ($playerIds as $playerId) {
			$this->media_player_model->addMediaToPlayer($playerId, $mediaIds);
		}
	}
}
