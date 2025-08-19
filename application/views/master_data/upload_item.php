<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <a href="<?= base_url('master/item'); ?>" class="ui blue button mb-3"><i class="fas fa-backward"></i> Item List</a>
    <div class="row">
        <!-- Category Reference Table -->
        <div class="col-md-4 mb-3">
            <h5>Category Reference</h5>
            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?= htmlspecialchars($cat['id_category']); ?></td>
                                <td><?= htmlspecialchars($cat['category_name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Brand Reference Table -->
        <div class="col-md-4 mb-3">
            <h5>Brand Reference</h5>
            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Brand Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($brands as $brand): ?>
                            <tr>
                                <td><?= htmlspecialchars($brand['id_brand']); ?></td>
                                <td><?= htmlspecialchars($brand['brand_name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Unit Reference Table -->
        <div class="col-md-4 mb-3">
            <h5>Unit Reference</h5>
            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Unit Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($units as $unit): ?>
                            <tr>
                                <td><?= htmlspecialchars($unit['id_unit']); ?></td>
                                <td><?= htmlspecialchars($unit['unit_name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- Download Template Button -->
    <a href="<?= base_url('master/download_item_template'); ?>" class="btn btn-success mb-3">
        <i class="fas fa-download"></i> Download Excel Template
    </a>

    <!-- Upload form -->
    <form action="<?= base_url('master/upload_item'); ?>" method="post" enctype="multipart/form-data" class="mb-4">
        <div class="form-group">
            <label for="file">Select Excel File (.xlsx)</label>
            <input type="file" name="file" id="file" class="form-control" accept=".xlsx" required>
        </div>
        <button type="submit" name="preview" value="1" class="btn btn-primary">Preview</button>
    </form>

    <!-- Preview table -->
    <?php if (!empty($preview_data)): ?>
        <h5>Preview Data</h5>
        <form action="<?= base_url('master/confirm_upload_item'); ?>" method="post">
            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Name</th>
                            <th>Category ID</th>
                            <th>Unit ID</th>
                            <th>Brand ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($preview_data as $row): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['item_name']); ?></td>
                                <td><?= htmlspecialchars($row['category_id']); ?></td>
                                <td><?= htmlspecialchars($row['unit_id']); ?></td>
                                <td><?= htmlspecialchars($row['brand_id']); ?></td>
                            </tr>
                            <!-- Hidden inputs to pass data -->
                            <input type="hidden" name="item_name[]" value="<?= htmlspecialchars($row['item_name']); ?>">
                            <input type="hidden" name="category_id[]" value="<?= htmlspecialchars($row['category_id']); ?>">
                            <input type="hidden" name="unit_id[]" value="<?= htmlspecialchars($row['unit_id']); ?>">
                            <input type="hidden" name="brand_id[]" value="<?= htmlspecialchars($row['brand_id']); ?>">
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
            <hr>
            <button type="submit" class="btn btn-success mb-3"
                onclick="return confirm('Are you sure you want to import this data?');">
                Confirm & Import
            </button>
        </form>
    <?php elseif ($this->input->post('preview')): ?>
        <div class="alert alert-warning">No data found in file or wrong format.</div>
    <?php endif; ?>
</div>
</div>