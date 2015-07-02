<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Media_model extends Generic_model
{
    public function __construct()
    {
        $this->_model = 'media';
        $this->_table = 'media';
        $this->_id_column = 'mediaId';


        $this->_create_validation_rules = array(
                'mediaName' => array(
                          'column_name' => 'Nume campanie'
                        , 'trim' => true
                        , 'not_empty' => true
                        , 'required' => true
                    )
                , 'mediaActive' => array(
                          'column_name' => 'Activ'
                        , 'trim' => true
                        , 'integer' => true
                    )
                , 'mediaLabels' => array(
                          'column_name' => 'Etichete'
                        , 'trim' => true
                    )
                /*, 'playStart' => array(
                          'column_name' => 'Sectiune start'
                        , 'trim' => true
                        , 'interger' => true
                    )
                , 'playEnd' => array(
                          'column_name' => 'Sectiune sfarsit'
                        , 'trim' => true
                    )*/
                , 'useDateInterval' => array(
                          'column_name' => 'Perioada de afisare'
                        , 'integer' => true
                    )
                , 'startDate' => array(
                          'column_name' => 'Inceput perioada de afisare'
                        , 'trim' => true
                    )
                , 'endDate' => array(
                          'column_name' => 'Sfarsit perioada de afisare'
                        , 'trim' => true
                    )
                , 'order' => array(
                          'column_name' => 'Ordine in playlist'
                        , 'trim' => true
                        , 'integer' => true
                    )
            );

        $this->load->model('media_player_model');

        parent::__construct();
    }

    public function getLastOrderIndex() {
        $query = sprintf("SELECT max(`order`) as maxorder FROM %s", $this->_table);
        $result = $this->db->query($query)->result_array();

        if (!empty($result)) {
            return $result[0]['maxorder'];
        }

        return 0;
    }

    public function create(&$Data, $Neighbour_id = NULL, $After = 1, $Create_with_id = false) {
        if (!array_key_exists('order', $Data) or !($Data['order'] > 0)) {
            $Data['order'] = $this->getLastOrderIndex() + 1;
        }

        return parent::create($Data, $Neighbour_id, $After, $Create_with_id);
    }

    //$Id can be integer or array of integers
    public function update($Id, &$Data, $Validation_rules = NULL)
    {
        $result = parent::update($Id, $Data, $Validation_rules);

        if ($result) {
            $this->load->model('media_player_model');
            $this->media_player_model->updatePlaylistLastUpdateForMediaIds($result);
        }

        return $result;
    }

    public function getAllMediaOnDate($date) {
        $result = $this->read_all([
                'order_by' => 'order',
                'where' => [
                    'OR' => [
                        'AND' => [
                            'useDateInterval' => '1',
                            'startDate<=' => $date,
                            'endDate>=' => $date
                        ],
                        'useDateInterval' => '0'
                    ]
                ]
            ]);

        return $result;
    }

    public function setPlaylistOrder($MediaIds, $Order) {

        if (!is_array($MediaIds)) {
            error('Media_model->setPlaylistOrder: $MediaIds must be an array', $MediaIds);
        }

        if (!is_array($Order)) {
            error('Media_model->setPlaylistOrder: $Order must be an array', $Order);
        }

        if (count($MediaIds) != count($Order)) {
            error('Media_model->setPlaylistOrder: $MediaIds and $Order must have the same number of elements', ['MediaIds' => $MediaIds, 'Order' => $Order]);
        }

        $this->db->trans_start();
        foreach ($MediaIds as $key => $mediaId) {
            $query = sprintf("UPDATE %s SET `order` = '%s' WHERE `mediaId` = '%s'",
                            $this->_table,
                            $Order[$key],
                            $mediaId);
            $this->db->query($query);
        }
        $this->db->trans_complete();

        $this->load->model('media_player_model');
        $this->media_player_model->updatePlaylistLastUpdateForMediaIds($MediaIds);
    }

    public function getPlaylistForPlayerOnDate($playerId, $date) {

        //SELECT * FROM media JOIN media_player ON media_player.mediaId = media.mediaId AND media_player.playerId = $playerId
        //      WHERE media.useDateInterval = 0 or (media.useDateInterval = 1 and media.endDate > $date)
        $this->load->model('media_player_model');
        $this->load->model('player_model');
        $mediaPlayerTable = $this->media_player_model->table();
        $mediaTable = $this->table();
        $PlayerTable = $this->player_model->table();

        $joinRule = sprintf('%s.mediaId = %s.mediaId AND %s.playerId = %s',$mediaTable, $mediaPlayerTable, $mediaPlayerTable, $playerId);
        $this->db   ->select('*')
                    ->from($mediaTable)
                    ->join($mediaPlayerTable, $joinRule)
                    ->where("useDateInterval = 0 OR (useDateInterval = 1 and endDate >= '$date')")
                    ->order_by("$mediaTable.order");

        $result = $this->db->get()->result_array();

        return $result;
    }

    public function getPlaylistForPlayerToday($playerId) {
        $date = date('Y-m-d');
        return $this->getPlaylistForPlayerOnDate($playerId, $date);
    }
}
