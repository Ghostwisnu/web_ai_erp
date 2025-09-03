<style>
    /* ---- Modal & Table Utilities ---- */
    .ui.modal .content {
        max-height: 80vh;
        overflow-y: auto;
    }

    .wide-scrollable-modal {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    .ui.celled.table {
        width: 100%;
        table-layout: fixed;
    }

    /* Smaller custom modal */
    .ui.modal.small-modal {
        width: 400px !important;
        max-width: 90% !important;
        top: auto;
        margin: 0 auto !important;
    }

    .ui.modal.small-modal .content {
        max-height: 60vh;
        overflow-y: auto;
    }

    /* Make wide tables scroll */
    table {
        width: 100%;
        display: block;
        overflow-x: auto;
    }

    /* Specific table container */
    #createTable {
        width: 100%;
        table-layout: fixed;
        display: block;
        overflow-x: auto;
    }
</style>

<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <!-- Form -->
    <form id="workOrderForm" class="ui form">
        <div class="fields">
            <div class="four wide field">
                <label>Date <span class="ui red text">*</span></label>
                <input type="date" id="date" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="four wide field">
                <label>KODE SJ <span class="ui red text">*</span></label>
                <input type="text" id="kode_sj" value="<?= $kode_sj ?>" required readonly>
            </div>
            <div class="four wide field">
                <label>NO SJ <span class="ui red text">*</span></label>
                <input type="text" id="no_sj" required>
            </div>

            <!-- FROM DEPT (searchable dropdown) -->
            <div class="four wide field">
                <label>FROM DEPT <span class="ui red text">*</span></label>
                <div id="from_dept_dropdown" class="ui fluid search selection dropdown">
                    <input type="hidden" id="from_dept">
                    <i class="dropdown icon"></i>
                    <div class="default text">-- Select Department --</div>
                    <div class="menu"></div>
                </div>
            </div>

            <!-- TO DEPT (searchable dropdown) -->
            <div class="four wide field">
                <label>TO DEPT <span class="ui red text">*</span></label>
                <div id="to_dept_dropdown" class="ui fluid search selection dropdown">
                    <input type="hidden" id="to_dept">
                    <i class="dropdown icon"></i>
                    <div class="default text">-- Select Department --</div>
                    <div class="menu"></div>
                </div>
            </div>
        </div>

        <button type="button" id="clearFormBtn" class="ui button">Clear Form</button>
        <button type="button" class="ui primary button" id="openWoModal">
            <i class="folder open icon"></i> Select Item
        </button>
        <button type="button" id="deleteCheckedRows" class="ui red button">Delete Checked Rows</button>
    </form>

    <button id="saveBtn" class="ui green button right floated mb-3" disabled>Save</button>

    <!-- Items Table -->
    <div class="wide-scrollable-modal">
        <table class="ui celled table" id="createTable">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Wo Number</th>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Art/Color</th>
                    <th>Consumption</th>
                    <th>Checkin QTY</th>
                    <th>Checkin Size</th>
                </tr>
            </thead>
            <tbody><!-- rows appended here --></tbody>
        </table>
    </div>
</div>

<!-- WO Modal (wide) -->
<div class="ui modal wide-scrollable-modal" id="woModal">
    <div class="header">Select Work Order</div>
    <div class="content">
        <div class="ui search">
            <div class="ui icon input" style="width: 100%; margin-bottom: 10px;">
                <input class="prompt" type="text" placeholder="Search WO...">
                <i class="search icon"></i>
            </div>
            <div class="results"></div>
        </div>

        <div style="overflow-x: auto; width: 100%;">
            <table class="ui celled table" id="woTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>WO Number</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Consumption</th>
                        <th>Total Consumption</th>
                    </tr>
                </thead>
                <tbody><!-- filled by JS --></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Global Quantity Modal (small) -->
<div class="ui modal small-modal" id="globalQtyModal">
    <div class="header">Add Global Quantity</div>
    <div class="content">
        <label for="globalQty">Enter Global Quantity:</label>
        <input type="number" id="globalQty" min="0" step="any">
    </div>
    <div class="actions">
        <button class="ui button" id="applyGlobalQty">Apply</button>
        <button class="ui button" id="cancelGlobalQty">Cancel</button>
    </div>
</div>

<!-- Size Run Modal (small) -->
<div class="ui modal small-modal" id="sizeRunModal">
    <div class="header">Add Size Run Quantity</div>
    <div class="content">
        <div id="sizeRunTotal" style="margin-bottom:8px;font-weight:bold;">Total: 0</div>
        <table class="ui table" id="sizeRunTable">
            <thead>
                <tr>
                    <th>Size</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody id="sizeRunContainer"><!-- filled by JS --></tbody>
        </table>
    </div>
    <div class="actions">
        <button class="ui button" id="applySizeRunQty">Apply</button>
        <button class="ui button" id="cancelSizeRunQty">Cancel</button>
    </div>
</div>

<!-- Size Run Details Modal (small) -->
<div class="ui modal small-modal" id="sizeRunDetailsModal">
    <div class="header">Size Run Details</div>
    <div class="content">
        <table class="ui table" id="sizeRunDetailsTable">
            <thead>
                <tr>
                    <th>Size</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody id="sizeRunDetailsContainer"><!-- filled by JS --></tbody>
        </table>
    </div>
    <div class="actions">
        <button class="ui button" id="closeSizeRunDetailsModal">Close</button>
    </div>
</div>
</div>

<script>
    // ===== Global state =====
    let sizeRunData = [];

    $(document).ready(function() {
        // ---- Init Semantic UI ----
        $('#woModal, #globalQtyModal, #sizeRunModal, #sizeRunDetailsModal').modal({
            autofocus: false
        });
        $('.ui.dropdown').dropdown();

        // ---- Searchable dropdown departments ----
        (function setupDeptDropdowns() {
            const DEPARTMENTS = ['CUTTING', 'WAREHOUSE', 'SEWING', 'SEMI WAREHOUSE', 'LASTING', 'FINISHING', 'PACKAGING', 'SUPPLIER'];

            function populateDropdown($dropdown, items) {
                const $menu = $dropdown.find('.menu');
                $menu.empty();
                items.forEach(label => $menu.append(`<div class="item" data-value="${label}">${label}</div>`));
            }

            const $from = $('#from_dept_dropdown');
            const $to = $('#to_dept_dropdown');
            populateDropdown($from, DEPARTMENTS);
            populateDropdown($to, DEPARTMENTS);

            $from.dropdown({
                fullTextSearch: 'exact',
                clearable: true,
                forceSelection: false,
                selectOnKeydown: false
            });
            $to.dropdown({
                fullTextSearch: 'exact',
                clearable: true,
                forceSelection: false,
                selectOnKeydown: false
            });

            function warnSameDept() {
                const fromV = $('#from_dept').val();
                const toV = $('#to_dept').val();
                if (fromV && toV && fromV === toV) {
                    console.warn('FROM DEPT dan TO DEPT sama. Pastikan ini memang disengaja.');
                }
            }
            $from.on('change', warnSameDept);
            $to.on('change', warnSameDept);
        })();

        // ---- Open WO modal ----
        $('#openWoModal').on('click', function() {
            $('#woModal').modal('show');
        });

        // ---- Clear form (optional) ----
        $('#clearFormBtn').on('click', function() {
            $('#no_sj').val('');
            $('#from_dept_dropdown').dropdown('clear');
            $('#to_dept_dropdown').dropdown('clear');
            $('#createTable tbody').empty();
            sizeRunData = [];
            updateSaveButtonState();
        });

        // ---- Global Qty modal ----
        $(document).on('click', '.add-global-qty', function() {
            const id = $(this).data('id');
            $('#globalQtyModal').modal('show').data('id', id);
        });

        $('#applyGlobalQty').on('click', function() {
            const id = $('#globalQtyModal').data('id');
            const globalQty = parseFloat($('#globalQty').val() || 0);
            setGlobalQtyForRow(id, globalQty);
            clearSizeRunForId(id); // exclusive rule
            $('#globalQtyModal').modal('hide');
            updateSaveButtonState();
        });

        // ---- Size Run modal ----
        $(document).on('click', '.add-size-run', function() {
            const id = $(this).data('id');
            const brand = $(this).data('brand');
            $('#sizeRunModal').modal('show').data('id', id).data('brand', brand);

            $.ajax({
                url: '<?= site_url('warehouse/get_size_run_data'); ?>',
                type: 'GET',
                data: {
                    brand
                },
                success: function(resp) {
                    const data = JSON.parse(resp);
                    updateSizeRunTable(data.sizes);
                    // Reset inputs & total
                    $('#sizeRunTable .size-run-qty').val(0);
                    $('#sizeRunTotal').text('Total: 0');
                }
            });
        });

        // Live total in Size Run
        $(document).on('input', '#sizeRunTable .size-run-qty', function() {
            let total = 0;
            $('#sizeRunTable .size-run-qty').each(function() {
                total += parseFloat($(this).val() || 0);
            });
            $('#sizeRunTotal').text('Total: ' + total);
        });

        $('#applySizeRunQty').on('click', function() {
            const id = $('#sizeRunModal').data('id');

            // collect size->qty
            let sizeData = [];
            $('#sizeRunTable tbody tr').each(function() {
                const $q = $(this).find('.size-run-qty');
                const size = $q.data('size');
                const qty = parseFloat($q.val() || 0);
                if (qty > 0) sizeData.push({
                    size,
                    quantity: qty
                });
            });

            // save/overwrite record for this id
            const idx = sizeRunData.findIndex(r => r.id === id);
            if (idx !== -1) sizeRunData[idx] = {
                id,
                sizes: sizeData
            };
            else sizeRunData.push({
                id,
                sizes: sizeData
            });

            // total → write to global-qty-input (exclusive with global manual)
            const totalSizeQty = sizeData.reduce((s, it) => s + (parseFloat(it.quantity) || 0), 0);
            clearGlobalQtyForRow(id);
            setGlobalQtyForRow(id, totalSizeQty);

            $('#sizeRunModal').modal('hide');
            updateSaveButtonState();
        });

        // ---- Size Run details modal ----
        $(document).on('click', '.size-run-btn', function() {
            const id = $(this).data('id');
            $('#sizeRunDetailsModal').modal('show').data('id', id);
            displaySizeRunDetails(id);
        });

        // ---- Build WO list (unique by (wo_number, item_name), chose min bom_cons) ----
        (function buildWoList() {
            let woList = <?= json_encode($wo_list); ?>;
            const uniqueRows = {};
            $('#woTable tbody').empty();

            $.each(woList, function(_i, row) {
                const key = (row.wo_number || '') + '_' + (row.mt_item_name || '');
                const bomCons = parseFloat(row.bom_cons);
                if (!uniqueRows[key] || bomCons < parseFloat(uniqueRows[key].bom_cons)) {
                    uniqueRows[key] = row;
                }
            });

            $.each(uniqueRows, function(_k, row) {
                const idx = $('#woTable tbody tr').length + 1;
                $('#woTable tbody').append(`
          <tr>
            <td>${idx}</td>
            <td>
              <button type="button" class="ui tiny primary button select-wo"
                data-id="${row.id_wo}"
                data-wonumber="${row.wo_number}"
                data-brand="${row.brand_name}"
                data-date="${row.date_of_order}"
                data-due="${row.due_date}"
                data-bomcons="${row.bom_cons}"
                data-itemcode="${row.mt_kode_item || ''}"
                data-itemname="${row.mt_item_name || ''}"
                data-unit="${row.mt_unit || ''}"
                data-category="${row.mt_category_name || ''}"
                data-artcolor="${row.artcolor_name || ''}">
                Select
              </button>
            </td>
            <td>${row.wo_number}</td>
            <td>${row.mt_item_name}</td>
            <td>${row.mt_category_name}</td>
            <td>${row.bom_qty}</td>
            <td>${row.bom_cons}</td>
          </tr>
        `);
            });
        })();

        // ---- Append selected WO item to createTable ----
        $(document).on('click', '.select-wo', function() {
            const id = $(this).data('id');
            const woNumber = $(this).data('wonumber');
            const brand = $(this).data('brand');
            const bomCons = $(this).data('bomcons');
            const itemCode = $(this).data('itemcode') || '';
            const itemName = $(this).data('itemname') || 'Unnamed Item';
            const unit = $(this).data('unit') || '';
            const category = $(this).data('category') || '';
            const artcolor = $(this).data('artcolor') || '';

            const row = `
        <tr data-id="${id}" data-itemcode="${itemCode}">
          <td><input type="checkbox" class="delete-row-checkbox" data-id="${id}" /></td>
          <td>${woNumber}</td>
          <td>${itemName}</td>
          <td>${unit}</td>
          <td>${category}</td>
          <td>${brand}</td>
          <td>${artcolor}</td>
          <td><input type="number" class="mini ui icon input item-qty" value="${bomCons}" readonly min="0" step="any" /></td>
          <td>
            <button type="button" class="mini ui  blue button mb-2 add-global-qty" data-id="${id}" data-itemcode="${itemCode}" data-itemname="${itemName}">Add Global Qty</button>
            <input type="number" class="mini ui icon input global-qty-input" value="0" readonly>
          </td>
          <td>
            <button type="button" class="mini ui green button mb-2 add-size-run" data-id="${id}" data-brand="${brand}">Add Size Run</button>
            <button class="ui mini button size-run-btn" data-id="${id}" data-itemname="${itemName}">View Size Run</button>
          </td>
        </tr>
      `;
            $('#createTable tbody').append(row);
            $('#woModal').modal('hide');
            updateSaveButtonState();
            if (typeof updateSerialNumbers === 'function') updateSerialNumbers();
        });

        // ---- Delete checked rows ----
        $('#deleteCheckedRows').on('click', function() {
            $('#createTable tbody tr').each(function() {
                const $tr = $(this);
                const checkbox = $tr.find('.delete-row-checkbox');
                if (checkbox.is(':checked')) {
                    const id = $tr.data('id');
                    sizeRunData = sizeRunData.filter(it => it.id !== id); // cleanup size run records
                    $tr.remove();
                }
            });
            updateSaveButtonState();
        });

        // ---- Enable/disable Save ----
        $(document).on('input', '.global-qty-input', updateSaveButtonState);

        function getSizeRunTotalById(id) {
            const rec = sizeRunData.find(r => r.id === id);
            if (!rec || !Array.isArray(rec.sizes)) return 0;
            return rec.sizes.reduce((sum, it) => sum + (parseFloat(it.quantity) || 0), 0);
        }

        function isRowComplete($tr) {
            const id = $tr.data('id');
            const globalQty = parseFloat($tr.find('.global-qty-input').val()) || 0;
            const sizeRunTotal = getSizeRunTotalById(id);
            return (globalQty > 0) || (sizeRunTotal > 0);
        }

        function updateSaveButtonState() {
            const $rows = $('#createTable tbody tr');
            if ($rows.length === 0) return $('#saveBtn').prop('disabled', true);
            const allComplete = $rows.toArray().every(tr => isRowComplete($(tr)));
            $('#saveBtn').prop('disabled', !allComplete);
        }

        // ---- Helpers for qty & size run ----
        function clearSizeRunForId(id) {
            sizeRunData = sizeRunData.filter(r => r.id !== id);
            if ($('#sizeRunDetailsModal').is(':visible')) {
                $('#sizeRunDetailsTable tbody').empty().append('<tr><td colspan="2">No size run data available for this item.</td></tr>');
            }
        }

        function clearGlobalQtyForRow(id) {
            $(`#createTable tbody tr[data-id="${id}"] .global-qty-input`).val(0);
        }

        function setGlobalQtyForRow(id, value) {
            $(`#createTable tbody tr[data-id="${id}"] .global-qty-input`).val(value);
        }

        function updateSizeRunTable(sizes) {
            const $tb = $('#sizeRunTable tbody');
            $tb.empty();
            if (sizes && sizes.length > 0) {
                $.each(sizes, function(_i, data) {
                    $tb.append(`
            <tr>
              <td>${data.size_name}</td>
              <td><input type="number" class="size-run-qty" data-size="${data.size_name}" value="0" min="0" step="any" /></td>
            </tr>
          `);
                });
            } else {
                $tb.append('<tr><td colspan="2">No size run data available for this brand.</td></tr>');
            }
        }

        function displaySizeRunDetails(id) {
            const $tb = $('#sizeRunDetailsTable tbody');
            $tb.empty();
            const sizeRun = sizeRunData.find(item => item.id === id);
            if (sizeRun) {
                $.each(sizeRun.sizes, function(_i, data) {
                    $tb.append(`<tr><td>${data.size}</td><td>${data.quantity}</td></tr>`);
                });
            } else {
                $tb.append('<tr><td colspan="2">No size run data available for this item.</td></tr>');
            }
        }

        // ---- Save (POST batch) ----
        function tdText($tr, idx) {
            return ($tr.find('td').eq(idx).text() || '').trim();
        }

        // extra validation wrapper: ensure depts selected
        $('#saveBtn').off('click.deptCheck').on('click.deptCheck', function() {
            const fromDept = $('#from_dept').val();
            const toDept = $('#to_dept').val();
            if (!fromDept || !toDept) {
                alert('FROM DEPT dan TO DEPT wajib dipilih.');
                return false;
            }
            return true;
        });

        $('#saveBtn').off('click.save').on('click.save', function(e) {
            e.preventDefault();
            if ($('#saveBtn').prop('disabled')) return;

            const dateVal = $('#date').val();
            const kodeSj = $('#kode_sj').val();
            const noSj = $('#no_sj').val();
            const fromDept = $('#from_dept').val();
            const toDept = $('#to_dept').val();

            if (!dateVal || !kodeSj || !noSj || !fromDept || !toDept) {
                alert('Form belum lengkap. Pastikan semua field terisi.');
                return;
            }

            const rows = []; // --> untuk wr_stock
            const sizerun_rows = []; // --> untuk wr_sizerun

            $('#createTable tbody tr').each(function() {
                const $tr = $(this);
                const id_wo = parseInt($tr.data('id'), 10) || 0;

                // mapping kolom sesuai header di tabel createTable
                // 0:Action, 1:Wo Number, 2:Name, 3:Unit, 4:Category, 5:Brand, 6:Art/Color, 7:Consumption, 8:Checkin QTY, 9:Checkin Size
                const wo_number = tdText($tr, 1);
                const item_name = tdText($tr, 2);
                const unit_name = tdText($tr, 3);
                const category_name = tdText($tr, 4);
                const brand_name = tdText($tr, 5);
                const artcolor = tdText($tr, 6);

                const bom_cons = parseFloat($tr.find('td').eq(7).find('input.item-qty').val() || '0');
                const kode_item = $tr.data('itemcode') || '';
                const checkin = ($tr.find('.global-qty-input').val() || '').toString().trim();

                // Lewati baris bila checkin kosong/0 (opsional — hapus blok ini kalau tetap mau simpan 0)
                if (checkin === '' || parseFloat(checkin) <= 0) {
                    return; // continue
                }

                // ----- wr_stock row -----
                rows.push({
                    id_wo,
                    kode_sj: kodeSj,
                    no_sj: noSj,
                    kode_bom: '',
                    wo_number,
                    kode_item,
                    category_name,
                    unit_name,
                    item_name,
                    brand: brand_name,
                    artcolor,
                    bom_cons: bom_cons.toString(),
                    checkin: checkin,
                    checkout: '', // biarkan kosong di create
                    from_dept: fromDept,
                    to_dept: toDept,
                    date_arrive: dateVal // tanggal form masuk ke kolom date_arrive
                });

                // ----- wr_sizerun rows (jika ada di sizeRunData) -----
                const srRec = sizeRunData.find(r => r.id === id_wo);
                if (srRec && Array.isArray(srRec.sizes) && srRec.sizes.length) {
                    srRec.sizes.forEach(s => {
                        const qty = (s.quantity != null && s.quantity !== '') ? String(s.quantity) : '0';
                        sizerun_rows.push({
                            id_wo: id_wo,
                            id_brand: null, // akan diisi di server dari brand_name
                            kode_sj: kodeSj,
                            kode_item: kode_item,
                            wo_number: wo_number,
                            brand_name: brand_name,
                            size_name: s.size,
                            sizeq_qty: qty // ikuti nama kolom di DB: sizeq_qty
                        });
                    });
                }
            });

            if (rows.length === 0) {
                alert('Tidak ada data dengan Checkin > 0 untuk disimpan.');
                return;
            }

            $.ajax({
                url: '<?= site_url('warehouse/save_stock'); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    rows: JSON.stringify(rows),
                    sizerun_rows: JSON.stringify(sizerun_rows)
                },
                success: function(resp) {
                    if (resp && resp.success) {
                        alert('Data berhasil disimpan.');
                        $('#createTable tbody').empty();
                        sizeRunData = [];
                        updateSaveButtonState();
                    } else {
                        alert(resp && resp.message ? resp.message : 'Gagal menyimpan data.');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText || xhr.statusText);
                    alert('Terjadi kesalahan saat menyimpan data.');
                }
            });
        });
    });
</script>