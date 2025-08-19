<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg">
            <?= form_error('purchasing/create_bom', '<div class="alert alert-danger" role="alert">', '</div') ?>
            <?= $this->session->flashdata('message'); ?>

            <a href="<?= base_url('purchasing/create_bom'); ?>" class="ui blue button mb-3"><i class="plus icon"></i>Create</a>
            <a href="<?= base_url('purchasing/upload_item'); ?>" class="ui blue button mb-3"><i class="upload icon"></i>Upload</a>
            <table class="table table-hover" id="myTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Print PDF</th>
                        <th scope="col">B.O.M.</th>
                        <th scope="col">FG Description</th>
                        <th scope="col">Unit</th>
                        <th scope="col">User</th>
                        <th scope="col">Created AT</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($bom as $br): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td>Print</td>
                            <td><?= $br['kode_bom']; ?></td>
                            <td><?= $br['fg_item_name']; ?></td>
                            <td><?= $br['fg_unit']; ?></td>
                            <td><?= $br['created_by']; ?></td>
                            <td><?= $br['created_at']; ?></td>
                            <td>
                                <a href="<?= base_url('purchasing/edit_bom/' . $br['kode_bom']); ?>" class="badge badge-warning">Edit</a>
                                <a href="<?= base_url('purchasing/delete_bom/' . $br['kode_bom']); ?>"
                                    class="badge badge-danger"
                                    onclick="return confirm('Are you sure you want to delete this BOM?');">
                                    <i class="trash icon"></i> Delete
                                </a>
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