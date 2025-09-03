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

    public function replace_bom($kode_bom, $fgData, $materials)
    {
        if (empty($materials)) {
            return false;
        }

        $this->db->trans_start();

        foreach ($materials as $row) {
            // Fetch kode_item for FG, HFG, MT
            $fg_kode_item  = $this->db->select('kode_item')->from('master_item')->where('id_item', $fgData['id'])->get()->row('kode_item');
            $hfg_kode_item = isset($row['hfg_id']) ? $this->db->select('kode_item')->from('master_item')->where('id_item', $row['hfg_id'])->get()->row('kode_item') : '';
            $mt_kode_item  = isset($row['material_id']) ? $this->db->select('kode_item')->from('master_item')->where('id_item', $row['material_id'])->get()->row('kode_item') : '';

            $data = [
                'kode_bom'          => $kode_bom,
                'fg_kode_item'      => $fg_kode_item ?? '',
                'hfg_kode_item'     => $hfg_kode_item ?? '',
                'mt_kode_item'      => $mt_kode_item ?? '',
                'id_fg_item'        => $fgData['id'],
                'fg_item_name'      => $fgData['name'],
                'fg_unit'           => $fgData['unit'],
                'fg_item_category'  => $fgData['category'],
                'brand_name'        => $fgData['brand'],
                'artcolor_name'     => $fgData['artcolor'],
                'id_hfg_item'       => $row['hfg_id'] ?? null,
                'hfg_item_name'     => $row['hfg_name'] ?? '',
                'hfg_item_category' => $row['hfg_cat'] ?? '',
                'hfg_unit'          => $row['hfg_unit'] ?? '',
                'id_mt_item'        => $row['material_id'] ?? null,
                'mt_item_name'      => $row['material_name'] ?? '',
                'mt_item_category'  => $row['category_name'] ?? '',
                'mt_unit'           => $row['unit_name'] ?? '',
                'bom_qty'           => isset($row['qty']) ? strval($row['qty']) : '0', // ✅ store exactly what you send
                'created_by'        => $this->session->userdata('email'),
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_by'        => $this->session->userdata('email')
            ];
            $this->insert_data('purchasing_bom', $data);
        }

        // Delete old BOM rows
        $this->db->where('kode_bom', $kode_bom);
        $this->db->where('created_at <', date('Y-m-d H:i:s'));
        $this->db->delete('purchasing_bom');

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_row_where($table, $where)
    {
        return $this->db->get_where($table, $where)->row_array();
    }

    public function get_result_where($table, $where)
    {
        return $this->db->get_where($table, $where)->result_array();
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
        master_item.kode_item AS kode_item,  
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
        master_item.kode_item AS kode_item, 
        master_category.id_category AS category_id, 
        master_category.category_name AS category_name,
        master_unit.id_unit AS unit_id, 
        master_unit.unit_name AS unit_name
    ');
        $this->db->from('master_item');
        $this->db->join('master_category', 'master_category.id_category = master_item.category_id', 'left');
        $this->db->join('master_unit', 'master_unit.id_unit = master_item.unit_id', 'left');

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
        artcolor_name, 
        fg_unit, 
        created_by, 
        created_at, 
        MIN(id_bom) as first_id
    ');
        $this->db->from('purchasing_bom');
        $this->db->group_by('kode_bom, fg_item_name, artcolor_name, fg_unit, created_by, created_at');
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
        master_item.category_id, 
        master_category.category_name,
        master_unit.unit_name,
        master_brand.brand_name
    ');
        $this->db->from('master_item');
        $this->db->join('master_category', 'master_item.category_id = master_category.id_category', 'left');
        $this->db->join('master_unit', 'master_item.unit_id = master_unit.id_unit', 'left');
        $this->db->join('master_brand', 'master_item.brand_id = master_brand.id_brand', 'left'); // ✅ join brand table
        $this->db->order_by('master_item.id_item', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_items_modal($category)
    {
        if ($category == 'Barang Jadi') {
            $this->db->select('
            MIN(id_bom) as id_bom, 
            fg_kode_item AS kode_item, 
            fg_item_name AS item_name, 
            fg_unit AS unit_name, 
            fg_item_category AS category_name, 
            brand_name, 
            artcolor_name,
            kode_bom
        ');
            $this->db->from('purchasing_bom');
            $this->db->where('fg_item_category', 'Barang Jadi'); // ✅ filter kategori
            $this->db->group_by([
                'kode_bom',
                'fg_kode_item',
                'fg_item_name',
                'fg_unit',
                'fg_item_category',
                'brand_name',
                'artcolor_name'
            ]);
        } elseif ($category == 'Barang Setengah Jadi') {
            $this->db->select('
            MIN(id_bom) as id_bom, 
            fg_kode_item AS kode_item, 
            fg_item_name AS item_name, 
            fg_unit AS unit_name, 
            fg_item_category AS category_name,
            brand_name, 
            artcolor_name,
            kode_bom
        ');
            $this->db->from('purchasing_bom');
            $this->db->where('fg_item_category', 'Barang Setengah Jadi'); // ✅ filter kategori
            $this->db->group_by([
                'kode_bom',
                'fg_kode_item',
                'fg_item_name',
                'fg_unit',
                'fg_item_category',
                'brand_name',
                'artcolor_name'
            ]);
        }

        return $this->db->get()->result_array();
    }

    public function get_fg_items()
    {
        return $this->db->select('kode_bom, fg_item_name, fg_unit, brand_name, artcolor_name')
            ->from('purchasing_bom')
            ->where('fg_item_category', 'FG') // Only FG
            ->group_by('kode_bom')
            ->get()
            ->result_array();
    }


    // HFG BOM for a specific FG
    // Get HFG for a specific BOM code
    public function get_bom_detail_by_kode_bom($kode_bom)
    {
        return $this->db->select('
        id_bom,
        kode_bom,
        fg_kode_item,
        fg_item_name,
        brand_name,
        artcolor_name,
        hfg_kode_item,
        hfg_item_name,
        hfg_item_category,
        hfg_unit,
        bom_qty
    ')
            ->from('purchasing_bom')
            ->where('kode_bom', $kode_bom)
            ->get()
            ->result_array();
    }

    // Get Materials for a specific BOM code
    public function get_material_list_by_kode_bom($kode_bom)
    {
        return $this->db->select('
        id_bom,
        kode_bom,
        fg_kode_item,
        fg_item_name,
        brand_name,
        artcolor_name,
        mt_kode_item,
        mt_item_name,
        mt_item_category,
        mt_unit,
        bom_qty
    ')
            ->from('purchasing_bom')
            ->where('kode_bom', $kode_bom)
            ->where_in('mt_item_category', ['Material', 'Barang Setengah Jadi'])
            ->get()
            ->result_array();
    }


    public function get_sizes_by_brand($brand_name = null)
    {
        $this->db->select('master_size.size_name');
        $this->db->from('master_size');
        $this->db->join('master_brand', 'master_size.id_brand = master_brand.id_brand');

        if ($brand_name) {
            $this->db->where('master_brand.brand_name', $brand_name);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    // In General_model.php
    public function get_work_orders_grouped()
    {
        $this->db->select('w.*');
        $this->db->from('purchasing_wo w');
        $this->db->join(
            '(SELECT wo_number, MIN(id_wo) as first_id
          FROM purchasing_wo
          GROUP BY wo_number) x',
            'w.wo_number = x.wo_number AND w.id_wo = x.first_id'
        );
        $this->db->order_by('w.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_brand_id_by_name($brand_name)
    {
        $row = $this->db->get_where('master_brand', ['brand_name' => $brand_name])->row_array();
        return $row ? $row['id_brand'] : null; // return null if brand not found
    }

    public function get_work_order_header($wo_number)
    {
        return $this->db->get_where('purchasing_wo', ['wo_number' => $wo_number])->row_array();
    }


    // Get ALL rows (lines) for a WO (FG/HFG/MT combos you saved)
    public function get_work_order_lines($wo_number)
    {
        return $this->db->get_where('purchasing_wo', ['wo_number' => $wo_number])->result_array();
    }

    public function get_wo_size_details($wo_number)
    {
        // Fetch size details from the purchasing_sizerun table
        $this->db->select('id_sizerun, size_name, size_qty'); // Select id_sizerun
        $this->db->from('purchasing_sizerun');
        $this->db->where('wo_number', $wo_number);
        $query = $this->db->get();

        return $query->result_array(); // Return the array of size details
    }


    // --- Get WO header ---
    public function get_work_order($wo_number)
    {
        return $this->db->get_where('purchasing_wo', ['wo_number' => $wo_number])->row_array();
    }

    public function delete_wo_by_number($wo_number)
    {
        $this->db->trans_start();
        $this->db->delete('purchasing_sizerun', ['wo_number' => $wo_number]);
        $this->db->delete('purchasing_wo',     ['wo_number' => $wo_number]);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // --- Batch insert new WO details ---
    public function insert_wo_details($table, $data)
    {
        return $this->db->insert_batch($table, $data);
    }

    public function insert_ro_batch(array $rows)
    {
        if (empty($rows)) return false;

        // Validasi ringan & normalisasi key
        $now = date('Y-m-d H:i:s');
        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'id_wo'         => (int)($r['id_wo'] ?? 0),
                'wo_number'     => trim($r['wo_number'] ?? ''),
                'kode_ro'       => trim($r['kode_ro'] ?? ''),
                'kode_item'     => trim($r['kode_item'] ?? ''),
                'item_name'     => trim($r['item_name'] ?? ''),
                'category'      => trim($r['category'] ?? ''),
                'unit'          => trim($r['unit'] ?? ''),
                // ⬇️ penting: total sizerun (pairs)
                'size_qty'      => (string)($r['size_qty'] ?? '0'),
                'ro_qty'        => (string)($r['ro_qty'] ?? '0'),
                'from_dept'     => trim($r['from_dept'] ?? ''),
                'to_dept'       => trim($r['to_dept'] ?? ''),
                // robust untuk brand/artcolor
                'brand_name'    => trim($r['brand_name'] ?? $r['brand'] ?? ''),
                'artcolor_name' => trim($r['artcolor_name'] ?? $r['artcolor'] ?? ''),
                'status_ro'     => trim($r['status_ro'] ?? 'menunggu dikirim'),
                'date_ro'       => trim($r['date_ro'] ?? date('Y-m-d 00:00:00')),
                'created_by'    => trim($r['created_by'] ?? ($this->session->userdata('email') ?? 'system')),
                'created_at'    => trim($r['created_at'] ?? $now),
                'delete_status' => 0
            ];
        }

        return $this->db->insert_batch('pr_ro', $out);
    }


    public function get_fg_data($wo_number)
    {
        $sql = "
        SELECT p.*
        FROM purchasing_wo p
        INNER JOIN (
            SELECT fg_kode_item, MIN(id_wo) AS min_id
            FROM purchasing_wo
            WHERE wo_number = ?
            GROUP BY fg_kode_item
        ) f ON p.id_wo = f.min_id
        WHERE p.wo_number = ?
    ";
        return $this->db->query($sql, [$wo_number, $wo_number])->result_array();
    }


    public function get_hfg_data($wo_number)
    {
        $sql = "
        SELECT p.*
        FROM purchasing_wo p
        INNER JOIN (
            SELECT hfg_kode_item, MIN(id_wo) AS min_id
            FROM purchasing_wo
            WHERE wo_number = ?
            GROUP BY hfg_kode_item
        ) h ON p.id_wo = h.min_id
        WHERE p.wo_number = ?
    ";
        return $this->db->query($sql, [$wo_number, $wo_number])->result_array();
    }

    public function get_mt_data($wo_number)
    {
        $sql = "
        SELECT p.*
        FROM purchasing_wo p
        INNER JOIN (
            SELECT mt_kode_item, MIN(id_wo) AS min_id
            FROM purchasing_wo
            WHERE wo_number = ?
            GROUP BY mt_kode_item
        ) m ON p.id_wo = m.min_id
        WHERE p.wo_number = ?
    ";
        return $this->db->query($sql, [$wo_number, $wo_number])->result_array();
    }

    public function update_wo_safely_complete($wo_number, array $fgRows, array $hfgRows, array $mtRows, array $sizeRows, $createdBy)
    {
        $now = date('Y-m-d H:i:s');
        $this->db->trans_start();

        // --- Hapus semua data lama ---
        $this->db->where('wo_number', $wo_number)->delete('purchasing_wo');
        $this->db->where('wo_number', $wo_number)->delete('purchasing_sizerun');

        // --- Loop FG ---
        foreach ($fgRows as $fg) {
            $fgId = $fg['fg_kode_item'];

            // Ambil HFG yang terkait, kalau tidak ada, buat 1 dummy
            $relatedHFG = array_filter($hfgRows, fn($h) => $h['fg_kode_item'] === $fgId);
            if (empty($relatedHFG)) $relatedHFG = [[
                'hfg_kode_item' => '',
                'hfg_item_name' => '',
                'hfg_category_name' => '',
                'hfg_unit' => ''
            ]];

            foreach ($relatedHFG as $hfg) {
                $hfgId = $hfg['hfg_kode_item'] ?? '';

                // Ambil MT yang terkait, kalau tidak ada, buat 1 dummy
                $relatedMT = array_filter($mtRows, fn($m) => $m['fg_kode_item'] == $fgId && ($m['hfg_kode_item'] ?? '') == $hfgId);
                if (empty($relatedMT)) $relatedMT = [[
                    'mt_kode_item' => '',
                    'mt_item_name' => '',
                    'mt_category_name' => '',
                    'mt_unit' => '',
                    'bom_qty' => 0,
                    'bom_cons' => 0
                ]];

                foreach ($relatedMT as $mt) {
                    $data = [
                        'kode_bom'          => $fg['kode_bom'],
                        'wo_number'         => $wo_number,
                        'fg_kode_item'      => $fg['fg_kode_item'],
                        'fg_item_name'      => $fg['fg_item_name'],
                        'fg_category_name'  => $fg['fg_category_name'],
                        'fg_unit'           => $fg['fg_unit'],
                        'brand_name'        => $fg['brand_name'],
                        'artcolor_name'     => $fg['artcolor_name'],
                        'wo_qty'            => $fg['wo_qty'],
                        'hfg_kode_item'     => $hfg['hfg_kode_item'] ?? '',
                        'hfg_item_name'     => $hfg['hfg_item_name'] ?? '',
                        'hfg_category_name' => $hfg['hfg_category_name'] ?? '',
                        'hfg_unit'          => $hfg['hfg_unit'] ?? '',
                        'mt_kode_item'      => $mt['mt_kode_item'] ?? '',
                        'mt_item_name'      => $mt['mt_item_name'] ?? '',
                        'mt_category_name'  => $mt['mt_category_name'] ?? '',
                        'mt_unit'           => $mt['mt_unit'] ?? '',
                        'bom_qty'           => $mt['bom_qty'] ?? 0,
                        'bom_cons'          => $mt['bom_cons'] ?? 0,
                        'created_at'        => $now,
                        'created_by'        => $createdBy,
                    ];

                    $this->insert_data('purchasing_wo', $data);
                }
            }
        }

        // --- Insert SizeRun ---
        foreach ($sizeRows as $sr) {
            $sr['id_brand']   = $this->get_brand_id_by_name($sr['brand_name']);
            $sr['wo_number']  = $wo_number;
            $sr['created_by'] = $createdBy;
            $sr['created_at'] = $now;
            if (isset($sr['kode_bom'])) unset($sr['kode_bom']);
            $this->insert_data('purchasing_sizerun', $sr);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Fetch the BOM header data
    public function getBomHeader()
    {
        // Example to fetch BOM header (adjust query based on your actual requirements)
        $this->db->select('*');
        $this->db->from('purchasing_wo');
        $this->db->limit(1);  // Adjust to return the right BOM header
        $query = $this->db->get();

        return $query->row_array();  // Return the first row
    }

    // Fetch materials for the BOM (HFG)
    public function getBomMaterials($fg_item_id)
    {
        $this->db->select('*');
        $this->db->from('purchasing_wo');
        $this->db->where('fg_item_id', $fg_item_id);
        $query = $this->db->get();

        return $query->result_array();  // Return all materials
    }

    // Fetch size run data
    public function getSizeRun($fg_item_id)
    {
        $this->db->select('*');
        $this->db->from('size_run');
        $this->db->where('fg_item_id', $fg_item_id);
        $query = $this->db->get();

        return $query->result_array();  // Return all size run data
    }

    // Insert new purchasing_wo record
    public function insertWo($data)
    {
        return $this->db->insert('purchasing_wo', $data);  // Insert and return the success of the insert
    }

    // Insert new size run data
    public function insertSizeRun($wo_id, $sizerun_data)
    {
        $now = date('Y-m-d H:i:s');
        $created_by = $this->session->userdata('email') ?? 'system';

        foreach ($sizerun_data as $size_run) {
            // Use provided id_brand or get it from brand_name
            $brand_id = $size_run['id_brand'] ?? null;

            if (!$brand_id && !empty($size_run['brand_name'])) {
                $brand_id = $this->get_brand_id_by_name($size_run['brand_name']);
            }

            if (!$brand_id) {
                log_message('error', 'Brand ID is required but not found for: ' . print_r($size_run, true));
                return false;
            }

            // Prepare the data to insert into purchasing_sizerun
            $data = [
                'id_brand' => $brand_id,
                'id_wo' => $wo_id,
                'wo_number' => $size_run['wo_number'] ?? '',
                'brand_name' => $size_run['brand_name'] ?? '',
                'size_name' => $size_run['size_name'] ?? $size_run['size'] ?? '',
                'size_qty' => $size_run['size_qty'] ?? $size_run['qty'] ?? 0,
                'created_by' => $created_by,
                'created_at' => $now,
            ];

            log_message('debug', 'Inserting size run: ' . print_r($data, true));

            if (!$this->db->insert('purchasing_sizerun', $data)) {
                log_message('error', 'Failed to insert size run: ' . print_r($data, true));
                log_message('error', 'Database error: ' . $this->db->error()['message']);
                return false;
            }
        }

        return true;
    }
    // application/models/General_model.php
    public function get_grouped_request_orders_active()
    {
        $this->db->select('
        t.wo_number,
        t.kode_ro,
        MIN(t.id_ro)  AS first_id,
        MAX(t.created_at) AS last_created_at,
        /* status terbaru per kode_ro */
        (
            SELECT t2.status_ro
            FROM pr_ro t2
            WHERE t2.kode_ro = t.kode_ro
              AND (t2.delete_status = 0 OR t2.delete_status IS NULL)
            ORDER BY t2.created_at DESC, t2.id_ro DESC
            LIMIT 1
        ) AS status_ro
    ', false);

        $this->db->from('pr_ro t');
        $this->db->group_start();
        $this->db->where('t.delete_status', 0);
        $this->db->or_where('t.delete_status IS NULL', null, false);
        $this->db->group_end();
        $this->db->group_by('t.wo_number, t.kode_ro');
        $this->db->order_by('last_created_at', 'DESC');

        return $this->db->get()->result_array();
    }

    // General_model.php
    public function get_wo_size_details_by_brand($wo_number, $brand_name = null)
    {
        // 1) Coba ambil dari purchasing_sizerun (yang spesifik WO)
        $this->db->select('size_name, size_qty');
        $this->db->from('purchasing_sizerun');
        $this->db->where('wo_number', $wo_number);
        if (!empty($brand_name)) {
            $this->db->where('brand_name', $brand_name);
        }
        $this->db->order_by('size_name', 'ASC');
        $q = $this->db->get()->result_array();

        if (!empty($q)) {
            // Format respons tetap {size_name, size_qty}
            return $q;
        }

        // 2) Fallback: kalau belum ada data sizerun untuk WO ini,
        //    tampilkan master_size berbasis brand (qty=0)
        $sizes = $this->get_sizes_by_brand($brand_name);
        $out = [];
        foreach ($sizes as $s) {
            $out[] = [
                'size_name' => $s['size_name'],
                'size_qty'  => 0
            ];
        }
        return $out;
    }

    public function get_grouped_ro_for_checkout($only_pending = true)
    {
        $this->db->select('kode_ro, wo_number, MAX(created_at) AS last_created_at');
        $this->db->from('pr_ro');
        $this->db->where('delete_status', 0); // hanya yang belum di-soft-delete
        if ($only_pending) {
            $this->db->where('LOWER(status_ro) <>', 'sudah dikirim'); // tampilkan yang masih pending
        }
        $this->db->group_by('kode_ro, wo_number');
        $this->db->order_by('last_created_at', 'DESC');
        return $this->db->get()->result_array();
    }
    // Fungsi untuk mendapatkan size_qty berdasarkan wo_number
    public function get_size_qty_by_kode_ro($kode_ro)
    {
        // Ambil salah satu size_qty yang unik berdasarkan kode_ro
        $this->db->select('size_qty');
        $this->db->from('pr_ro');
        $this->db->where('kode_ro', $kode_ro);
        $this->db->where('delete_status', 0);  // Pastikan hanya yang belum dihapus
        $this->db->distinct();  // Mengambil satu baris saja jika ada duplikasi kode_ro dan size_qty
        $result = $this->db->get()->row_array();

        return $result['size_qty'] ?? 0;  // Kembalikan nilai size_qty, jika tidak ada kembalikan 0
    }

    // Get total RO Qty for a specific WO and item (kode_item)
    public function get_total_ro_qty($wo_number, $kode_item)
    {
        $this->db->select_sum('ro_qty');
        $this->db->from('pr_ro');
        $this->db->where('wo_number', $wo_number);
        $this->db->where('kode_item', $kode_item); // Tambahkan kondisi kode_item
        $result = $this->db->get()->row_array();
        return $result['ro_qty'] ?? 0; // Mengembalikan total ro_qty atau 0 jika tidak ada
    }

    // Get total Checkin Qty for a specific WO and item (kode_item)
    public function get_total_checkin_qty($wo_number, $kode_item)
    {
        // Karena kolom 'checkin' bertipe VARCHAR, kita CAST hanya jika isinya numerik.
        $this->db->select("
        COALESCE(SUM(
            CASE 
                WHEN `checkin` REGEXP '^-?[0-9]+(\\.[0-9]+)?$'
                    THEN CAST(`checkin` AS DECIMAL(18,4))
                ELSE 0
            END
        ), 0) AS checkin_qty
    ", false);
        $this->db->from('wr_stock');
        $this->db->where('wo_number', $wo_number);
        $this->db->where('kode_item', $kode_item);
        $row = $this->db->get()->row_array();
        return (float)($row['checkin_qty'] ?? 0);
    }
}
