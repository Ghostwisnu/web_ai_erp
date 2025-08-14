<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-code"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Inventory ERP</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Menu -->
    <?php
    $role_id = $this->session->userdata('user_role_id');
    $queryMenu = "SELECT `master_menu`.`id_menu`, `menu_name` 
                    FROM `master_menu` JOIN `master_user_access_menu`
                    ON  `master_menu`.`id_menu` = `master_user_access_menu`.`id_menu`
                    WHERE `master_user_access_menu`.`id_role` = $role_id 
                    ORDER BY `master_user_access_menu`.`id_menu` ASC ";
    $menu = $this->db->query($queryMenu)->result_array();
    ?>
    <!-- Loooping Menu -->
    <?php foreach ($menu as $m): ?>
        <div class="sidebar-heading">
            <?= $m['menu_name']; ?>
        </div>
        <!-- Siapkan Sub menu sesuai Menu -->
        <?php
        $menuId = $m['id_menu'];
        $querySubMenu = "SELECT * FROM `master_sub_menu`
                    WHERE `id_menu` = $menuId
                    AND `menu_is_active` = 1";
        $subMenu = $this->db->query($querySubMenu)->result_array();
        ?>
        <?php foreach ($subMenu as $sm): ?>
            <?php if ($title == $sm['submenu_name']) : ?>
                <li class="nav-item active">
                <?php else : ?>
                <li class="nav-item">
                <?php endif; ?>
                <a class="nav-link pb-0" href="<?= base_url($sm['menu_url']); ?>">
                    <i class="<?= $sm['menu_icon']; ?>"></i>
                    <span><?= $sm['submenu_name']; ?></span></a>
                </li>
            <?php endforeach; ?>
            <hr class="sidebar-divider mt-3">
        <?php endforeach; ?>

        <!-- Nav Item - Dashboard -->


        <!-- Divider -->

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                <span>Logout</span></a>
        </li>


        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

</ul>
<!-- End of Sidebar -->