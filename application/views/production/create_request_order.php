<!-- application/views/purchasing/request_order_form.php -->
<style>
    .wide-scrollable-modal {
        max-width: 95% !important;
        width: auto !important;
        max-height: 90vh;
        overflow-y: auto;
    }

    .wide-scrollable-modal .ui.segment {
        overflow-x: auto;
        padding: .5em 1em;
    }

    .wide-scrollable-modal table.ui.celled.table {
        min-width: 800px;
        width: auto;
    }

    .mt-2 {
        margin-top: .75rem;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .mb-3 {
        margin-bottom: .75rem;
    }

    .readonly-input {
        background: #f9fafb !important;
        pointer-events: none;
    }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= isset($title) ? $title : 'Request Order'; ?></h1>

    <?= form_error('request_order', '<div class="alert alert-danger" role="alert">', '</div>') ?>
    <?= $this->session->flashdata('message'); ?>

    <!-- Back & Pickers -->
    <a href="<?= base_url('production'); ?>" class="ui blue button mb-3">
        <i class="arrow left icon"></i> Back
    </a>
    <button type="button" class="ui blue button" id="openWoBtn">Choose / Change WO</button>
    <button type="button" class="ui blue button disabled" id="openItemBtn" title="Pilih WO terlebih dahulu">Add Item</button>

    <!-- Header Form -->
    <div class="ui form mt-3">
        <div class="fields">
            <div class="five wide field">
                <label>WO Number</label>
                <div class="ui action input">
                    <input type="text" id="woNumber" readonly>
                    <input type="hidden" id="woId">
                    <input type="hidden" id="woBrand">
                    <input type="hidden" id="woArtcolor"><!-- NEW: simpan artcolor -->
                    <button type="button" class="ui teal button" id="openWoBtn2"><i class="search icon"></i></button>
                </div>
            </div>
            <div class="four wide field">
                <label>Kode RO</label>
                <input type="text" id="kodeRo" placeholder="Auto" class="readonly-input" readonly>
            </div>
            <div class="three wide field">
                <label>Tanggal RO</label>
                <input type="date" id="dateRo" value="<?= date('Y-m-d'); ?>">
            </div>
            <div class="four wide field">
                <label>Created By</label>
                <input type="text" id="createdBy" value="<?= $this->session->userdata('email') ?? $this->session->userdata('username') ?>" readonly>
            </div>
        </div>

        <div class="fields">
            <!-- From Dept -->
            <div class="four wide field">
                <label>From Dept</label>
                <div class="ui search selection dropdown" id="fromDeptDd">
                    <input type="hidden" id="fromDept">
                    <i class="dropdown icon"></i>
                    <div class="default text">Select From Dept</div>
                    <div class="menu"><!-- diisi via JS --></div>
                </div>
            </div>

            <!-- To Dept -->
            <div class="four wide field">
                <label>To Dept</label>
                <div class="ui search selection dropdown" id="toDeptDd">
                    <input type="hidden" id="toDept">
                    <i class="dropdown icon"></i>
                    <div class="default text">Select To Dept</div>
                    <div class="menu"><!-- diisi via JS --></div>
                </div>
            </div>

            <!-- Hidden utk kode dept (generator kode RO) -->
            <input type="hidden" id="fromDeptCode">
            <input type="hidden" id="toDeptCode">

            <div class="eight wide field">
                <label>Catatan</label>
                <input type="text" id="roNote" placeholder="(opsional) catatan untuk RO ini">
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="ui top attached tabular menu mt-3">
        <a class="item active" data-tab="tab-items"><i class="boxes icon"></i> Items</a>
        <a class="item" data-tab="tab-sizerun"><i class="ruler combined icon"></i> Sizerun</a>
    </div>

    <!-- Tab: Items -->
    <div class="ui bottom attached active tab segment" data-tab="tab-items">
        <table class="ui celled table" id="roTable">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Kode Item</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>BOM Cons</th>
                    <th style="width:160px;">Qty (readonly)</th>
                    <th style="width:120px;">Action</th>
                </tr>
            </thead>
            <tbody><!-- JS --></tbody>
        </table>
        <div class="ui message">
            <div class="header">Catatan Qty</div>
            <p>Qty = <strong>Total Sizerun</strong> × <strong>BOM QTY</strong> (per item dari WO). Nilai diisi otomatis & readonly.</p>
        </div>
    </div>

    <!-- Tab: Sizerun -->
    <div class="ui bottom attached tab segment" data-tab="tab-sizerun">
        <div class="ui form">
            <div class="fields">
                <div class="six wide field">
                    <label>Brand (auto dari WO)</label>
                    <input type="text" id="brandReadonly" class="readonly-input" readonly>
                </div>
                <div class="six wide field">
                    <label>Art/Color (auto dari WO)</label>
                    <input type="text" id="artReadonly" class="readonly-input" readonly><!-- NEW: tampilkan artcolor -->
                </div>
                <div class="four wide field">
                    <label>Total Sizerun (pairs)</label>
                    <input type="number" id="totalSizerun" class="readonly-input" value="0" readonly>
                </div>
            </div>
        </div>
        <table class="ui celled table" id="sizerunTable">
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th>Size</th>
                    <th style="width:180px;">Pairs</th>
                </tr>
            </thead>
            <tbody><!-- JS --></tbody>
        </table>
    </div>

    <!-- Actions -->
    <button class="ui green button" id="reviewSubmitBtn"><i class="check icon"></i> Review &amp; Submit</button>
</div>

<!-- WO Modal -->
<div class="ui modal wide-scrollable-modal" id="woModal">
    <i class="close icon"></i>
    <div class="header">Select Work Order</div>
    <div class="ui segment">
        <table class="ui celled table" id="woTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Select</th>
                    <th>WO Number</th>
                    <th>FG Kode</th>
                    <th>FG Name</th>
                    <th>FG Category</th>
                    <th>FG Unit</th>
                    <th>Brand</th>
                    <th>Art/Color</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody><!-- JS --></tbody>
        </table>
    </div>
</div>

<!-- Item Modal -->
<div class="ui modal wide-scrollable-modal" id="itemModal">
    <i class="close icon"></i>
    <div class="header">Select Item (Materials of selected WO)</div>
    <div class="ui segment">
        <table class="ui celled table" id="itemPickTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Qty</th>
                    <th>Select</th>
                    <th>Kode Item</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>BOM Cons</th>
                    <th>BOM QTY</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody><!-- JS --></tbody>
        </table>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="ui modal wide-scrollable-modal" id="confirmModal">
    <i class="close icon"></i>
    <div class="header">Confirm Request Order</div>
    <div class="ui segment">
        <div class="ui list">
            <div class="item"><strong>WO Number:</strong> <span id="cf_wo"></span></div>
            <div class="item"><strong>Kode RO:</strong> <span id="cf_kode"></span></div>
            <div class="item"><strong>Tanggal:</strong> <span id="cf_tgl"></span></div>
            <div class="item"><strong>From Dept:</strong> <span id="cf_from"></span></div>
            <div class="item"><strong>To Dept:</strong> <span id="cf_to"></span></div>
            <div class="item"><strong>Brand:</strong> <span id="cf_brand"></span></div><!-- NEW -->
            <div class="item"><strong>Art/Color:</strong> <span id="cf_art"></span></div><!-- NEW -->
            <div class="item"><strong>Created By:</strong> <span id="cf_creator"></span></div>
            <div class="item"><strong>Catatan:</strong> <span id="cf_note"></span></div>
            <div class="item"><strong>Total Sizerun:</strong> <span id="cf_total_size"></span></div>
        </div>

        <table class="ui celled table" id="confirmTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Item</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>BOM Cons</th>
                    <th>BOM QTY</th>
                    <th>Qty (= total size × bom_qty)</th>
                </tr>
            </thead>
            <tbody><!-- JS --></tbody>
        </table>

        <div class="ui divider"></div>
        <button class="ui grey button" id="editBackBtn"><i class="arrow left icon"></i> Edit Again</button>
        <button class="ui green button" id="confirmSubmitBtn"><i class="save icon"></i> Confirm & Submit</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        // ====== SEMANTIC & TABS ======
        $('.menu .item').tab();
        $('#fromDeptDd, #toDeptDd').dropdown();

        // Hidden input utk kode dept (kalau belum ada)
        if (!$('#fromDeptCode').length) $('body').append('<input type="hidden" id="fromDeptCode">');
        if (!$('#toDeptCode').length) $('body').append('<input type="hidden" id="toDeptCode">');

        // ====== STATE ======
        let roItems = []; // [{kode,name,cat,unit,bom_cons,bom_qty,qty}]
        let selectedWoNumber = null; // string
        let selectedBrandName = null; // string (WO)
        let selectedArtColor = null; // string (WO)  <-- NEW
        let woDT = null,
            itemDT = null; // DataTable instances

        // ====== HELPERS ======
        function safeInitDT($tbl, opts, key) {
            if (window[key]) {
                try {
                    window[key].destroy();
                } catch (e) {}
            }
            window[key] = $tbl.DataTable(opts);
        }

        function enableAddItemBtn(enabled) {
            $('#openItemBtn')[enabled ? 'removeClass' : 'addClass']('disabled')
                .attr('title', enabled ? '' : 'Pilih WO terlebih dahulu');
        }

        function renderRoTable() {
            let tb = '';
            roItems.forEach((it, idx) => {
                const qtyDisp = (isFinite(it.qty) ? (+it.qty).toFixed(4).replace(/\.?0+$/, '') : '0');
                tb += `
          <tr>
            <td>${idx + 1}</td>
            <td>${it.kode}</td>
            <td>${it.name}</td>
            <td>${it.cat}</td>
            <td>${it.unit}</td>
            <td>${it.bom_cons ?? ''}</td>
            <td>
              <input type="number" class="readonly-input" readonly
                     value="${qtyDisp}" step="0.0001" style="width:140px">
            </td>
            <td><button class="ui tiny red button del-item" data-idx="${idx}">Delete</button></td>
          </tr>`;
            });
            $('#roTable tbody').html(tb);
        }

        function recalcQtyFromSizerun() {
            const totalSize = parseFloat($('#totalSizerun').val() || 0);
            roItems = roItems.map(it => {
                const bq = parseFloat(it.bom_qty || 0);
                const q = totalSize * bq;
                return {
                    ...it,
                    qty: (isFinite(q) ? q : 0)
                };
            });
            renderRoTable();
        }

        function recomputeTotalSizerun() {
            let sum = 0;
            $('#sizerunTable .sizerun-qty').each(function() {
                const v = parseFloat($(this).val() || 0);
                if (!isNaN(v)) sum += v;
            });
            $('#totalSizerun').val(sum);
            recalcQtyFromSizerun();
        }

        function loadDepartments() {
            const url = "<?= base_url('production/dept_list'); ?>";
            const bustUrl = url + (url.indexOf('?') === -1 ? '?' : '&') + '_=' + Date.now();

            $.ajax({
                url: bustUrl,
                type: 'GET',
                dataType: 'json',
                cache: false,
                success: function(resp) {
                    const rows = resp.data || [];
                    rows.sort((a, b) => (a.dept_name || '').localeCompare(b.dept_name || ''));

                    const itemsHtml = rows.map(r => `
            <div class="item" data-value="${r.dept_name}" data-code="${(r.kode_dept||'').toUpperCase()}">
              ${r.dept_name}
            </div>`).join('');

                    // FROM
                    const $from = $('#fromDeptDd');
                    $from.find('.menu').html(itemsHtml);
                    $from.dropdown('clear');
                    $from.dropdown('refresh');

                    // TO
                    const $to = $('#toDeptDd');
                    $to.find('.menu').html(itemsHtml);
                    $to.dropdown('clear');
                    $to.dropdown('refresh');
                },
                error: function(xhr) {
                    console.error('dept_list error:', xhr.responseText);
                }
            });
        }

        function maybeGenerateKodeRo() {
            const fromCode = ($('#fromDeptCode').val() || '').trim();
            const toCode = ($('#toDeptCode').val() || '').trim();

            if (!fromCode || !toCode) {
                $('#kodeRo').val('');
                return;
            }

            const url = "<?= base_url('production/generate_kode_ro'); ?>";
            const params = $.param({
                from_code: fromCode,
                to_code: toCode,
                _: Date.now()
            });

            $.getJSON(url + '?' + params, function(res) {
                $('#kodeRo').val(res.kode_ro || '');
            }).fail(function(xhr) {
                console.error('generate_kode_ro error:', xhr.responseText);
                $('#kodeRo').val('');
            });
        }

        // Dropdown change handlers
        $('#fromDeptDd').dropdown({
            onChange: function(value, text, $choice) {
                $('#fromDept').val(value);
                $('#fromDeptCode').val($choice && $choice.data('code') ? $choice.data('code') : '');
                maybeGenerateKodeRo();
            }
        });
        $('#toDeptDd').dropdown({
            onChange: function(value, text, $choice) {
                $('#toDept').val(value);
                $('#toDeptCode').val($choice && $choice.data('code') ? $choice.data('code') : '');
                maybeGenerateKodeRo();
            }
        });

        // ====== LOADERS (WO / Items / Sizerun) ======
        function loadWoList() {
            $.getJSON("<?= base_url('production/wo_list'); ?>", function(resp) {
                const rows = resp.data || [];
                let tb = '';
                rows.forEach((r, i) => {
                    tb += `
            <tr>
              <td>${i + 1}</td>
              <td>
                <button class="ui tiny blue button select-wo"
                  data-id="${r.id_wo}"
                  data-number="${r.wo_number}"
                  data-brand="${r.brand_name || ''}"
                  data-artcolor="${r.artcolor_name || ''}">✔ Select</button>
              </td>
              <td>${r.wo_number}</td>
              <td>${r.fg_kode_item || '-'}</td>
              <td>${r.fg_item_name || '-'}</td>
              <td>${r.fg_category_name || '-'}</td>
              <td>${r.fg_unit || '-'}</td>
              <td>${r.brand_name || '-'}</td>
              <td>${r.artcolor_name || '-'}</td>
              <td>${r.due_date || ''}</td>
            </tr>`;
                });
                $('#woTable tbody').html(tb);
                safeInitDT($('#woTable'), {
                    paging: true,
                    searching: true,
                    info: false,
                    lengthChange: false
                }, 'woDT');
            });
        }

        function loadItemsForWO(woNum, brandName) {
            if (!woNum) return;
            $.getJSON("<?= base_url('production/wo_items'); ?>", {
                wo_number: woNum,
                brand_name: brandName || ''
            }, function(resp) {
                const rows = resp.data || [];
                let tb = '';
                rows.forEach((it, i) => {
                    const bomCons = (it.bom_cons != null) ? String(it.bom_cons) : '';
                    const bomQty = (it.bom_qty != null) ? String(it.bom_qty) : '0';
                    tb += `
            <tr>
              <td>${i + 1}</td>
              <td><input type="number" min="1" value="1" class="modal-qty" data-kode="${it.kode_item}"></td>
              <td>
                <button class="ui tiny blue button select-item"
                  data-id="${it.id_wo}"
                  data-kode="${it.kode_item}"
                  data-name="${it.item_name}"
                  data-cat="${it.category_name}"
                  data-unit="${it.unit_name}"
                  data-bomcons="${bomCons}"
                  data-bomqty="${bomQty}">✔ Select</button>
              </td>
              <td>${it.kode_item}</td>
              <td>${it.item_name}</td>
              <td>${it.category_name}</td>
              <td>${bomCons}</td>
              <td>${bomQty}</td>
              <td>${it.unit_name}</td>
            </tr>`;
                });
                $('#itemPickTable tbody').html(tb);
                safeInitDT($('#itemPickTable'), {
                    paging: true,
                    searching: true,
                    info: false,
                    lengthChange: false
                }, 'itemDT');
            });
        }

        function loadSizerun(woNumber, brandName) {
            $.ajax({
                url: "<?= base_url('production/get_sizerun_data'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    wo_number: woNumber,
                    brand_name: brandName || ''
                },
                success: function(rows) {
                    let tb = '',
                        idx = 1;
                    (rows || []).forEach(r => {
                        const size = r.size_name || r.size || '';
                        const qty = (typeof r.size_qty !== 'undefined') ? r.size_qty : 0;
                        tb += `
              <tr>
                <td>${idx++}</td>
                <td>${size}</td>
                <td><input type="number" min="0" value="${qty}" class="sizerun-qty" data-size="${size}" style="width:140px"></td>
              </tr>`;
                    });
                    $('#sizerunTable tbody').html(tb);
                    $('#brandReadonly').val(selectedBrandName || '');
                    $('#artReadonly').val(selectedArtColor || '');
                    recomputeTotalSizerun();
                }
            });
        }

        // ====== OPEN MODALS ======
        $('#openWoBtn, #openWoBtn2').on('click', function() {
            $('#woModal').modal('show');
            loadWoList();
        });

        enableAddItemBtn(false); // disabled sampai WO dipilih

        $('#openItemBtn').on('click', function() {
            if ($(this).hasClass('disabled')) {
                alert('Pilih WO terlebih dahulu.');
                return;
            }
            if (selectedWoNumber && !$('#itemPickTable tbody').children().length) {
                loadItemsForWO(selectedWoNumber, selectedBrandName);
            }
            $('#itemModal').modal('show');
        });

        // ====== SELECT WO ======
        $(document).on('click', '.select-wo', function() {
            const id = $(this).data('id');
            const num = $(this).data('number');
            const brand = ($(this).data('brand') || '').toString();
            const art = ($(this).data('artcolor') || '').toString(); // NEW

            if (selectedWoNumber && selectedWoNumber !== num && roItems.length > 0) {
                if (!confirm('Mengganti WO akan mengosongkan daftar item & sizerun. Lanjutkan?')) return;
                roItems = [];
                renderRoTable();
                $('#sizerunTable tbody').empty();
                $('#totalSizerun').val(0);
            }

            $('#woId').val(id);
            $('#woNumber').val(num);
            $('#woBrand').val(brand);
            $('#woArtcolor').val(art); // NEW
            selectedWoNumber = num;
            selectedBrandName = brand;
            selectedArtColor = art; // NEW
            $('#brandReadonly').val(brand);
            $('#artReadonly').val(art); // NEW

            enableAddItemBtn(true);
            loadItemsForWO(selectedWoNumber, selectedBrandName);
            loadSizerun(selectedWoNumber, selectedBrandName);

            $('#woModal').modal('hide');
            maybeGenerateKodeRo();
        });

        // ====== PICK ITEM DARI MODAL ======
        $(document).on('click', '.select-item', function() {
            const kode = $(this).data('kode');
            const name = $(this).data('name');
            const cat = $(this).data('cat');
            const unit = $(this).data('unit');
            const bomCons = String($(this).data('bomcons') ?? '');
            const bomQty = String($(this).data('bomqty') ?? '0');

            const exists = roItems.some(x => x.kode === kode);
            if (exists) {
                alert('Item sudah ada di daftar.');
                $('#itemModal').modal('hide');
                return;
            }

            const totalSize = parseFloat($('#totalSizerun').val() || 0);
            const qty = totalSize * parseFloat(bomQty || 0);

            roItems.push({
                kode,
                name,
                cat,
                unit,
                bom_cons: bomCons,
                bom_qty: bomQty,
                qty: (isFinite(qty) ? qty : 0)
            });
            renderRoTable();
            $('#itemModal').modal('hide');
        });

        // ====== DELETE ITEM ======
        $(document).on('click', '.del-item', function() {
            const idx = $(this).data('idx');
            if (confirm('Hapus item ini dari RO?')) {
                roItems.splice(idx, 1);
                renderRoTable();
            }
        });

        // ====== SIZERUN CHANGE ======
        $(document).on('input change', '.sizerun-qty', function() {
            recomputeTotalSizerun();
        });

        // ====== REVIEW & SUBMIT (OPEN CONFIRM) ======
        $('#reviewSubmitBtn').on('click', function() {
            const woId = $('#woId').val();
            const woNumber = $('#woNumber').val();
            const kodeRo = $('#kodeRo').val();
            const fromDept = $('#fromDept').val();
            const toDept = $('#toDept').val();
            const dateRo = $('#dateRo').val();

            if (!woId || !woNumber) {
                alert('Pilih WO terlebih dahulu.');
                return;
            }
            if (!fromDept) {
                alert('Pilih From Dept.');
                return;
            }
            if (!toDept) {
                alert('Pilih To Dept.');
                return;
            }
            if (!kodeRo) {
                alert('Kode RO belum tergenerate.');
                return;
            }
            if (roItems.length === 0) {
                alert('Tambahkan minimal satu item.');
                return;
            }

            // Ringkasan header
            $('#cf_wo').text(woNumber);
            $('#cf_kode').text(kodeRo);
            $('#cf_tgl').text(dateRo);
            $('#cf_from').text(fromDept);
            $('#cf_to').text(toDept);
            $('#cf_brand').text(selectedBrandName || ''); // NEW
            $('#cf_art').text(selectedArtColor || ''); // NEW
            $('#cf_creator').text($('#createdBy').val());
            $('#cf_note').text($('#roNote').val() || '-');
            $('#cf_total_size').text($('#totalSizerun').val());

            // Tabel konfirmasi
            let tb = '';
            roItems.forEach((it, i) => {
                const qtyDisp = (isFinite(it.qty) ? (+it.qty).toFixed(4).replace(/\.?0+$/, '') : '0');
                tb += `
          <tr>
            <td>${i + 1}</td>
            <td>${it.kode}</td>
            <td>${it.name}</td>
            <td>${it.cat}</td>
            <td>${it.unit}</td>
            <td>${it.bom_cons ?? ''}</td>
            <td>${it.bom_qty ?? ''}</td>
            <td>${qtyDisp}</td>
          </tr>`;
            });
            $('#confirmTable tbody').html(tb);
            $('#confirmModal').modal('show');
        });

        // ====== BACK FROM CONFIRM ======
        $('#editBackBtn').on('click', function() {
            $('#confirmModal').modal('hide');
        });

        // ====== FINAL SUBMIT (SIMPAN KE DB pr_ro) ======
        $('#confirmSubmitBtn').on('click', function() {
            const nowDt = new Date().toISOString().slice(0, 19).replace('T', ' ');
            const woId = parseInt($('#woId').val() || 0);
            const woNumber = $('#woNumber').val();
            const kodeRo = $('#kodeRo').val();
            const fromDept = $('#fromDept').val();
            const toDept = $('#toDept').val();
            const dateRo = $('#dateRo').val() + ' 00:00:00';
            const creator = $('#createdBy').val();

            // >>> ambil total sizerun (pairs)
            const totalSizerunPairs = String($('#totalSizerun').val() || '0');

            const payload = roItems.map(it => ({
                id_wo: woId,
                wo_number: woNumber,
                kode_ro: kodeRo,

                // **samakan dengan kolom DB**
                brand_name: (selectedBrandName || ''),
                artcolor_name: (selectedArtColor || ''),

                kode_item: it.kode,
                item_name: it.name,
                category: it.cat,
                unit: it.unit,

                // >>> kirim total sizerun ke kolom size_qty
                size_qty: totalSizerunPairs,

                ro_qty: String(
                    isFinite(it.qty) ? (+it.qty).toFixed(4).replace(/\.?0+$/, '') : '0'
                ),
                from_dept: fromDept,
                to_dept: toDept,

                // biarkan status_ro diisi di controller
                date_ro: dateRo,
                created_by: creator,
                created_at: nowDt
            }));

            $('#confirmSubmitBtn').prop('disabled', true);

            $.ajax({
                url: "<?= base_url('production/save_ro'); ?>",
                type: "POST",
                data: JSON.stringify(payload),
                contentType: "application/json",
                success: function(res) {
                    alert("Request Order berhasil disimpan.");
                    window.location.href = "<?= base_url('production'); ?>";
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat menyimpan RO.");
                },
                complete: function() {
                    $('#confirmSubmitBtn').prop('disabled', false);
                }
            });
        });
        // ====== INIT CALLS ======
        loadDepartments();
    });
</script>