<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Player_model extends Generic_model
{
    public function __construct()
    {
        $this->_model = 'player';
        $this->_table = 'players';
        $this->_id_column = 'playerId';


        $this->_create_validation_rules = array(
                'playerName' => array(
                          'column_name' => 'Nume'
                        , 'trim' => true
                        , 'not_empty' => true
                        , 'required' => true
                    )
                , 'playerActive' => array(
                          'column_name' => 'Activ'
                        , 'trim' => true
                        , 'integer' => true
                    )
                , 'playerLabels' => array(
                          'column_name' => 'Etichete'
                        , 'trim' => true
                    )
                , 'code' => array(
                          'column_name' => 'Cod'
                        , 'trim' => true
                        , 'not_empty' => true
                    )
                , 'county' => array(
                          'column_name' => 'Judet'
                        , 'trim' => true
                    )
                , 'city' => array(
                          'column_name' => 'Oras'
                        , 'trim' => true
                    )
                , 'location' => array(
                          'column_name' => 'Localizare'
                        , 'trim' => true
                    )
                , 'comment' => array(
                          'column_name' => 'Observatii'
                        , 'trim' => true
                    )
                , 'mfStart' => array(
                          'column_name' => 'Ora de start luni-vineri'
                        , 'trim' => true
                        , 'integer' => true
                        , 'min' => 0
                        , 'max' => 23
                    )
                , 'mfEnd' => array(
                          'column_name' => 'Ora de inchidere luni-vineri'
                        , 'trim' => true
                        , 'integer' => true
                        , 'min' => 0
                        , 'max' => 23
                    )
                , 'satStart' => array(
                          'column_name' => 'Ora de start sambata'
                        , 'trim' => true
                        , 'integer' => true
                        , 'min' => 0
                        , 'max' => 23
                    )
                , 'satEnd' => array(
                          'column_name' => 'Ora de inchidere sambata'
                        , 'trim' => true
                        , 'integer' => true
                        , 'min' => 0
                        , 'max' => 23
                    )
                , 'sunStart' => array(
                          'column_name' => 'Ora de start duminica'
                        , 'trim' => true
                        , 'integer' => true
                        , 'min' => 0
                        , 'max' => 23
                    )
                , 'sunEnd' => array(
                          'column_name' => 'Ora de inchidere duminica'
                        , 'trim' => true
                        , 'integer' => true
                        , 'min' => 0
                        , 'max' => 23
                    )
            );

        parent::__construct();
    }

    public function updatePlayingFile($playerCode, $mediaFile, $mediaFileDuration) {
        $now = iso_date_time_now();

        $updateData = ['playedFile' => $mediaFile, 'playedFileDuration' => $mediaFileDuration , 'lastMessage' => $now];
        return $this->update_where(['code' => $playerCode], $updateData);
    }

    public function getPlayerByCode($code) {
        return $this->read_by('code', $code);
    }

    public function getPlayerVersion() {
        $this->load->config('app');
        $config = $this->config->item('playerVersion');
    }

    public function updatePlaylistLastUpdate($playerIds) {
        $playerIds = toArray($playerIds);

        $query = sprintf('UPDATE players SET playlistLastUpdate = now() WHERE playerId in (%s)', implode(',', $playerIds));
        $result = $this->db->query($query);

        return $result;
    }
}
