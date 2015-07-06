<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

function validate(&$Data, $Rules = NULL)
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
        ferror($function, '$Data should be array', $Data);
        return FALSE;
    }

    //if no rules $Data is valid
    if (empty($Rules))
    {
        return true;
    }

//set column names, they are used in error messages
//$Rules = $this->add_column_names_to_validation_rules($Rules, $Data);

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
                        ferror($function, "$rule is not a valid validation rule");
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
