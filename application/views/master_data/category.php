<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg">
            <?= form_error('category', '<div class="alert alert-danger" role="alert">', '</div') ?>
            <?= $this->session->flashdata('message'); ?>
            <!-- Button trigger modal -->
            <a href="" class="ui blue button mb-3" data-toggle="modal" data-target="#newCategoryModal"><i class="plus icon"></i>Add New Category</a>
            <!-- <div class="table-responsive"> -->
            <table class="table table-hover" id="myTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">CATEGORY NAME</th>
                        <th scope="col">CREATED AT</th>
                        <th scope="col">CREATED BY</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $cat['category_name']; ?></td>
                            <td><?= $cat['created_at']; ?></td>
                            <td><?= $cat['created_by']; ?></td>
                            <td>
                                <a href="<?= base_url('master/delete/' . $cat['id_category']); ?>" class="badge badge-danger" onclick="return confirm('Are you sure you want to delete this category?');"><i class="trash icon"></i> Delete</a>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- </div> -->
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<div class="modal fade" id="newCategoryModal" tabindex="-1" aria-labelledby="newCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newCategoryModalLabel">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('master'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category">Category <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="category" name="category" placeholder="Enter category name" required>
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
<!-- Create Modal -->