<style>
    /* Highlight baris yang salah tanpa ubah font */
    .row-error td {
        background-color: #ffe6e6 !important;
        font-size: inherit;
        color: inherit;
    }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <!-- Error message and flash message -->
    <?= form_error('Production/output', '<div class="ui negative message">', '</div>'); ?>
    <?= $this->session->flashdata('message'); ?>

    <div class="mb-3">
        <a href="<?= base_url('Production/output'); ?>" class="ui blue button">
            <i class="arrow left icon"></i> Back to List
        </a>
    </div>

    <form id="productionReportForm" method="POST" action="<?= base_url('production/save_production_report'); ?>">

        <!-- RO Header Data -->
        <div class="ui segment">
            <h4 class="ui header">Request Order Details</h4>
            <div class="ui grid">
                <div class="four wide column">
                    <strong>WO Number:</strong>
                    <p><?= htmlspecialchars($header['wo_number']); ?></p>
                </div>
                <div class="four wide column">
                    <strong>Kode RO:</strong>
                    <p><?= htmlspecialchars($header['kode_ro']); ?></p>
                </div>
                <div class="four wide column">
                    <strong>Dept:</strong>
                    <p><?= htmlspecialchars($header['from_dept']); ?></p>
                </div>
            </div>
        </div>

        <!-- Sizerun Details -->
        <div class="ui segment">
            <h4 class="ui header">Sizerun Details</h4>
            <table class="ui celled table" id="sizerunTable">
                <thead>
                    <tr>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>WO Size Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sizerun)): ?>
                        <?php $total_qty = 0; ?>
                        <?php foreach ($sizerun as $size): ?>
                            <tr>
                                <td><?= htmlspecialchars($size['size_name']); ?></td>
                                <td>
                                    <input type="number" name="sizerun_qty[<?= $size['id_sizerun']; ?>]" class="form-control" min="0" required />
                                </td>
                                <td class="totalQty"><?= $size['size_qty']; ?></td>
                            </tr>
                            <?php $total_qty += $size['size_qty']; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="ui message">
                <strong>Total Quantity (Sizerun + Missing): <span id="totalSizerun"><?= $total_qty; ?></span></strong>
            </div>
        </div>

        <!-- WO Quantity Validation -->
        <div class="ui segment">
            <h4 class="ui header">RO Quantity Validation</h4>
            <div class="ui grid">
                <div class="four wide column">
                    <strong>WO Quantity:</strong>
                    <p id="roQty"><?= $wo_qty; ?></p>
                </div>
            </div>
            <div class="ui message" id="validationMessage">
                <div class="header">Validation Status</div>
                <p id="validationStatus"></p>
            </div>
        </div>

        <!-- Missing Quantity Section (if applicable) -->
        <div id="missingQtySection" class="ui segment" style="display: none;">
            <h4 class="ui header">Enter Missing Quantity</h4>
            <div class="ui grid">
                <div class="eight wide column">
                    <button type="button" class="ui button blue" id="btnBelumProduksi">Belum Produksi</button>
                    <button type="button" class="ui button green" id="btnDefect">Defect</button>
                </div>
            </div>
            <table class="ui celled table" id="missingQtyTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Missing quantity rows will be added here via DataTables -->
                </tbody>
            </table>
        </div>

        <!-- Submit Button -->
        <div class="ui segment">
            <button type="submit" id="saveBtn" class="ui green button" disabled>Save Report</button>
        </div>
    </form>
</div>

<!-- Modal Defect -->
<div class="ui modal" id="defectModal">
    <i class="close icon"></i>
    <div class="header">Select Defect Category</div>
    <div class="content">
        <table class="ui celled table" id="defectTable">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bisa di Repair</td>
                    <td><button type="button" class="ui button green" id="btnBisaRepair">Add</button></td>
                </tr>
                <tr>
                    <td>Tidak Bisa di Repair</td>
                    <td><button type="button" class="ui button red" id="btnTidakBisaRepair">Add</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- End of Main Content -->

<script>
    $(document).ready(function() {
        // --- INIT ELEMENTS ---
        const $saveBtn = $('#saveBtn');
        const $roQtyEl = $('#roQty');
        const $totalEl = $('#totalSizerun');
        const $vm = $('#validationMessage');
        const $vs = $('#validationStatus');
        const $missingSection = $('#missingQtySection');
        const $missingTbl = $('#missingQtyTable');

        // --- INIT DATATABLES UNTUK MISSING ---
        const missingDT = $missingTbl.DataTable({
            paging: false,
            searching: false,
            info: false,
            lengthChange: false,
            ordering: false,
            autoWidth: false,
            columns: [{
                    title: "Category",
                    width: "60%"
                },
                {
                    title: "Quantity",
                    width: "40%"
                }
            ]
        });

        // --- MODAL (Semantic UI) ---
        $('#defectModal')
            .appendTo('body')
            .modal({
                observeChanges: true,
                autofocus: false,
                closable: true
            });

        // --- UTIL: SHOW/HIDE + ENABLE/DISABLE INPUT MISSING ---
        function toggleMissingSection(show) {
            const $inputs = $missingSection.find('input, select, textarea, button');
            if (show) {
                $missingSection.show();
                $inputs.prop('disabled', false);
            } else {
                $missingSection.hide();
                // Disable agar data tersembunyi tidak ikut terkirim
                $inputs.prop('disabled', true);
            }
        }

        // --- VALIDASI ---
        function validateQty() {
            let totalSizerunQty = 0; // HANYA dari tabel sizerun
            let totalMissingQty = 0; // HANYA dari tabel missing
            const roQty = parseInt($roQtyEl.text(), 10) || 0;
            let isValid = true;
            let invalidRow = false;

            // Hitung & validasi SIZERUN
            $('#sizerunTable tbody tr').each(function() {
                const $row = $(this);
                const val = parseInt($row.find('input[name^="sizerun_qty"]').val(), 10) || 0;
                totalSizerunQty += val;

                const woSizeQty = parseInt($row.find('.totalQty').text(), 10) || 0;

                if (val > woSizeQty) {
                    isValid = false;
                    invalidRow = true;
                    $row.addClass('row-error');
                    $row.find('input[name^="sizerun_qty"]').attr('aria-invalid', 'true');
                } else {
                    $row.removeClass('row-error');
                    $row.find('input[name^="sizerun_qty"]').removeAttr('aria-invalid');
                }
            });

            // Hitung & validasi MISSING (kompatibel DataTables)
            $('#missingQtyTable tbody input[name="missing_qty[]"]').each(function() {
                const qty = parseFloat($(this).val()) || 0;
                totalMissingQty += qty;

                const $tr = $(this).closest('tr');
                if (qty <= 0) {
                    isValid = false;
                    $tr.addClass('row-error');
                } else {
                    $tr.removeClass('row-error');
                }
            });

            // Total gabungan
            const totalQty = totalSizerunQty + totalMissingQty;
            $totalEl.text(totalQty);

            // Status pesan
            $vm.removeClass('positive negative warning');
            let msg = '';

            if (isValid && totalQty === roQty) {
                $vm.addClass('positive');
                msg = 'Semua qty per size sesuai dengan WO Size Qty dan Total Quantity = WO Quantity. Form siap disimpan.';
            } else if (!isValid && invalidRow) {
                $vm.addClass('negative');
                msg = 'Ada baris dengan qty yang tidak sesuai dengan WO Size Qty.';
            } else if (isValid && totalQty !== roQty) {
                $vm.addClass('warning');
                msg = `Semua baris sudah sesuai, tetapi Total Quantity (${totalQty}) ≠ WO Quantity (${roQty}). Mohon isi Missing Quantity.`;
            } else {
                $vm.addClass('negative');
                msg = `Ada baris yang tidak sesuai dan Total Quantity (${totalQty}) ≠ WO Quantity (${roQty}). Mohon perbaiki.`;
            }
            $vs.html(msg);

            // === ATUR VISIBILITY MISSING: 
            // Hide hanya bila SIZERUN SAJA sudah pas (totalSizerunQty === roQty). 
            // Kalau Sizerun belum pas, Missing harus TETAP MUNCUL, bahkan bila total gabungan sudah pas.
            const shouldShowMissing = (totalSizerunQty !== roQty);
            toggleMissingSection(shouldShowMissing);

            // Enable/disable Save (berdasarkan total gabungan)
            const formIsValid = isValid && totalQty === roQty;
            $saveBtn.prop('disabled', !formIsValid);

            return formIsValid;
        }

        // --- UTIL: TAMBAH BARIS MISSING VIA DATATABLES ---
        function addMissingRow(label) {
            missingDT.row.add([
                label,
                '<input type="number" name="missing_qty[]" min="1" class="form-control" required />'
            ]).draw(false);
            validateQty();
        }

        // --- HANDLER INPUT & SUBMIT ---
        // Re-validate saat input SIZERUN berubah
        $(document).on('input', 'input[name^="sizerun_qty"]', function() {
            validateQty();
        });

        // Re-validate saat input MISSING berubah (delegated)
        $(document).on('input change', '#missingQtyTable input[name="missing_qty[]"]', function() {
            validateQty();
        });

        // Cegah submit form jika belum valid
        $('#productionReportForm').on('submit', function(e) {
            if (!validateQty()) {
                e.preventDefault();
            }
        });

        // Cegah Enter di form agar tidak submit tak sengaja
        $('#productionReportForm').on('keydown', function(e) {
            if (e.key === 'Enter') {
                const tag = (e.target.tagName || '').toLowerCase();
                if (tag === 'input') {
                    e.preventDefault();
                }
            }
        });

        // --- HANDLER TOMBOL ---
        $('#btnBelumProduksi').on('click', function(e) {
            e.preventDefault();
            addMissingRow('Belum Produksi');
        });

        $('#btnDefect').on('click', function(e) {
            e.preventDefault();
            $('#defectModal').modal('show');
        });

        $('#btnBisaRepair').on('click', function(e) {
            e.preventDefault();
            addMissingRow('Bisa di Repair');
            $('#defectModal').modal('hide');
        });

        $('#btnTidakBisaRepair').on('click', function(e) {
            e.preventDefault();
            addMissingRow('Tidak Bisa di Repair');
            $('#defectModal').modal('hide');
        });

        // --- STATE AWAL ---
        // Di awal, tampilkan/hidden sesuai aturan baru.
        validateQty();
    });
</script>