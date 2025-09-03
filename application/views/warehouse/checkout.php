<?php
// Diasumsikan controller mengirim $title dan $request
// $data['title']   = 'Check Out';
// $data['request'] = $this->General_model->get_grouped_ro_for_checkout(true);
?>
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
        margin-top: .75rem
    }

    .mt-3 {
        margin-top: 1rem
    }

    .mb-3 {
        margin-bottom: .75rem
    }

    .readonly-input {
        background: #f9fafb !important;
        pointer-events: none
    }

    /* Pastikan modal menumpuk teratas */
    .ui.dimmer.modals {
        z-index: 2050 !important;
    }

    .ui.modal {
        z-index: 2060 !important;
    }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">
        <?= isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') : 'Checkout Request Order'; ?>
    </h1>

    <?= $this->session->flashdata('message'); ?>

    <div class="ui segment">
        <table class="ui celled table" id="roTable">
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th>WO Number</th>
                    <th>Kode RO</th>
                    <th>Tanggal Terakhir</th>
                    <th style="width:220px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($request)): $no = 1;
                    foreach ($request as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['wo_number'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($row['kode_ro'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($row['last_created_at'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <button class="ui tiny button teal btn-detail"
                                    data-kode="<?= htmlspecialchars($row['kode_ro'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="info circle icon"></i> Detail
                                </button>
                                <button class="ui tiny button blue btn-sj"
                                    data-kode="<?= htmlspecialchars($row['kode_ro'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="shipping fast icon"></i> Buat Surat Jalan
                                </button>
                            </td>
                        </tr>
                <?php endforeach;
                endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal DETAIL RO -->
<div class="ui modal wide-scrollable-modal" id="detailModal">
    <i class="close icon"></i>
    <div class="header">Detail Request Order</div>
    <div class="ui segment">
        <div class="ui list">
            <div class="item"><strong>WO Number:</strong> <span id="dt_wo"></span></div>
            <div class="item"><strong>Kode RO:</strong> <span id="dt_kode"></span></div>
            <div class="item"><strong>Status:</strong> <span id="dt_status"></span></div>
            <div class="item"><strong>From Dept:</strong> <span id="dt_from"></span></div>
            <div class="item"><strong>To Dept:</strong> <span id="dt_to"></span></div>
            <div class="item"><strong>Tanggal RO:</strong> <span id="dt_tgl"></span></div>
            <div class="item"><strong>Dibuat oleh:</strong> <span id="dt_creator"></span></div>
            <div class="item"><strong>Dibuat pada:</strong> <span id="dt_created_at"></span></div>
            <div class="item"><strong>Brand:</strong> <span id="dt_brand"></span></div>
            <div class="item"><strong>Art/Color:</strong> <span id="dt_artcolor"></span></div>
        </div>

        <table class="ui celled table" id="detailLinesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Item</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>RO Qty</th>
                </tr>
            </thead>
            <tbody><!-- inject JS --></tbody>
        </table>
    </div>
</div>

<!-- Modal BUAT SJ -->
<div class="ui modal wide-scrollable-modal" id="sjModal">
    <i class="close icon"></i>
    <div class="header">Buat Surat Pengeluaran Barang</div>
    <div class="ui segment">
        <div class="ui form">
            <div class="fields">
                <div class="four wide field">
                    <label>Kode SJ</label>
                    <input type="text" id="sj_kode" class="readonly-input" readonly placeholder="Auto">
                </div>
                <div class="four wide field">
                    <label>No. SJ</label>
                    <input type="text" id="sj_nomor" class="readonly-input" readonly placeholder="Auto">
                </div>
                <div class="four wide field">
                    <label>Tanggal</label>
                    <input type="date" id="sj_tanggal" value="<?= date('Y-m-d'); ?>">
                </div>
                <div class="four wide field">
                    <label>User</label>
                    <input type="text" id="sj_user" value="<?= $this->session->userdata('email') ?? $this->session->userdata('username'); ?>" readonly>
                </div>
            </div>

            <div class="fields">
                <div class="four wide field">
                    <label>WO Number</label>
                    <input type="text" id="sj_wo" class="readonly-input" readonly>
                </div>
                <div class="four wide field">
                    <label>Kode RO</label>
                    <input type="text" id="sj_ro" class="readonly-input" readonly>
                </div>
                <div class="four wide field">
                    <label>From Dept (SJ)</label>
                    <input type="text" id="sj_from" class="readonly-input" readonly>
                </div>
                <div class="four wide field">
                    <label>To Dept (SJ)</label>
                    <input type="text" id="sj_to" class="readonly-input" readonly>
                </div>
                <div class="ui message"><!-- status akan diisi JS --></div>
            </div>

            <div class="fields">
                <div class="eight wide field">
                    <label>Brand</label>
                    <input type="text" id="sj_brand" class="readonly-input" readonly>
                </div>
                <div class="eight wide field">
                    <label>Art/Color</label>
                    <input type="text" id="sj_artcolor" class="readonly-input" readonly>
                </div>
            </div>
        </div>

        <table class="ui celled table" id="sjLinesTable">
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th>Kode Item</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>RO Qty</th>
                    <th style="width:200px;">Checkout Qty & Status</th>
                    <!-- Kolom baru untuk stock yang tersedia -->
                    <th style="width:200px;">Status Stock</th>
                </tr>
            </thead>
            <tbody><!-- inject JS --></tbody>
        </table>

        <div class="ui divider"></div>
        <button class="ui grey button" id="sjCancelBtn"><i class="arrow left icon"></i> Batal</button>
        <button class="ui green button" id="sjSubmitBtn"><i class="save icon"></i> Simpan & Kirim</button>
    </div>
</div>
</div>

<script>
    // ===== Bridge Konflik Bootstrap vs Semantic (jika Bootstrap ikut dipakai) =====
    try {
        if ($.fn.modal && $.fn.modal.noConflict) {
            var bootstrapModal = $.fn.modal.noConflict();
            $.fn.bsModal = bootstrapModal; // pakai .bsModal() kalau perlu modal Bootstrap
        }
    } catch (e) {}

    // ===== Utils =====
    function esc(s) {
        return $('<div/>').text(s == null ? '' : s).html();
    }

    // Helper untuk enable/disable tombol simpan
    function setSubmitEnabled(enabled, note = '') {
        const $btn = $('#sjSubmitBtn');
        $btn.prop('disabled', !enabled);
        if (note) $btn.attr('title', note);
        else $btn.removeAttr('title');
    }

    (function() {
        // ===== State =====
        let currentHeader = null;
        let currentLines = null;
        let canSubmitByStock = false; // hasil dari renderTotalsAndBadges (total & per-item)
        let canSubmitByInputs = false; // hasil validasi input qty vs max

        // ===== Helpers =====
        function safeInitDT($tbl, opts, holderName) {
            if (window[holderName]) {
                try {
                    window[holderName].destroy();
                } catch (e) {}
            }
            window[holderName] = $tbl.DataTable(opts || {});
        }

        function generateNoSjByDate(dateStr) {
            $.getJSON("<?= base_url('warehouse/generate_no_sj'); ?>", {
                    date: dateStr,
                    _: Date.now()
                })
                .done((res) => {
                    $('#sj_nomor').val(res.no_sj || '');
                })
                .fail(() => {
                    $('#sj_nomor').val('');
                });
        }

        function fillDetailModal(header, lines) {
            const brand = header?.brand ?? header?.brand_name ?? '';
            const art = header?.artcolor ?? header?.artcolor_name ?? '';
            $('#dt_wo').text(header?.wo_number ?? '-');
            $('#dt_kode').text(header?.kode_ro ?? '-');
            $('#dt_status').text(header?.status_ro ?? '-');
            $('#dt_from').text(header?.from_dept ?? '-');
            $('#dt_to').text(header?.to_dept ?? '-');
            $('#dt_tgl').text(header?.date_ro ?? '-');
            $('#dt_creator').text(header?.created_by ?? '-');
            $('#dt_created_at').text(header?.created_at ?? '-');
            $('#dt_brand').text(brand || '-');
            $('#dt_artcolor').text(art || '-');

            let tb = '';
            (lines || []).forEach((ln, i) => {
                tb += `<tr>
                    <td>${i + 1}</td>
                    <td>${esc(ln.kode_item || '')}</td>
                    <td>${esc(ln.item_name || '')}</td>
                    <td>${esc(ln.category || '')}</td>
                    <td>${esc(ln.unit || '')}</td>
                    <td>${esc(ln.ro_qty || '')}</td>
                </tr>`;
            });
            $('#detailLinesTable tbody').html(tb);
        }

        // Validasi semua input checkout dibanding max yang diset
        function validateLineInputs() {
            let valid = true;
            $('#sjLinesTable .sj-checkout').each(function() {
                const max = parseFloat($(this).attr('max') || '0');
                const val = parseFloat($(this).val() || '0');
                if (isNaN(val) || val < 0 || (max > 0 && val > max)) {
                    valid = false;
                    return false; // break
                }
            });
            canSubmitByInputs = valid;
            updateSubmitButton();
        }

        function updateSubmitButton() {
            const can = canSubmitByStock && canSubmitByInputs;
            setSubmitEnabled(can, can ? '' : 'Stok kurang atau qty melebihi batas');
        }

        function renderTotalsAndBadges(header, lines) {
            const items = (lines || []).map(ln => ln?.kode_item).filter(Boolean);

            $('#sjModal .message').html(`<div class="ui info message">Menghitung ketersediaan stok...</div>`);
            canSubmitByStock = false;
            updateSubmitButton();

            $.getJSON("<?= base_url('warehouse/wo_item_totals'); ?>", {
                wo_number: header?.wo_number || '',
                items: items // jQuery serialize sebagai items[]=...
            }).done(function(res) {
                const sum = res?.summary || {};
                const roSum = parseFloat(sum.ro_total || 0);
                const inSum = parseFloat(sum.checkin_total || 0);
                const totalCukup = (inSum >= roSum);
                const rem = inSum - roSum;

                const totalMsg = totalCukup ?
                    `<div class="ui positive message">
             <div class="header">Cukup</div>
             <p>Total Check-in: ${inSum.toLocaleString()} dari RO: ${roSum.toLocaleString()} (Sisa: ${rem.toLocaleString()})</p>
           </div>` :
                    `<div class="ui negative message">
             <div class="header">Kurang</div>
             <p>Total Check-in: ${inSum.toLocaleString()} dari RO: ${roSum.toLocaleString()} (Kurang: ${(roSum - inSum).toLocaleString()})</p>
           </div>`;
                $('#sjModal .message').html(totalMsg);

                // — Per-item status dengan jumlah stock yang lebih besar —
                let allItemsEnough = true;

                $('#sjLinesTable tbody tr').each(function(i) {
                    const ln = (lines || [])[i];
                    if (!ln) return;

                    const kode = ln?.kode_item || '';
                    const rowData = res?.items?.[kode]; // { ro_total, checkin_total }
                    if (!rowData) return;

                    const r = parseFloat(rowData.ro_total || 0);
                    const c = parseFloat(rowData.checkin_total || 0);
                    const rm = r - c;

                    if (c < r) allItemsEnough = false;

                    // Badge status per item (cukup atau kurang)
                    const badge =
                        c >= r ?
                        `<div class="ui tiny green label" title="Check-in ${c} / RO ${r}">cukup</div>` :
                        `<div class="ui tiny red label" title="Check-in ${c} / RO ${r}">kurang (${rm.toLocaleString()})</div>`;

                    const $qtyCell = $(this).find('td').eq(6); // kolom Checkout Qty & Status
                    if ($qtyCell.find('.ui.label').length === 0) {
                        $qtyCell.append(`<div class="mt-2">${badge}</div>`);
                    } else {
                        $qtyCell.find('.ui.label').parent().html(badge);
                    }

                    // Menambahkan status stock di kolom baru "Status Stock"
                    const $statusCell = $(this).find('td').eq(7); // Kolom baru untuk Status Stock
                    const statusMessage = (c >= r) ?
                        `<div class="ui positive message">
                    <div class="header">Cukup</div>
                    <p>Stock Tersedia: ${c.toLocaleString()}</p>
                </div>` :
                        `<div class="ui negative message">
                    <div class="header">Kurang</div>
                    <p>Stock Tersedia: ${c.toLocaleString()} (Kurang: ${(r - c).toLocaleString()})</p>
                </div>`;
                    if ($statusCell.length === 0) {
                        $(this).append(`<td class="status-cell">${statusMessage}</td>`);
                    } else {
                        $statusCell.html(statusMessage);
                    }

                    // Set batas checkout tidak melebihi yang tersedia (stock atau RO)
                    const maxCheckout = Math.max(0, Math.min(c, r));
                    const $inp = $qtyCell.find('input.sj-checkout');
                    $inp.attr('max', maxCheckout);

                    // Jika nilai sekarang > max, turunkan ke max
                    const curVal = parseFloat($inp.val() || '0') || 0;
                    if (curVal > maxCheckout) {
                        $inp.val(maxCheckout);
                    }
                });

                canSubmitByStock = totalCukup && allItemsEnough;
                validateLineInputs(); // juga akan memanggil updateSubmitButton()
            }).fail(function(xhr) {
                console.error(xhr.responseText);
                $('#sjModal .message').html(`<div class="ui warning message">Gagal menghitung ketersediaan stok.</div>`);
                canSubmitByStock = false;
                updateSubmitButton();
            });
        }

        function fillSjModal(header, lines) {
            const roFrom = header?.from_dept ?? '';
            const roTo = header?.to_dept ?? '';
            const brand = header?.brand ?? header?.brand_name ?? '';
            const art = header?.artcolor ?? header?.artcolor_name ?? '';

            // RO From→To dibalik untuk SJ
            $('#sj_from').val(roTo);
            $('#sj_to').val(roFrom);
            $('#sj_wo').val(header?.wo_number ?? '');
            $('#sj_ro').val(header?.kode_ro ?? '');
            $('#sj_brand').val(brand);
            $('#sj_artcolor').val(art);

            const today = '<?= date('Y-m-d'); ?>';
            $('#sj_tanggal').val(today);
            $('#sj_nomor').val('');

            // Generate Kode SJ
            $.getJSON("<?= base_url('warehouse/generate_kode_sj'); ?>", {
                    from_dept: $('#sj_from').val() || '',
                    to_dept: $('#sj_to').val() || '',
                    _: Date.now(),
                })
                .done((res) => {
                    $('#sj_kode').val(res.kode_sj || '');
                })
                .fail(() => {
                    $('#sj_kode').val('');
                });

            // Generate Nomor SJ (berdasarkan tanggal)
            generateNoSjByDate(today);

            // Build tabel baris
            let tb = '';
            let totalCheckoutQty = 0;
            (lines || []).forEach((ln, i) => {
                const roq = parseFloat(ln?.ro_qty ?? 0) || 0;
                tb += `<tr>
                    <td>${i + 1}</td>
                    <td>${esc(ln.kode_item || '')}</td>
                    <td>${esc(ln.item_name || '')}</td>
                    <td>${esc(ln.category || '')}</td>
                    <td>${esc(ln.unit || '')}</td>
                    <td>${roq}</td>
                    <td>
                        <input type="number" min="0" step="0.0001" value="${roq}" class="sj-checkout"
                            data-kode="${esc(ln.kode_item || '')}"
                            data-item="${esc(ln.item_name || '')}"
                            data-cat ="${esc(ln.category || '')}"
                            data-unit="${esc(ln.unit || '')}">
                    </td>
                </tr>`;
                totalCheckoutQty += roq;
            });
            $('#sjLinesTable tbody').html(tb);

            // Info awal sebelum hitung stok (opsional)
            const totalROQty = (lines || []).reduce(
                (a, ln) => a + (parseFloat(ln?.ro_qty ?? 0) || 0),
                0
            );
            $('#sjModal .message').html(
                `<div class="ui info message">
                    Total RO Qty: ${totalROQty.toLocaleString()} | Total Checkout (sementara): ${totalCheckoutQty.toLocaleString()}
                </div>`
            );

            // Reset state tombol sebelum kalkulasi server
            canSubmitByStock = false;
            canSubmitByInputs = false;
            updateSubmitButton();

            // Hitung status dari stok (server)
            renderTotalsAndBadges(header, lines);
        }

        // ==== Init DataTable & Modal placement ====
        $(function() {
            safeInitDT(
                $('#roTable'), {
                    paging: true,
                    searching: true,
                    info: false,
                    lengthChange: false,
                },
                'roDT'
            );

            // Pastikan modal berada langsung di <body> & inisialisasi
            $('#detailModal')
                .appendTo('body')
                .modal({
                    observeChanges: true,
                    autofocus: false,
                    closable: true,
                });
            $('#sjModal')
                .appendTo('body')
                .modal({
                    observeChanges: true,
                    autofocus: false,
                    closable: true,
                });
        });

        // Update nomor SJ saat ganti tanggal
        $(document).on('change', '#sj_tanggal', function() {
            const d = $(this).val() || '<?= date('Y-m-d'); ?>';
            generateNoSjByDate(d);
        });

        // ==== Actions ====

        // DETAIL
        $(document).on('click', '.btn-detail', function() {
            const kode = $(this).data('kode') || '';
            if (!kode) return;

            $.getJSON("<?= base_url('warehouse/ro_details'); ?>", {
                    kode_ro: kode,
                    _: Date.now()
                })
                .done(function(resp) {
                    fillDetailModal(resp.header || {}, resp.lines || []);
                    $('#detailModal').appendTo('body').modal('show');
                })
                .fail(function(xhr) {
                    console.error(xhr.responseText);
                    alert('Gagal mengambil detail RO.');
                });
        });

        // BUAT SJ
        $(document).on('click', '.btn-sj', function() {
            const kode = $(this).data('kode') || '';
            if (!kode) return;

            $.getJSON("<?= base_url('warehouse/ro_details'); ?>", {
                    kode_ro: kode,
                    _: Date.now()
                })
                .done(function(resp) {
                    currentHeader = resp.header || {};
                    currentLines = resp.lines || [];
                    if (!currentHeader || !currentHeader.kode_ro) {
                        alert('Header RO tidak ditemukan.');
                        return;
                    }
                    if ((currentHeader.status_ro || '').toLowerCase() === 'sudah dikirim') {
                        alert('RO ini sudah dikirim.');
                        return;
                    }
                    fillSjModal(currentHeader, currentLines);
                    $('#sjModal').appendTo('body').modal('show');
                })
                .fail(function(xhr) {
                    console.error(xhr.responseText);
                    alert('Gagal memuat data RO.');
                });
        });

        // Validasi input realtime
        $(document).on('input change', '#sjLinesTable .sj-checkout', function() {
            validateLineInputs();
        });

        $('#sjCancelBtn').on('click', function() {
            $('#sjModal').modal('hide');
        });

        // SUBMIT SJ
        $('#sjSubmitBtn').on('click', function() {
            // Last guard (hindari bypass)
            if (!(canSubmitByStock && canSubmitByInputs)) {
                alert('Tidak bisa kirim: stok kurang atau qty tidak valid.');
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true);

            const kode_sj = ($('#sj_kode').val() || '').trim();
            const no_sj = ($('#sj_nomor').val() || '').trim();
            const tgl_sj = $('#sj_tanggal').val();
            const user = $('#sj_user').val();

            const hdr = currentHeader || {};
            const wo_number = hdr.wo_number || '';
            const kode_ro = hdr.kode_ro || '';
            const id_wo = hdr.id_wo || 0;

            const sj_from = $('#sj_from').val() || '';
            const sj_to = $('#sj_to').val() || '';

            const brandName = $('#sj_brand').val() || (hdr.brand ?? hdr.brand_name ?? '');
            const artName = $('#sj_artcolor').val() || (hdr.artcolor ?? hdr.artcolor_name ?? '');

            if (!no_sj) {
                alert('No. SJ belum tergenerate.');
                btn.prop('disabled', false);
                return;
            }
            if (!tgl_sj) {
                alert('Tanggal SJ wajib diisi.');
                btn.prop('disabled', false);
                return;
            }

            // Kumpulkan lines
            const lines = [];
            let inputsOk = true;
            $('#sjLinesTable .sj-checkout').each(function() {
                const $i = $(this);
                const qty = parseFloat($i.val() || 0);
                const max = parseFloat($i.attr('max') || '0');
                if (isNaN(qty) || qty < 0 || (max > 0 && qty > max)) {
                    inputsOk = false;
                    return false; // break
                }
                lines.push({
                    kode_item: $i.data('kode') || '',
                    item_name: $i.data('item') || '',
                    category: $i.data('cat') || '',
                    unit: $i.data('unit') || '',
                    checkout: String(qty),
                });
            });
            if (!inputsOk) {
                alert('Qty checkout tidak valid atau melebihi batas.');
                btn.prop('disabled', false);
                return;
            }
            if (!lines.length) {
                alert('Tidak ada baris checkout.');
                btn.prop('disabled', false);
                return;
            }

            const payload = {
                kode_ro: kode_ro,
                header: {
                    id_wo: id_wo,
                    wo_number: wo_number,
                    from_dept: sj_from,
                    to_dept: sj_to,
                    kode_sj: kode_sj,
                    no_sj: no_sj,
                    date_sj: tgl_sj + ' 00:00:00',
                    created_by: user,
                    brand_name: brandName,
                    artcolor_name: artName,
                    brand: brandName,
                    artcolor: artName,
                },
                lines: lines,
            };

            $.ajax({
                url: "<?= base_url('warehouse/checkout_save'); ?>",
                type: 'POST',
                data: JSON.stringify(payload),
                contentType: 'application/json',
                success: function(res) {
                    alert('Surat Jalan berhasil disimpan & status RO diupdate.');
                    location.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Gagal menyimpan Surat Jalan.');
                    btn.prop('disabled', false);
                },
            });
        });
    })();
</script>