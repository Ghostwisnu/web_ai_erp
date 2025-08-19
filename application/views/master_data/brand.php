<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg">
            <?= form_error('brand', '<div class="alert alert-danger" role="alert">', '</div') ?>
            <?= $this->session->flashdata('message'); ?>
            <!-- Button trigger modal -->
            <a href="" class="badge badge-primary mb-3" data-toggle="modal" data-target="#newBrandModal"><i class="plus icon"></i>Add New Brand</a>
            <table class="table table-hover" id="myTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">BRAND NAME</th>
                        <th scope="col">CREATED AT</th>
                        <th scope="col">CREATED BY</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($brands as $br): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $br['brand_name']; ?></td>
                            <td><?= $br['created_at']; ?></td>
                            <td><?= $br['created_by']; ?></td>
                            <td>
                                <a href="<?= base_url('master/size/' . $br['id_brand']); ?>" class="badge badge-warning">Size</a>
                                <a href="<?= base_url('master/delete_brand/' . $br['id_brand']); ?>" class="badge badge-danger" onclick="return confirm('Are you sure you want to delete this brand?');"><i class="trash icon"></i> Delete</a>
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
<div class="modal fade" id="newBrandModal" tabindex="-1" aria-labelledby="newBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newBrandModalLabel">Add New Brand</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('master/brand'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="brand_name">Brand Name</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Enter brand name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>