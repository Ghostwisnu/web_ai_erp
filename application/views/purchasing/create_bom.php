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

            <button type="button" class="ui blue button" id="openFgBtn">
                Choose / Change FG
            </button>
            <!-- FG Info -->
            <div class="ui form">
                <div class="fields">
                    <div class="six wide field">
                        <label>FG DESCRIPTION</label>
                        <input type="text" id="fgDescription" class="form-control" placeholder="Select FG" readonly>
                        <input type="hidden" name="fgItemId" id="fgItemId">
                        <input type="hidden" id="fgCategoryName">
                        <input type="hidden" id="fgCategoryId">
                    </div>
                    <div class="three wide field">
                        <label>UNIT</label>
                        <input type="text" id="fgUnit" class="form-control" readonly>
                        <input type="hidden" id="fgUnitId">
                    </div>
                    <div class="four wide field">
                        <label>BRAND</label>
                        <div class="ui action input">
                            <input type="hidden" id="brandId">
                            <input type="text" id="brandName" placeholder="Select Brand" readonly>
                            <button type="button" class="ui teal button" id="openBrandModal">
                                <i class="plus icon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="seven wide field">
                        <label>Art Color</label>
                        <div class="ui action input">
                            <input type="text" id="artColor" name="art_color" placeholder="Add Art Color" readonly>
                            <button type="button" class="ui teal button" id="openArtColorModal">
                                <i class="plus icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="ui clearing segment" id="materialActions" style="display:none;">
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


            <table class="table table-hover" id="bomTable">

                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th scope="col"><input type="checkbox" id="selectAll"></th>
                        <th scope="col">MATERIAL DESCRIPTION</th>
                        <th scope="col">CATEGORY</th>
                        <th scope="col">UNIT</th>
                        <th scope="col">QTY</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bom)) : ?>
                        <?php foreach ($bom as $index => $mat) : ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><input type="checkbox" class="rowCheckbox" value="<?= $mat['id_mt_item'] ?>"></td>
                                <td><?= $mat['mt_item_name'] ?></td>
                                <td><?= $mat['mt_item_category'] ?></td>
                                <td><?= $mat['mt_unit'] ?></td>
                                <td><?= $mat['bom_qty'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>

                    <?php endif; ?>
                </tbody>
            </table>
            <div id="saveBomContainer" style="display:none; margin-top:15px;">
                <button id="saveBomBtn" class="ui green button">
                    <i class="save icon"></i> Save BOM
                </button>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

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

        // ============================
        // Save BOM AJAX
        // ============================
        $('#saveBomBtn').on('click', function() {
            const fgItemId = $('#fgItemId').val();
            if (!fgItemId) {
                alert("Please select Finish Good before saving.");
                return;
            }

            const fgItemName = $('#fgDescription').val();
            const fgUnit = $('#fgUnit').val();
            const fgCategory = $('#fgCategoryName').val();
            const brandName = $('#brandName').val();
            const artColor = $('#artColor').val();

            const materials = [];
            $('#bomTable tbody tr').each(function() {
                const materialId = $(this).find('.material-id').text();
                if (!materialId) return;
                materials.push({
                    material_id: materialId,
                    material_name: $(this).find('.material-name').text(),
                    unit_name: $(this).find('td:nth-child(5)').text(),
                    category_name: $(this).find('td:nth-child(4)').text(),
                    qty: $(this).find('.material-qty').val()
                });
            });

            if (!materials.length) {
                alert("Please add at least one material.");
                return;
            }

            $.ajax({
                url: "<?= base_url('purchasing/save_bom') ?>",
                type: "POST",
                data: JSON.stringify({
                    fg_item_id: fgItemId,
                    fg_item_name: fgItemName,
                    fg_unit_name: fgUnit,
                    fg_category_name: fgCategory,
                    brand_name: brandName,
                    art_color: artColor,
                    materials: materials
                }),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(resp) {
                    alert("BOM saved successfully!");
                    location.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert("Error saving BOM. Please try again.");
                }
            });
        });

    });
</script>