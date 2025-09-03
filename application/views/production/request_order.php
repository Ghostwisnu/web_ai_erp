<!-- application/views/production/request_order.php -->
<style>
    .mt-2 {
        margin-top: .75rem
    }

    .mt-3 {
        margin-top: 1rem
    }

    .mb-3 {
        margin-bottom: .75rem
    }

    .ui.modal .scrolling.content {
        max-height: 70vh;
        overflow-y: auto
    }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <?= form_error('production', '<div class="ui negative message">', '</div>'); ?>
    <?= $this->session->flashdata('message'); ?>

    <div class="mb-3">
        <a href="<?= base_url('production/create_request_order'); ?>" class="ui blue button">
            <i class="plus icon"></i> Create
        </a>
        <a href="<?= base_url('production/upload_item'); ?>" class="ui teal button">
            <i class="upload icon"></i> Upload
        </a>
    </div>

    <table id="roTable" class="ui celled table">
        <thead>
            <tr>
                <th style="width:60px;">#</th>
                <th style="width:120px;">Print PDF</th>
                <th>WO Number</th>
                <th>Kode Request Order</th>
                <th>Tgl Request Order</th>
                <th>Status RO</th> <!-- NEW -->
                <th style="width:140px;">Detail</th>
                <th style="width:160px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($request)): $i = 1; ?>
                <?php foreach ($request as $ro): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td>
                            <a href="<?= base_url('production/ro_pdf?kode_ro=' . urlencode($ro['kode_ro'])) ?>" class="ui small button">
                                <i class="print icon"></i> Print
                            </a>
                        </td>
                        <td><?= htmlspecialchars($ro['wo_number']); ?></td>
                        <td><?= htmlspecialchars($ro['kode_ro']); ?></td>
                        <td><?= htmlspecialchars($ro['last_created_at']); ?></td>

                        <!-- NEW: Status RO -->
                        <?php
                        $status = strtolower($ro['status_ro'] ?? '');
                        $labelClass = 'grey';
                        if ($status === 'menunggu dikirim') $labelClass = 'yellow';
                        elseif ($status === 'sudah dikirim') $labelClass = 'green';
                        elseif ($status === 'dibatalkan') $labelClass = 'red';
                        ?>
                        <td><span class="ui <?= $labelClass; ?> label"><?= htmlspecialchars($ro['status_ro'] ?? '-'); ?></span></td>

                        <td>
                            <button class="ui small blue button btn-detail" data-koderro="<?= htmlspecialchars($ro['kode_ro']); ?>">
                                <i class="info circle icon"></i> Lihat Detail
                            </button>
                        </td>
                        <td>
                            <a href="<?= base_url('production/edit_ro?kode_ro=' . urlencode($ro['kode_ro'])) ?>" class="ui small orange button">
                                <i class="edit icon"></i> Edit
                            </a>
                            <button type="button" class="ui small red button btn-soft-delete" data-kode="<?= htmlspecialchars($ro['kode_ro']); ?>">
                                <i class="trash icon"></i> Delete
                            </button>
                            <a href="<?= base_url('production/create_production_report?kode_ro=' . urlencode($ro['kode_ro'])) ?>" class="ui small purple button">
                                <i class="chart bar icon"></i> Create Report
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="center aligned">Belum ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Detail (Semantic UI) -->
<div class="ui modal" id="roDetailModal" role="dialog" aria-modal="true">
    <i class="close icon" aria-label="Close"></i>
    <div class="header">
        Detail Request Order
    </div>
    <div class="content scrolling">
        <div id="roDetailLoading" class="ui active inverted dimmer" style="display:none;">
            <div class="ui text loader">Loading</div>
        </div>

        <div id="roDetailError" class="ui negative message" style="display:none;"></div>

        <div id="roDetailHeader" class="ui relaxed list" style="display:none;">
            <div class="item"><strong>WO Number:</strong> <span id="d_wo"></span></div>
            <div class="item"><strong>Kode RO:</strong> <span id="d_kode"></span></div>
            <div class="item"><strong>Status:</strong> <span id="d_status"></span></div>
            <div class="item"><strong>From Dept:</strong> <span id="d_from"></span></div>
            <div class="item"><strong>To Dept:</strong> <span id="d_to"></span></div>
            <div class="item"><strong>Tanggal RO:</strong> <span id="d_tgl"></span></div>
            <div class="item"><strong>Created By:</strong> <span id="d_user"></span></div>
            <div class="item"><strong>Created At:</strong> <span id="d_created"></span></div>
            <div class="item"><strong>Total Sizerun:</strong> <span id="d_total_sizerun"></span></div> <!-- NEW -->
        </div>


        <h4 class="ui dividing header" id="roDetailLinesTitle" style="display:none;">Items</h4>
        <table class="ui celled table" id="roDetailLines" style="display:none;">
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th>Kode Item</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th class="right aligned">Qty</th>
                </tr>
            </thead>
            <tbody><!-- JS --></tbody>
        </table>
    </div>
    <div class="actions">
        <div class="ui grey button deny">Close</div>
    </div>
</div>

<script>
    $(function() {
        // ---- DataTables init: hanya sekali, aman dari reinit ----
        if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#roTable')) {
            $('#roTable').DataTable({
                paging: true,
                searching: true,
                info: false,
                lengthChange: false
            });
        }

        // ---- Inisialisasi modal Semantic UI ----
        $('#roDetailModal').modal({
            autofocus: false,
            observeChanges: true,
            onShow: function() {
                // bersih state
                $('#roDetailError').hide().empty();
                $('#roDetailHeader').hide();
                $('#roDetailLines').hide();
                $('#roDetailLinesTitle').hide();
                $('#roDetailLines tbody').empty();
            },
            onVisible: function() {
                // fokuskan ke tombol "Close" (aksesibilitas)
                $(this).find('.actions .button').eq(0).trigger('focus');
            }
        });

        function showDetailLoading(show) {
            $('#roDetailLoading')[show ? 'show' : 'hide']();
        }

        function openRoDetail(kode_ro) {
            $('#roDetailModal').modal('show');
            showDetailLoading(true);

            $.getJSON("<?= base_url('production/ro_details'); ?>", {
                    kode_ro: kode_ro
                })
                .done(function(res) {
                    showDetailLoading(false);

                    if (!res || (!res.header && !res.lines)) {
                        $('#roDetailError').text('Data detail tidak ditemukan.').show();
                        return;
                    }

                    // Header
                    if (res.header) {
                        $('#d_wo').text(res.header.wo_number || '');
                        $('#d_kode').text(res.header.kode_ro || '');
                        $('#d_status').text(res.header.status_ro || '');
                        $('#d_from').text(res.header.from_dept || '');
                        $('#d_to').text(res.header.to_dept || '');
                        $('#d_tgl').text(res.header.date_ro || '');
                        $('#d_user').text(res.header.created_by || '');
                        $('#d_created').text(res.header.created_at || '');
                        $('#d_total_sizerun').text(res.header.total_sizerun || '0'); // Show total sizerun
                        $('#roDetailHeader').show();
                    }

                    // Lines
                    const lines = res.lines || [];
                    if (lines.length > 0) {
                        let tb = '';
                        lines.forEach((ln, i) => {
                            tb += `<tr>
                        <td>${i+1}</td>
                        <td>${ln.kode_item || ''}</td>
                        <td>${ln.item_name || ''}</td>
                        <td>${ln.category || ''}</td>
                        <td>${ln.unit || ''}</td>
                        <td class="right aligned">${ln.ro_qty || ''}</td>
                    </tr>`;
                        });
                        $('#roDetailLines tbody').html(tb);
                        $('#roDetailLinesTitle').show();
                        $('#roDetailLines').show();
                    } else {
                        $('#roDetailError').text('Tidak ada item untuk RO ini.').show();
                    }
                })
                .fail(function(xhr) {
                    showDetailLoading(false);
                    $('#roDetailError').text('Gagal memuat detail: ' + (xhr.responseText || xhr.statusText)).show();
                });
        }

        // ---- Klik tombol Detail ----
        $(document).on('click', '.btn-detail', function() {
            const kode = $(this).data('koderro');
            if (!kode) return;
            openRoDetail(kode);
        });
        // Soft delete by kode_ro
        $(document).on('click', '.btn-soft-delete', function() {
            const kode = $(this).data('kode');
            if (!kode) return;

            if (!confirm('Hapus semua item untuk Kode RO: ' + kode + ' ?')) return;

            $.ajax({
                url: "<?= base_url('production/ro_soft_delete'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    kode_ro: kode
                },
                success: function(res) {
                    // refresh halaman atau hapus baris di tabel
                    location.reload();
                },
                error: function(xhr) {
                    let msg = 'Gagal menghapus.';
                    try {
                        msg = JSON.parse(xhr.responseText).error || msg;
                    } catch (e) {}
                    alert(msg);
                }
            });
        });
    });
</script>