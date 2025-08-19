<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg">
            <?= form_error('item', '<div class="alert alert-danger" role="alert">', '</div') ?>
            <?= $this->session->flashdata('message'); ?>
            <!-- Button trigger modal -->
            <a href="" class="ui blue button mb-3" data-toggle="modal" data-target="#newItemModal"><i class="plus icon"></i>Add New Item</a>
            <a href="<?= base_url('master/upload_item'); ?>" class="ui blue button mb-3"><i class="upload icon"></i>Upload</a>
            <!-- <div class="table-responsive"> -->
            <table class="table table-hover" id="myTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">KODE ITEM</th> <!-- New -->
                        <th scope="col">DESCRIPTION</th>
                        <th scope="col">CATEGORY</th>
                        <th scope="col">BRAND</th>
                        <th scope="col">UNIT</th>
                        <th scope="col">CREATED AT</th>
                        <th scope="col">CREATED BY</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($item as $it): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $it['kode_item']; ?></td>
                            <td><?= $it['item_name']; ?></td>
                            <td><?= $it['category_name']; ?></td>
                            <td><?= $it['brand_name']; ?></td>
                            <td><?= $it['unit_name']; ?></td>
                            <td><?= $it['created_at']; ?></td>
                            <td><?= $it['created_by']; ?></td>
                            <td>
                                <a href="<?= base_url('master/delete_item/' . $it['id_item']); ?>" class="badge badge-danger" onclick="return confirm('Are you sure you want to delete this category?');"><i class="trash icon"></i> Delete</a>
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
<div class="modal fade" id="newItemModal" tabindex="-1" aria-labelledby="newItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newItemModalLabel">Add New Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('master/item'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter item name" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id_category']; ?>"><?= $cat['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="brand_id">Brand</label>
                        <select class="form-control" id="brand_id" name="brand_id">
                            <option value="">Select Brand</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand['id_brand']; ?>"><?= $brand['brand_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <select class="form-control" id="unit_id" name="unit_id" required>
                            <option value="">Select Unit</option>
                            <?php foreach ($units as $unit): ?>
                                <option value="<?= $unit['id_unit']; ?>"><?= $unit['unit_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function generateKode() {
        let category = $("#category_id option:selected").text();
        let brandId = $("#brand_id").val();

        let prefix = "";
        if (category.toLowerCase().includes("finish")) prefix = "FG";
        else if (category.toLowerCase().includes("half")) prefix = "HFG";
        else prefix = "MT";

        if (brandId) {
            // This is only a preview — actual unique code generated in PHP model
            $("#kode_item").val(prefix + "-" + brandId + "-XXXX");
        } else {
            $("#kode_item").val("");
        }
    }

    $("#category_id, #brand_id").change(generateKode);
</script>