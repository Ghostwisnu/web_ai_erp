<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">
        <?= $title; ?> - <span class="text-primary"><?= $brand['brand_name']; ?></span>
    </h1>

    <div class="row">
        <div class="col-lg">
            <?= form_error('size_name', '<div class="alert alert-danger" role="alert">', '</div>') ?>
            <?= $this->session->flashdata('message'); ?>
            <!-- Button trigger modal -->
            <a href="" class="badge badge-primary mb-3" data-toggle="modal" data-target="#newSizeModal"><i class="plus icon"></i>Add New Size</a>
            <a href="<?= base_url('master/brand'); ?>" class="badge badge-warning mb-3"> Back To Brand</a>
            <table class="table table-hover" id="myTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">SIZE NAME</th>
                        <th scope="col">CREATED AT</th>
                        <th scope="col">CREATED BY</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($sizes as $sz): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $sz['size_name']; ?></td>
                            <td><?= $sz['created_at']; ?></td>
                            <td><?= $sz['created_by']; ?></td>
                            <td>
                                <a href="<?= base_url('master/delete_size/' . $sz['id_size'] . '/' . $brand['id_brand']); ?>" class="badge badge-danger" onclick="return confirm('Are you sure you want to delete this size?');"><i class="trash icon"></i> Delete</a>
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
<div class="modal fade" id="newSizeModal" tabindex="-1" aria-labelledby="newSizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newSizeModalLabel">Add New Size for <span class="text-primary"><?= $brand['brand_name']; ?></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('master/size/' . $brand['id_brand']); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="size_name">Size Name</label>
                        <input type="text" class="form-control" id="size_name" name="size_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Size</button>
                </div>
            </form>
        </div>
    </div>
</div>