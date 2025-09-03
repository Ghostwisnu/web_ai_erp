<style>
    /* ===== Universal scroll for all Semantic UI modals ===== */
    .ui.modal.scrolling>.content,
    .ui.modal>.scrolling.content,
    .ui.modal>.content {
        max-height: min(70vh, calc(100vh - 12rem));
        /* fleksibel, adaptif layar */
        overflow-y: auto !important;
        /* vertikal scroll */
        -webkit-overflow-scrolling: touch;
        /* momentum scroll iOS */
    }

    /* Tabel lebar di dalam modal: scroll horizontal */
    .ui.modal>.content table {
        display: block;
        /* agar overflow-x bekerja */
        overflow-x: auto;
        width: 100%;
    }

    /* Optional: wrapper jika kamu pakai .modal-body-scroll */
    .modal-body-scroll {
        max-height: 60vh;
        overflow-y: auto;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* pastikan konten modal bisa scroll vertikal */
    .ui.modal>.scrolling.content {
        max-height: 70vh;
        /* tinggi area konten */
        overflow-y: auto !important;
        /* paksa scroll vertikal */
        -webkit-overflow-scrolling: touch;
    }

    /* wrapper khusus body tabel agar robust di semua browser */
    .modal-body-scroll {
        max-height: 60vh;
        /* sisakan ruang untuk header/actions */
        overflow-y: auto;
        /* scroll vertikal di sini juga */
        overflow-x: auto;
        /* dan scroll horizontal jika tabel lebar */
        -webkit-overflow-scrolling: touch;
    }

    /* kalau sebelumnya ada table { display:block }, biarkan;
   tapi ini memastikan tabel tidak memaksa tinggi konten */
    #wrSizeRunByRowTable {
        width: 100%;
    }
</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') : 'Master Item'; ?></h1>

    <?php if ($this->session->flashdata('message')): ?>
        <div class="ui message">
            <?= $this->session->flashdata('message'); ?>
        </div>
    <?php endif; ?>

    <?php
    // ===================== GROUPING (by category_id first, fallback category_name) =====================
    $tabs = [
        'Barang Jadi'          => [],
        'Barang Setengah Jadi' => [],
        'Material'             => [],
    ];

    function mapCategoryToTab(array $row): string
    {
        // Sesuaikan mapping ID jika berbeda
        $mapById = [1 => 'Material', 2 => 'Barang Setengah Jadi', 3 => 'Barang Jadi'];
        if (isset($row['category_id'])) {
            $cid = (int)$row['category_id'];
            if (isset($mapById[$cid])) return $mapById[$cid];
        }

        $name = strtoupper(trim($row['category_name'] ?? ''));
        if (in_array($name, ['BARANG JADI', 'FINISHED GOODS', 'FINISHED', 'FG'], true))  return 'Barang Jadi';
        if (in_array($name, ['BARANG SETENGAH JADI', 'SEMI FINISHED', 'SEMI-FINISHED', 'HALF FINISHED GOODS', 'HFG', 'SEMI'], true)) return 'Barang Setengah Jadi';
        if (in_array($name, ['MATERIAL', 'RAW MATERIAL', 'MT'], true)) return 'Material';

        return 'Material';
    }

    if (!empty($master_items) && is_array($master_items)) {
        foreach ($master_items as $it) {
            $tabs[mapCategoryToTab($it)][] = $it;
        }
    }

    // ===================== TABLE RENDERER =====================
    function render_items_table(array $items): void
    { ?>
        <div class="wide-scrollable-modal" style="overflow-x:auto">
            <table class="ui celled striped compact table items-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Aksi</th>
                        <th>Kode Item</th>
                        <th>Nama Item</th>
                        <th>Unit</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr class="original-empty">
                            <td colspan="9" class="center aligned">Tidak ada data.</td>
                        </tr>
                        <?php else: $i = 1;
                        foreach ($items as $row):
                            $kode = htmlspecialchars($row['kode_item']     ?? '-', ENT_QUOTES, 'UTF-8');
                            $nama = htmlspecialchars($row['item_name']     ?? '-', ENT_QUOTES, 'UTF-8');
                            $unit = htmlspecialchars($row['unit_name']     ?? '-', ENT_QUOTES, 'UTF-8');
                            $cat  = htmlspecialchars($row['category_name'] ?? '-', ENT_QUOTES, 'UTF-8');
                            $br   = htmlspecialchars($row['brand_name']    ?? '-', ENT_QUOTES, 'UTF-8');
                            $cby  = htmlspecialchars($row['created_by']    ?? '-', ENT_QUOTES, 'UTF-8');
                            $catd = htmlspecialchars($row['created_at']    ?? '-', ENT_QUOTES, 'UTF-8');
                        ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td>
                                    <!-- Tombol STOCK (detail baris per baris dari wr_stock) -->
                                    <button
                                        class="ui tiny teal button open-stock-by-item"
                                        data-itemname="<?= $nama === '-' ? '' : $nama; ?>"
                                        data-kodeitem="<?= $kode === '-' ? '' : $kode; ?>"
                                        data-brand="<?= $br === '-' ? '' : $br; ?>">
                                        <i class="boxes icon"></i> Summary
                                    </button>
                                    <!-- Tombol SUMMARY (agregasi per WO + brand untuk item ini) -->
                                    <button
                                        class="ui tiny purple button open-stock-summary"
                                        data-itemname="<?= $nama === '-' ? '' : $nama; ?>"
                                        data-kodeitem="<?= $kode === '-' ? '' : $kode; ?>"
                                        data-brand="<?= $br === '-' ? '' : $br; ?>">
                                        <i class="chart bar icon"></i> Stock
                                    </button>
                                </td>
                                <td><?= $kode; ?></td>
                                <td><?= $nama; ?></td>
                                <td><?= $unit; ?></td>
                                <td><?= $cat; ?></td>
                            </tr>
                    <?php endforeach;
                    endif; ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

    <!-- ===== Search box (global untuk tab aktif) ===== -->
    <div class="ui grid" style="margin-bottom:10px;">
        <div class="sixteen wide column" style="text-align:right;">
            <div class="ui action input">
                <input type="text" id="tabSearch" placeholder="Search item... (Kode, Nama, Unit, Kategori, Brand)">
                <button class="ui button" id="clearSearch">Clear</button>
            </div>
        </div>
    </div>

    <!-- ===================== TABS ===================== -->
    <div class="ui top attached tabular menu">
        <a class="item active" data-tab="barang-jadi">Barang Jadi</a>
        <a class="item" data-tab="barang-setengah-jadi">Barang Setengah Jadi</a>
        <a class="item" data-tab="material">Material</a>
    </div>

    <div class="ui bottom attached active tab segment" data-tab="barang-jadi">
        <?php render_items_table($tabs['Barang Jadi']); ?>
    </div>

    <div class="ui bottom attached tab segment" data-tab="barang-setengah-jadi">
        <?php render_items_table($tabs['Barang Setengah Jadi']); ?>
    </div>

    <div class="ui bottom attached tab segment" data-tab="material">
        <?php render_items_table($tabs['Material']); ?>
    </div>

</div>
<!-- /.container-fluid -->

<!-- ===================== MODAL: WR_STOCK by Item (detail) ===================== -->
<div class="ui modal large" id="stockByItemModal">
    <div class="header">
        Stock untuk: <span id="stockByItemTitle">-</span>
    </div>
    <div class="content">
        <div class="ui action input" style="margin-bottom:10px; width:100%;">
            <input type="text" id="stockByItemSearch" placeholder="Search in result...">
            <button class="ui button" id="clearStockByItemSearch">Clear</button>
        </div>

        <div style="overflow-x:auto;">
            <table class="ui celled compact striped table" id="wrStockByItemTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode SJ</th>
                        <th>No SJ</th>
                        <th>WO Number</th>
                        <th>Kode Item</th>
                        <th>Item</th>
                        <th>Unit</th>
                        <th>Kategori</th>
                        <th>Brand</th>
                        <th>Art/Color</th>
                        <th>Checkin</th>
                        <th>Checkout</th>
                        <th>Date Arrive</th>
                        <th>From Dept</th>
                        <th>To Dept</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Size Run</th> <!-- kolom baru -->
                    </tr>
                </thead>
                <tbody><!-- filled via AJAX --></tbody>
            </table>
        </div>
    </div>
    <div class="actions">
        <div class="ui cancel button">Close</div>
    </div>
</div>

<!-- ===================== MODAL: WR_STOCK Summary (agregasi) ===================== -->
<div class="ui modal large" id="stockSummaryModal">
    <div class="header">
        Ringkasan Stock: <span id="stockSummaryTitle">-</span>
    </div>
    <div class="content">
        <div class="ui action input" style="margin-bottom:10px; width:100%;">
            <input type="text" id="stockSummarySearch" placeholder="Search in summary...">
            <button class="ui button" id="clearStockSummarySearch">Clear</button>
        </div>

        <div style="overflow-x:auto;">
            <table class="ui celled compact striped table" id="wrStockSummaryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>WO Number</th>
                        <th>Kode Item</th>
                        <th>Item</th>
                        <th>Brand</th>
                        <th>Σ Checkin</th>
                        <th>Σ Checkout</th>
                        <th>Balance (In-Out)</th>
                    </tr>
                </thead>
                <tbody><!-- filled via JS (agregasi) --></tbody>
            </table>
        </div>
    </div>
    <div class="actions">
        <div class="ui cancel button">Close</div>
    </div>
</div>

<div class="ui small scrolling modal" id="sizeRunByRowModal">
    <div class="header">
        Size Run — <span id="sizeRunByRowTitle">-</span>
    </div>

    <!-- penting: gunakan 'scrolling content' -->
    <div class="scrolling content">
        <div class="ui action input" style="margin-bottom:10px; width:100%;">
            <input type="text" id="sizeRunByRowSearch" placeholder="Search in size run...">
            <button class="ui button" id="clearSizeRunByRowSearch">Clear</button>
        </div>

        <!-- bungkus tabel dengan container scroll -->
        <div class="modal-body-scroll">
            <table class="ui celled compact striped table" id="wrSizeRunByRowTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode SJ</th>
                        <th>WO Number</th>
                        <th>Kode Item</th>
                        <th>Brand</th>
                        <th>Size</th>
                        <th>Qty</th>
                        <th>Created By</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody><!-- filled via AJAX --></tbody>
            </table>
        </div>
    </div>

    <div class="actions">
        <div class="ui cancel button">Close</div>
    </div>
</div>
</div>



<script>
    // Init Semantic UI tabs
    $(function() {
        $('.menu .item').tab();

        // Aktifkan scroll untuk semua modal + opsi aman
        $('.ui.modal')
            .addClass('scrolling')
            .modal({
                autofocus: false,
                observeChanges: true,
                allowMultiple: true,
                detachable: true
            });

        // ================= Search active tab =================
        function renumberActive() {
            const $seg = $('.tab.segment.active');
            const $rows = $seg.find('tbody tr:visible').not('.no-results');
            let i = 1;
            $rows.each(function() {
                $(this).children('td,th').first().text(i++);
            });
        }

        function ensureNoResultsRow($seg) {
            const $tbody = $seg.find('tbody');
            if (!$tbody.find('tr.no-results').length) {
                $tbody.append('<tr class="no-results" style="display:none;"><td colspan="9" class="center aligned">No matching items.</td></tr>');
            }
        }

        function filterActiveTab() {
            const q = ($('#tabSearch').val() || '').trim().toLowerCase();
            const $seg = $('.tab.segment.active');
            const $tbody = $seg.find('tbody');

            ensureNoResultsRow($seg);
            const $rows = $tbody.find('tr').not('.no-results').not('.original-empty');

            if (!q) {
                $rows.show();
                $tbody.find('.no-results').hide();
                renumberActive();
                return;
            }

            let visibleCount = 0;
            $rows.each(function() {
                const text = $(this).text().toLowerCase();
                const shown = text.indexOf(q) !== -1;
                $(this).toggle(shown);
                if (shown) visibleCount++;
            });

            $tbody.find('.no-results').toggle(visibleCount === 0);
            renumberActive();
        }

        $('#tabSearch').on('input', filterActiveTab);
        $('#clearSearch').on('click', function() {
            $('#tabSearch').val('');
            filterActiveTab();
            $('#tabSearch').focus();
        });
        $('.menu .item').on('click', function() {
            setTimeout(filterActiveTab, 0);
        });
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') $('#clearSearch').click();
        });
        filterActiveTab();

        // ================= Utilities =================
        function esc(s) {
            return $('<div/>').text(s == null ? '' : s).html();
        }

        function toNum(x) {
            const n = parseFloat(x);
            return isNaN(n) ? 0 : n;
        }

        // ================= Size Run by ONE stock row =================
        const $modalSR = $('#sizeRunByRowModal');
        const $tbodySR = $('#wrSizeRunByRowTable tbody');

        function renumberSR() {
            let i = 1;
            $('#wrSizeRunByRowTable tbody tr:visible').not('.no-results').each(function() {
                $(this).children('td,th').first().text(i++);
            });
        }

        function filterSizeRunByRow() {
            const q = ($('#sizeRunByRowSearch').val() || '').toLowerCase();
            const $rows = $('#wrSizeRunByRowTable tbody tr');
            $('#wrSizeRunByRowTable tbody tr.no-results').remove();

            if (!$rows.length) return;

            if (!q) {
                $rows.show();
                renumberSR();
                return;
            }

            let shown = 0;
            $rows.each(function() {
                const text = $(this).text().toLowerCase();
                const ok = text.indexOf(q) !== -1;
                $(this).toggle(ok);
                if (ok) shown++;
            });

            renumberSR();
            if (!shown) {
                $('#wrSizeRunByRowTable tbody').append('<tr class="no-results"><td colspan="9" class="center aligned">No matching rows.</td></tr>');
            }
        }

        $('#sizeRunByRowSearch').on('input', filterSizeRunByRow);
        $('#clearSizeRunByRowSearch').on('click', function() {
            $('#sizeRunByRowSearch').val('');
            filterSizeRunByRow();
            $('#sizeRunByRowSearch').focus();
        });

        // Open modal from Size Run button on each stock row
        $(document).on('click', '.open-sizerun-by-row', function() {
            const kode_sj = $(this).data('kode_sj') || '';
            const wo_number = $(this).data('wo_number') || '';
            const kode_item = $(this).data('kode_item') || '';
            const brand = $(this).data('brand_name') || '';

            // Title ringkas
            $('#sizeRunByRowTitle').text(`${kode_sj} — ${wo_number} — ${kode_item} — ${brand}`);

            $modalSR.modal({
                autofocus: false,
                observeChanges: true,
                allowMultiple: true, // jika kamu buka beberapa modal bersamaan
                detachable: true
            }).modal('show');

            // load sizerun
            loadSizeRunByRow(kode_sj, wo_number, kode_item, brand);
        });

        function loadSizeRunByRow(kode_sj, wo_number, kode_item, brand_name) {
            $tbodySR.empty().append('<tr><td colspan="9" class="center aligned">Loading...</td></tr>');

            $.ajax({
                url: '<?= site_url('warehouse/get_wr_sizerun'); ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    kode_sj: kode_sj,
                    wo_number: wo_number,
                    kode_item: kode_item,
                    brand_name: brand_name
                },
                success: function(resp) {
                    $tbodySR.empty();
                    const rows = (resp && resp.data) ? resp.data : [];

                    if (!rows.length) {
                        $tbodySR.append('<tr><td colspan="9" class="center aligned">Tidak ada data.</td></tr>');
                        return;
                    }

                    let i = 1;
                    rows.forEach(r => {
                        $tbodySR.append(`
                        <tr>
                            <td>${i++}</td>
                            <td>${esc(r.kode_sj)}</td>
                            <td>${esc(r.wo_number)}</td>
                            <td>${esc(r.kode_item)}</td>
                            <td>${esc(r.brand_name)}</td>
                            <td>${esc(r.size_name)}</td>
                            <td>${esc(r.sizeq_qty)}</td>
                            <td>${esc(r.created_by)}</td>
                            <td>${esc(r.created_at)}</td>
                        </tr>
                        `);
                    });

                    filterSizeRunByRow(); // apply filter bila ada kata kunci
                },
                error: function(xhr) {
                    console.error(xhr.responseText || xhr.statusText);
                    $tbodySR.empty().append('<tr><td colspan="9" class="center aligned">Gagal memuat data.</td></tr>');
                }
            });
        }


        // ================= Modal Stock (DETAIL) =================
        const $modalDetail = $('#stockByItemModal');
        const $tbodyDetail = $('#wrStockByItemTable tbody');

        $(document).on('click', '.open-stock-by-item', function() {
            const itemName = $(this).data('itemname') || '';
            const kodeItem = $(this).data('kodeitem') || '';
            const brand = $(this).data('brand') || '';
            $('#stockByItemTitle').text(itemName + (kodeItem ? ' (' + kodeItem + ')' : ''));

            $modalDetail.modal({
                autofocus: false,
                observeChanges: true
            }).modal('show');

            loadWrStockByItem(itemName, kodeItem, brand);
        });

        function loadWrStockByItem(itemName, kodeItem, brandName) {
            $tbodyDetail.empty().append('<tr class="loading"><td colspan="18" class="center aligned">Loading...</td></tr>');

            $.ajax({
                url: '<?= site_url('warehouse/get_wr_stock'); ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    item_name: itemName,
                    kode_item: kodeItem
                },
                success: function(resp) {
                    $tbodyDetail.empty();
                    let rows = (resp && resp.data) ? resp.data : [];

                    if (brandName) {
                        const b = (brandName + '').toLowerCase();
                        rows = rows.filter(r => ((r.brand || '') + '').toLowerCase() === b);
                    }

                    if (!rows.length) {
                        $tbodyDetail.append('<tr><td colspan="18" class="center aligned">Tidak ada data.</td></tr>');
                        return;
                    }

                    let i = 1;
                    rows.forEach(r => {
                        $tbodyDetail.append(`
                        <tr>
                            <td>${i++}</td>
                            <td>${esc(r.kode_sj)}</td>
                            <td>${esc(r.no_sj)}</td>
                            <td>${esc(r.wo_number)}</td>
                            <td>${esc(r.kode_item)}</td>
                            <td>${esc(r.item_name)}</td>
                            <td>${esc(r.unit_name)}</td>
                            <td>${esc(r.category_name)}</td>
                            <td>${esc(r.brand)}</td>
                            <td>${esc(r.artcolor)}</td>
                            <td>${esc(r.checkin)}</td>
                            <td>${esc(r.checkout)}</td>
                            <td>${esc(r.date_arrive)}</td>
                            <td>${esc(r.from_dept)}</td>
                            <td>${esc(r.to_dept)}</td>
                            <td>${esc(r.created_by)}</td>
                            <td>${esc(r.created_at)}</td>
                            <td>
                            <button
                                class="ui tiny orange button open-sizerun-by-row"
                                data-kode_sj="${esc(r.kode_sj)}"
                                data-wo_number="${esc(r.wo_number)}"
                                data-kode_item="${esc(r.kode_item)}"
                                data-brand_name="${esc(r.brand)}">
                                Size Run
                            </button>
                            </td>
                        </tr>
                        `);
                    });

                    filterWrStockByItem(); // tetap aktifkan filter bila ada kata kunci
                },
                error: function(xhr) {
                    console.error(xhr.responseText || xhr.statusText);
                    $tbodyDetail.empty().append('<tr><td colspan="18" class="center aligned">Gagal memuat data.</td></tr>');
                }
            });
        }


        function renumberWrStockByItem() {
            let i = 1;
            $('#wrStockByItemTable tbody tr:visible').not('.no-results').each(function() {
                $(this).children('td,th').first().text(i++);
            });
        }

        function filterWrStockByItem() {
            const q = ($('#stockByItemSearch').val() || '').toLowerCase();
            const $rows = $('#wrStockByItemTable tbody tr');
            $('#wrStockByItemTable tbody tr.no-results').remove();

            if (!$rows.length) return;

            if (!q) {
                $rows.show();
                renumberWrStockByItem();
                return;
            }

            let shown = 0;
            $rows.each(function() {
                const text = $(this).text().toLowerCase();
                const ok = text.indexOf(q) !== -1;
                $(this).toggle(ok);
                if (ok) shown++;
            });

            renumberWrStockByItem();
            if (!shown) {
                $('#wrStockByItemTable tbody').append('<tr class="no-results"><td colspan="18" class="center aligned">No matching rows.</td></tr>');
            }
        }

        $('#stockByItemSearch').on('input', filterWrStockByItem);
        $('#clearStockByItemSearch').on('click', function() {
            $('#stockByItemSearch').val('');
            filterWrStockByItem();
            $('#stockByItemSearch').focus();
        });

        // ================= Modal Summary (AGREGASI) =================
        const $modalSum = $('#stockSummaryModal');
        const $tbodySum = $('#wrStockSummaryTable tbody');

        $(document).on('click', '.open-stock-summary', function() {
            const itemName = $(this).data('itemname') || '';
            const kodeItem = $(this).data('kodeitem') || '';
            const brand = $(this).data('brand') || '';
            $('#stockSummaryTitle').text(itemName + (brand ? ' — ' + brand : ''));

            $modalSum.modal({
                autofocus: false,
                observeChanges: true
            }).modal('show');

            // Ambil data dasar, lalu agregasi di client
            loadWrStockSummary(itemName, kodeItem, brand);
        });

        function loadWrStockSummary(itemName, kodeItem, brandName) {
            $tbodySum.empty().append('<tr class="loading"><td colspan="8" class="center aligned">Loading...</td></tr>');

            $.ajax({
                url: '<?= site_url('warehouse/get_wr_stock'); ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    item_name: itemName,
                    kode_item: kodeItem
                },
                success: function(resp) {
                    $tbodySum.empty();
                    let rows = (resp && resp.data) ? resp.data : [];

                    // Filter brand (jika diberikan)
                    if (brandName) {
                        const b = (brandName + '').toLowerCase();
                        rows = rows.filter(r => ((r.brand || '') + '').toLowerCase() === b);
                    }

                    if (!rows.length) {
                        $tbodySum.append('<tr><td colspan="8" class="center aligned">Tidak ada data.</td></tr>');
                        return;
                    }

                    // Group by (wo_number, item_name, brand)
                    const groups = {};
                    rows.forEach(r => {
                        const wo = r.wo_number || '';
                        const itm = r.item_name || '';
                        const br = r.brand || '';
                        const key = [wo, itm, br].join('||');

                        if (!groups[key]) {
                            groups[key] = {
                                wo_number: wo,
                                kode_item: r.kode_item || '',
                                item_name: itm,
                                brand: br,
                                sum_in: 0,
                                sum_out: 0
                            };
                        }
                        groups[key].sum_in += toNum(r.checkin);
                        groups[key].sum_out += toNum(r.checkout);
                    });

                    // Render satu baris per group
                    const arr = Object.values(groups);
                    if (!arr.length) {
                        $tbodySum.append('<tr><td colspan="8" class="center aligned">Tidak ada data.</td></tr>');
                        return;
                    }

                    let i = 1;
                    arr.forEach(g => {
                        const balance = g.sum_in - g.sum_out;
                        $tbodySum.append(`
                            <tr>
                                <td>${i++}</td>
                                <td>${esc(g.wo_number)}</td>
                                <td>${esc(g.kode_item)}</td>
                                <td>${esc(g.item_name)}</td>
                                <td>${esc(g.brand)}</td>
                                <td>${esc(g.sum_in.toFixed(2))}</td>
                                <td>${esc(g.sum_out.toFixed(2))}</td>
                                <td>${esc(balance.toFixed(2))}</td>
                            </tr>
                        `);
                    });

                    filterWrStockSummary(); // apply modal search jika ada
                },
                error: function(xhr) {
                    console.error(xhr.responseText || xhr.statusText);
                    $tbodySum.empty().append('<tr><td colspan="8" class="center aligned">Gagal memuat ringkasan.</td></tr>');
                }
            });
        }

        function renumberWrStockSummary() {
            let i = 1;
            $('#wrStockSummaryTable tbody tr:visible').not('.no-results').each(function() {
                $(this).children('td,th').first().text(i++);
            });
        }

        function filterWrStockSummary() {
            const q = ($('#stockSummarySearch').val() || '').toLowerCase();
            const $rows = $('#wrStockSummaryTable tbody tr');
            $('#wrStockSummaryTable tbody tr.no-results').remove();

            if (!$rows.length) return;

            if (!q) {
                $rows.show();
                renumberWrStockSummary();
                return;
            }

            let shown = 0;
            $rows.each(function() {
                const text = $(this).text().toLowerCase();
                const ok = text.indexOf(q) !== -1;
                $(this).toggle(ok);
                if (ok) shown++;
            });

            renumberWrStockSummary();
            if (!shown) {
                $('#wrStockSummaryTable tbody').append('<tr class="no-results"><td colspan="8" class="center aligned">No matching rows.</td></tr>');
            }
        }

        $('#stockSummarySearch').on('input', filterWrStockSummary);
        $('#clearStockSummarySearch').on('click', function() {
            $('#stockSummarySearch').val('');
            filterWrStockSummary();
            $('#stockSummarySearch').focus();
        });
    });
</script>