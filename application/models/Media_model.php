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
    }
}