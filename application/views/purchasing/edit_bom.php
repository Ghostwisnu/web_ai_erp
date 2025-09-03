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
                <input type="text" id="fgDescription" readonly value="<?= $bom_header['fg_item_name'] ?? '' ?>">
                <input type="hidden" id="fgItemId" value="<?= $bom_header['id_fg_item'] ?? '' ?>">
                <input type="hidden" id="fgCategoryId" value="<?= $bom_header['fg_item_category'] ?? '' ?>">
                <input type="hidden" id="fgCategoryName" value="<?= $bom_header['fg_item_category'] ?? '' ?>">
                <input type="hidden" id="fgUnitId" value="<?= $bom_header['fg_unit'] ?? '' ?>">
                <input type="hidden" id="fgKodeItem" value="<?= $bom_header['fg_kode_item'] ?? '' ?>">
            </div>
            <div class="three wide field">
                <label>UNIT</label>
                <input type="text" id="fgUnit" readonly value="<?= $bom_header['fg_unit'] ?? '' ?>">
            </div>
            <div class="four wide field">
                <label>BRAND</label>
                <div class="ui action input">
                    <input type="text" id="brandName" readonly value="<?= $bom_header['brand_name'] ?? '' ?>">
                    <button type="button" class="ui teal button" id="openBrandModal"><i class="plus icon"></i></button>
                </div>
            </div>
            <div class="seven wide field">
                <label>Art Color</label>
                <div class="ui action input">
                    <input type="text" id="artColor" readonly value="<?= $bom_header['artcolor_name'] ?? '' ?>">
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
            <!-- Preloaded HFG rows via JS -->
        </tbody>
    </table>

    <!-- Save BOM -->
    <button class="ui green button" id="saveEntireBomBtn"><i class="save icon"></i> Save Entire BOM</button>
</div>

<!-- Modals -->
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
                <div class="field"><label>Art</label><input type="text" id="artInput"></div>
                <div class="field"><label>Color</label><input type="text" id="colorInput"></div>
            </div>
            <button type="button" class="ui green button" id="saveArtColorBtn">Save</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // ---------- Open modals ----------
        $('#openFgBtn').on('click', () => $('#fgModal').modal('show'));
        $('#openHfgBtn').on('click', () => $('#hfgModal').modal('show'));
        $('#openBrandModal').on('click', () => $('#brandModal').modal('show'));
        $('#openArtColorModal').on('click', () => $('#artColorModal').modal('show'));

        // ---------- Build initial HFG structure from PHP ----------
        let hfgData = <?= json_encode($bom_materials); ?>;
        const groupedHfg = [];
        hfgData.forEach(b => {
            const hfgId = b.id_hfg_item || 0;
            let hfg = groupedHfg.find(h => h.id === hfgId);
            const material = {
                id_bom: b.id_bom || null, // <-- keep DB id
                id: b.id_mt_item,
                name: b.mt_item_name,
                unit: b.mt_unit,
                catName: b.mt_item_category,
                kode: b.mt_kode_item,
                qty: parseFloat(b.bom_qty, 10) || 0
            };
            if (hfg) {
                hfg.materials.push(material);
            } else {
                groupedHfg.push({
                    id: hfgId,
                    name: b.hfg_item_name || 'HFG None',
                    unit: b.hfg_unit || '',
                    catName: b.hfg_item_category || '',
                    kode: b.hfg_kode_item || '',
                    materials: [material]
                });
            }
        });
        hfgData = groupedHfg;

        // ---------- Render ----------
        function renderHfgTable() {
            let tbody = '';
            hfgData.forEach((hfg, index) => {
                tbody += `
            <tr>
                <td>${index + 1}</td>
                <td>${hfg.name}</td>
                <td>${hfg.unit}</td>
                <td>
                    <button class="ui tiny blue button add-material-btn" data-index="${index}">Add Material</button>
                    <button class="ui tiny red button delete-hfg-btn" data-hfg="${index}">Delete HFG</button>
                    <table class="ui celled table mt-material" style="margin-top:5px;">
                        <thead><tr><th>Material</th><th>Qty</th><th>Action</th></tr></thead>
                        <tbody>`;
                hfg.materials.forEach((mt, mi) => {
                    tbody += `
                    <tr>
                        <td>${mt.name}</td>
                        <td><input type="number" min="0.01" step="0.01" value="${mt.qty}" class="material-qty" data-hfg="${index}" data-mt="${mi}"></td>
                        <td><button class="ui tiny red button delete-material-btn" data-hfg="${index}" data-mt="${mi}">Delete</button></td>
                    </tr>`;
                });
                tbody += `       </tbody>
                    </table>
                </td>
            </tr>`;
            });
            $('#hfgTable tbody').html(tbody);
        }
        renderHfgTable();

        // ---------- HFG/material actions ----------
        $(document).on('click', '.delete-hfg-btn', function() {
            hfgData.splice($(this).data('hfg'), 1);
            renderHfgTable();
        });

        $(document).on('click', '.delete-material-btn', function() {
            const hfgIdx = $(this).data('hfg');
            const mtIdx = $(this).data('mt');
            hfgData[hfgIdx].materials.splice(mtIdx, 1);
            renderHfgTable();
        });

        $(document).on('input', '.material-qty', function() {
            const hfgIdx = $(this).data('hfg');
            const mtIdx = $(this).data('mt');
            hfgData[hfgIdx].materials[mtIdx].qty = parseFloat($(this).val()) || 0;
        });



        // ---------- Add HFG from modal ----------
        $(document).on('click', '.select-hfg', function() {
            const id = Number($(this).data('id'));
            const exists = hfgData.some(h => Number(h.id) === id);
            if (!exists) {
                hfgData.push({
                    id: id,
                    name: $(this).data('name') || '',
                    unit: $(this).data('unit-name') || '',
                    catName: $(this).data('cat-name') || '',
                    kode: $(this).data('kode-item') || '',
                    materials: []
                });
                renderHfgTable();
            }
            $('#hfgModal').modal('hide');
        });

        // ---------- Add material to selected HFG ----------
        $(document).on('click', '.add-material-btn', function() {
            const idx = $(this).data('index');
            $('#materialModal').modal('show').data('hfgIndex', idx);
            $('#materialModal .menu .item').tab('change tab', 'material-tab');
        });

        // Pick from Materials or HFG-as-material tabs
        $(document).on('click', '.select-material, .select-hfg-as-material', function() {
            const idx = $('#materialModal').data('hfgIndex');
            if (idx === undefined) return;

            const qty = parseFloat($(this).closest('tr').find('.modal-qty').val()) || 1;
            const mt = {
                id: Number($(this).data('id')),
                name: $(this).data('name'),
                unit: $(this).data('unit-name'),
                catName: $(this).data('cat-name'),
                kode: $(this).data('kode-item'),
                qty: qty
            };

            // prevent duplicates within the same HFG
            if (!hfgData[idx].materials.some(m => Number(m.id) === mt.id && m.kode === mt.kode)) {
                hfgData[idx].materials.push(mt);
                renderHfgTable();
            } else {
                alert('Material already exists in this HFG.');
            }
            $('#materialModal').modal('hide');
        });

        // ---------- FG / Brand / ArtColor pickers ----------
        $('.select-fg').click(function() {
            $('#fgItemId').val($(this).data('id'));
            $('#fgDescription').val($(this).data('name'));
            $('#fgUnit').val($(this).data('unit-name'));
            $('#fgCategoryId').val($(this).data('cat-id'));
            $('#fgCategoryName').val($(this).data('cat-name'));
            $('#fgKodeItem').val($(this).data('kode-item'));
            $('#fgModal').modal('hide');
        });

        $('.select-brand').click(function() {
            $('#brandName').val($(this).data('name'));
            $('#brandModal').modal('hide');
        });

        $('#saveArtColorBtn').click(function() {
            const art = $('#artInput').val() || '';
            const color = $('#colorInput').val() || '';
            $('#artColor').val(`${art} - ${color}`.trim());
            $('#artColorModal').modal('hide');
        });

        // ---------- SAVE: flatten hfgData -> materials (the keys your PHP expects) ----------
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

            // Flatten HFG groups into server-expected materials[]
            const materials = [];
            hfgData.forEach(hfg => {
                (hfg.materials || []).forEach(mt => {
                    materials.push({
                        id_bom: mt.id_bom || null,
                        material_id: mt.id,
                        material_name: mt.name,
                        unit_name: mt.unit,
                        category_name: mt.catName,
                        qty: parseFloat(mt.qty || 0), // <-- ensure it's float
                        hfg_id: hfg.id || 0,
                        hfg_name: hfg.name || '',
                        hfg_cat: hfg.catName || '',
                        hfg_unit: hfg.unit || '',
                        kode: mt.kode || '', // mt_kode_item
                        hfg_kode: hfg.kode || '', // hfg_kode_item
                        fg_kode: $('#fgKodeItem').val() || '' // fg_kode_item
                    });
                });
            });

            const payload = {
                kode_bom: "<?= $kode_bom ?>",
                fg_item_id: $('#fgItemId').val(),
                fg_item_name: $('#fgDescription').val(),
                fg_unit_name: $('#fgUnit').val(),
                fg_category_name: $('#fgCategoryName').val(),
                brand_name: $('#brandName').val(),
                art_color: $('#artColor').val(),
                materials: materials
            };

            // Debug if needed:
            // console.log('PAYLOAD >>', payload);

            $.ajax({
                url: base_url + 'purchasing/update_bom/' + payload.kode_bom, // pass kode_bom via URL
                type: 'POST',
                data: JSON.stringify(payload), // send payload instead of bomData
                contentType: 'application/json',
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.status === 'success') {
                        alert('BOM updated successfully');
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                }
            });
        });
    });
</script>