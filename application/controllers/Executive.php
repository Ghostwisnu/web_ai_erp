<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Executive extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        // $this->load->helper(['url']); // kalau butuh
    }

    public function index()
    {
        $data['title'] = 'Production Progress Summary';
        // Current logged-in user (for header/sidebar data)
        $data['user'] = $this->General_model->get_row_where(
            'master_user',
            ['user_email' => $this->session->userdata('email')]
        );
        // Subquery: ambil WO unik (wo_number, artcolor_name, brand_name)
        $sql = "
            SELECT 
                p.wo_number,
                p.artcolor_name,
                p.brand_name
            FROM purchasing_wo p
            GROUP BY p.wo_number, p.artcolor_name, p.brand_name
        ";
        $wo_rows = $this->db->query($sql)->result_array();

        if (empty($wo_rows)) {
            $data['rows'] = [];
            $this->load->view('pps', $data);
            return;
        }

        // Ambil semua wo_number yang ada untuk agregasi wr_stock sekali query
        $wo_list = array_column($wo_rows, 'wo_number');

        // Buat placeholder IN clause
        $placeholders = implode(',', array_fill(0, count($wo_list), '?'));

        // Agregasi per WO dari wr_stock (sum checkin per to_dept)
        $agg_sql = "
            SELECT 
                s.wo_number,
                SUM(CASE WHEN s.from_dept = 'Cutting' 
                         THEN CAST(COALESCE(s.checkin, '0') AS UNSIGNED) ELSE 0 END) AS cutting,
                SUM(CASE WHEN s.from_dept = 'Sewing' 
                         THEN CAST(COALESCE(s.checkin, '0') AS UNSIGNED) ELSE 0 END) AS sewing,
                SUM(CASE WHEN s.from_dept IN ('Semi', 'Semi Warehouse')
                         THEN CAST(COALESCE(s.checkin, '0') AS UNSIGNED) ELSE 0 END) AS semi_warehouse,
                SUM(CASE WHEN s.from_dept = 'Lasting' 
                         THEN CAST(COALESCE(s.checkin, '0') AS UNSIGNED) ELSE 0 END) AS lasting,
                SUM(CASE WHEN s.from_dept = 'Finishing' 
                         THEN CAST(COALESCE(s.checkin, '0') AS UNSIGNED) ELSE 0 END) AS finishing
            FROM wr_stock s
            WHERE s.wo_number IN ($placeholders)
            GROUP BY s.wo_number
        ";

        $agg_rows = $this->db->query($agg_sql, $wo_list)->result_array();
        // Index-kan hasil agregasi by wo_number untuk akses cepat
        $agg_by_wo = [];
        foreach ($agg_rows as $r) {
            $agg_by_wo[$r['wo_number']] = $r;
        }

        // Gabungkan data WO unik + agregasi tahap + hitung Finish Goods (min enam kolom)
        $rows = [];
        foreach ($wo_rows as $r) {
            $wo = $r['wo_number'];

            $cutting  = isset($agg_by_wo[$wo]) ? (int)$agg_by_wo[$wo]['cutting'] : 0;
            $sewing   = isset($agg_by_wo[$wo]) ? (int)$agg_by_wo[$wo]['sewing'] : 0;
            $semi_wh  = isset($agg_by_wo[$wo]) ? (int)$agg_by_wo[$wo]['semi_warehouse'] : 0;
            $lasting  = isset($agg_by_wo[$wo]) ? (int)$agg_by_wo[$wo]['lasting'] : 0;
            $finishing = isset($agg_by_wo[$wo]) ? (int)$agg_by_wo[$wo]['finishing'] : 0;
            // $packaging = isset($agg_by_wo[$wo]) ? (int)$agg_by_wo[$wo]['packaging'] : 0;

            // Finish Goods = minimum dari keenam tahap
            $finish_goods = min($cutting, $sewing, $semi_wh, $lasting, $finishing);

            $rows[] = [
                'wo_number'      => $wo,
                'artcolor_name'  => $r['artcolor_name'],
                'brand_name'     => $r['brand_name'],
                'cutting'        => $cutting,
                'sewing'         => $sewing,
                'semi_warehouse' => $semi_wh,
                'lasting'        => $lasting,
                'finishing'      => $finishing,
                // 'packaging'      => $packaging,
                'finish_goods'   => $finish_goods,
            ];
        }

        $data['rows'] = $rows;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('executive/pps', $data);
        $this->load->view('templates/footer');
    }
}
