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

    public function getAllMediaOnDate($date) {

        $this->db->select('*');
        $this->db->where([
                    'startDate<=' => $date,
                    'endDate>=' => $date,
                ]);
        $this->db->or_where([
                    'startDate' => '0000-00-00',
                    'endDate' => '0000-00-00',
                ]);

        $this->db->order_by('order');

        $result = $this->db->get($this->_table)->result_array();
        return $result;
    }

}