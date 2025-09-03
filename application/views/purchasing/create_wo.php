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

    <!-- Form -->
    <div class="ui form mt-3">
        <div class="fields">
            <div class="four wide field">
                <label>Date Of Order</label>
                <input type="date" id="dateOfOrder" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="four wide field">
                <label>XFD</label>
                <input type="date" id="xfdDate" required>
            </div>
            <div class="four wide field">
                <label>WO Number</label>
                <input type="text" id="woNumber" required>
            </div>
            <div class="four wide field">
                <label>Total QTY</label>
                <input type="text" id="totalQty" readonly required>
            </div>
        </div>
        <div class="fields">
            <div class="four wide field">
                <label>FG DESCRIPTION</label>
                <input type="text" id="fgDescription" readonly value="<?= $bom_header['fg_item_name'] ?? 'Default FG Description' ?>">
                <input type="hidden" id="fgItemId" value="<?= $bom_header['id_fg_item'] ?? '' ?>">
                <input type="hidden" id="fgCategoryId" value="<?= $bom_header['fg_item_category'] ?? '' ?>">
                <input type="hidden" id="fgCategoryName" value="<?= $bom_header['fg_item_category'] ?? '' ?>">
                <input type="hidden" id="fgUnitId" value="<?= $bom_header['fg_unit'] ?? '' ?>">
                <input type="hidden" id="fgKodeItem" value="<?= $bom_header['fg_kode_item'] ?? 'DEFAULT_FG_KODE' ?>">
            </div>
            <div class="four wide field">
                <label>UNIT</label>
                <input type="text" id="fgUnit" readonly value="<?= $bom_header['fg_unit'] ?? 'Default Unit' ?>">
            </div>
            <div class="four wide field">
                <label>BRAND</label>
                <input type="text" id="brandName" readonly value="<?= $bom_header['brand_name'] ?? 'Default Brand' ?>" required>
            </div>
            <div class="four wide field">
                <label>Art Color</label>
                <input type="text" id="artColor" readonly value="<?= $bom_header['artcolor_name'] ?? 'Default Art Color' ?>">
            </div>
        </div>
    </div>

    <!-- Tabs Menu -->
    <div class="ui top attached tabular menu">
        <a class="item active" data-tab="hfg">Half Finish Goods (HFG)</a>
        <a class="item" data-tab="size">Size Run</a>
    </div>

    <!-- HFG Tab -->
    <div class="ui bottom attached tab segment active" data-tab="hfg">
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
                <!-- Filled via JS renderHfgTable() -->
            </tbody>
        </table>
    </div>

    <!-- SIZE RUN Tab -->
    <div class="ui bottom attached tab segment" data-tab="size">
        <h5 class="ui header">Size Run</h5>
        <table class="ui celled table" id="sizeTable">
            <thead>
                <tr>
                    <th>Size</th>
                    <th>QTY</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sizes)): ?>
                    <?php foreach ($sizes as $size): ?>
                        <tr data-item-id="<?= $bom_header['fg_kode_item'] ?>">
                            <td><?= $size['size_name'] ?></td>
                            <td>
                                <input type="number"
                                    class="size-qty"
                                    value="0"
                                    min="0"
                                    style="width:70px"
                                    data-item-id="<?= $bom_header['fg_kode_item'] ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td id="sizeTotal">0</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Save BOM -->
    <button class="ui green button" id="saveEntireBomBtn" disabled><i class="save icon"></i> Save Entire WO</button>
</div>

<script>
    $(document).ready(function() {
        // Initialize Semantic UI tabs
        $('.menu .item').tab();

        // Open modals
        $('#openFgBtn').on('click', () => $('#fgModal').modal('show'));
        $('#openHfgBtn').on('click', () => $('#hfgModal').modal('show'));
        $('#openBrandModal').on('click', () => $('#brandModal').modal('show'));
        $('#openArtColorModal').on('click', () => $('#artColorModal').modal('show'));

        // Build initial HFG structure from PHP
        let hfgData = <?= json_encode($bom_materials); ?>;
        const groupedHfg = [];

        hfgData.forEach(b => {
            const hfgId = b.id_hfg_item || 0;
            let hfg = groupedHfg.find(h => h.id === hfgId);
            const material = {
                material_id: b.id_mt_item, // Material ID
                material_name: b.mt_item_name,
                unit_name: b.mt_unit,
                category_name: b.mt_item_category,
                kode: b.mt_kode_item, // Material code
                cons: parseFloat(b.bom_qty) || 0, // Consumption
                fg_kode: $('#fgKodeItem').val() // Add fg_kode_item for materials
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
                    materials: [material],
                });
            }
        });

        hfgData = groupedHfg;

        // Render HFG Table
        function renderHfgTable() {
            let tbody = '';
            hfgData.forEach((hfg, index) => {
                tbody += `
            <tr>
                <td>${index + 1}</td>
                <td>${hfg.name}</td>
                <td>${hfg.unit}</td>
                <td>
                    <table class="ui celled table mt-material" style="margin-top:5px;">
                        <thead><tr><th>Material</th><th>Consumption</th><th>Total Consumption</th></tr></thead>
                        <tbody>`;
                hfg.materials.forEach((mt, mi) => {
                    tbody += `
                <tr>
                    <td>${mt.material_name}</td>
                    <td><input type="number" min="0.01" step="0.01" value="${mt.cons}" class="material-cons" data-hfg="${index}" data-mt="${mi}" readonly></td>
                    <td><input type="number" value="0" class="material-qty" data-hfg="${index}" data-mt="${mi}" readonly></td>
                </tr>`;
                });
                tbody += `</tbody>
                    </table>
                </td>
            </tr>`;
            });
            $('#hfgTable tbody').html(tbody);
            updateHfgQty(); // recalc after rendering
        }
        renderHfgTable();

        // Update HFG Qty (Consumption × SizeRunTotal)
        function updateHfgQty() {
            const totalSize = getSizeTotal();
            $('#hfgTable tbody tr').each(function() {
                const consInput = $(this).find('.material-cons');
                const qtyInput = $(this).find('.material-qty');
                if (consInput.length && qtyInput.length) {
                    const consumption = parseFloat(consInput.val()) || 0;
                    qtyInput.val(consumption * totalSize);
                }
            });
        }

        // Size Run total updater
        function getSizeTotal() {
            let total = 0;
            $('#sizeTable .size-qty').each(function() {
                total += parseInt($(this).val()) || 0;
            });
            $('#sizeTotal').text(total);
            $('#totalQty').val(total);
            return total;
        }

        $(document).on('input', '.size-qty', function() {
            getSizeTotal();
            updateHfgQty();
        });
        getSizeTotal();
        updateHfgQty();

        // Save Button enable/disable
        function checkRequiredFields() {
            const dateOfOrder = $('#dateOfOrder').val();
            const woNumber = $('#woNumber').val();
            const totalQty = $('#totalQty').val();
            if (dateOfOrder && woNumber && totalQty) {
                $('#saveEntireBomBtn').prop('disabled', false); // Enable button
            } else {
                $('#saveEntireBomBtn').prop('disabled', true); // Disable button
            }
        }

        // Validate Fields before Submit
        $('#dateOfOrder, #woNumber, #totalQty').on('input', function() {
            checkRequiredFields();
        });

        checkRequiredFields(); // Initial check

        $('#saveEntireBomBtn').click(function() {
            const fgId = $('#fgItemId').val();
            const woNumber = $('#woNumber').val();
            const dateOfOrder = $('#dateOfOrder').val();
            const xfdDate = $('#xfdDate').val();
            const totalQty = $('#totalQty').val();
            const fgKode = $('#fgKodeItem').val();
            const brandName = $('#brandName').val();

            // Ensure that required fields are filled before proceeding
            if (!fgId || !woNumber || !dateOfOrder || !totalQty || !xfdDate) {
                alert("All required fields must be filled.");
                return;
            }

            // Prepare materials data - flatten all materials into single arrays
            const allMaterials = [];
            const allHfgs = [];

            hfgData.forEach(hfg => {
                // Add HFG info
                allHfgs.push({
                    hfg_id: hfg.id,
                    hfg_name: hfg.name,
                    hfg_unit: hfg.unit,
                    hfg_category: hfg.catName,
                    hfg_kode: hfg.kode
                });

                // Add all materials from this HFG
                (hfg.materials || []).forEach(mt => {
                    allMaterials.push({
                        material_id: mt.material_id,
                        material_name: mt.material_name,
                        unit_name: mt.unit_name,
                        category_name: mt.category_name,
                        cons: parseFloat(mt.cons || 0),
                        kode: mt.kode,
                        hfg_id: hfg.id,
                        hfg_name: hfg.name,
                        hfg_kode: hfg.kode
                    });
                });
            });

            // Calculate total consumption (consumption * total size) and reset bom_cons for each material
            let totalBomCons = 0;
            allMaterials.forEach(mt => {
                const materialTotalCons = mt.cons * getSizeTotal(); // consumption * total size
                mt.bom_cons = materialTotalCons;
                totalBomCons += materialTotalCons; // Sum all materials' bom_cons
            });

            // Prepare size runs with required fields for the model
            const sizeRuns = [];
            $('#sizeTable tbody tr').each(function() {
                const size = $(this).find('td:first').text().trim();
                const qty = parseInt($(this).find('.size-qty').val()) || 0;
                if (qty > 0) {
                    sizeRuns.push({
                        size: size,
                        qty: qty,
                        wo_number: woNumber, // Add wo_number for model
                        brand_name: brandName, // Add brand_name for model
                        size_name: size, // Add size_name as expected by model
                        size_qty: qty // Add size_qty as expected by model
                    });
                }
            });

            // Construct the payload to match controller expectations
            const payload = {
                kode_bom: "<?= $kode_bom ?>",
                wo_number: woNumber,
                fg_item_id: parseInt(fgId) || 0,

                // Single values as expected by controller
                fg_kode_item: fgKode || 'DEFAULT_FG_KODE',
                hfg_kode_item: allHfgs.length > 0 ? allHfgs[0].hfg_kode : 'DEFAULT_HFG_KODE',
                mt_kode_item: allMaterials.length > 0 ? allMaterials[0].kode : 'DEFAULT_MT_KODE',

                // Names and details
                fg_item_name: $('#fgDescription').val() || '',
                hfg_item_name: allHfgs.length > 0 ? allHfgs[0].hfg_name : 'Default HFG',
                mt_item_name: allMaterials.length > 0 ? allMaterials[0].material_name : 'Default Material',

                // Categories
                fg_category_name: $('#fgCategoryName').val() || '',
                hfg_category_name: allHfgs.length > 0 ? allHfgs[0].hfg_category : 'Default Category',
                mt_category_name: allMaterials.length > 0 ? allMaterials[0].category_name : 'Default Category',

                // Units
                fg_unit: $('#fgUnit').val() || '',
                hfg_unit: allHfgs.length > 0 ? allHfgs[0].hfg_unit : 'Default Unit',
                mt_unit: allMaterials.length > 0 ? allMaterials[0].unit_name : 'Default Unit',

                // Other details
                brand_name: brandName || '',
                artcolor_name: $('#artColor').val() || '',

                // Quantities
                bom_qty: allMaterials.length > 0 ? allMaterials[0].cons : 0,
                bom_cons: totalBomCons, // Total consumption calculated
                wo_qty: parseInt(totalQty) || 0,

                // Dates
                date_of_order: dateOfOrder,
                due_date: xfdDate,

                // Arrays for additional processing
                materials: allMaterials,
                hfgs: allHfgs,
                sizerun: sizeRuns,
                total_qty: parseInt(totalQty) || 0
            };

            // Debugging: Log the payload
            console.log("Complete Payload being sent:", JSON.stringify(payload, null, 2));

            // Show loading state
            $('#saveEntireBomBtn').prop('disabled', true).html('<i class="spinner loading icon"></i> Saving...');

            $.ajax({
                url: base_url + 'purchasing/save_wo',
                type: 'POST',
                data: JSON.stringify(payload),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    console.log("Server response:", response);
                    if (response.status === 'success') {
                        alert('WO created successfully');
                        window.location.href = base_url + 'purchasing';
                    } else {
                        alert('Error: ' + (response.message || 'Unknown error occurred'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", {
                        status: status,
                        error: error,
                        responseText: xhr.responseText
                    });
                    alert('Server error occurred. Please check console for details.');
                },
                complete: function() {
                    $('#saveEntireBomBtn').prop('disabled', false).html('<i class="save icon"></i> Save Entire WO');
                }
            });
        });
    });
</script>