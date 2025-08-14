<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-6">
            <?= form_error('user_management', '<div class="alert alert-danger" role="alert">', '</div') ?>
            <?= $this->session->flashdata('message'); ?>
            <!-- Button trigger modal -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Active</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($users as $us): ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $us['user_name']; ?></td>
                                <td><?= $us['user_email']; ?></td>
                                <td><?= $us['user_role_id']; ?></td>
                                <td><?= $us['user_is_active']; ?></td>
                                <td>
                                    <a href="#" class="badge badge-success" data-toggle="modal" data-target="#editRoleModal<?= $us['id_user']; ?>">Edit</a>
                                    <a href="<?= base_url('admin/delete/' . $us['id_user']) ?>" class="badge badge-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                </td>
                            </tr>

                            <!-- Edit Role Modal -->
                            <!-- Edit Role Modal -->
                            <div class="modal fade" id="editRoleModal<?= $us['id_user']; ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <form action="<?= base_url('admin/update_user') ?>" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Role & Status</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id_user" value="<?= $us['id_user']; ?>">

                                                <!-- Role select -->
                                                <div class="form-group">
                                                    <label for="user_role_id">Role</label>
                                                    <select class="form-control" name="user_role_id" id="user_role_id">
                                                        <?php foreach ($roles as $role): ?>
                                                            <option value="<?= $role['id_role']; ?>"
                                                                <?= $role['id_role'] == $us['user_role_id'] ? 'selected' : ''; ?>>
                                                                <?= $role['role_name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <!-- Active checkbox -->
                                                <div class="form-group">
                                                    <label>
                                                        <input type="checkbox" name="user_is_active" value="1"
                                                            <?= $us['user_is_active'] == 1 ? 'checked' : ''; ?>>
                                                        Active
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        <?php $i++;
                        endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->