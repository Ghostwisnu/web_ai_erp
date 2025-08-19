<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg">
            <?= form_error('unit', '<div class="alert alert-danger" role="alert">', '</div') ?>
            <?= $this->session->flashdata('message'); ?>
            <!-- Button trigger modal -->
            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newUnitModal">Add New Unit</a>
            <table class="table table-hover" id="myTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Unit Name</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Created By</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($units as $unit): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $unit['unit_name']; ?></td>
                            <td><?= $unit['created_at']; ?></td>
                            <td><?= $unit['created_by']; ?></td>
                            <td>
                                <a href="<?= base_url('master/delete_unit/' . $unit['id_unit']); ?>" class="badge badge-danger" onclick="return confirm('Are you sure you want to delete this unit?');"><i class="trash icon"></i> Delete</a>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<div class="modal fade" id="newUnitModal" tabindex="-1" aria-labelledby="newUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newUnitModalLabel">Add New Unit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('master/unit'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="unit_name">Unit Name</label>
                        <input type="text" class="form-control" id="unit_name" name="unit_name" placeholder="Enter unit name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Modal -->