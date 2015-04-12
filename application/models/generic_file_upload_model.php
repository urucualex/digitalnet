<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//THIS CLASS MUST BE INHERITED TO BE USED

class Generic_file_upload_model extends generic_model
{

    protected $_model = 'generic_file_upload';
    protected $_table = '';
    protected $_id_column = '';

    protected $_filetype = ''; //used as seed for filename on the server
    protected $_original_filename_column = 'Filename';
    protected $_filename_column = 'File';
    protected $_path_column = 'Path';
    protected $_path = ''; //'./img/avatars/'
    protected $_allowed_file_types = ''; //'gif|jpg|png'
    protected $_max_file_size = 0; //not limited

    //Save uploaded file and create db_entry with file's details
    public function get_from_upload($Field_name = 'file', $Data = NULL)
    {
        $_function = 'get_from_upload';

        if (!isset($_FILES[$Field_name]))
        {
            set_error("Field $Field_name is not set in FILES");
            return FALSE;
        }

        //if more than 1 files uploaded prepare $_FILES for each of them and save with CI
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

        $new_file_name = md5($this->_filetype.$new_id);

        $config = array(
                  'upload_path' => $this->_path
                , 'max_size' => $this->_max_file_size
                , 'file_name' => $new_file_name
            );

        if (!empty($this->_allowed_file_types))
        {
            $config['allowed_types'] = $this->_allowed_file_types;
        }

        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($Field_name))
        {
            $this->error($_function, $this->upload->display_errors());
            set_error(strip_tags($this->upload->display_errors()));
            return FALSE;
        }

        $file_data = $this->upload->data();

        $update_data = array(
                  $this->_original_filename_column => urlencode($file_data['client_name'])
                , $this->_filename_column => $file_data['file_name']
            );

        $this->update($new_id, $update_data);

        $data[$this->_original_filename_column] = urlencode($file_data['client_name']);
        $data[$this->_id_column] = $new_id;
        $data[$this->_filename_column] = $file_data['file_name'];

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

    public function filepath($Image_id)
    {
        $file_data = $this->read($Image_id);

        $file_path = NULL;
        if (!empty($file_data))
        {
            $file_path = $file_data[$this->_path_column].$file_data[$this->_filename_column];
        }

        return $file_path;
    }

    public function download($Id)
    {
        $file_data = $this->read($Id);

        if (!empty($file_data))
        {
            $file_path = $file_data[$this->_path_column].$file_data[$this->_filename_column];
            $fp = fopen($file_path, 'rb');

            if ($fp)
            {
                header('Content-Disposition: attachment; filename="' . basename($file_data[$this->_original_filename_column]) . '"');
                header('Content-Length: '.filesize($file_path));
                fpassthru($fp);
            }
            return TRUE;
        }
        return FALSE;
    }

    public function serve_image($Id)
    {
        $file_data = $this->read($Id);

        if (!empty($file_data))
        {
            $file_path = $file_data[$this->_path_column].$file_data[$this->_filename_column];
            $size = getimagesize($file_path);

            $fp = fopen($file_path, 'rb');

            if ($size and $fp)
            {
                header('Content-Type: '.$size['mime']);
                header('Content-Length: '.filesize($file_path));

                fpassthru($fp);
            }
            return TRUE;
        }
        return FALSE;
    }

}