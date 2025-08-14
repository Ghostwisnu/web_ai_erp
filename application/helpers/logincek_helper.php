<?php

function is_logged_in()
{
    $ci = get_instance();

    // Check login first
    if (!$ci->session->userdata('email')) {
        redirect('auth');
    }

    // Get user role
    $role_id = $ci->session->userdata('user_role_id');

    // Get current URL segment (controller/method)
    $current_menu = $ci->uri->segment(1);
    $current_submenu = $ci->uri->segment(2); // in case method is important

    // Look in master_sub_menu instead of master_menu
    $ci->db->where('menu_url', $current_menu); // or 'menu_link' if that's your column name
    $submenu = $ci->db->get('master_sub_menu')->row_array();

    // If not found by just segment(1), try with controller/method
    if (!$submenu && $current_submenu) {
        $ci->db->where('menu_url', $current_menu . '/' . $current_submenu);
        $submenu = $ci->db->get('master_sub_menu')->row_array();
    }

    // If still no match, block access
    if (!$submenu) {
        redirect('auth/blocked');
    }

    $menu_id = $submenu['id_menu'];

    // Check if user role has access to the parent menu
    $userAccess = $ci->db->get_where('master_user_access_menu', [
        'id_role' => $role_id,
        'id_menu' => $menu_id
    ]);

    if ($userAccess->num_rows() < 1) {
        redirect('auth/blocked');
    }
}

function check_access($role_id, $menu_id)
{
    $ci = get_instance();
    $ci->db->where('id_role', $role_id);
    $ci->db->where('id_menu', $menu_id);
    $result = $ci->db->get('master_user_access_menu');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}
