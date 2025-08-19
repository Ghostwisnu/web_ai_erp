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

    public function get_where($table, $where)
    {
        return $this->db->get_where($table, $where)->result_array();
    }

    public function getSubMenu()
    {
        $query = "SELECT `master_sub_menu`.*, `master_menu`.`menu_name`
                FROM `master_sub_menu` JOIN `master_menu`
                ON `master_sub_menu`.`id_menu` = `master_menu`.`id_menu`
        ";
        return $this->db->query($query)->result_array();
    }

    public function get_finish_and_half_finish_goods($category)
    {
        $this->db->select('
        master_item.id_item AS item_id, 
        master_item.item_name AS item_name,
        master_category.id_category AS category_id, 
        master_category.category_name AS category_name,
        master_unit.id_unit AS unit_id, 
        master_unit.unit_name AS unit_name
    ');
        $this->db->from('master_item');
        $this->db->join('master_category', 'master_category.id_category = master_item.category_id', 'left');
        $this->db->join('master_unit', 'master_unit.id_unit = master_item.unit_id', 'left');
        $this->db->where('master_category.category_name', $category); // Filter by parameter
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_material_items()
    {
        $this->db->select('
        master_item.id_item AS item_id, 
        master_item.item_name AS item_name,
        master_category.id_category AS category_id, 
        master_category.category_name AS category_name,
        master_unit.id_unit AS unit_id, 
        master_unit.unit_name AS unit_name
    ');
        $this->db->from('master_item');
        $this->db->join('master_category', 'master_category.id_category = master_item.category_id', 'left');
        $this->db->join('master_unit', 'master_unit.id_unit = master_item.unit_id', 'left');

        // Return both Material and Barang Setengah Jadi
        $this->db->where_in('master_category.category_name', ['Material', 'Barang Setengah Jadi']);

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Generate a new unique BOM code
     * Format: BOM-YYYYMMDD-XXX
     * Where XXX is auto-increment for the day
     */
    public function generate_kode_bom()
    {
        $date = date('Ymd'); // e.g., 20250818
        $prefix = 'BOM-' . $date . '-';

        // Find the last kode_bom of today
        $this->db->like('kode_bom', $prefix, 'after');
        $this->db->order_by('id_bom', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get('purchasing_bom')->row_array();

        if ($last) {
            // Extract last number
            $lastNumber = (int)substr($last['kode_bom'], -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Get BOM grouped by kode_bom
     */
    public function get_bom_grouped()
    {
        $this->db->select('
        kode_bom, 
        fg_item_name, 
        fg_unit, 
        created_by, 
        created_at, 
        MIN(id_bom) as first_id
    ');
        $this->db->from('purchasing_bom');
        $this->db->group_by('kode_bom, fg_item_name, fg_unit, created_by, created_at');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result_array();
    }


    /**
     * Get all materials by kode_bom
     */
    public function get_materials_by_kode($kode_bom)
    {
        return $this->db->get_where('purchasing_bom', ['kode_bom' => $kode_bom])->result_array();
    }

    public function generate_kode_item($category_id, $brand_id = null)
    {
        // Map category_id to prefix
        $prefixMap = [
            3 => 'FG',   // Finished Goods
            2 => 'HFG',  // Half Finished Goods
            1 => 'MT'    // Material
        ];
        $prefix = isset($prefixMap[$category_id]) ? $prefixMap[$category_id] : 'UNK';

        // Build search key depending on brand
        if (!empty($brand_id)) {
            $searchPrefix = $prefix . '-' . $brand_id . '-';
        } else {
            $searchPrefix = $prefix . '-';
        }

        // Get the latest kode for this pattern
        $this->db->select('kode_item');
        $this->db->from('master_item');
        $this->db->like('kode_item', $searchPrefix, 'after');
        $this->db->order_by('id_item', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get()->row_array();

        if ($query) {
            $lastKode = explode('-', $query['kode_item']);
            return intval(end($lastKode)); // just return the number
        }

        return 0; // no data yet
    }

    public function format_kode_item($category_id, $brand_id = null, $number = 1)
    {
        $prefixMap = [
            3 => 'FG',
            2 => 'HFG',
            1 => 'MT'
        ];
        $prefix = isset($prefixMap[$category_id]) ? $prefixMap[$category_id] : 'UNK';

        if (!empty($brand_id)) {
            return $prefix . '-' . $brand_id . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } else {
            return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        }
    }

    public function get_items_with_details()
    {
        $this->db->select('
        master_item.id_item,
        master_item.item_name,
        master_item.kode_item,
        master_item.created_at,
        master_item.created_by,
        master_category.category_name,
        master_unit.unit_name,
        master_brand.brand_name
    ');
        $this->db->from('master_item');
        $this->db->join('master_category', 'master_item.category_id = master_category.id_category');
        $this->db->join('master_unit', 'master_item.unit_id = master_unit.id_unit');
        $this->db->join('master_brand', 'master_item.brand_id = master_brand.id_brand', 'left'); // ✅ join brand table
        $this->db->order_by('master_item.id_item', 'DESC');
        return $this->db->get()->result_array();
    }
}
