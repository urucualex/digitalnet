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
                        , 'integer' => true
                        , 'min' => 1

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
                        , 'integer' => true
                        , 'min' => 1

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

        return true;
    }
}