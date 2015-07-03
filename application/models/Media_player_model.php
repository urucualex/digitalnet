<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Media_player_model extends Generic_model
{
    public function __construct()
    {
        $this->_model = 'media_player';
        $this->_table = 'media_player';
        //$this->_id_column = 'playerId';


        $this->_create_validation_rules = array(
                'playerId' => array(
                          'column_name' => 'Player'
                        , 'required' => true
                        , 'trim' => true
                        , 'not_empty' => true
                        , 'integer' => true
                        , 'min' => 1
                ),
                'mediaId' => array(
                          'column_name' => 'Media'
                        , 'required' => true
                        , 'trim' => true
                        , 'not_empty' => true
                        , 'integer' => true
                        , 'min' => 1
                ),
                'uploaded' => array(
                          'column_name' => 'Uploaded'
                        , 'trim' => true
                        , 'not_empty' => true
                )
            );

        $this->_update_validation_rules = array(
                'playerId' => array(
                          'column_name' => 'Player'
                        , 'trim' => true
                        , 'not_empty' => true
                        , 'integer' => true
                        , 'min' => 1
                ),
                'mediaId' => array(
                          'column_name' => 'Media'
                        , 'trim' => true
                        , 'not_empty' => true
                        , 'integer' => true
                        , 'min' => 1
                ),
                'uploaded' => array(
                          'column_name' => 'Uploaded'
                        , 'trim' => true
                        , 'not_empty' => true
                )
            );

        $this->_join = array(
                array(
                    'model' => 'media_model',
                    'column' => 'mediaId',
                ),
                array(
                    'model' => 'player_model',
                    'column' => 'playerId',
                )
            );

        parent::__construct();
    }

    public function addMediaToPlayer($playerId, $mediaIds) {
        if (!is_array($mediaIds)) {
            $mediaIds = array($mediaIds);
        }
        foreach ($mediaIds as $mediaId) {
            $data = array(
                'playerId' => $playerId,
                'mediaId' => $mediaId
            );
            $result = $this->create($data);
        }

        $this->load->model('player_model');
        $this->player_model->updatePlaylistLastUpdate($playerId);

        return true;
    }

    public function playlist($playerId, $date) {
        return $this->read_all([
            'order_by' => 'order',
            'where' => [
                $this->_table.'.playerId' => $playerId,
                'OR' => [
                    'AND' => [
                        'media.useDateInterval' => '1',
                        'media.startDate<=' => $date,
                        'media.endDate>=' => $date
                    ],
                    'OR' => [
                        'media.useDateInterval' => '0'
                    ]
                ]
            ],
            'join' => true
        ]);
    }


    public function players($mediaId) {
        return $this->read_all([
            'order_by' => 'county, city, playerName',
            'where' => [$this->_table.'.mediaId' => $mediaId],
            'join' => true
        ]);
    }

    public function updatePlaylistLastUpdateForMediaIds($mediaIds) {
        $mediaIds = toArray($mediaIds);

        $query = sprintf('UPDATE players SET playlistLastUpdate = now() WHERE players.playerId in (SELECT playerId FROM media_player WHERE mediaId in (%s) )', implode(',', $mediaIds));
        $result = $this->db->query($query);

        return $result;
    }

    public function confirmMediaDownload($playerCode, $mediaFileName) {

        $this->load->model('media_model');
        $media = $this->media_model->read_by('file', $mediaFileName, 'mediaId');

        $this->load->model('player_model');
        $player = $this->player_model->read_by('code', $playerCode, 'playerId');

        if (!$media) {
            return false;
        }

        if (!$player) {
            return false;
        }

        $updateData = ['uploaded' => iso_date_time_now()];
        $this->update_where(['playerId' => $player['playerId'], 'mediaId' => $media['mediaId']], $updateData);

        return true;

    }
}
