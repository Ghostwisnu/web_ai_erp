<?php
defined('BASEPATH') or exit('No direct script access allowed');

class General_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert data into a table
     * 
     * @param string $table Table name
     * @param array  $data  Data to insert
     * @return bool|int     Insert ID on success, false on failure
     */
    public function insert_data($table, $data)
    {
        $insert = $this->db->insert($table, $data);

        if ($insert) {
            return $this->db->insert_id(); // return last insert ID
        } else {
            return false;
        }
    }

    public function update_data($table, $where, $data)
    {
        $this->db->where($where);
        return $this->db->update($table, $data);
    }


    public function delete_data($table, $where)
    {
        $this->db->where($where);
        return $this->db->delete($table);
    }


    public function get_row_where($table, $where)
    {
        return $this->db->get_where($table, $where)->row_array();
    }

    public function get_all_data($table)
    {
        return $this->db->get($table)->result_array();
    }

    public function getSubMenu()
    {
        $query = "SELECT `master_sub_menu`.*, `master_menu`.`menu_name`
                FROM `master_sub_menu` JOIN `master_menu`
                ON `master_sub_menu`.`id_menu` = `master_menu`.`id_menu`
        ";
        return $this->db->query($query)->result_array();
    }
}
