<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
        Player
                - id 
                - name
                - active
                - labels
                - code
                - county
                - city
                - locations
                - played_file
                - player_file_duration
                - last_message
                - last_update
                - updating
                - playlist_length_today
                - playlist_count_today
                - last_error
                - external_ip
                - internal_ip
                - comment

        Media
                - id
                - name
                - labels
                - lastChange

                - file
                - duration
                - playStart
                - playEnd

                - startDate
                - endDate

                - client
                - playersCount
                - type

                - order

                - comment

        Player_Media
                - idPlayer
                - idMedia
                - uploadTime
*/

class Migration_Add_Players_Media extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'playerId' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'playerName' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100'
                        ),
                        'playerActive' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                                'constraint' => '1'
                        ),
                        'playerLabels' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '1000'
                        ),                        
                        'code' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100'
                        ),                        
                        'county' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100'
                        ),                        
                        'city' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100'
                        ),                        
                        'location' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '300'
                        ),                        
                        'playedFile' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100'
                        ),                        
                        'playerFileDuration' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                                'constraint' => '8'
                        ),                        
                        'lastChange' => array(
                                'type' => 'TIMESTAMP'
                        ),  
                        'lastMessage' => array(
                                'type' => 'TIMESTAMP'
                        ),                        
                        'lastUpdate' => array(
                                'type' => 'TIMESTAMP'
                        ),                        
                        'updating' => array(
                                'type' => 'TIMESTAMP'
                        ),                        
                        'playlistLengthToday' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                                'constraint' => '8'
                        ),                        
                        'playlistCountToday' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                                'constraint' => '8'
                        ),                        
                        'lastError' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '300'
                        ),                        
                        'lastErrorTime' => array(
                                'type' => 'TIMESTAMP'
                        ),                        
                        'externalIp' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100'
                        ),                        
                        'internalIp' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100'
                        ),                        
                        'comment' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '1000'
                        )
                ));
                $this->dbforge->add_key('playerId', TRUE);
                $this->dbforge->create_table('players');



                $this->dbforge->add_field(array(
                        'mediaId' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'mediaName' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '300'
                        ),
                        'mediaLabels' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '1000'
                        ),
                        'lastChange' => array(
                                'type' => 'TIMESTAMP',
                        ),
                        'file' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100'
                        ),
                        'duration' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                                'constraint' => '8'
                        ),
                        'playStart' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                                'constraint' => '8'
                        ),
                        'playEnd' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                                'constraint' => '8'
                        ),
                        'startDate' => array(
                                'type' => 'DATE'
                        ),
                        'endDate' => array(
                                'type' => 'DATE'
                        ),
                        'client' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100'
                        ),
                        'playersCount' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                                'constraint' => '8'
                        ),
                        'type' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100'
                        ),
                        'order' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                                'constraint' => '8'
                        ),
                        'comment' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '1000'
                        )
                ));
                $this->dbforge->add_key('mediaId', TRUE);
                $this->dbforge->create_table('media');

                $this->dbforge->add_field(array(
                        'mediaId' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                        ),        
                        'playerId' => array(
                                'type' => 'INT',
                                'unsigned' => TRUE,
                        ),
                        'uploaded' => array(
                                'type' => 'TIMESTAMP'
                        )
                ));                                    
                $this->dbforge->add_key('mediaId');
                $this->dbforge->add_key('playerId');
                $this->dbforge->create_table('media_player');
        }

        public function down()
        {
                $this->dbforge->drop_table('players');
                $this->dbforge->drop_table('media');
                $this->dbforge->drop_table('media_player');
        }
}