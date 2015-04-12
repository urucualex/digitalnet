<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Generic_model extends CI_Model
{
    protected $_model = 'generic';

    protected $_validation_rules = array();

    protected $_table = '';
    protected $_search_table = '';
    protected $_id_column = 'id';
    protected $_order_column = '';
    protected $_account_column = ''; //used for checking if the logged user can see this data
    protected $_verify_account = TRUE; //enforce user's account = data's account
    protected $_max_user_level_create = 2; //administrator
    protected $_max_user_level_update = 2; //administrator
    protected $_max_user_level_read = 5; //viewer


    public $_md5_salt = ''; //used for md5 encription (password data validation)

    protected $_parent = array();
    /*
        used for setting order within same parent children and for path generation
        $this->_parent = array(
              'model' => 'model_name'
            , 'column' => 'column_name'
        );
    */

    protected $_path_data = array();
    /*
        $_path_data = array(
                  'type' => 'item type' //string with item type
                , 'type_column' => 'item_type_column' //column in db specifying item type
                , 'name_column' => 'name_column'
            );
    */

    protected $_join = array();
    /*
        $this->_join = array(
                'table_alias' => array(
                  'model' => 'name_model'           //model we are joining, inherited from genetic_model
                , 'column' => 'column_name'         //column from current table/model that links to foreign model
                , 'foreign_column' => 'column_name' //column from foreign model (other than id_column() )
                , 'type' => 'right' )               //left is implied
            , ...)
    */

    protected $_external_data = array();
    /*
        used for accesing data from other linked tables

        $this->_external_data = array(
                'Account' => array(                     //wanted data
                          'model' => 'result_model'     //relating model
                        , 'link_column' => 'Result'     //column from current table that links to the relating model/table
                    )
            );
    */


    public function __construct()
    {
        $_function = '__construct';

        parent::__construct();

        if ($this->_verify_account) 
        {
            $this->load->model('user_model');
        }

        $this->set_user_rights_from_config($this->_model);

        //add id to standatd validation
        $this->_validation_rules = array_merge($this->id_validation_rules(), $this->_validation_rules);

        return TRUE;
    }

    public function id_validation_rules()
    {
        //set validation for id_column
        $id_validation = array(
                $this->_id_column => array(
                          'integer' => TRUE
                        , 'remove_if_empty' => TRUE
                        , 'min' => 1
                    )
            );
        return $id_validation;
    }

    public function account_column()
    {
        return $this->_account_column;
    }

    public function id_column()
    {
        return $this->_id_column;
    }

    public function error($Function, $Message, $Var = NULL)
    {
        error($this->_model."_model->$Function: $Message ".($Var !== NULL ? 'Received: '.print_r($Var, TRUE) : ''));
        return TRUE;
    }

    public function debug($Function, $Message)
    {
        debug($this->_model."_model->$Function: $Message");
        return TRUE;
    }

    public function table()
    {
        $_function = 'table()';
        return $this->_table;
    }

    //adds column_name for each validation rule and for each $Data(array) key
    //column_names are required for generating validation errors
    private function add_column_names_to_validation_rules($Rules, $Data = NULL)
    {
        $function = "add_column_names_to_validation_rules";
        if (!is_array($Rules))
        {
            $this->error($function, 'validation $Rules should be an array!', $Rules);
            return FALSE;
        }

        foreach ($Rules as $column_name => $rules)
        {
            if (empty($rules['column_name']))
            {
                $Rules[$column_name]['column_name'] = $column_name;
            }
        }

        if (is_array($Data))
        {
            foreach ($Data as $column => $value)
            {
                if (empty($Rules[$column]['column_name']))
                {
                    $Rules[$column]['column_name'] = $column;
                }
            }
        }

        return $Rules;
    }

    public function validate(&$Data, $Rules = NULL)
    {
        /*
            + required
            + matches
            + trim (alter data)
            + is_unique (in table)
            + min_length
            + max_length
            + exact_length
            + alpha
            + alpha_numeric
            - alpha_dash
            + date
            + integer (typecast, alter data)
                + min
                + max
                + greater
                + greater_or_equal
                + smaller
                + smaller_or_equal
                - not_in ...
                + not_equal
                + null (value can be null / not set)
            + 0_is_null (changes 0 to NULL)
            + -999999.99_is_null
            + empty_is_null (changes empty string to null)
            + equals
            + allow_all_html_tags (allow ALL html tags)
            + allowed_html_tags (ignored if allow_all_html_tags is true)
            + email
            - ip
            + remove_if_invalid
            + remove
            + remove_if_empty
            + md5 = TRUE
            + user_role = max user_role allowed to change this column
            + to_lowercase - transforms string to lowercase
        */
        $function = 'validate';

        //$Data should be array
        if (!is_array($Data))
        {
            $this->error($function, '$Data should be array');
            return FALSE;
        }

        //if no rules $Data is valid
        if (empty($Rules))
        {
            if (empty($this->_validation_rules))
            {
                return TRUE;
            }
            $Rules = $this->_validation_rules;
        }

        //set column names, they are used in error messages
        $Rules = $this->add_column_names_to_validation_rules($Rules, $Data);
//debug('Validation rules:', print_r($Rules, TRUE));        
        foreach ($Rules as $column=>$rules)
        {
//debug('Column: ', $column);
//debug('Value:', $Data[$column]);
            //id $column data is not pressent but is required: error
            if (!array_key_exists($column, $Data))
            {
                if (!empty($rules['required']) and ($rules['required'] === TRUE))
                {
                    if (!empty($rules['remove_if_invalid']))
                    {
                        unset($Data[$column]);
                        continue;
                    }
                    set_error(sprintf(__("%s is required"), __($rules['column_name'])));
                    return FALSE;
                }
            }
            else
            {
                //filter html tags
                if ((empty($rules['allow_all_html_tags']) or !($rules['allow_all_html_tags'])) and !is_array($Data[$column]))
                {
                    $Data[$column] = strip_tags($Data[$column], (empty($rules['allowed_html_tags']) ? '' : $rules['allowed_html_tags']));
                }

                //test for each rule
                foreach ($rules as $rule => $value)
                {
//debug('Rule: ', $rule);
                    switch ($rule) {

                        case 'allow_all_html_tags':
                        case 'allowed_html_tags':
                        case 'column_name':
                        case 'required':
                        case 'remove_if_invalid':
                            break;

                        case 'not_empty':
                            if ($value === TRUE)
                            {
                                //if ((!isset($Data[$column])) or (isset($Data[$column]) and (strlen($Data[$column]) == 0)))
                                //if (empty($Data[$column]))

                                //value of 0 is empty only if column is of type integer
                                //in php empty(0) === TRUE

                                if (!array_key_exists($column, $Data) 
                                    or ($Data[$column] === '') 
                                    or (array_key_exists('integer', $rules) and ($rules['integer'] === TRUE) and ($Data[$column] === 0)))
                                {
                                    if (!empty($rules['remove_if_invalid']))
                                    {
                                        unset($Data[$column]);
                                        break 2;
                                    }
                                    set_error(sprintf(__("%s should not be empty"), __($rules['column_name'])));
                                    return FALSE;
                                }
                            }
                            break;

                        case 'matches':
                            $column_name = (empty($Rules[$value]['column_name']) ? $value : $Rules[$value]['column_name']);
                            //if matching value is not set
                            if (!isset($Data[$value]))
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s is required"), __($column_name)));
                                return FALSE;
                            }

                            //if values are not equal
                            if ($Data[$value] != $Data[$column])
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s does not match %s"), __($rules['column_name']), __($Rules[$value]['column_name'])));
                                return FALSE;
                            }

                            break;

                        case 'trim':
                            if ($value === TRUE)
                            {
                                $Data[$column] = trim($Data[$column]);
                            }
                            break;

                        case 'is_unique':
                            if ($value === TRUE)
                            {
                                $result = $this->db->where($column, $Data[$column])->get($this->table())->row_array();
                                if (!empty($result))
                                {
                                    if (empty($this->_id_column) or empty($Data[$this->_id_column]) or ($result[$this->_id_column] != $Data[$this->_id_column]))
                                    {
                                        if (!empty($rules['remove_if_invalid']))
                                        {
                                            unset($Data[$column]);
                                            break 2;
                                        }
                                        set_error(sprintf(__("%s should be unique"), __($rules['column_name']))) ;
                                        return FALSE;
                                    }
                                }
                            }
                            break;

                        case 'min_length':
                            if (strlen($Data[$column]) < $value)
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should have at least %s characters"), __($rules['column_name']), $value));
                                return FALSE;
                            }
                            break;

                        case 'max_length':
                            if (strlen($Data[$column]) > $value)
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should have at most %s characters"), __($rules['column_name']), $value));
                                return FALSE;
                            }
                            break;

                        case 'exact_length':
                            if (strlen($Data[$column]) != $value)
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should be exactly %s character long"), __($rules['column_name']), $value));
                                return FALSE;
                            }
                            break;

                        case 'alpha':
                            if (!ctype_alpha($Data[$column]))
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should contain only letters"), __($rules['column_name'])));
                                return FALSE;
                            }
                            break;

                        case 'alpha_numeric':
                            if (!ctype_alnum($Data[$column]))
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should contain only alphanumeric characters"), __($rules['column_name'])));
                                return FALSE;
                            }
                            break;
                        case 'date':
                            if ($value === TRUE)
                            {
                                if (!is_iso_date($Data[$column]))
                                {
                                    $Data[$column] = date_to_iso_date($Data[$column], '-');
                                }
                            }
                            break;
                        case 'integer':
                            if ($value === TRUE)
                            {
                                if (!empty($Rules[$column]['null']) AND ($Data[$column] === null))
                                    break;

                                $Data[$column] = (integer) $Data[$column];
                            }
                            break;

                        case '0_is_null':
                            if (($value === TRUE) and ($Data[$column] == 0))
                            {
                                $Data[$column] = NULL;
                            }
                            break;

                        case '-999999.99_is_null':
                            if (($value === TRUE) and ($Data[$column] == '-999999.99'))
                            {
                                $Data[$column] = NULL;
                            }
                            break;

                        case 'empty_is_null':
                            //this is used in indicator score test so dont test with empty() because 0 becomes null
                            if (($value === TRUE) and ($Data[$column] === ''))
                            {
                                $Data[$column] = NULL;
                            }
                            break;

                        case 'min':
                            if (($Data[$column] < $value))
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should be greater than %s"), __($rules['column_name']), $value));
                                return FALSE;
                            }
                            break;

                        case 'max':
                            if (($Data[$column] > $value))
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should be smaller than %s"), __($rules['column_name']), $value));
                                return FALSE;
                            }
                            break;

                        case 'equals':
                            if (($Data[$column] != $value))
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should be %s"), __($rules['column_name']), $value));
                                return FALSE;
                            }
                            break;

                        case 'greater':
                            $column_name = (empty($Rules[$value]['column_name']) ? $value : $Rules[$value]['column_name']);
                            //if comparing column is not set
                            if (!isset($Data[$value]))
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s is required"), __($column_name)));
                                return FALSE;
                            }

                            if ($Data[$column] <= $Data[$value])
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should be greater than %s"), __($rules['column_name']), __($column_name)));
                                return FALSE;
                            }
                            break;

                        case 'greater_or_equal':
                            $column_name = (empty($Rules[$value]['column_name']) ? $value : $Rules[$value]['column_name']);
                            //if comparing column is not set
                            if (!isset($Data[$value]))
                            {
                                break;
                            }

                            if ($Data[$column] < $Data[$value])
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should be greater or equal to %s"), __($rules['column_name']), __($column_name)));
                                return FALSE;
                            }
                            break;

                        case 'smaller':
                            $column_name = (empty($Rules[$value]['column_name']) ? $value : $Rules[$value]['column_name']);
                            //if comparing column is not set
                            if (!isset($Data[$value]))
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s is required"), __($column_name)));
                                return FALSE;
                            }

                            if ($Data[$column] >= $Data[$value])
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should be smaller than %s"), __($rules['column_name']), __($column_name)));
                                return FALSE;
                            }
                            break;

                        case 'smaller_or_equal':
                            $column_name = (empty($Rules[$value]['column_name']) ? $value : $Rules[$value]['column_name']);
                            //if comparing column is not set
                            if (!isset($Data[$value]))
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s is required"), __($column_name)));
                                return FALSE;
                            }

                            if ($Data[$column] > $Data[$value])
                            {
                                if (!empty($rules['remove_if_invalid']))
                                {
                                    unset($Data[$column]);
                                    break 2;
                                }
                                set_error(sprintf(__("%s should be smaller or equal to %s"), __($rules['column_name']), __($column_name)));
                                return FALSE;
                            }
                            break;

                        case 'not_equal':
                            $column_name = (empty($Rules[$value]['column_name']) ? $value : $Rules[$value]['column_name']);
                            //if comparing column is not set
                            if (is_integer($value))
                            {
                                if ($Data[$column] == $value)
                                {
                                    if (!empty($rules['remove_if_invalid']))
                                    {
                                        unset($Data[$column]);
                                        break 2;
                                    }
                                    set_error(sprintf(__("%s should be different than %s"), __($rules['column_name']), __($column_name)));
                                    return FALSE;
                                }
                            }
                            else
                            {
                                //if comparing with another column and the data given does not contain the column data is valid
                                if (!isset($Data[$value]))
                                {
                                    break;
                                }

                                if ($Data[$column] == $Data[$value])
                                {
                                    if (!empty($rules['remove_if_invalid']))
                                    {
                                        unset($Data[$column]);
                                        break 2;
                                    }
                                    set_error(sprintf(__("%s should be different than %s"), __($rules['column_name']), __($column_name)));
                                    return FALSE;
                                }
                            }
                            break;

                        case 'email':
                            if (($value == TRUE) and (!empty($Data[$column])))
                            {
                                if (!filter_var($Data[$column], FILTER_VALIDATE_EMAIL))
                                {
                                    if (!empty($rules['remove_if_invalid']))
                                    {
                                        unset($Data[$column]);
                                        break 2;
                                    }
                                    set_error(sprintf(__("%s is not a valid e-mail address"), __($rules['column_name'])));
                                    return FALSE;
                                }
                            }
                            break;

                        case 'remove':
                            if ($value == TRUE)
                            {
                                unset($Data[$column]);
                                break 2;
                            }
                            break;

                        case 'remove_if_empty':
                            if (empty($Data[$column]) and ($value == TRUE))
                            {
                                unset($Data[$column]);
                                break 2;
                            }
                            break;

                        case 'md5':
                            if ($value == TRUE)
                            {
                                $Data[$column] = md5($this->_md5_salt.$Data[$column]);
                            }
                            break;

                        case 'user_role':
                            if (!$this->user_model->is_logged() or ($value < $this->user_model->role()))
                            {
                                unset($Data[$column]);
                                break 2;
                            }
                            break;

                        case 'to_lowercase':
                            if ($value == TRUE)
                            {
                                $Data[$column] = strtolower($Data[$column]);
                            }
                            break;

                        default:
                            $this->error($function, "$rule is not a valid validation rule");
                            break;
                    }

                }
            }
        }

        if (!empty($this->_account_column) and $this->user_model->is_logged())
        {
            if (!$this->user_model->is_superadmin() and isset($Data[$this->_account_column]))
            {
                $Data[$this->_account_column] = $this->user_model->account();
            }
        }
        return TRUE;
    }

    protected function add_account_check(&$Where)
    {
        if ($this->_verify_account and !empty($this->_account_column) and (!$this->user_model->is_superadmin()))
        {
            $Where[$this->_table.'.'.$this->_account_column] = $this->user_model->account();
        }

        return $Where;
    }

    public function create(&$Data, $Neighbour_id = NULL, $After = 1, $Create_with_id = FALSE)
    {
        $function = 'create';

        if ($this->_verify_account and ($this->user_model->get('UserRole') > $this->_max_user_level_create))
        {
            set_error(__('You are not allowed to create this type of data!'));
            return FALSE;
        }

        //handle ID_column if is set in $Data: update if it's valid, delete if not
        if (isset($Data[$this->_id_column]) and !$Create_with_id)
        {
            if ($Data[$this->_id_column] > 0)
            {
                return $this->update(0, $Data);
            }
            else
            {
                unset($Data[$this->_id_column]);
            }
        }
        else
        {
            if (isset($Data[$this->_id_column]))
            {
                $Data[$this->_id_column] = (integer) $Data[$this->_id_column];
                if ($Data[$this->_id_column] < 1)
                {
                    unset($Data[$this->_id_column]);
                }
            }
        }

        if (!$this->validate($Data))
        {
            return FALSE;
        }

        //set ORDER as last if _order_column is set, and neighbour_id is NULL
        if (!empty($this->_order_column) and empty($Data[$this->_order_column]) and !($Neighbour_id > 0))
        {
            $max_order = $this->get_max_order_value( (empty($this->_parent['column']) or empty($Data[$this->_parent['column']])) ? NULL : $Data[$this->_parent['column']]);
            $Data[$this->_order_column] = $max_order + 1;
        }


        $last_db_debug_state = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        $result = $this->db->insert($this->table(), $Data);
        if (!$result)
        {
            //debug('DB Error number: ',$this->db->_error_number());
            //debug('DB Error text: ',$this->db->_error_message());

            switch ($this->db->_error_number())
            {
                case 1062:
                    set_error(__("The record is not unique!"));
                    break;
                default:
                    $this->error($function, $this->db->_error_message(), $this->db->last_query());
                    set_error($this->db->_error_message());
                    return false;
            }
        }
        $this->db->db_debug = $last_db_debug_state;

        $id = FALSE;
        if ($result)
        {
            $id =  $this->db->insert_id();
            if (!$id)
            {
                $id = TRUE;
            }
        }

        //set order as Neighbour's Order (+$After), after shifting greater Orders (with the same parent)
        if (($id > 0) and ($id !== TRUE))
        {
            if (!empty($this->_order_column) and ($Neighbour_id > 0))
            {
                $this->set_order_before($id, $Neighbour_id, $After);
            }
        }

        return $id;
    }

    public function read($Id, $Select = '*', $Use_join = TRUE)
    {
        $function = 'read';

        if ($this->_verify_account and ($this->user_model->get('UserRole') > $this->_max_user_level_read))
        {
            set_error(__('You are not allowed to access this type of data!'));
            return FALSE;
        }

        $Id = (integer)$Id;
        if (!($Id > 0))
        {
            $this->error($function, '$Id must be integer greater than 0', $Id);
            return FALSE;
        }

        $where = array($this->_table.'.'.$this->_id_column => $Id);



        $result = $this->read_all(0, NULL, NULL, NULL, $Select, $where, $Use_join);
        if (!empty($result))
        {
            return $result[0];
        }

        return FALSE;
    }

    public function read_by($Column, $Value, $Columns = NULL)
    {
        $function = 'read_by';

        if (!empty($Columns))
        {
            $this->db->select($Columns);
        }

        if (!is_string($Column))
        {
            $this->error($function, 'Column must be string representing one column!', $Column);
            return FALSE;
        }

        if (!is_string($Columns))
        {
            $this->error($function, 'Columns must be string representing the columns that must be selected!', $Columns);
            return FALSE;
        }

        $where = array(
                $Column => $Value
            );

        $this->add_account_check($where);

        return $this->db->where($where)
                        ->select($Columns)
                        ->get($this->table())->row_array();
    }

    //$Id can be integer or array of integers
    public function update($Id, &$Data, $Validation_rules = NULL)
    {
        $function = 'update';
        if ($this->_verify_account and ($this->user_model->get('UserRole') > $this->_max_user_level_update))
        {
            set_error(__('You are not allowed to edit this type of data'));
            return FALSE;
        }

        if (is_array($Id))
        {
            $where = array($this->id_column().' IN ' =>  '( '.implode(', ', $Id).' ) ');
        }
        else
        {
            $Id = (integer) $Id;
            if (!($Id > 0) and (isset($Data[$this->_id_column])))
            {
                $Id = $Data[$this->_id_column];
                unset($Data[$this->_id_column]);
            }

            if (!($Id > 0))
            {
                $this->error($function, '$Id must be integer greater than 0', $Id);
                return FALSE;
            }

            $Data[$this->_id_column] = $Id; //used for checking unique columns

            $where = array($this->id_column() => $Id);
        }

        if (!$this->validate($Data, $Validation_rules))
        {
            return FALSE;
        }

        $this->add_account_check($where);

        $last_db_debug_state = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        $result = $this->db->where($where, NULL, FALSE)->update($this->table(), $Data);
        if (!$result)
        {
            //debug('DB Error number: ',$this->db->_error_number());
            //debug('DB Error text: ',$this->db->_error_message());

            switch ($this->db->_error_number())
            {
                case 1062:
                    set_error(__("The record is not unique!"));
                    break;
                default:
                    $this->error($function, $this->db->_error_message(), $this->db->last_query());
                    set_error($this->db->_error_message());
                    return false;
            }
        }
        $this->db->db_debug = $last_db_debug_state;

        if ($result)
        {
            return $Id;
        }

        return FALSE;
    }

    public function update_where($Where, &$Data)
    {

        return $this->update_all($Where, $Data);
    }

    public function update_all($Where, &$Data)
    {
        if ($this->_verify_account and ($this->user_model->get('UserRole') > $this->_max_user_level_update))
        {
            set_error(__('You are not allowed to edit this type of data'));
            return FALSE;
        }

        if (!$this->validate($Data))
        {
            return FALSE;
        }

        $this->add_account_check($Where);

        $last_db_debug_state = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        if (!empty($Where))
        {
            $__do_not_escape = FALSE;
            if (is_array($Where) and !empty($Where['__do_not_escape']))
            {
                $__do_not_escape = TRUE;
            }

            $this->db->where($Where, NULL, !$__do_not_escape);
        }

        $result = $this->db->update($this->table(), $Data);

        if (!$result)
        {
            //debug('DB Error number: ',$this->db->_error_number());
            //debug('DB Error text: ',$this->db->_error_message());

            switch ($this->db->_error_number())
            {
                case 1062:
                    set_error(__("The record is not unique!"));
                    break;
                default:
                    set_error($this->db->_error_message());
                    return false;
            }
        }

        $this->db->db_debug = $last_db_debug_state;

        if ($result)
        {
            return TRUE;
        }

        return FALSE;
    }

    //get total count
    public function count_all($Where = NULL, $Use_join = FALSE)
    {
        $__do_not_escape = FALSE;
        if (is_array($Where) or is_string($Where))
        {
            if (!empty($Where['__do_not_escape']))
            {
                $__do_not_escape = TRUE;
                unset($Where['__do_not_escape']);
            }

            $this->add_account_check($Where);
            $this->db->where($Where, NULL, !$__do_not_escape);
        }

        if ($Use_join)
        {
            $this->join();
        }

        if (!empty($this->_search_table))
        {
            $this->db->join($this->_search_table, sprintf('%s.%s = %s.%s', $this->_search_table, $this->_id_column, $this->_table, $this->_id_column));
        }

        $result = $this->db->select(sprintf('COUNT(%s.%s) as count', $this->_table, $this->_id_column))->get($this->table())->row_array();

        if (!empty($result))
        {
            return $result['count'];
        }
        return 0;
    }

    protected function join()
    {
        $function = 'join';
        if (is_array($this->_join))
        {
            foreach ($this->_join as $index => $rule)
            {
                if (!is_array($rule))
                {
                    $this->error($function, 'join rule is not array', $rule);
                    continue;
                }

                $model = $rule['model'];
                $this->load->model($model);


                if (!is_numeric($index))
                {
                    $table_alias = $index;
                }
                else
                {
                    $table_alias = FALSE;
                }

                $join_rule = sprintf(
                          '%s = %s.%s'
                        , (strpos($rule['column'], '.') !== FALSE ? $rule['column'] : $this->table().'.'.$rule['column'])
                        , ($table_alias? $table_alias : $this->$model->table())
                        , (!empty($rule['foreign_column']) ? $rule['foreign_column'] : $this->$model->id_column())
                    );

                $table_account_column = $this->$model->account_column();
                if ($this->_verify_account and !$this->user_model->is_superadmin() and !empty($table_account_column))
                {
                    $join_rule .= sprintf(
                              ' AND %s.%s = %s'
                            , (!empty($table_alias) ? $table_alias : $this->$model->table())
                            , $this->$model->account_column()
                            , $this->user_model->account()
                        );
                }

                $this->db->join(
                      $this->$model->table().($table_alias ? " AS $table_alias" : '')
                    , $join_rule
                    , (!empty($rule['type']) ? $rule['type'] : 'left')
                );
            }
        }
        else
        {
            return FALSE;
        }

        return TRUE;
    }

    //get items list
    public function read_all($Offset = 0, $Limit = NULL, $Order_by = NULL, $Sort_order = NULL, $Select = '*', &$Where = NULL, $Use_join = FALSE, $Distinct = false)
    {
/*
debug('Select: ', $Select);
debug('Where: ', $Where);
debug('Model: ', $this->_model);
debug('Table: ', $this->_table);
debug('Use_join: ', $Use_join);
*/

//debug('Current username: ', $this->user_model->get('Username'));
//debug('Current user_role: ', $this->user_model->get('UserRole'));
//debug('Current max read level: ', $this->_max_user_level_read);


        if ($this->_verify_account and ($this->user_model->get('UserRole') > $this->_max_user_level_read))
        {
            set_error(__('You are not allowed to access this type of data'));
            return FALSE;
        }

        $Offset = (integer) $Offset;
        $Limit = (integer) $Limit;
        if ($Limit > 0)
        {
            $this->db->limit($Limit, $Offset);
        }

        if (!empty($Order_by))
        {
            if (is_string($Sort_order) and in_array($Sort_order, array('asc', 'desc')))
            {
                $this->db->order_by($Order_by, $Sort_order);
            }
            else
            {
                $this->db->order_by($Order_by);
            }
        }

        $__do_not_escape = FALSE;
        if (!empty($Where))
        {
            if (is_array($Where) and !empty($Where['__do_not_escape']))
            {
                $__do_not_escape = TRUE;
                unset($Where['__do_not_escape']);
            }

            if (is_array($Where) and (!empty($Where['SearchString'])))
            {
                $search_string = $Where['SearchString'];
                if (empty($this->_search_table))
                {
                    $this->error($function, '$this->_search_table not defined');
                }
                else
                {
                    $this->db->join(
                              $this->_search_table
                            , sprintf("%s.%s = %s.%s"
                                        , $this->_search_table
                                        , $this->_id_column
                                        , $this->_table
                                        , $this->_id_column
                                    )
                            , 'left'
                        );
                    $Where[$this->_search_table.".SearchString LIKE "] = "%$search_string%";
                    unset($Where['SearchString']);
                }
            }

            $this->add_account_check($Where);

            $this->db->where($Where, NULL, !$__do_not_escape);
            if ($__do_not_escape)
            {
                $Where['__do_not_escape'] = TRUE;
            }
        }

        if ($Use_join and (is_array($this->_join)) and (!empty($this->_join)))
        {
            $this->join();
        }

        if ($Distinct)
        {
            $this->db->distinct();
        }

        $return = $this->db->select($Select, !$__do_not_escape)
                        ->get($this->table())
                        ->result_array();
//debug('Query: ', $this->db->last_query());
        return $return;
    }

    public function delete($Id)
    {
        $function = 'delete';

        if ($this->_verify_account and ($this->user_model->get('UserRole') > $this->_max_user_level_update))
        {
            set_error(__('You are not allowed to delete this type of data'));
            return FALSE;
        }

        $Id = (integer) $Id;
        if ($Id > 0)
        {
            $last_db_debug_state = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $where = array(
                    $this->_id_column => $Id
                );
            $this->add_account_check($where);
            $result = $this->db->where($where)->delete($this->table());
            if (!$result)
            {
                switch ($this->db->_error_number())
                {
                    case '1451':
                        set_error(__("Cannot delete the item! Please delete all it's depending items first."));
                        break;

                    echo($this->db->_error_message());
                }
            }
            $this->db->db_debug = $last_db_debug_state;

            return $result;
        }
        $this->error($function, '$Id parameter is not integer greater than 0');
        return FALSE;
    }

    //delete records from table
    public function delete_all($Where)
    {
        $function = "delete_all";

        if ($this>_verify_account and ($this->user_model->get('UserRole') > $this->_max_user_level_update))
        {
            set_error(__('You are not allowed to delete this type of data'));
            return FALSE;
        }

        if (!is_array($Where))
        {
            $this->error($function, '$Where parameter is not array');
            return FALSE;
        }

        if (empty($Where))
        {
            $this->error($function, '$Where parameter is empty');
            return FALSE;
        }

        $last_db_debug_state = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $this->add_account_check($Where);

        $__do_not_escape = FALSE;
        if (!empty($Where['__do_not_escape']))
        {
            $__do_not_escape = TRUE;
            unset($Where['__do_not_escape']);
        }
        $result = $this->db->where($Where, NULL, !$__do_not_escape)->delete($this->table());
        if (!$result)
        {
            switch ($this->db->_error_number())
            {
                case '1451':
                    set_error(__("Cannot delete an item! Please delete all depending items first."));
                    break;

                echo($this->db->_error_message());
            }
        }
        $this->db->db_debug = $last_db_debug_state;
        return $result;
    }

    //gets the order value of last element (by order)
    public function get_max_order_value($Parent_id = NULL)
    {
        if (!empty($this->_parent['column']) and ($Parent_id > 0))
        {
            $this->db->where($this->_parent['column'], $Parent_id);
        }

        $result = $this->db->select_max($this->_order_column)->get($this->table())->row_array();
        if (empty($result))
        {
            return 0;
        }
        return $result[$this->_order_column];
    }

    //SHIFT order_column values (> $Min_order) of elements (with parent_id=$Parent_id) by $Shift_value
    //You can overload the function with a different $Where selector
    public function shift_order_numbers($Min_order, $Parent_id = NULL, $Shift_value = 1, $Where = NULL)
    {
        $function = 'shift_order_numbers';
        if (empty($this->_order_column))
        {
            $this->error($function, 'Please set the $_order_column');
            return FALSE;
        }

        $where = array("$this->_order_column >= " => $Min_order);

        if (!empty($this->_parent['column']) and ($Parent_id > 0))
        {
            $where[$this->_parent['column']] = $Parent_id;
        }

        if (is_array($Where))
        {
            $where = $Where;
        }

        $this->db->set($this->_order_column, "$this->_order_column + ($Shift_value)", FALSE);
        $result = $this->db->where($where)->update($this->_table);
        return $result;
    }

    //shifts the order of elements starting with Neighbour_id by +1 and sets the order of element($Id) in place of Neighbour
    //Order_alter afects the order with which to begin the shift (and the order of the Id element in relation to Neighbour)
    public function set_order_before($Id, $Neighbour_id, $Order_alter = 0)
    {
        $function = 'set_order_before';
        if (empty($this->_order_column))
        {
            $this->error($function, 'Please set the $_order_column');
            return FALSE;
        }

        $neighbour = $this->read($Neighbour_id);
        if (empty($neighbour))
        {
            $this->error($function, "Record with `$this->_id_column` = '$Neighbour_id' does not exist in table $this->_table");
        }
        $order = $neighbour[$this->_order_column] + $Order_alter;
        $parent_id = (empty($this->_parent['column']) or empty($neighbour[$this->_parent['column']])) ? NULL : $neighbour[$this->_parent['column']];
        $this->shift_order_numbers($order, $parent_id);

        return $this->db->where($this->_id_column, $Id)->set($this->_order_column, $order)->update($this->table());
    }

    //sets the order_column for an item before another item in db
    public function set_order_after($Id, $Neighbour_id)
    {
        return $this->set_order_before($Id, $Neighbour_id, 1);
    }

    public function get_by_id($Id)
    {
        return $this->read($Id);
    }


    public function get_external($Column, $Item_id)
    {
        $_function = 'get_external';
        $Item_id = (integer) $Item_id;
        if ($Item_id > 0)
        {
            $item = $this->read($Item_id);
        }
        else
        {
            $this->error($_function, '$Item_id should be integer greater than 0', $Item_id);
            return FALSE;
        }

        //check item
        if (empty($item))
        {
            return FALSE;
        }

        //check if there is a link defined
        if (empty($this->_external_data[$Column]))
        {
            if (empty($item[$Column]))
            {
                return $item;
            }
            else
            {
                return $item[$Column];
            }
        }

        //check if there is data for the link
        if (empty($item[$this->_external_data[$Column]['link_column']]))
        {
            return FALSE;
        }
        $model = $this->_external_data[$Column]['model'];
        $this->load->model($model);
        return $this->$model->get_external($Column, $item[$this->_external_data[$Column]['link_column']]);
    }


    //returns a list of all the parents of item $Id
    public function get_path($Id)
    {
        $function = "get_path";
        if (empty($this->_path_data))
        {
            error($function, '$this->_path_data is not set for current model');
            return FALSE;
        }

        $item = $this->read($Id);

        if (empty($item))
        {
            return FALSE;
        }

        if (!empty($this->_path_data['type']))
        {
            $type = $this->_path_data['type'];
        }
        else
        {
            $type = $item[$this->_path_data['type_column']];
        }

        $result = array(array(
                  'type' => $type
                , 'name' => $item[$this->_path_data['name_column']]
                , 'id' => $item[$this->_id_column]
            ));

        if (!empty($this->_parent) and !empty($item[$this->_parent['column']]))
        {
            $model = $this->_parent['model'];
            $this->load->model($model);
            $parent = $this->$model->get_path($item[$this->_parent['column']]);
        }

        if (!empty($parent))
        {
            return array_merge($parent, $result);
        }

        return $result;
    }

    public function user_max_level_create()
    {
        return $this->_max_user_level_create;
    }

    public function set_user_max_level_create($Level)
    {
        $this->_max_user_level_create = $Level;
    }

    public function user_max_level_update()
    {
        return $this->_max_user_level_update;
    }

    public function set_user_max_level_update($Level)
    {
        $this->_max_user_level_update = $Level;
    }

    public function user_max_level_read()
    {
        return $this->_max_user_level_read;
    }

    public function set_user_max_level_read($Level)
    {
        $this->_max_user_level_read = $Level;
    }

    public function set_user_rights_from_config($Model_name = '')
    {
        $this->config->load('user_rights');
        $user_rights = $this->config->item('user_rights');

        if (!empty($user_rights[$Model_name]))
        {
            $this->set_user_max_level_create($user_rights[$Model_name]['create']);
            $this->set_user_max_level_update($user_rights[$Model_name]['update']);
            $this->set_user_max_level_read($user_rights[$Model_name]['read']);
            return TRUE;
        }

        return FALSE;
    }

    public function verify_account()
    {
        return $this->_verify_account;
    }

    public function set_verify_account($Status)
    {
        $this->_verify_account = !empty($Status);
        return TRUE;
    }
}


