<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package     CodeIgniter
 * @author      ExpressionEngine Dev Team
 * @copyright   Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license     http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Form Validation Class
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Validation
 * @author      ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/libraries/form_validation.html
 */
class MY_Form_validation extends CI_Form_validation {

    public function __construct(){ parent::__construct(); }

    public function run($module = '', $group = '')
    {
       (is_object($module)) AND $this->CI = &$module;
        return parent::run($group);
    }

    public function error_array()
    {
        if (count($this->_error_array) === 0)
        {
            return FALSE;
        }
        else
            return $this->_error_array;
    }

    /*
    public function set_value($field = '', $default = '')
    {
        if ( ! isset($this->_field_data[$field]))
        {
            return $default;
        }

        $field = &$this->_field_data[$field]['postdata'];

        if (is_array($field))
        {
            $current = each($field);
            return $current['value'];
        }

        return $field;
    }
    */

/**
     * Get the value from a form
     *
     * Permits you to repopulate a form field with the value it was submitted
     * with, or, if that value doesn't exist, with the default
     *
     * @access    public
     * @param    string    the field name
     * @param    string
     * @return    void
     */
    public function set_value($field = '', $default = '')
    {
        if ( ! isset($this->_field_data[$field]))
        {
            if( $this->CI->input->post($field)===FALSE)
            {
                return $default;
            }
            else
            {
                return $this->CI->input->post($field);
            }
        }

        return $this->_field_data[$field]['postdata'];
    }

    // --------------------------------------------------------------------

    /**
     * Set Select
     *
     * Enables pull-down lists to be set to the value the user
     * selected in the event of an error
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    string
     */
    public function set_select($field = '', $value = '', $default = FALSE)
    {
        return $this->set_value_array($field, $value, ' selected="selected"', $default);
    }

    // --------------------------------------------------------------------

    /**
     * Set Radio
     *
     * Enables radio buttons to be set to the value the user
     * selected in the event of an error
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    string
     */
    public function set_radio($field = '', $value = '', $default = FALSE)
    {
        return $this->set_value_array($field, $value, ' selected="selected"', $default);
    }

    // --------------------------------------------------------------------

    /**
     * Set Checkbox
     *
     * Enables checkboxes to be set to the value the user
     * selected in the event of an error
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    string
     */
    public function set_checkbox($field = '', $value = '', $default = FALSE)
    {
        return $this->set_value_array($field, $value, ' checked="checked"', $default);
    }

    public function set_value_array($field = '', $value = '', $default_value = '' ,$default = FALSE){


        if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata']))
        {
            if( ! ($this->CI->input->post($field) === FALSE))
            {
                $field = $this->CI->input->post($field);
            }
            else
            {
                if ($default === TRUE) // AND count($this->_field_data) === 0)
                {
                    return $default_value;
                }
                return '';
            }
        }
        else
        {
        $field = $this->_field_data[$field]['postdata'];

        }

        if (is_array($field))
        {
            if ( ! in_array($value, $field))
            {
                return '';
            }
        }
        else
        {
            if (($field == '' OR $value == '') OR ($field != $value))
            {
                return '';
            }
        }

        return $default_value;
    }

    public function is_unique($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return is_object($this->CI->db)
            ? ($this->CI->db->get_where($table, array($field => $str))->num_rows() === 0)
            : FALSE;
    }

    public function is_exist($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);

        if(is_object($this->CI->db)){
            if(($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() !== 0)){
                return TRUE;
            } else {
                $this->CI->form_validation->set_message('is_exist', "{$str} is not exist in the {$table} {$field} list");
            }
        }

        return FALSE;

        // return is_object($this->CI->db)
        //     ? ($this->CI->db->get_where($table, array($field => $str))->num_rows() > 0)
        //     : FALSE;
    }

}