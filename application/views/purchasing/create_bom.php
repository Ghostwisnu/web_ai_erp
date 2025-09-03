<style>
    /* Wide, scrollable Semantic UI modal */
    .wide-scrollable-modal {
        max-width: 95% !important;
        width: auto !important;
        max-height: 90vh;
        overflow-y: auto;
    }

    .wide-scrollable-modal .ui.segment {
        overflow-x: auto;
        padding: 0.5em 1em;
    }

    .wide-scrollable-modal table.ui.celled.table {
        min-width: 800px;
        width: auto;
    }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <?= form_error('purchasing', '<div class="alert alert-danger" role="alert">', '</div>') ?>
    <?= $this->session->flashdata('message'); ?>

    <!-- Back and Select FG -->
    <a href="<?= base_url('purchasing'); ?>" class="ui blue button mb-3"><i class="arrow left icon"></i> Back</a>
    <button type="button" class="ui blue button" id="openFgBtn">Choose / Change FG</button>

    <!-- FG Info -->
    <div class="ui form mt-3">
        <div class="fields">
            <div class="six wide field">
                <label>FG DESCRIPTION</label>
                <input type="text" id="fgDescription" readonly>
                <input type="hidden" id="fgItemId">
                <input type="hidden" id="fgCategoryId">
                <input type="hidden" id="fgCategoryName">
                <input type="hidden" id="fgUnitId">
                <input type="hidden" id="fgKodeItem">
            </div>
            <div class="three wide field">
                <label>UNIT</label>
                <input type="text" id="fgUnit" readonly>
            </div>
            <div class="four wide field">
                <label>BRAND</label>
                <div class="ui action input">
                    <input type="text" id="brandName" readonly>
                    <button type="button" class="ui teal button" id="openBrandModal"><i class="plus icon"></i></button>
                </div>
            </div>
            <div class="seven wide field">
                <label>Art Color</label>
                <div class="ui action input">
                    <input type="text" id="artColor" readonly>
                    <button type="button" class="ui teal button" id="openArtColorModal"><i class="plus icon"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- HFG Section -->
    <button type="button" class="ui blue button mt-2" id="openHfgBtn">Add HFG</button>
    <table class="ui celled table" id="hfgTable">
        <thead>
            <tr>
                <th>#</th>
                <th>HFG NAME</th>
                <th>UNIT</th>
                <th>Materials</th>
            </tr>
        </thead>
        <tbody>
            <!-- HFG rows populated via JS -->
        </tbody>
    </table>

    <!-- Save BOM -->
    <button class="ui green button" id="saveEntireBomBtn"><i class="save icon"></i> Save Entire BOM</button>
</div>

<!-- FG Modal -->
<div class="ui modal wide-scrollable-modal" id="fgModal">
    <i class="close icon"></i>
    <div class="header">Select Finish Good</div>
    <div class="ui segment">
        <table class="ui celled table" id="finishGoodsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Select</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Kode Item</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($finish_goods as $i => $fg): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <button class="ui tiny blue button select-fg"
                                data-id="<?= $fg['item_id'] ?>"
                                data-name="<?= $fg['item_name'] ?>"
                                data-unit-id="<?= $fg['unit_id'] ?>"
                                data-unit-name="<?= $fg['unit_name'] ?>"
                                data-cat-id="<?= $fg['category_id'] ?>"
                                data-cat-name="<?= $fg['category_name'] ?>"
                                data-kode-item="<?= $fg['kode_item'] ?>">✔ Select</button>
                        </td>
                        <td><?= $fg['item_name'] ?></td>
                        <td><?= $fg['category_name'] ?></td>
                        <td><?= $fg['unit_name'] ?></td>
                        <td><?= $fg['kode_item'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- HFG Modal -->
<div class="ui modal wide-scrollable-modal" id="hfgModal">
    <i class="close icon"></i>
    <div class="header">Select Half Finish Good</div>
    <div class="ui segment">
        <table class="ui celled table" id="halfFinishGoodsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Select</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Kode Item</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($half_finish_goods as $i => $hfg): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <button class="ui tiny blue button select-hfg"
                                data-id="<?= $hfg['item_id'] ?>"
                                data-name="<?= $hfg['item_name'] ?>"
                                data-unit-id="<?= $hfg['unit_id'] ?>"
                                data-unit-name="<?= $hfg['unit_name'] ?>"
                                data-cat-id="<?= $hfg['category_id'] ?>"
                                data-cat-name="<?= $hfg['category_name'] ?>"
                                data-kode-item="<?= $hfg['kode_item'] ?>">✔ Select</button>
                        </td>
                        <td><?= $hfg['item_name'] ?></td>
                        <td><?= $hfg['category_name'] ?></td>
                        <td><?= $hfg['unit_name'] ?></td>
                        <td><?= $hfg['kode_item'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Material Modal -->
<div class="ui modal wide-scrollable-modal" id="materialModal">
    <i class="close icon"></i>
    <div class="header">Select Material / HFG</div>

    <div class="ui top attached tabular menu">
        <a class="item active" data-tab="material-tab">Materials</a>
        <a class="item" data-tab="hfg-tab">Half Finished Goods (HFG)</a>
    </div>

    <!-- Material Tab -->
    <div class="ui bottom attached tab segment active" data-tab="material-tab">
        <table class="ui celled table" id="materialItemsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Qty</th>
                    <th>Select</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Kode Item</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($material_items as $i => $mt): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><input type="number" min="1" value="1" class="modal-qty" data-id="<?= $mt['item_id'] ?>"></td>
                        <td>
                            <button class="ui tiny blue button select-material"
                                data-id="<?= $mt['item_id'] ?>"
                                data-name="<?= $mt['item_name'] ?>"
                                data-unit-id="<?= $mt['unit_id'] ?>"
                                data-unit-name="<?= $mt['unit_name'] ?>"
                                data-cat-id="<?= $mt['category_id'] ?>"
                                data-cat-name="<?= $mt['category_name'] ?>"
                                data-kode-item="<?= $mt['kode_item'] ?>">✔ Select</button>
                        </td>
                        <td><?= $mt['item_name'] ?></td>
                        <td><?= $mt['category_name'] ?></td>
                        <td><?= $mt['unit_name'] ?></td>
                        <td><?= $mt['kode_item'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- HFG Tab -->
    <div class="ui bottom attached tab segment" data-tab="hfg-tab">
        <table class="ui celled table" id="materialHfgTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Qty</th>
                    <th>Select</th>
                    <th>HFG Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Kode Item</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($half_finish_goods as $i => $hfg): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><input type="number" min="1" value="1" class="modal-qty" data-id="<?= $hfg['item_id'] ?>"></td>
                        <td>
                            <button class="ui tiny blue button select-hfg-as-material"
                                data-id="<?= $hfg['item_id'] ?>"
                                data-name="<?= $hfg['item_name'] ?>"
                                data-unit-id="<?= $hfg['unit_id'] ?>"
                                data-unit-name="<?= $hfg['unit_name'] ?>"
                                data-cat-id="<?= $hfg['category_id'] ?>"
                                data-cat-name="<?= $hfg['category_name'] ?>"
                                data-kode-item="<?= $hfg['kode_item'] ?>">✔ Select</button>
                        </td>
                        <td><?= $hfg['item_name'] ?></td>
                        <td><?= $hfg['category_name'] ?></td>
                        <td><?= $hfg['unit_name'] ?></td>
                        <td><?= $hfg['kode_item'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Brand Modal -->
<div class="ui modal wide-scrollable-modal" id="brandModal">
    <i class="close icon"></i>
    <div class="header">Select Brand</div>
    <div class="ui segment">
        <table class="ui celled table" id="brandTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Select</th>
                    <th>Brand Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($master_brand as $i => $b): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><button class="ui tiny blue button select-brand" data-name="<?= $b['brand_name'] ?>">✔ Select</button></td>
                        <td><?= $b['brand_name'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ArtColor Modal -->
<div class="ui modal wide-scrollable-modal" id="artColorModal">
    <i class="close icon"></i>
    <div class="header">Input Art and Color</div>
    <div class="ui segment">
        <div class="ui form">
            <div class="two fields">
                <div class="field">
                    <label>Art</label>
                    <input type="text" id="artInput">
                </div>
                <div class="field">
                    <label>Color</label>
                    <input type="text" id="colorInput">
                </div>
            </div>
            <button type="button" class="ui green button" id="saveArtColorBtn">Save</button>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {

        let hfgData = [];

        // Show modals
        $('#openFgBtn').click(() => $('#fgModal').modal('show'));
        $('#openHfgBtn').click(() => $('#hfgModal').modal('show'));
        $('#openBrandModal').click(() => $('#brandModal').modal('show'));
        $('#openArtColorModal').click(() => $('#artColorModal').modal('show'));
        // Initialize searchable tables inside the Material Modal
        $('#materialItemsTable').DataTable({
            paging: true,
            searching: true, // enables search
            info: false,
            lengthChange: false
        });

        $('#materialHfgTable').DataTable({
            paging: true,
            searching: true,
            info: false,
            lengthChange: false
        });


        // Initialize Material modal tabs
        $('#materialModal .menu .item').tab();

        // FG select
        $('.select-fg').click(function() {
            $('#fgItemId').val($(this).data('id'));
            $('#fgDescription').val($(this).data('name'));
            $('#fgUnit').val($(this).data('unit-name'));
            $('#fgCategoryId').val($(this).data('cat-id'));
            $('#fgCategoryName').val($(this).data('cat-name'));
            $('#fgKodeItem').val($(this).data('kode-item'));
            $('#fgModal').modal('hide');
        });

        // HFG select
        $(document).on('click', '.select-hfg', function() {
            if (hfgData.length >= 6) {
                alert("Maximum 6 HFGs allowed.");
                return;
            }
            let hfg = {
                id: $(this).data('id'),
                name: $(this).data('name'),
                unit: $(this).data('unit-name'),
                catName: $(this).data('cat-name'),
                kode: $(this).data('kode-item'),
                materials: []
            };
            hfgData.push(hfg);
            renderHfgTable();
            $('#hfgModal').modal('hide');
        });

        function renderHfgTable() {
            let tbody = '';
            hfgData.forEach((hfg, index) => {
                tbody += `<tr>
            <td>${index+1}</td>
            <td>${hfg.name}</td>
            <td>${hfg.unit}</td>
            <td>
                <button class="ui tiny blue button add-material-btn" data-index="${index}">Add Material</button>
                <button class="ui tiny red button delete-hfg-btn" data-hfg="${index}">Delete HFG</button>
                <table class="ui celled table mt-material" style="margin-top:5px;">
                    <thead><tr><th>Material</th><th>Qty</th><th>Action</th></tr></thead>
                    <tbody>`;
                hfg.materials.forEach((mt, mi) => {
                    tbody += `<tr>
                <td>${mt.name}</td>
                <td><input type="number" min="0.0001" step="0.0001" value="${mt.qty}" class="material-qty" data-hfg="${index}" data-mt="${mi}"></td>
                <td><button class="ui tiny red button delete-material-btn" data-hfg="${index}" data-mt="${mi}">Delete</button></td>
            </tr>`;
                });
                tbody += `</tbody></table>
            </td>
        </tr>`;
            });
            $('#hfgTable tbody').html(tbody);
        }
        // Delete HFG
        $(document).on('click', '.delete-hfg-btn', function() {
            let hfgIdx = $(this).data('hfg');
            if (confirm("Are you sure you want to delete this HFG?")) {
                hfgData.splice(hfgIdx, 1); // remove HFG
                renderHfgTable(); // re-render table
            }
        });

        // Delete material
        $(document).on('click', '.delete-material-btn', function() {
            let hfgIdx = $(this).data('hfg');
            let mtIdx = $(this).data('mt');
            hfgData[hfgIdx].materials.splice(mtIdx, 1); // remove material
            renderHfgTable(); // re-render table
        });

        // Show Material modal when adding material to HFG
        $(document).on('click', '.add-material-btn', function() {
            $('#materialModal').modal('show').data('hfgIndex', $(this).data('index'));
            // Default to Materials tab
            $('#materialModal .menu .item').tab('change tab', 'material-tab');
        });

        // Select material (Material tab or HFG-as-material tab)
        $(document).on('click', '.select-material, .select-hfg-as-material', function() {
            let idx = $('#materialModal').data('hfgIndex');
            if (idx === undefined) return;

            let qty = $(this).closest('tr').find('.modal-qty').val() || 1;
            let mt = {
                id: $(this).data('id'),
                name: $(this).data('name'),
                unit: $(this).data('unit-name'),
                catName: $(this).data('cat-name'),
                kode: $(this).data('kode-item'),
                qty: parseFloat(qty)
            };

            // Prevent duplicates
            let exists = hfgData[idx].materials.some(m => m.id === mt.id && m.kode === mt.kode);
            if (!exists) {
                hfgData[idx].materials.push(mt);
                renderHfgTable();
            } else {
                alert("This material is already added for this HFG.");
            }

            $('#materialModal').modal('hide');
        });

        // Update material qty
        $(document).on('change', '.material-qty', function() {
            let hfgIdx = $(this).data('hfg');
            let mtIdx = $(this).data('mt');
            hfgData[hfgIdx].materials[mtIdx].qty = parseFloat($(this).val());
        });

        // Brand select
        $('.select-brand').click(function() {
            $('#brandName').val($(this).data('name'));
            $('#brandModal').modal('hide');
        });

        // ArtColor save
        $('#saveArtColorBtn').click(function() {
            let art = $('#artInput').val();
            let color = $('#colorInput').val();
            $('#artColor').val(`${art} - ${color}`);
            $('#artColorModal').modal('hide');
        });

        // Save Entire BOM
        $('#saveEntireBomBtn').click(function() {
            const fgId = $('#fgItemId').val();
            if (!fgId) {
                alert("Select FG first.");
                return;
            }
            if (hfgData.length === 0) {
                alert("Add at least one HFG.");
                return;
            }

            let bomData = [];
            hfgData.forEach(hfg => {
                hfg.materials.forEach(mt => {
                    bomData.push({
                        id_fg_item: fgId,
                        id_hfg_item: hfg.id,
                        id_mt_item: mt.id,
                        fg_kode_item: $('#fgKodeItem').val(),
                        hfg_kode_item: hfg.kode,
                        mt_kode_item: mt.kode,
                        fg_item_name: $('#fgDescription').val(),
                        hfg_item_name: hfg.name,
                        mt_item_name: mt.name,
                        fg_item_category: $('#fgCategoryName').val(),
                        hfg_item_category: hfg.catName,
                        mt_item_category: mt.catName,
                        fg_unit: $('#fgUnit').val(),
                        hfg_unit: hfg.unit,
                        mt_unit: mt.unit,
                        brand_name: $('#brandName').val(),
                        artcolor_name: $('#artColor').val(),
                        bom_qty: mt.qty
                    });
                });
            });

            $.ajax({
                url: "<?= base_url('purchasing/save_bom') ?>",
                type: "POST",
                data: JSON.stringify(bomData),
                contentType: "application/json",
                success: function() {
                    alert("BOM saved!");
                    location.reload();
                },
                error: function() {
                    alert("Error saving BOM.");
                }
            });
        });

    });
</script>