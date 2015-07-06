<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Media_file_model extends Generic_file_model {
	protected $_model = 'Media_file_model';

    // File upload setup
    protected $_path = './upload/media'; //'./img/avatars/'
    protected $_allowed_file_types = '*'; //'gif|jpg|png'
    protected $_max_file_size = 0; //not limited
}