<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg">
            <?= form_error('warehouse', '<div class="alert alert-danger" role="alert">', '</div') ?>
            <?= $this->session->flashdata('message'); ?>

            <a href="<?= base_url('warehouse/create_sj'); ?>" class="ui blue button mb-3"><i class="plus icon"></i>Create</a>
            <a href="<?= base_url('warehouse/upload_item'); ?>" class="ui blue button mb-3"><i class="upload icon"></i>Upload</a>
            <table class="table table-hover" id="myTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Print PDF</th>
                        <th scope="col">Kode SJ</th>
                        <th scope="col">No. SJ</th>
                        <th scope="col">Incoming Date</th>
                        <th scope="col">From Dept</th>
                        <th scope="col">To Dept</th>
                        <th scope="col">User</th>
                        <th scope="col">Created AT</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $seen_kode_sj = []; // penanda kode_sj yang sudah tampil
                    ?>
                    <?php foreach ($checkin as $ck): ?>
                        <?php
                        // Skip jika kode_sj sudah pernah ditampilkan
                        if (isset($seen_kode_sj[$ck['kode_sj']])) {
                            continue;
                        }
                        // Tandai sebagai sudah ditampilkan
                        $seen_kode_sj[$ck['kode_sj']] = true;
                        ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td>Print</td>
                            <td><?= $ck['kode_sj']; ?></td>
                            <td><?= $ck['no_sj']; ?></td>
                            <td><?= $ck['date_arrive']; ?></td>
                            <td><?= $ck['from_dept']; ?></td>
                            <td><?= $ck['to_dept']; ?></td>
                            <td><?= $ck['created_by']; ?></td>
                            <td><?= $ck['created_at']; ?></td>
                            <td>
                                <a href="<?= base_url('warehouse/edit_sj/' . $ck['wo_number']); ?>" class="badge badge-warning">Edit</a>
                                <a href="<?= base_url('warehouse/delete_by_kodesj/' . $ck['kode_sj']); ?>"
                                    class="badge badge-danger"
                                    onclick="return confirm('Are you sure you want to delete the record with this Kode SJ?');">
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