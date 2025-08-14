<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>


    <div class="row">
        <div class="col-lg-6">
            <?= form_error('role_name', '<div class="alert alert-danger" role="alert">', '</div') ?>
            <?= $this->session->flashdata('message'); ?>
            <!-- Button trigger modal -->
            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newRoleModal">Add New Role</a>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($role as $r): ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $r['role_name']; ?></td>
                                <td>
                                    <a href="<?= base_url('admin/roleaccess/') . $r['id_role']; ?>" class="badge badge-warning">Access</a>
                                    <a href="#" class="badge badge-success" data-toggle="modal" data-target="#editRoleModal<?= $r['id_role']; ?>">Edit</a>
                                    <a href="<?= base_url('admin/delete/' . $r['id_role']) ?>" class="badge badge-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                </td>
                            </tr>

                            <!-- Edit Role Modal -->
                            <div class="modal fade" id="editRoleModal<?= $r['id_role']; ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <form action="<?= base_url('admin/update') ?>" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Role</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id_role" value="<?= $r['id_role']; ?>">
                                                <div class="form-group">
                                                    <label for="role_name">Role Name</label>
                                                    <input type="text" class="form-control" name="role_name" value="<?= $r['role_name']; ?>">
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

<!-- Modal -->
<div class="modal fade" id="newRoleModal" tabindex="-1" aria-labelledby="newRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRoleModalLabel">Add New Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/role'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="role_name" name="role_name" placeholder="Role Name">
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