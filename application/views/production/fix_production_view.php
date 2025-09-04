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
    <?= form_error('production', '<div class="ui negative message">', '</div>'); ?>
    <?= $this->session->flashdata('message'); ?>

    <div class="mb-3">
        <a href="<?= base_url('production/output'); ?>" class="ui blue button">
            <i class="arrow left icon"></i> Back to List
        </a>
    </div>

    <!-- Form -->
    <form id="productionReportForm" method="POST" action="<?= base_url('production/save_fix_production'); ?>">

        <!-- RO Header Data -->
        <div class="ui segment">
            <h4 class="ui header">Request Order Details</h4>
            <div class="ui grid">
                <div class="four wide column">
                    <strong>WO Number:</strong>
                    <p><?= htmlspecialchars($header['wo_number']); ?></p>
                    <!-- Hidden Input for WO Number -->
                    <input type="hidden" name="wo_number" value="<?= htmlspecialchars($header['wo_number']); ?>" />
                </div>
                <div class="four wide column">
                    <strong>Kode RO:</strong>
                    <p><?= htmlspecialchars($header['kode_ro']); ?></p>
                    <!-- Hidden Input for Kode RO -->
                    <input type="hidden" name="kode_ro" value="<?= htmlspecialchars($header['kode_ro']); ?>" />
                </div>
                <div class="four wide column">
                    <strong>Dept:</strong>
                    <p><?= htmlspecialchars($header['from_dept']); ?></p>
                    <!-- Hidden Input for ID RO and ID WO -->
                    <input type="hidden" name="id_ro" value="<?= $header['id_ro']; ?>" />
                    <input type="hidden" name="id_wo" value="<?= $header['id_wo']; ?>" />
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
                        <th>Previous Qty</th> <!-- Previous Qty -->
                        <th>Missing Category</th>
                        <th>Missing Qty</th>
                        <th>Total Size Qty</th> <!-- Total Size Qty -->
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sizerun)): ?>
                        <?php foreach ($sizerun as $size): ?>
                            <tr>
                                <td><?= htmlspecialchars($size['size_name']); ?></td>
                                <td>
                                    <input type="number" name="sizerun_qty[<?= $size['id_sizerun']; ?>]" class="form-control" min="0" required />
                                </td>
                                <td class="totalQty"><?= $size['size_qty']; ?></td>
                                <td>
                                    <input type="number" class="form-control previousQty" name="previous_qty[<?= $size['id_sizerun']; ?>]" value="<?= $size['previous_qty']; ?>" readonly />
                                </td>
                                <td>
                                    <select name="mis_category[<?= $size['id_sizerun']; ?>]" class="form-control">
                                        <option value="sudah lengkap">Select Category</option>
                                        <option value="belum produksi">Belum Produksi</option>
                                        <option value="bisa repair">Bisa di Repair</option>
                                        <option value="tidak bisa repair">Tidak Bisa di Repair</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="mis_qty[<?= $size['id_sizerun']; ?>]" class="form-control" min="0" value="0" />
                                </td>
                                <td class="totalSizeQty">
                                    <?php
                                    // Menampilkan Total Size Qty: Sizerun Qty + Previous Qty
                                    $totalSizeQty = $size['size_qty'] + $size['previous_qty'];
                                    echo $totalSizeQty;
                                    ?>
                                </td> <!-- Display total size qty per row -->
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
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

        <!-- Submit Button -->
        <div class="ui segment">
            <button type="submit" id="saveBtn" class="ui green button" disabled>Save Report</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // --- INIT ELEMENTS --- 
        const $saveBtn = $('#saveBtn');
        const $roQtyEl = $('#roQty');
        const $totalEl = $('#totalSizerun');
        const $vm = $('#validationMessage');
        const $vs = $('#validationStatus');

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
                const previousQty = parseInt($row.find('input[name^="previous_qty"]').val(), 10) || 0; // Ambil Previous Qty
                totalSizerunQty += (val + previousQty);

                const woSizeQty = parseInt($row.find('.totalQty').text(), 10) || 0;

                // Mengambil mis_qty (missing quantity) untuk kategori missing
                const misQty = parseInt($row.find('input[name^="mis_qty"]').val(), 10) || 0;
                totalMissingQty += misQty;

                // Menambahkan mis_qty ke total sizerun qty
                const totalRowQty = val + misQty; // Total per row (Sizerun + Missing)
                const totalSizeQty = val + previousQty; // Menghitung Total Size Qty (Sizerun + Previous Qty)

                // Menampilkan Total Size Qty di tabel
                $row.find('.totalSizeQty').text(totalSizeQty); // Menampilkan Total Size Qty per baris

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

            // Total gabungan
            const totalQty = totalSizerunQty + totalMissingQty;
            $totalEl.text(totalQty); // Menampilkan Total Qty

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

            // Enable/disable Save (berdasarkan total gabungan)
            const formIsValid = isValid && totalQty === roQty;
            $saveBtn.prop('disabled', !formIsValid);

            return formIsValid;
        }

        // --- HANDLER INPUT & SUBMIT --- 
        // Re-validate saat input SIZERUN atau MISSING berubah
        $(document).on('input', 'input[name^="sizerun_qty"], input[name^="mis_qty"], input[name^="previous_qty"]', function() {
            validateQty();
        });

        // Cegah submit form jika belum valid
        $('#productionReportForm').on('submit', function(e) {
            if (!validateQty()) {
                e.preventDefault();
            }
        });

        // --- STATE AWAL --- 
        validateQty();
    });
</script>