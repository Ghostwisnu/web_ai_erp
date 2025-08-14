<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <?php if (validation_errors()): ?>
        <div class="alert alert-danger" role="alert">
            <?= validation_errors(); ?>
        </div>
    <?php endif; ?>

    <?= $this->session->flashdata('message'); ?>

    <!-- Button trigger modal -->
    <a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newSubMenuModal">
        Add New Sub Menu
    </a>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Menu</th>
                    <th>URL</th>
                    <th>Icon</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($submenu as $sm): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $sm['submenu_name']; ?></td>
                        <td><?= $sm['menu_name']; ?></td>
                        <td><?= $sm['menu_url']; ?></td>
                        <td><i class="<?= $sm['menu_icon']; ?>"></i></td>
                        <td><?= $sm['menu_is_active'] ? 'Yes' : 'No'; ?></td>
                        <td>
                            <!-- Edit Button -->
                            <a href="#"
                                class="badge badge-success"
                                data-toggle="modal"
                                data-target="#editSubMenuModal<?= $sm['id_submenu']; ?>">
                                Edit
                            </a>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editSubMenuModal<?= $sm['id_submenu']; ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="<?= base_url('menu/editsubmenu'); ?>" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Sub Menu</h5>
                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="id_submenu" name="id_submenu" value="<?= $sm['id_submenu']; ?>">

                                        <div class="form-group">
                                            <label for="submenu_name">Sub Menu Name</label>
                                            <input type="text" class="form-control" id="submenu_name" name="submenu_name" value="<?= $sm['submenu_name']; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Menu</label>
                                            <div class="ui search selection dropdown" id="menu_dropdown_edit_<?= $sm['id_submenu']; ?>">
                                                <input type="hidden" id="id_menu" name="id_menu" value="<?= $sm['id_menu']; ?>">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">Select Menu</div>
                                                <div class="menu">
                                                    <?php foreach ($menu as $m): ?>
                                                        <div class="item" data-value="<?= $m['id_menu']; ?>">
                                                            <?= $m['menu_name']; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="url">URL</label>
                                            <input type="text" class="form-control" id="menu_url" name="menu_url" value="<?= $sm['menu_url']; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="icon">Icon</label>
                                            <input type="text" class="form-control" id="menu_icon" name="menu_icon" value="<?= $sm['menu_icon']; ?>">
                                        </div>

                                        <div class="form-group">
                                            <div class="ui checkbox">
                                                <input type="checkbox" id="menu_is_active" name="menu_is_active" <?= $sm['menu_is_active'] ? 'checked' : ''; ?>>
                                                <label>Active</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
<!-- End of Main Content -->


<!-- Add New Modal -->
<div class="modal fade" id="newSubMenuModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= base_url('menu/submenu'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Sub Menu</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="submenu_name">Sub Menu Name</label>
                        <input type="text" class="form-control" id="submenu_name" name="submenu_name">
                    </div>

                    <div class="form-group">
                        <label>Menu</label>
                        <div class="ui search selection dropdown" id="menu_name_dropdown">
                            <input type="hidden" id="id_menu" name="id_menu">
                            <i class="dropdown icon"></i>
                            <div class="default text">Select Menu</div>
                            <div class="menu">
                                <?php foreach ($menu as $m): ?>
                                    <div class="item" data-value="<?= $m['id_menu']; ?>">
                                        <?= $m['menu_name']; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="menu_url">URL</label>
                        <input type="text" class="form-control" id="menu_url" name="menu_url">
                    </div>

                    <div class="form-group">
                        <label for="menu_icon">Icon</label>
                        <input type="text" class="form-control" id="menu_icon" name="menu_icon">
                    </div>

                    <div class="form-group">
                        <div class="ui checkbox">
                            <input type="checkbox" value="1" id="menu_is_active" name="menu_is_active" checked>
                            <label>Active</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    $(document).ready(function() {
        // Add New modal dropdown
        $('#newSubMenuModal').on('shown.bs.modal', function() {
            $('#menu_name_dropdown').dropdown({
                fullTextSearch: true
            });
            $('.ui.checkbox').checkbox();
        });

        // Edit modal dropdowns
        <?php foreach ($submenu as $sm): ?>
            $('#editSubMenuModal<?= $sm['id_submenu']; ?>').on('shown.bs.modal', function() {
                $('#menu_dropdown_edit_<?= $sm['id_submenu']; ?>').dropdown({
                    fullTextSearch: true
                });
                $('.ui.checkbox').checkbox();
            });
        <?php endforeach; ?>
    });
</script>