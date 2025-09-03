<!-- application/views/workorder_edit.php -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <form id="workOrderForm" class="ui form">
        <div class="fields">
            <div class="four wide field">
                <label>Brand <span class="ui red text">*</span></label>
                <input type="text" id="brand" name="brand" readonly value="<?= $wo['brand_name'] ?>">
            </div>
            <div class="four wide field">
                <label>Date <span class="ui red text">*</span></label>
                <input type="date" id="date" value="<?= $wo['date_of_order'] ?>" required>
            </div>
            <div class="four wide field">
                <label>WO Number <span class="ui red text">*</span></label>
                <input type="text" id="wo_number" value="<?= $wo['wo_number'] ?>" readonly>
            </div>
            <div class="four wide field">
                <label>Due Date <span class="ui red text">*</span></label>
                <input type="date" id="due_date" value="<?= $wo['due_date'] ?>">
            </div>
        </div>
        <button type="button" class="ui primary button">Clear Form</button>
    </form>

    <!-- Tabs -->
    <div class="ui top attached tabular menu">
        <a class="active item" data-tab="create">List FG</a>
        <a class="item" data-tab="bom">List HFG</a>
        <a class="item" data-tab="cost">List MATERIAL</a>
        <a class="item" data-tab="size">SIZE RUN</a>
    </div>

    <!-- CREATE FG -->
    <div class="ui bottom attached active tab segment" data-tab="create">
        <div class="mb-3">
            <button class="ui primary button" id="openSelectModal">
                <i class="folder open icon"></i> Select Item
            </button>
            <button id="updateBtn" class="ui green button right floated">Update</button>
        </div>
        <table class="ui celled table" id="createTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Check</th>
                    <th>Action</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Art/Color</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fg_data as $i => $fg): ?>
                    <tr data-fg-id="<?= $fg['fg_kode_item'] ?>"
                        data-brand="<?= $fg['brand_name'] ?>"
                        data-kode-bom="<?= $fg['kode_bom'] ?>">
                        <td></td>
                        <td><input type="checkbox" class="item-checkbox"></td>
                        <td><button type="button" class="ui red tiny button deleteRowBtn">Delete</button></td>
                        <td><?= $fg['kode_bom'] ?></td>
                        <td><?= $fg['fg_item_name'] ?></td>
                        <td><?= $fg['fg_unit'] ?></td>
                        <td><?= $fg['fg_category_name'] ?></td>
                        <td><?= $fg['brand_name'] ?></td>
                        <td><?= $fg['artcolor_name'] ?></td>
                        <td><input type="number" class="form-control form-control-sm total-qty" value="<?= $fg['wo_qty'] ?>" readonly></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- HFG -->
    <div class="ui bottom attached tab segment" data-tab="bom">
        <h5 class="ui header">HFG List</h5>
        <table class="ui celled table" id="bomTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode BOM</th>
                    <th>Art/Color</th>
                    <th>RM Code</th>
                    <th>RM Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hfg_data as $i => $hfg): ?>
                    <tr data-fg-id="<?= $fg['fg_kode_item'] ?>"
                        data-hfg-id=" <?= $hfg['hfg_kode_item'] ?>"
                        data-kode-bom="<?= $hfg['kode_bom'] ?>">
                        <td><?= $i + 1 ?></td>
                        <td><?= $hfg['kode_bom'] ?></td>
                        <td><?= $hfg['artcolor_name'] ?></td>
                        <td><?= $hfg['hfg_kode_item'] ?></td>
                        <td><?= $hfg['hfg_item_name'] ?></td>
                        <td><?= $hfg['hfg_category_name'] ?></td>
                        <td><?= $hfg['hfg_unit'] ?></td>
                        <td><input type="number" class="bom-qty" value="<?= $hfg['bom_qty'] ?>" readonly data-consumption="<?= $hfg['bom_qty'] ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- MATERIAL -->
    <div class="ui bottom attached tab segment" data-tab="cost">
        <h5 class="ui header">Material List</h5>
        <table class="ui celled table" id="materialTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Art/Color</th>
                    <th>Consumption</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mt_data as $i => $mt): ?>
                    <tr data-fg-id="<?= $mt['fg_kode_item'] ?>">
                        <td><?= $i + 1 ?></td>
                        <td><?= $mt['mt_kode_item'] ?></td>
                        <td><?= $mt['mt_item_name'] ?></td>
                        <td><?= $mt['mt_unit'] ?></td>
                        <td><?= $mt['mt_category_name'] ?></td>
                        <td><?= $mt['brand_name'] ?></td>
                        <td><?= $mt['artcolor_name'] ?></td>
                        <td><?= $mt['bom_cons'] ?></td>
                        <td><input type="number" class="bom-qty" value="<?= $mt['bom_qty'] ?>" readonly data-consumption="<?= $mt['bom_cons'] ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- SIZE RUN -->
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
                <?php foreach ($fg_data as $fg):
                    $itemId = $fg['fg_kode_item'];
                    foreach ($sizes as $size):
                        $qty = $sizerun_map[$size['size_name']] ?? 0;
                ?>
                        <tr data-item-id="<?= $itemId ?>">
                            <td><?= $size['size_name'] ?></td>
                            <td><input type="number" class="size-qty" value="<?= $qty ?>" min="0" style="width:70px" data-item-id="<?= $itemId ?>"></td>
                        </tr>
                <?php endforeach;
                endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td id="sizeTotal">0</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
</div>

<!-- FG Modal -->
<div class="ui modal" id="itemModal">
    <div class="header">Select FG Item</div>
    <div class="content">
        <table class="ui celled table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Action</th>
                    <th>Kode BOM</th>
                    <th>FG Name</th>
                    <th>Brand</th>
                    <th>Art/Color</th>
                    <th>UoM</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($fg_items as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <button type="button" class="ui tiny primary button select-item"
                                data-code="<?= $row['kode_bom'] ?>"
                                data-name="<?= $row['item_name'] ?>"
                                data-uom="<?= $row['unit_name'] ?>"
                                data-brand="<?= $row['brand_name'] ?>"
                                data-artcolor="<?= $row['artcolor_name'] ?>"
                                data-category="FG">
                                Select
                            </button>
                        </td>
                        <td><?= $row['kode_bom'] ?></td>
                        <td><?= $row['item_name'] ?></td>
                        <td><?= $row['brand_name'] ?></td>
                        <td><?= $row['artcolor_name'] ?></td>
                        <td><?= $row['unit_name'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Init Semantic UI tabs
        $('.menu .item').tab();

        // Open modal
        $('#openSelectModal').on('click', function() {
            $('#itemModal').modal('show');
        });

        // Function to renumber rows in CREATE tab
        function renumberRows() {
            $('#createTable tbody tr').each(function(index) {
                $(this).find('td:eq(0)').text(index + 1);
            });
        }

        // --- Load Edit Data Function ---
        function loadEditData(editData) {
            $('#createTable tbody').empty();
            $('#sizeTable tbody').empty();
            $('#bomTable tbody').empty();
            $('#materialTable tbody').empty();

            editData.fg.forEach(function(fgRow, index) {
                let fgId = fgRow.fg_kode_item;
                let newRow = $(`
                <tr data-fg-id="${fgId}" data-brand="${fgRow.brand_name}">
                    <td>${index+1}</td>
                    <td><input type="checkbox" class="item-checkbox"></td>
                    <td><button type="button" class="ui red tiny button deleteRowBtn">Delete</button></td>
                    <td>${fgRow.fg_kode_item}</td>
                    <td>${fgRow.fg_item_name}</td>
                    <td>${fgRow.fg_unit}</td>
                    <td>${fgRow.fg_category_name}</td>
                    <td>${fgRow.brand_name}</td>
                    <td>${fgRow.artcolor_name}</td>
                    <td><input type="number" class="form-control form-control-sm total-qty" value="0" readonly></td>
                </tr>
            `);
                $('#createTable tbody').append(newRow);

                // Size Run
                if (editData.sizerun[fgId]) {
                    editData.sizerun[fgId].forEach(function(sizeRow) {
                        $('#sizeTable tbody').append(`
                        <tr data-item-id="${fgId}">
                            <td>${sizeRow.size_name}</td>
                            <td><input type="number" class="size-qty" value="${sizeRow.size_qty}" min="0" data-item-id="${fgId}"></td>
                        </tr>
                    `);
                    });
                }

                // HFG
                if (editData.hfg[fgId]) {
                    editData.hfg[fgId].forEach(function(hfg, i) {
                        $('#bomTable tbody').append(`
                        <tr data-fg-id="${fgId}">
                            <td>${i+1}</td>
                            <td>${hfg.kode_bom}</td>
                            <td>${hfg.artcolor_name}</td>
                            <td>${hfg.hfg_kode_item}</td>
                            <td>${hfg.hfg_item_name}</td>
                            <td>${hfg.hfg_item_category}</td>
                            <td>${hfg.hfg_unit}</td>
                            <td><input type="number" class="bom-qty" value="0" readonly data-consumption="${hfg.bom_qty}"></td>
                        </tr>
                    `);
                    });
                }

                // Material
                if (editData.material[fgId]) {
                    editData.material[fgId].forEach(function(mt, i) {
                        $('#materialTable tbody').append(`
                        <tr data-fg-id="${fgId}">
                            <td>${i+1}</td>
                            <td>${mt.mt_kode_item}</td>
                            <td>${mt.mt_item_name}</td>
                            <td>${mt.mt_unit}</td>
                            <td>${mt.mt_item_category}</td>
                            <td>${mt.brand_name}</td>
                            <td>${mt.artcolor_name}</td>
                            <td>${mt.bom_qty}</td>
                            <td><input type="number" class="bom-qty" value="0" readonly data-consumption="${mt.bom_qty}"></td>
                        </tr>
                    `);
                    });
                }

                // Update total qty based on size
                updateCreateQty(fgId);
            });

            // Set brand field
            if (editData.fg.length > 0) $('#brand').val(editData.fg[0].brand_name);

            // Enable Save
            $('#saveBtn').prop('disabled', editData.fg.length === 0);
        }

        // Delete row
        $(document).on('click', '.deleteRowBtn', function() {
            let fgId = $(this).closest('tr').data('fg-id');
            $('#sizeTable tbody tr[data-item-id="' + fgId + '"]').remove();
            $('#bomTable tbody tr[data-fg-id="' + fgId + '"]').remove();
            $('#materialTable tbody tr[data-fg-id="' + fgId + '"]').remove();
            $(this).closest('tr').remove();
            renumberRows();

            if ($('#createTable tbody tr').length === 0) {
                $('#saveBtn').prop('disabled', true);
            }

            updateSizeTabTotal();
        });

        // Size input change
        $(document).on('input', '.size-qty', function() {
            let itemId = $(this).data('item-id');
            updateCreateQty(itemId);
        });

        // Update totals
        function updateCreateQty(itemId) {
            let totalSize = 0;
            $('#sizeTable tbody tr[data-item-id="' + itemId + '"]').each(function() {
                totalSize += parseInt($(this).find('.size-qty').val()) || 0;
            });

            // Update CREATE tab total
            $('#createTable tbody tr[data-fg-id="' + itemId + '"] .total-qty').val(totalSize);

            // Update HFG qty
            $('#bomTable tbody tr[data-fg-id="' + itemId + '"]').each(function() {
                $(this).find('.bom-qty').val(totalSize * 1);
            });

            // Update Material qty
            $('#materialTable tbody tr[data-fg-id="' + itemId + '"]').each(function() {
                let consumption = parseFloat($(this).find('.bom-qty').data('consumption')) || 0;
                $(this).find('.bom-qty').val(consumption * totalSize);
            });

            updateSizeTabTotal();
        }

        // Update total in SIZE RUN tab
        function updateSizeTabTotal() {
            let total = 0;
            $('#sizeTable tbody tr').each(function() {
                total += parseInt($(this).find('.size-qty').val()) || 0;
            });
            $('#sizeTotal').text(total);
        }

        // --- Save Edit / Create ---
        $('#updateBtn').on('click', function() {
            let wo_number = $('#wo_number').val();
            let date_of_order = $('#date').val();
            let due_date = $('#due_date').val();
            let created_by = '<?= $user['user_email'] ?>';

            if (!wo_number) {
                alert('WO Number is required!');
                $('#wo_number').focus();
                return;
            }
            if (!date_of_order) {
                alert('Date is required!');
                $('#date').focus();
                return;
            }

            let dataToSave = [];
            let sizeRunData = [];

            $('#createTable tbody tr').each(function() {
                let fgRow = $(this);
                let fgId = fgRow.data('fg-id');

                // Total Size
                let totalSize = 0;
                $('#sizeTable tbody tr[data-item-id="' + fgId + '"]').each(function() {
                    totalSize += parseInt($(this).find('.size-qty').val()) || 0;
                });
                if (totalSize <= 0) return;

                // Size Run
                $('#sizeTable tbody tr[data-item-id="' + fgId + '"]').each(function() {
                    let sizeRow = $(this);
                    let sizeName = sizeRow.find('td:eq(0)').text();
                    let sizeQty = parseInt(sizeRow.find('.size-qty').val()) || 0;
                    if (sizeQty <= 0) return;
                    sizeRunData.push({
                        wo_number: wo_number,
                        kode_bom: fgId,
                        brand_name: $('#brand').val(),
                        size_name: sizeName,
                        size_qty: sizeQty,
                        created_by: created_by
                    });
                });

                // HFG
                let hfgRows = $('#bomTable tbody tr[data-fg-id="' + fgId + '"]');
                if (hfgRows.length === 0) {
                    dataToSave.push({
                        kode_bom: fgId,
                        wo_number: wo_number,
                        fg_kode_item: fgId,
                        hfg_kode_item: '',
                        mt_kode_item: '',
                        fg_item_name: fgRow.find('td:eq(4)').text(),
                        hfg_item_name: '',
                        mt_item_name: '',
                        fg_category_name: fgRow.find('td:eq(6)').text(),
                        hfg_category_name: '',
                        mt_category_name: '',
                        fg_unit: fgRow.find('td:eq(5)').text(),
                        hfg_unit: '',
                        mt_unit: '',
                        brand_name: fgRow.find('td:eq(7)').text(),
                        artcolor_name: fgRow.find('td:eq(8)').text(),
                        wo_qty: totalSize,
                        bom_qty: 0,
                        consumption: 0,
                        size_name: '',
                        date_of_order: date_of_order,
                        due_date: due_date,
                        created_by: created_by
                    });
                } else {
                    hfgRows.each(function() {
                        let hfgRow = $(this);
                        let hfgKode = hfgRow.find('td:eq(3)').text();
                        let mtRows = $('#materialTable tbody tr[data-fg-id="' + fgId + '"]');

                        if (mtRows.length === 0) {
                            dataToSave.push({
                                kode_bom: fgId,
                                wo_number: wo_number,
                                fg_kode_item: fgId,
                                hfg_kode_item: hfgKode,
                                mt_kode_item: '',
                                fg_item_name: fgRow.find('td:eq(4)').text(),
                                hfg_item_name: hfgRow.find('td:eq(4)').text(),
                                mt_item_name: '',
                                fg_category_name: fgRow.find('td:eq(6)').text(),
                                hfg_category_name: hfgRow.find('td:eq(5)').text(),
                                mt_category_name: '',
                                fg_unit: fgRow.find('td:eq(5)').text(),
                                hfg_unit: hfgRow.find('td:eq(6)').text(),
                                mt_unit: '',
                                brand_name: fgRow.find('td:eq(7)').text(),
                                artcolor_name: fgRow.find('td:eq(8)').text(),
                                wo_qty: totalSize,
                                bom_qty: 0,
                                consumption: 0,
                                size_name: '',
                                date_of_order: date_of_order,
                                due_date: due_date,
                                created_by: created_by
                            });
                        } else {
                            mtRows.each(function() {
                                let mtRow = $(this);
                                let consumption = parseFloat(mtRow.find('.bom-qty').data('consumption')) || 0;
                                let bomQty = consumption * totalSize;

                                dataToSave.push({
                                    kode_bom: fgId,
                                    wo_number: wo_number,
                                    fg_kode_item: fgId,
                                    hfg_kode_item: hfgKode,
                                    mt_kode_item: mtRow.find('td:eq(1)').text(),
                                    fg_item_name: fgRow.find('td:eq(4)').text(),
                                    hfg_item_name: hfgRow.find('td:eq(4)').text(),
                                    mt_item_name: mtRow.find('td:eq(2)').text(),
                                    fg_category_name: fgRow.find('td:eq(6)').text(),
                                    hfg_category_name: hfgRow.find('td:eq(5)').text(),
                                    mt_category_name: mtRow.find('td:eq(4)').text(),
                                    fg_unit: fgRow.find('td:eq(5)').text(),
                                    hfg_unit: hfgRow.find('td:eq(6)').text(),
                                    mt_unit: mtRow.find('td:eq(3)').text(),
                                    brand_name: fgRow.find('td:eq(7)').text(),
                                    artcolor_name: fgRow.find('td:eq(8)').text(),
                                    wo_qty: totalSize,
                                    bom_qty: bomQty,
                                    consumption: consumption,
                                    size_name: '',
                                    date_of_order: date_of_order,
                                    due_date: due_date,
                                    created_by: created_by
                                });
                            });
                        }
                    });
                }
            });

            // AJAX SAVE
            $.ajax({
                url: '<?= base_url("purchasing/update/") ?>' + wo_number,
                method: 'POST',
                data: {
                    data: JSON.stringify(dataToSave),
                    sizerun: JSON.stringify(sizeRunData)
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        alert('WO saved successfully!');
                        location.reload();
                    } else {
                        alert('Failed to save WO: ' + res.message);
                    }
                }
            });
        });

    });
</script>