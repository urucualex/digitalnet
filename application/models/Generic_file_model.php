<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//THIS CLASS MUST BE INHERITED TO BE USED

class Generic_file_model extends Generic_Model
{

    protected $_model = 'generic_file';

    // DB table setup
    protected $_table = '';
    protected $_id_column = '';
    protected $_original_filename_column = 'Filename';
    protected $_filename_column = 'File';
    protected $_path_column = 'Path';

    // File upload setup
    protected $_filetype = ''; //used as seed for filename on the server
    protected $_path = ''; //'./img/avatars/'
    protected $_allowed_file_types = ''; //'gif|jpg|png'
    protected $_max_file_size = 0; //not limited

    // Generate unique file name based on the old one
    public function new_file_name($old_file_name) {
        $this->load->helper('string');
        $random_str = random_string('alnum', 5);
        return $random_str.'.'.$old_file_name;
    }

    //Save uploaded file and create db_entry with file's details
    public function get_from_upload($Field_name = 'file', $Data = NULL)
    {
        $_function = 'get_from_upload';

        if (!isset($_FILES[$Field_name]))
        {
            set_error("Field $Field_name is not set in FILES");
            return FALSE;
        }

        //if more than 1 files uploaded prepare $_FILES for each of them and call get_from_upload (as for a single file)
        if (is_array($_FILES[$Field_name]['name']))
        {
            $files_count = count($_FILES[$Field_name]['name']);

            $files = $_FILES;

            $results = array();

            for($i = 0 ; $i < $files_count ; $i++)
            {
                $_FILES = array();
                $_FILES[$Field_name] = array();

                foreach ($files[$Field_name] as $key => $elements)
                {
                    $_FILES[$Field_name][$key] = $files[$Field_name][$key][$i];
                }

                $result = $this->get_from_upload($Field_name, $Data);

                if (!empty($result))
                {
                    $results = array_merge($results, array($result));
                }
            }

            $_FILES = $files;

            if (empty($results) or (count($results) == 0))
            {
                return FALSE;
            }

            return $results;
        }

        // Create entry in DB
        if (!empty($this->_table)) {
            $data = array(
                    $this->_path_column => $this->_path
                );

            if (is_array($Data))
            {
                $data = array_merge($data, $Data);
            }

            $new_id = $this->create($data);

            if (!$new_id)
            {
                return $new_id;
            }            
        }

        // Generate new file name
        $new_file_name = $this->new_file_name($_FILES[$Field_name]['name']);

        $config = array(
                  'upload_path' => $this->_path
                , 'max_size' => $this->_max_file_size
                , 'file_name' => $new_file_name
            );

        // Check file type 
        if (!empty($this->_allowed_file_types))
        {
            $config['allowed_types'] = $this->_allowed_file_types;
        }

        // Initialize file manipulation with CI
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($Field_name))
        {
            $this->error($_function, $this->upload->display_errors());
            set_error(strip_tags($this->upload->display_errors()));
            return FALSE;
        }

        // Do the actual move and rename of the uploaded file  with CI
        $file_data = $this->upload->data();
        if (!empty($this->_table)) {
            $update_data = array(
                      $this->_original_filename_column => urlencode($file_data['client_name'])
                    , $this->_filename_column => $file_data['file_name']
                );

            $this->update($new_id, $update_data);            
        }

        // Prepare function result 
        if (!empty($this->_table)) {
            $data[$this->_original_filename_column] = urlencode($file_data['client_name']);
            $data[$this->_id_column] = $new_id;
            $data[$this->_filename_column] = $file_data['file_name'];            
        }
        else {
            $data = array(
                'original_file_name' => $file_data['client_name'],
                'file_name' => $file_data['file_name'],
                'path' => $this->_path
            );
        }

        return $data;
    }

    public function filename($Image_id)
    {
        $image = $this->read($Image_id);

        if (empty($image))
        {
            return FALSE;
        }

        return $image[$this->_original_filename_column];
    }

    public function path_to_file($file_name, $path = NULL) {
        if ($path == NULL){
            $path = $this->_path;
        }

        if (substr($path, -1) != '/' ) {
            $path = $path.'/';
        }

        $file_path = $path.$file_name;
        return $file_path;
    }


    public function filepath($Image_id)
    {
        $file_data = $this->read($Image_id);

        $file_path = NULL;
        if (!empty($file_data))
        {
            $file_path = $this->path_to_file($file_data[$this->_filename_column], $file_data[$this->_path_column]);
        }

        return $file_path;
    }

    public function download_file($filename, $path = NULL, $served_file_name = NULL) {
        $file_path = $this->path_to_file($filename, $path);
        
        if ($served_file_name === NULL) {
            $served_file_name = $filename;
        } 
        
        $served_file_name = basename($served_file_name);

        $fp = fopen($file_path, 'rb');
        if ($fp)
        {
            header('Content-Disposition: attachment; filename="' . basename($served_file_name) . '"');
            header('Content-Length: '.filesize($file_path));
            fpassthru($fp);
        }
        else {
            set_error('Could not open file');
            return false;
        }
        return TRUE;        
    }

    public function serve_image_file($Filename, $Path = NULL)
    {
        $file_path = $this->path_to_file($Filename, $Path);

        $size = getimagesize($file_path);

        $fp = fopen($file_path, 'rb');

        if ($size and $fp)
        {
            header('Content-Type: '.$size['mime']);
            header('Content-Length: '.filesize($file_path));

            fpassthru($fp);
        }
        else {
            set_error('File not found');
            return FALSE;
        }
        return TRUE;
    }


    public function serve_file($Filename, $Path = NULL)
    {
        $file_path = $this->path_to_file($Filename, $Path);

        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
        $file_mime_type =finfo_file($finfo, $file_path);
        finfo_close($finfo);

        $fp = fopen($file_path, 'rb');

        if ($fp)
        {
            header('Content-Type: '.$file_mime_type);
            header('Content-Length: '.filesize($file_path));

            fpassthru($fp);
        }
        else {
            set_error('File not found');
            return FALSE;
        }
        return TRUE;
    }


    public function download($Id)
    {
        $file_data = $this->read($Id);

        if (!empty($file_data))
        {
            return $this->download_file($file_data[$this->_filename_column], $file_data[$this->_path_column], $file_data[$this->_original_filename_column]);
        }

        set_error('File not found!');
        return FALSE;
    }

    public function serve($Id)
    {
        $file_data = $this->read($Id);

        if (!empty($file_data))
        {
            return $this->serve_file($file_data[$this->_filename_column], $file_data[$this->_path_column]);
        }
        else {
            set_error("File is not in the database");
        }
        return FALSE;
    }
}