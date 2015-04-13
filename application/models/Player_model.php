<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Player_model extends Generic_model
{
    public function __construct()
    {
        $this->_model = 'player';
        $this->_table = 'players';
        $this->_id_column = 'idPlayer';
        $this->_join = array(
                  array(
                      'model' => 'indicator_type_model'
                    , 'column' => 'IndicatorType'
                )
            );

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
            );

        parent::__construct();
    }

}