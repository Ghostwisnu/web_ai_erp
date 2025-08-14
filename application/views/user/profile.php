<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="card mb-3" style="max-width: 540px;">
        <?= $this->session->flashdata('message'); ?>
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="<?= base_url('assets/img/profile/') . $user['user_image']; ?>" class="card-img">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"><?= $user['user_name']; ?></h5>
                    <p class="card-text"><?= $user['user_email']; ?></p>
                    <p class="card-text"><small class="text-muted">Member Since : <?= $user['user_created_at']; ?></small></p>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->