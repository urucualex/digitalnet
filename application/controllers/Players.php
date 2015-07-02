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
		$player = $this->player_model->read_by('code', $playerCode);

		if (!$player) {
			return;
		}

		$this->load->config('app');
		$playerVersion = $this->config->item('playerVersion');
		$result = "playerVersion=$playerVersion\n";

		$result .= "playlistDate=".$player['playlistLastUpdate'];

		echo $result;
	}

	public function playlist($playerCode) {
		$this->load->model('media_model');
		$player = $this->player_model->getPlayerByCode($playerCode);

		if (!$player) {
			return;
		}

		$playlist = $this->media_model->getPlaylistForPlayerToday($player['playerId']);

		$result = '';
		if (!empty($playlist)) {
			foreach($playlist as $media) {
				$result .= $media['file'].'?'.($media['duration']*1000).($media['useDateInterval'] ? ($media['startDate']).'?'.$media['endDate'] : '')."\r\n";
			}
		}

		echo $result;
	}

	public function player_download($playerCode) {
		$player = $this->player_model->getPlayerByCode($playerCode);

		if (!$player) {
            error('Could not find player by code: ', $playerCode);
			return;
		}

		$this->load->config('app');
		$playerFile = $this->config->item('playerFile');

		if (!$playerFile) {
			return;
		}

		$fp = fopen($playerFile, 'rb');
        if ($fp) {
            header('Content-Disposition: attachment; filename="' . basename($playerFile) . '"');
            header('Content-Length: '.filesize($playerFile));
			header('application/octet-stream');
			fpassthru($fp);
		}
		else {
            error('Could not open file: ', $playerFile);
        }

	}

	public function playing($playerCode, $playingMediaFile) {
		debug('playing', ['playerCode' => $playerCode, 'file' => $playingMediaFile]);

		$this->player_model->updatePlayingFile($playerCode, $playingMediaFile);

		$this->status($playerCode);
	}
}
