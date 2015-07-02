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

	// return player version, playlist md5, settings md5, commands
	public function status($playerCode) {
		$this->load->config('app');
		$playerVersion = $this->config->item('playerVersion');
		$result = "playerVersion=$playerVersion\n";

		echo $result;
	}

	public function playlist($playerCode) {
		$this->load->model('media_model');
		$player = $this->player_model->getPlayerByCode($playerCode);
		$playlist = $this->media_model->getPlaylistForPlayerToday($player['playerId']);

		$result = '';
		if (!empty($playlist)) {
			foreach($playlist as $media) {
				$result .= $media['file'].'?'.($media['duration']*1000).($media['useDateInterval'] ? ($media['startDate']).'?'.$media['endDate'] : '')."\n";
			}
		}

		echo $result;
	}

	public function playing($playerCode, $playingMediaFile) {
		debug('playing', ['playerCode' => $playerCode, 'file' => $playingMediaFile]);

		$this->player_model->updatePlayingFile($playerCode, $playingMediaFile);

		$this->status($playerCode);
	}
}
