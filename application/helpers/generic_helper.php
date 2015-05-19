<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    $GLOBALS['__last_error'] = '';

    //wrapper to log a debug message in CodeIgniter log
    function debug($message, $var = NULL)
    {
        return log_message('debug', $message.($var !== NULL ? print_r($var, TRUE) : ''));
    }

    //wrapper to log an error message in CodeIgniter log
    function error($message, $var = NULL)
    {
        return log_message('error', $message.($var !== NULL ? print_r($var, TRUE) : ''));
    }

    //wrapper to log an error message in CodeIgniter log
    function ferror($function, $message, $var = NULL)
    {
        return log_message('error', $function.' -> '.$message.($var !== NULL ? print_r($var, TRUE) : ''));
    }


    //save an error message
    function set_error($message)
    {
        $GLOBALS['__last_error'] = $message;
    }

    //clear and return error, if no error return std_message
    function get_error($std_message)
    {
        if (empty($__last_error))
        {
            $return = $GLOBALS['__last_error'];
            $GLOBALS['__last_error'] = '';
           return $return;
        }
        return $std_message;
    }

    //get the last error without clearing it
    function peek_error($std_message = 'Error')
    {
        if (empty($GLOBALS['__last_error']))
        {
            return $std_message;
        }
        
        return $GLOBALS['__last_error'];
    }

    //reindexes a db result array by a column
    function index_by_column($array, $column)
    {
        if (empty($array) or !is_array($array))
        {
            return array();
        }

        $result = array();
        foreach($array as $key => $item)
        {
            if (isset($item[$column]))
            {
                $result[$item[$column]] = $item;
            }
            unset($array[$key]);
        }

        if (!empty($array))
        {
            return array_merge($result, $array);
        }

        return $result;
    }

    //translates a string into current language
    function __($string)
    {
        $ci = &get_instance();
        $translation = $ci->lang->line($string);
        return (empty($translation) ? '*'.$string : $translation);
    }


    function path_to_string($Path)
    {
        $result = '';
        if (is_array($Path))
        {
            $first = TRUE;
            foreach ($Path as $level => $item)
            {
                if (!$first)
                {
                    $result .= '<br/>';
                }
                for( $i = 0 ; $i < $level ; $i++)
                {
                    $result .= '&nbsp;&nbsp;';
                }
                $result .= (empty($item['type'])? '' : '<b>'.$item['type'].'</b>: ').$item['name'];
                $first = FALSE;
            }
        }
        return $result;
    }

    function path_to_string_xls($Path)
    {
        $result = '';
        if (is_array($Path))
        {
            $first = TRUE;
            foreach ($Path as $level => $item)
            {
                if (!$first)
                {
                    $result .='&#10; ';
                }
                for( $i = 0 ; $i < $level ; $i++)
                {
                    $result .= '  ';
                }
                $result .= (empty($item['type'])? '' : $item['type'].': ').$item['name'];
                $first = FALSE;
            }
        }
        return $result;
    }

    function check_user_logged()
    {
        $ci = &get_instance();

        if (!$ci->user_model->is_logged())
        {
            if (!$ci->input->is_ajax_request())
            {
                $ci->load->helper('url');
                redirect('/user/login');
            }
            exit;
        }

        return TRUE;
    }

    function sanitize_account(&$Account_id, $Url='/')
    {
        $ci = &get_instance();
        $Account_id = (integer) $Account_id;
        if (!$ci->user_model->is_logged())
        {
            exit;
            return FALSE;
        }

        if (empty($Account_id) or (($Account_id != $ci->user_model->account()) and (!$ci->user_model->is_superadmin())))
        {
            $Account_id = $ci->user_model->account();
        }

        return $Account_id;
    }

    //check if account is valid, if not redirect to given url
    function verify_account($Account_id, $Url='/')
    {
        $ci = &get_instance();

        $Account_id = (integer) $Account_id;

        if (!$ci->user_model->is_logged() or empty($Account_id) or (($Account_id != $ci->user_model->account()) and (!$ci->user_model->is_superadmin())))
        {
            $ci->load->helper('url');
            redirect($Url);
            return FALSE;
        }

        return TRUE;
    }

    function check_user_role($Max_user_role)
    {
        $ci = &get_instance();
        if ((!$ci->user_model->is_logged()) or ($ci->user_model->role() > $Max_user_role))
        {
            if (!$ci->input->is_ajax_request())
            {
                $ci->load->helper('url');
                redirect('/');
            }
            else
            {
                exit();
            }
        }

        return TRUE;
    }

    //$Action = ['read' | 'update' | 'create']
    function check_user_right($Section, $Action)
    {
        $ci = &get_instance();
        $ci->config->load('user_rights');
        $user_rights = $ci->config->item('user_rights');

        if ((isset($user_rights[strtolower($Section)][strtolower($Action)])) and ($ci->user_model->role() > $user_rights[strtolower($Section)][strtolower($Action)]))
        {
            return FALSE;
        }
        else
        {
            error("$Section section or $Action action not found in user_rights config file!");
        }
        return TRUE;
    }

    function sql_list($Array)
    {
        if (!is_array($Array))
        {
            error('generic_helper - sql_list: parameter $Array is not array', $Array);
            return FALSE;
        }

        return '('.implode(', ', $Array).')';
    }

    function check_create_array_key(&$Array, $Key)
    {
        if (!is_array($Array))
        {
            error('generic_helper - check_create_array_key: parameter $Array is not array', $Array);
            return FALSE;            
        }

        if (($Key === '') or ($Key === NULL) or ($Key === FALSE))
        {
            error('generic_helper - check_create_array_key: parameter $Key should not be empty', $Key);
            return FALSE;                        
        }

        if (!isset($Array[$Key]))
        {
            $Array[$Key] = array();
        }

        return TRUE;
    }

    function th($value, $type = 'string', $rowspan = 1, $colspan = 1, $class = '')
    {
        $result = array(
                  'cell' => 'th'
                , 'value' => $value
                , 'type' => $type
                , 'rowspan' => $rowspan
                , 'colspan' => $colspan
                , 'class' => $class
            );

        return $result;
    }


    function td($value, $type = 'string', $rowspan = 1, $colspan = 1, $class = '', $attr = NULL)
    {
        $result = array(
                  'cell' => 'td'
                , 'value' => $value
                , 'type' => $type
                , 'rowspan' => $rowspan
                , 'colspan' => $colspan
                , 'class' => $class
                , 'attr' => $attr
            );

        return $result;
    }

    function parse_table($Table_rows, $Merge_enabled_columns = array())
    {
        $result = array();
        if (!is_array($Table_rows))
        {
            return $result;
        }

        $current_elem = end($Table_rows);

        while (!empty($current_elem))
        {
            $prev_elem = prev($Table_rows);

            if (!empty($prev_elem) and is_array($current_elem) and is_array($prev_elem))
            {
                foreach ($current_elem as $key => $cell)
                {
                    if (!empty($Merge_enabled_columns[$key]) and isset($prev_elem[$key]['value']) and ($cell['value'] == $prev_elem[$key]['value']))
                    {
                        $prev_elem[$key]['rowspan'] += $cell['rowspan'];
                        unset($current_elem[$key]);
                    }
                }
            }
            $result = array_merge(array($current_elem), $result);

            $current_elem = $prev_elem;
        }

        return $result;
    }
    
    function html_options($options, $selected_value, $value = 'value', $label = 'label')
    {
        if (!is_array($options))
        {
            return FALSE;
        }
        foreach ($options as $key => $option) 
        {
            if (!is_array($option))
            {
                if ($option == $selected_value)
                {
                    $selected = TRUE;
                }
                else
                {
                    $selected = FALSE;
                }
?>
                <option value="<?=$option?>" <?=$selected ? 'selected="selected"' : ''?>><?=$option?></option>            
<?php                
            }
            else
            {
                if ($option[$value] == $selected_value)
                {
                    $selected = TRUE;
                }
                else
                {
                    $selected = FALSE;
                }
?>  
                <option value="<?=$option[$value]?>" <?=$selected ? 'selected="selected"' : ''?>><?=$option[$label]?></option>            
<?php                        
            }
        }

        return TRUE;
    }

    function is_iso_date($Date, $Splitter = '-')
    {
        $date_elems = explode($Splitter, $Date);
        if (count($date_elems) < 3)
        {
            return FALSE;
        }

        if ($date_elems[0] < 1000)
        {
            return FALSE;
        }        

        if (($date_elems[1] < 1) or ($date_elems[1] > 12))
        {
            return FALSE;
        }        

        if (($date_elems[1] < 1) or ($date_elems[1] > 31))
        {
            return FALSE;
        }        
        return TRUE;
    }

    function date_to_iso_date($Date_str, $Splitter = '-')
    {
        $date_elems = explode($Splitter, $Date_str);
        if (count($date_elems) < 3)
        {
            return NULL;
        }

        if ($date_elems[2] < 1000)
        {
            return NULL;
        }

        return $date_elems[2].$Splitter.$date_elems[1].$Splitter.$date_elems[0];
    }

    function iso_date_year($Date, $Splitter = '-')
    {
        $date_elems = explode($Splitter, $Date);
        if (count($date_elems) < 3)
        {
            return FALSE;
        }

        if ($date_elems[0] < 1000)
        {
            return FALSE;
        }        

        return $date_elems[0];
    }

    function iso_date_month($Date, $Splitter = '-')
    {
        $date_elems = explode($Splitter, $Date);
        if (count($date_elems) < 3)
        {
            return FALSE;
        }

        if ($date_elems[0] < 1000)
        {
            return FALSE;
        }   

        if (($date_elems[1] < 1) or ($date_elems[1] > 12))
        {
            return FALSE;
        }

        return $date_elems[1];
    }

    function iso_date_diff($Date1, $Date2, $Splitter = '-')
    {
        if ($Splitter != '-')
        {
            $Date1 = strreplace($Splitter, '-', $Date1);
            $Date2 = strreplace($Splitter, '-', $Date2);
        }
        $unix_time1 = strtotime($Date1);
        $unix_time2 = strtotime($Date2);
        $seconds_diff = $unix_time1 - $unix_time2;
        return floor($seconds_diff / 3600 / 24);
    }

    function iso_date_to_date($Date_str, $Splitter = '-')
    {
        $date_elems = explode($Splitter, $Date_str);
        if (count($date_elems) < 3)
        {
            return FALSE;
        }

        if ($date_elems[0] < 1000)
        {
            return FALSE;
        }

        return $date_elems[2].$Splitter.$date_elems[1].$Splitter.$date_elems[0];
    }

    function iso_date_min($Date1, $Date2)
    {
        if ($Date1 < $Date2)
        {
            return $Date1;
        }
        return $Date2;
    }

    function iso_date_now() {
        return date('Y-m-d');
    }

if (!function_exists('array_column'))
{    
    function array_column($Array, $Column, $Index_column = NULL)
    {
        $result = array();
        if (!is_array($Array))
        {
            return $result;
        }

        foreach($Array as $elem)
        {
            if (array_key_exists($Column, $elem))
            {
                if (array_key_exists($Index_column, $elem))
                {
                    $result[$elem[$Index_column]] = $elem[$Column];
                }
                else
                {
                    $result[] = $elem[$Column];
                }
            }
        }
        
        return $result;
    }
}

    /*
        $intervals = array(
                '10' => 'white'
                '50' => 'grey'
                '100' => 'black'
            ); //KEYS MUST BE SORTED ASCENDING

        pick_max(0, $intervals) = 'white';
        pick_max(30, $intervals) = 'white';
        pick_max(50, $intervals) = 'grey'; 
        pick_max(60, $intervals) = 'black'; 
        pick_max(101, $intervals) = 'black'; 
    */
    function pick_max($Value, $Result_array, $Default_value = FALSE)
    {

        if (!is_numeric($Value)) 
        {
            return $Default_value;
        }

        $Value = (integer) $Value;

        $result = reset($Result_array);

        foreach ($Result_array as $key => $value) 
        {
            if ($Value >= $key)
            {
                $result = $value;
            }
        }

        return $result;
    }

    function twoDigits($value) {
        if (is_numeric($value) and ($value < 10) and ($value > -10)) {
            if ($value < 0) {
                return '-0'.abs($value);
            }

            return '0'.$value;
        }

        return $value;
    }

    function toHHMMSS($value, $includeHours = FALSE, $separator = ':') {

        $hours = floor($value / 3600);
        $value %= 3600;
        $minutes = floor($value / 60);
        $seconds = $value % 60;

        return (($hours > 0) || $includeHours ? twoDigits($hours) . $separator : '') . twoDigits($minutes) . $separator . twoDigits($seconds);
    }