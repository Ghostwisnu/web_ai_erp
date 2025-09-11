<style>
    /* Highlight baris yang salah tanpa ubah font */
    .row-error td {
        background-color: #ffe6e6 !important;
        font-size: inherit;
        color: inherit;
    }

    .four.wide.column p {
        color: #333;
        /* Warna font gelap */
    }

    .four.wide.column strong {
        color: #333;
        /* Warna font gelap untuk label (strong) */
    }

    .ui.green.button {
        cursor: pointer;
    }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <!-- Error message and flash message -->
    <?= form_error('Production/output', '<div class="ui negative message">', '</div>'); ?>
    <?= $this->session->flashdata('message'); ?>

    <div class="mb-3">
        <a href="<?= base_url('Production/output'); ?>" class="ui blue button">
            <i class="arrow left icon"></i> Back to List
        </a>
        <button type="button" class="ui blue button" id="openModalBtn">
            <i class="eye icon"></i> Lihat Daftar HFG
        </button>
    </div>

    <!-- Form -->
    <form id="productionReportForm" method="POST" action="<?= base_url('production/save_production_report'); ?>">

        <!-- Modal -->
        <!-- Modal -->
        <div class="ui modal" id="hfgModal">
            <div class="header">
                Daftar HFG
            </div>
            <div class="content">
                <table class="ui celled table" id="hfgTable">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Kode Item</th>
                            <th>Nama Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data HFG akan dimuat di sini melalui AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="actions">
                <button class="ui red button" id="closeModalBtn">Tutup</button>
            </div>
        </div>


        <!-- RO Header Data -->
        <div class="ui segment">
            <h4 class="ui header">Request Order Details</h4>
            <div class="ui grid">
                <div class="four wide column">
                    <strong>WO Number:</strong>
                    <p><?= htmlspecialchars($header['wo_number']); ?></p>
                    <input type="hidden" name="wo_number" value="<?= htmlspecialchars($header['wo_number']); ?>" />
                </div>
                <div class="four wide column">
                    <strong>Kode RO:</strong>
                    <p><?= htmlspecialchars($header['kode_ro']); ?></p>
                    <input type="hidden" name="kode_ro" value="<?= htmlspecialchars($header['kode_ro']); ?>" />
                </div>
                <div class="four wide column">
                    <strong>Dept:</strong>
                    <p><?= htmlspecialchars($header['from_dept']); ?></p>
                    <input type="hidden" name="id_ro" value="<?= $header['id_ro']; ?>" />
                    <input type="hidden" name="id_wo" value="<?= $header['id_wo']; ?>" />
                </div>

                <div class="four wide column">
                    <h4 class="ui header">Selected HFG</h4>
                    <!-- Hidden Input untuk Menyimpan Data HFG yang Dipilih -->
                    <input type="hidden" name="hfg_items" id="hfgItemsInput">
                    <p id="selectedHFG">Belum ada item yang dipilih</p>
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
                        <th>Missing Category</th>
                        <th>Missing Qty</th>
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
                                <td>
                                    <select name="mis_category[<?= $size['id_sizerun']; ?>]" class="form-control">
                                        <option value="">Select Category</option>
                                        <option value="belum produksi">Belum Produksi</option>
                                        <option value="bisa repair">Bisa di Repair</option>
                                        <option value="tidak bisa repair">Tidak Bisa di Repair</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="mis_qty[<?= $size['id_sizerun']; ?>]" class="form-control" min="0" value="0" />
                                </td>
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

        <!-- Submit Button -->
        <div class="ui segment">
            <button type="submit" id="saveBtn" class="ui green button" disabled>Save Report</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        const $saveBtn = $('#saveBtn');
        const $roQtyEl = $('#roQty');
        const $totalEl = $('#totalSizerun');
        const $vm = $('#validationMessage');
        const $vs = $('#validationStatus');

        // Ketika tombol "Select" diklik
        $(document).on('click', '.select-btn', function() {
            const selectedHFG = $(this).data('hfg');
            const selectedHFGName = $(this).closest('tr').find('td').eq(2).text(); // Ambil nama item dari kolom ketiga

            // Tampilkan item yang dipilih di bagian bawah form
            $('#selectedHFG').text(`Kode Item: ${selectedHFG}, Nama Item: ${selectedHFGName}`);

            // Menyimpan data yang dipilih dalam hidden input
            let currentItems = JSON.parse($('#hfgItemsInput').val() || '[]'); // Ambil data yang sudah ada di hidden input (jika ada)
            if (!currentItems.includes(selectedHFG)) {
                currentItems.push(selectedHFG); // Tambahkan kode item HFG yang dipilih ke array
                $('#hfgItemsInput').val(JSON.stringify(currentItems)); // Update hidden input
            }

            // Tutup modal setelah memilih
            $('#hfgModal').modal('hide');
        });

        // Ketika tombol "Lihat Daftar HFG" diklik, buka modal dan ambil data HFG dari server
        $('#openModalBtn').on('click', function() {
            const woNumber = $("input[name='wo_number']").val();

            $.ajax({
                url: '<?= base_url('production/get_hfg_data'); ?>',
                type: 'GET',
                data: {
                    wo_number: woNumber
                },
                success: function(response) {
                    let data = JSON.parse(response);
                    let tableBody = $('#hfgTable tbody');
                    tableBody.empty(); // Clear existing data

                    // Filter unique HFG items based on hfg_kode_item
                    let uniqueData = [];
                    let seenItems = new Set();
                    data.forEach(item => {
                        if (!seenItems.has(item.hfg_kode_item)) {
                            uniqueData.push(item);
                            seenItems.add(item.hfg_kode_item);
                        }
                    });

                    // Populate table with unique items as buttons
                    if (uniqueData.length > 0) {
                        uniqueData.forEach(item => {
                            tableBody.append(`
                            <tr>
                                <td><button class="ui blue button select-btn" data-hfg="${item.hfg_kode_item}">Select</button></td>
                                <td>${item.hfg_kode_item}</td>
                                <td>${item.hfg_item_name}</td>
                            </tr>
                        `);
                        });
                    } else {
                        tableBody.append('<tr><td colspan="3">Tidak ada data HFG</td></tr>');
                    }

                    // Show modal
                    $('#hfgModal').modal('show');
                },
                error: function() {
                    alert('Error fetching data');
                }
            });
        });

        // Close modal
        $('#closeModalBtn').on('click', function() {
            $('#hfgModal').modal('hide');
        });

        // --- VALIDASI --- 
        function validateQty() {
            let totalSizerunQty = 0;
            let totalMissingQty = 0;
            const roQty = parseInt($roQtyEl.text(), 10) || 0;
            let isValid = true;
            let invalidRow = false;

            $('#sizerunTable tbody tr').each(function() {
                const $row = $(this);
                const val = parseInt($row.find('input[name^="sizerun_qty"]').val(), 10) || 0;
                totalSizerunQty += val;

                const woSizeQty = parseInt($row.find('.totalQty').text(), 10) || 0;
                const misQty = parseInt($row.find('input[name^="mis_qty"]').val(), 10) || 0;
                totalMissingQty += misQty;

                const totalRowQty = val + misQty;

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

            const totalQty = totalSizerunQty + totalMissingQty;
            $totalEl.text(totalQty);

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

            const formIsValid = isValid && totalQty === roQty;
            $saveBtn.prop('disabled', !formIsValid);

            return formIsValid;
        }

        $(document).on('input', 'input[name^="sizerun_qty"], input[name^="mis_qty"]', function() {
            validateQty();
        });

        $('#productionReportForm').on('submit', function(e) {
            if (!validateQty()) {
                e.preventDefault();
            }
        });

        validateQty();
    });
</script>