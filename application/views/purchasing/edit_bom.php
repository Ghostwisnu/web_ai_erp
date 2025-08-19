<?php
// Pre-fill FG, Brand, Art Color, and Materials from selected BOM
$fgItem = !empty($bom) ? $bom[0] : null;
$fgItemId = $fgItem['id_fg_item'] ?? '';
$fgItemName = $fgItem['fg_item_name'] ?? '';
$fgUnit = $fgItem['fg_unit'] ?? '';
$fgCategoryName = $fgItem['fg_item_category'] ?? '';
$brandName = $fgItem['brand_name'] ?? '';
$artColor = $fgItem['artcolor_name'] ?? '';
?>
<style>
    /* Wide, scrollable Semantic UI modal */
    #newFgModal {
        max-width: 95% !important;
        /* almost full width */
        width: auto !important;
        max-height: 90vh;
        /* limit height */
        overflow-y: auto;
        /* vertical scroll if content too tall */
    }

    /* Segment inside modal allows horizontal scroll */
    #newFgModal .ui.segment {
        overflow-x: auto;
        /* horizontal scroll for wide tables */
        padding: 0.5em 1em;
    }

    /* Tables keep original layout, not responsive */
    #newFgModal table.ui.celled.table {
        min-width: 800px;
        /* prevent shrinking */
        width: auto;
        /* use natural table width */
    }

    /* Wide, scrollable Semantic UI modal for all modals */
    .wide-scrollable-modal {
        max-width: 95% !important;
        /* almost full width */
        width: auto !important;
        max-height: 90vh;
        /* vertical limit */
        overflow-y: auto;
        /* vertical scroll if content too tall */
    }

    .wide-scrollable-modal .ui.segment {
        overflow-x: auto;
        /* horizontal scroll for wide tables */
        padding: 0.5em 1em;
    }

    .wide-scrollable-modal table.ui.celled.table {
        min-width: 800px;
        /* prevent shrinking */
        width: auto;
    }
</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg">
            <?= form_error('purchasing', '<div class="alert alert-danger" role="alert">', '</div') ?>
            <?= $this->session->flashdata('message'); ?>

            <!-- Back to Purchasing page -->
            <a href="<?= base_url('purchasing'); ?>" class="ui blue button mb-3">
                <i class="arrow left icon"></i> Back to Purchasing
            </a>

            <!-- FG Selector -->
            <button type="button" class="ui blue button" id="openFgBtn">
                Change FG
            </button>

            <!-- FG Info -->
            <div class="ui form">
                <div class="fields">
                    <div class="six wide field">
                        <label>FG DESCRIPTION</label>
                        <input type="text" id="fgDescription" class="form-control"
                            value="<?= $bom_header['fg_item_name'] ?>" readonly>
                        <input type="hidden" id="kode_bom" value="<?= $kode_bom ?>">
                        <input type="hidden" name="fgItemId" id="fgItemId" value="<?= $bom_header['id_fg_item'] ?>">
                        <input type="hidden" id="fgCategoryName" value="<?= $bom_header['fg_item_category'] ?>">
                    </div>
                    <div class="three wide field">
                        <label>UNIT</label>
                        <input type="text" id="fgUnit" class="form-control"
                            value="<?= $bom_header['fg_unit'] ?>" readonly>
                    </div>
                    <div class="four wide field">
                        <label>BRAND</label>
                        <div class="ui action input">
                            <input type="text" id="brandName" value="<?= $bom_header['brand_name'] ?>" readonly>
                            <button type="button" class="ui teal button" id="openBrandModal">
                                <i class="plus icon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="seven wide field">
                        <label>Art Color</label>
                        <div class="ui action input">
                            <input type="text" id="artColor" name="art_color"
                                value="<?= $bom_header['artcolor_name'] ?>" readonly>
                            <button type="button" class="ui teal button" id="openArtColorModal">
                                <i class="plus icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="ui clearing segment" id="materialActions">
                <div class="ui buttons">
                    <button type="button" class="ui blue button" id="openMaterialModal">
                        <i class="plus icon"></i> ADD MATERIAL
                    </button>
                    <a href="#" id="deleteSelected" class="ui red button">
                        <i class="trash icon"></i> DELETE MATERIAL
                    </a>
                    <a href="#" id="undeleteSelected" class="ui orange button">
                        <i class="undo icon"></i> UNDELETE MATERIAL
                    </a>
                </div>
            </div>

            <!-- BOM Table -->
            <table class="table table-hover" id="bomTable">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>MATERIAL DESCRIPTION</th>
                        <th>CATEGORY</th>
                        <th>UNIT</th>
                        <th>QTY</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bom_materials)) : ?>
                        <?php foreach ($bom_materials as $i => $mat): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><input type="checkbox" class="rowCheckbox" value="<?= $mat['id_mt_item'] ?>"></td>
                                <td><?= $mat['mt_item_name'] ?></td>
                                <td><?= $mat['mt_item_category'] ?></td>
                                <td><?= $mat['mt_unit'] ?></td>
                                <td><input type="number" class="form-control qty-input"
                                        data-material-id="<?= $mat['id_mt_item'] ?>"
                                        value="<?= $mat['bom_qty'] ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Save Button -->
            <div id="saveBomContainer" style="margin-top:15px;">
                <button id="saveEditBom" class="ui green button">
                    <i class="save icon"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End of Main Content -->

<!-- Modal -->
<div class="ui modal" id="newFgModal">
    <i class="close icon"></i>
    <div class="header">Select Item</div>

    <div class="ui top attached tabular menu">
        <a class="active item" data-tab="finishGoods">FINISH GOODS</a>
        <a class="item" data-tab="halfFinishGoods">HALF FINISH GOODS</a>
    </div>

    <div class="ui bottom attached tab segment active" data-tab="finishGoods">
        <div class="ui segment">
            <table class="ui celled table" id="finishTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Select</th>
                        <th>ITEM NAME</th>
                        <th>CATEGORY</th>
                        <th>UNIT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($finish_goods as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <button
                                    class="ui tiny blue button select-item"
                                    data-item-id="<?= $item['item_id'] ?>"
                                    data-item-name="<?= $item['item_name'] ?>"
                                    data-category-id="<?= $item['category_id'] ?>"
                                    data-category-name="<?= $item['category_name'] ?>"
                                    data-unit-id="<?= $item['unit_id'] ?>"
                                    data-unit-name="<?= $item['unit_name'] ?>">✔ Select</button>
                            </td>
                            <td><?= $item['item_name'] ?></td>
                            <td><?= $item['category_name'] ?></td>
                            <td><?= $item['unit_name'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="ui bottom attached tab segment" data-tab="halfFinishGoods">
        <div class="ui segment">
            <table class="ui celled table" id="halfFinishTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Select</th>
                        <th>ITEM NAME</th>
                        <th>CATEGORY</th>
                        <th>UNIT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($half_finish_goods as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <button
                                    class="ui tiny blue button select-item"
                                    data-item-id="<?= $item['item_id'] ?>"
                                    data-item-name="<?= $item['item_name'] ?>"
                                    data-category-id="<?= $item['category_id'] ?>"
                                    data-category-name="<?= $item['category_name'] ?>"
                                    data-unit-id="<?= $item['unit_id'] ?>"
                                    data-unit-name="<?= $item['unit_name'] ?>">✔ Select</button>
                            </td>
                            <td><?= $item['item_name'] ?></td>
                            <td><?= $item['category_name'] ?></td>
                            <td><?= $item['unit_name'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="ui modal" id="artColorModal">
    <div class="header">Add Art Color</div>
    <div class="content">
        <form id="artColorForm" class="ui form">
            <div class="two fields">
                <div class="field">
                    <label>Art</label>
                    <input type="text" id="artInput" placeholder="Enter Art">
                </div>
                <div class="field">
                    <label>Color</label>
                    <input type="text" id="colorInput" placeholder="Enter Color">
                </div>
            </div>
            <button type="submit" class="ui primary button">OK</button>
        </form>
    </div>
</div>

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
                <?php foreach ($master_brand as $i => $brand): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <button
                                class="ui tiny blue button select-brand"
                                data-brand-id="<?= $brand['id_brand'] ?>"
                                data-brand-name="<?= $brand['brand_name'] ?>">✔ Select</button>
                        </td>
                        <td><?= $brand['brand_name'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="ui modal wide-scrollable-modal" id="materialModal">
    <i class="close icon"></i>
    <div class="header">Select Material</div>

    <!-- Tabs -->
    <div class="ui top attached tabular menu">
        <a class="active item" data-tab="materialTab">MATERIAL</a>
        <a class="item" data-tab="semiFinishedTab">BARANG SETENGAH JADI</a>
    </div>

    <!-- Material Tab -->
    <div class="ui bottom attached tab segment active" data-tab="materialTab">
        <div class="ui segment">
            <table class="ui celled table" id="materialOnlyTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Select</th>
                        <th>ITEM NAME</th>
                        <th>CATEGORY</th>
                        <th>UNIT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($material_items as $i => $mat): ?>
                        <?php if ($mat['category_name'] == 'Material'): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <button
                                        class="ui tiny blue button select-material"
                                        data-item-id="<?= $mat['item_id'] ?>"
                                        data-item-name="<?= $mat['item_name'] ?>"
                                        data-unit-id="<?= $mat['unit_id'] ?>"
                                        data-unit-name="<?= $mat['unit_name'] ?>"
                                        data-category-id="<?= $mat['category_id'] ?>"
                                        data-category-name="<?= $mat['category_name'] ?>">✔ Select</button>
                                </td>
                                <td><?= $mat['item_name'] ?></td>
                                <td><?= $mat['category_name'] ?></td>
                                <td><?= $mat['unit_name'] ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Semi-Finished Tab -->
    <div class="ui bottom attached tab segment" data-tab="semiFinishedTab">
        <div class="ui segment">
            <table class="ui celled table" id="semiFinishedTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Select</th>
                        <th>ITEM NAME</th>
                        <th>CATEGORY</th>
                        <th>UNIT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($material_items as $i => $mat): ?>
                        <?php if ($mat['category_name'] == 'Barang Setengah Jadi'): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <button
                                        class="ui tiny blue button select-material"
                                        data-item-id="<?= $mat['item_id'] ?>"
                                        data-item-name="<?= $mat['item_name'] ?>"
                                        data-unit-id="<?= $mat['unit_id'] ?>"
                                        data-unit-name="<?= $mat['unit_name'] ?>"
                                        data-category-id="<?= $mat['category_id'] ?>"
                                        data-category-name="<?= $mat['category_name'] ?>">✔ Select</button>
                                </td>
                                <td><?= $mat['item_name'] ?></td>
                                <td><?= $mat['category_name'] ?></td>
                                <td><?= $mat['unit_name'] ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

<script>
    $(document).ready(function() {

        // ============================
        // Reusable function to init modal with tabs and tables
        // ============================
        function initModalWithTabs(modalId, tableIds = []) {
            const $modal = $(modalId);

            // Initialize Semantic UI modal
            $modal.modal({
                closable: true,
                autofocus: false,
                transition: 'scale',
                onShow: function() {
                    $.fn.dataTable.tables({
                        visible: true,
                        api: true
                    }).columns.adjust();
                }
            });

            // Initialize tabs inside modal
            $modal.find('.menu .item').tab({
                onVisible: function(tabPath) {
                    $.fn.dataTable.tables({
                        visible: true,
                        api: true
                    }).columns.adjust();
                }
            });

            // Initialize DataTables
            tableIds.forEach(function(tableId) {
                $(tableId).DataTable({
                    responsive: false,
                    scrollX: true
                });
            });
        }

        // ============================
        // Initialize all modals
        // ============================
        initModalWithTabs('#newFgModal', ['#finishTable', '#halfFinishTable']);
        initModalWithTabs('#materialModal', ['#materialOnlyTable', '#semiFinishedTable']);

        $('#brandModal').modal({
            closable: true,
            autofocus: false,
            transition: 'scale'
        });
        $('#brandTable').DataTable({
            responsive: false,
            scrollX: true
        });

        $('#artColorModal').modal({
            closable: true,
            autofocus: false,
            transition: 'scale'
        });

        // ============================
        // Open modal triggers
        // ============================
        $('#openFgBtn, #fgDescription, #fgUnit').on('click', function() {
            $('#newFgModal').modal('show');
        });
        $('#openMaterialModal').on('click', function() {
            $('#materialModal').modal('show');
        });
        $('#openBrandModal, #brandName').on('click', function() {
            $('#brandModal').modal('show');
        });
        $('#openArtColorModal').on('click', function() {
            $('#artColorModal').modal('show');
        });

        // ============================
        // FG Selection
        // ============================
        $('#newFgModal').on('click', '.select-item', function() {
            const btn = $(this);
            $('#fgDescription').val(btn.data('item-name'));
            $('#fgUnit').val(btn.data('unit-name'));
            $('#fgItemId').val(btn.data('item-id'));
            $('#fgCategoryName').val(btn.data('category-name'));
            $('#fgCategoryId').val(btn.data('category-id'));
            $('#fgUnitId').val(btn.data('unit-id'));

            $('#materialActions').show();
            $('#newFgModal').modal('hide');
        });

        // ============================
        // Material Selection
        // ============================
        $('#materialModal').on('click', '.select-material', function() {
            const btn = $(this);
            const row = `
            <tr>
                <td>*</td>
                <td><input type="checkbox" class="rowCheckbox"></td>
                <td>${btn.data('item-name')}</td>
                <td>${btn.data('category-name')}</td>
                <td>${btn.data('unit-name')}</td>
                <td><input type="number" class="form-control material-qty" value="1"></td>
                <td style="display:none;" class="material-id">${btn.data('item-id')}</td>
                <td style="display:none;" class="material-name">${btn.data('item-name')}</td>
                <td style="display:none;" class="unit-id">${btn.data('unit-id')}</td>
                <td style="display:none;" class="category-id">${btn.data('category-id')}</td>
            </tr>`;
            $('#bomTable tbody').append(row);
            $('#materialModal').modal('hide');
            $('#saveBomContainer').show();
        });

        // ============================
        // Brand Selection
        // ============================
        $('#brandModal').on('click', '.select-brand', function() {
            const btn = $(this);
            $('#brandName').val(btn.data('brand-name'));
            $('#brandId').val(btn.data('brand-id'));
            $('#brandModal').modal('hide');
        });

        // ============================
        // Art Color Form
        // ============================
        $('#artColorForm').on('submit', function(e) {
            e.preventDefault();
            const art = $('#artInput').val().trim();
            const color = $('#colorInput').val().trim();
            if (!art || !color) {
                alert("Both Art and Color are required.");
                return;
            }

            $('#artColor').val(`${art} - ${color}`);
            $('#artColorModal').modal('hide');
            $('#artInput, #colorInput').val('');
        });

        // ============================
        // BOM Table logic
        // ============================
        $('#bomTable').DataTable({
            paging: true,
            ordering: true,
            info: false
        });

        // Select All
        $('#selectAll').on('change', function() {
            $('.rowCheckbox').prop('checked', $(this).prop('checked'));
        });

        // Delete selected rows
        $('#deleteSelected').on('click', function() {
            const ids = $('.rowCheckbox:checked').map(function() {
                return $(this).val();
            }).get();
            if (!ids.length) return alert('Please select at least one material to delete.');
            if (confirm('Are you sure you want to delete selected materials?')) {
                $.post("<?= base_url('bom/delete_bulk_material') ?>", {
                    ids: ids
                }, function() {
                    location.reload();
                });
            }
        });

        // Save / Update BOM  (REPLACE THIS WHOLE BLOCK)
        $('#saveEditBom').on('click', function() {
            // Read FG/header fields from your current view
            const kodeBom = $('#kode_bom').val(); // <input type="hidden" id="kode_bom" ...>
            const fgItemId = $('#fgItemId').val(); // <input type="hidden" id="fgItemId" ...>
            const fgItemName = $('#fgDescription').val(); // visible text input with FG description/name
            const fgUnit = $('#fgUnit').val(); // visible text input
            const fgCategoryName = $('#fgCategoryName').val(); // <input type="hidden" id="fgCategoryName" ...>
            const brandName = $('#brandName').val();
            const artColor = $('#artColor').val();

            // Collect materials from the table (supports both existing and newly added rows)
            let materials = [];
            $('#bomTable tbody tr').each(function() {
                const $tr = $(this);

                // Case A: existing BOM rows loaded from PHP use .qty-input with data-material-id
                const $qtyExisting = $tr.find('input.qty-input');

                // Case B: rows added from the "Select Material" modal use .material-qty and hidden td cells
                const $qtyNew = $tr.find('input.material-qty');

                let material_id = null,
                    material_name = '',
                    unit_name = '',
                    category_name = '',
                    qty = null;

                if ($qtyExisting.length) {
                    qty = $qtyExisting.val();
                    material_id = $qtyExisting.data('material-id');
                    material_name = $tr.find('td:eq(2)').text().trim(); // MATERIAL DESCRIPTION
                    category_name = $tr.find('td:eq(3)').text().trim(); // CATEGORY
                    unit_name = $tr.find('td:eq(4)').text().trim(); // UNIT
                } else if ($qtyNew.length) {
                    qty = $qtyNew.val();
                    material_id = ($tr.find('td.material-id').text() || '').trim();
                    material_name = ($tr.find('td.material-name').text() || $tr.find('td:eq(2)').text() || '').trim();
                    category_name = ($tr.find('td:eq(3)').text() || '').trim();
                    unit_name = ($tr.find('td:eq(4)').text() || '').trim();
                }

                if (material_id && qty && parseFloat(qty) > 0) {
                    materials.push({
                        material_id: material_id,
                        material_name: material_name,
                        unit_name: unit_name,
                        category_name: category_name,
                        qty: qty
                    });
                }
            });

            // Basic guard (keeps the same validation as your controller)
            if (!fgItemId || !kodeBom || materials.length === 0) {
                alert('FG, Kode BOM, and at least one material are required.');
                console.log({
                    fgItemId,
                    kodeBom,
                    materials
                }); // help you debug quickly
                return;
            }

            // Build payload with keys EXACTLY as your controller expects
            const payload = {
                kode_bom: kodeBom,
                fg_item_id: fgItemId,
                fg_item_name: fgItemName,
                fg_unit_name: fgUnit,
                fg_category_name: fgCategoryName,
                brand_name: brandName,
                art_color: artColor,
                materials: materials
            };

            // Send JSON
            $.ajax({
                url: "<?= base_url('purchasing/save_edit_bom') ?>",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(payload),
                success: function(resp) {
                    let res = typeof resp === 'object' ? resp : {};
                    try {
                        if (!Object.keys(res).length) res = JSON.parse(resp);
                    } catch (e) {}
                    if (res.status === "success") {
                        alert("BOM updated successfully!");
                        window.location.reload();
                    } else {
                        alert(res.message || 'Failed to update BOM.');
                        console.log('Server response:', resp);
                    }
                },
                error: function(xhr) {
                    alert("Error while updating BOM.");
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>