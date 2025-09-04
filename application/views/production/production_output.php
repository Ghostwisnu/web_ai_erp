<style>
    /* Highlight baris yang salah tanpa ubah font */
    .row-error td {
        background-color: #ffe6e6 !important;
        font-size: inherit;
        color: inherit;
    }

    /* Styling untuk setiap status */
    .status-menunggu-dikirim {
        background-color: yellow;
    }

    .status-sudah-dikirim {
        background-color: blue;
    }

    .status-sudah-lengkap {
        background-color: green;
    }

    .status-belum-lengkap {
        background-color: red;
    }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <!-- Error message and flash message -->
    <?= form_error('production', '<div class="ui negative message">', '</div>'); ?>
    <?= $this->session->flashdata('message'); ?>

    <!-- Action buttons -->
    <div class="mb-3">
        <a href="<?= base_url('production/create_request_order'); ?>" class="ui blue button">
            <i class="plus icon"></i> Create
        </a>
        <a href="<?= base_url('production/upload_item'); ?>" class="ui teal button">
            <i class="upload icon"></i> Upload
        </a>
    </div>

    <!-- Table for Request Orders -->
    <table id="roTable" class="ui celled table">
        <thead>
            <tr>
                <th style="width:60px;">#</th>
                <th style="width:120px;">Print PDF</th>
                <th>WO Number</th>
                <th>Kode Request Order</th>
                <th>Tgl Request Order</th>
                <th>Status</th>
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
                        <?php
                        $status = strtolower($ro['status_ro'] ?? ''); // Mengambil status dan memastikan lowercase
                        $labelClass = 'grey'; // Default class untuk status yang tidak dikenal

                        // Tentukan kelas berdasarkan status
                        switch ($status) {
                            case 'menunggu dikirim':
                                $labelClass = 'yellow'; // Kuning untuk 'Menunggu Dikirim'
                                break;
                            case 'sudah dikirim':
                                $labelClass = 'blue'; // Biru untuk 'Sudah Dikirim'
                                break;
                            case 'produksi sudah lengkap':
                                $labelClass = 'green'; // Hijau untuk 'Produksi Sudah Lengkap'
                                break;
                            case 'produksi belum lengkap':
                                $labelClass = 'red'; // Merah untuk 'Produksi Belum Lengkap'
                                break;
                            default:
                                $labelClass = 'grey'; // Default jika status tidak ditemukan
                        }
                        ?>
                        <td><span class="ui <?= $labelClass; ?> label"><?= htmlspecialchars($ro['status_ro'] ?? '-'); ?></span></td>
                        <td>
                            <button class="ui small blue button btn-detail" data-koderro="<?= htmlspecialchars($ro['kode_ro']); ?>">
                                <i class="info circle icon"></i> Lihat Detail
                            </button>
                        </td>
                        <td>
                            <!-- Tombol Create Report hanya muncul jika statusnya 'Sudah Dikirim' -->
                            <a href="<?= base_url('production/production_report?kode_ro=' . urlencode($ro['kode_ro'])) ?>" class="ui small orange button" id="createReportBtn-<?= $ro['kode_ro']; ?>"
                                <?php if (strtolower($ro['status_ro']) !== 'sudah dikirim') echo 'hidden'; ?>>
                                <i class="edit icon"></i> Create Report
                            </a>

                            <!-- Tombol Delete -->
                            <button type="button" class="ui small red button btn-soft-delete" data-kode="<?= htmlspecialchars($ro['kode_ro']); ?>">
                                <i class="trash icon"></i> Delete
                            </button>

                            <!-- Tombol Export Report hanya muncul jika statusnya 'Produksi Sudah Lengkap' -->
                            <a href="<?= base_url('production/create_production_report?kode_ro=' . urlencode($ro['kode_ro'])) ?>" class="ui small purple button" id="exportReportBtn-<?= $ro['kode_ro']; ?>"
                                <?php if (strtolower($ro['status_ro']) !== 'produksi sudah lengkap') echo 'hidden'; ?>>
                                <i class="chart bar icon"></i> Export Report
                            </a>

                            <!-- Tombol hanya muncul jika status "Produksi Belum Lengkap" -->
                            <?php if ($status === 'produksi belum lengkap'): ?>
                                <a href="<?= base_url('production/fix_production/' . urlencode($ro['kode_ro'])); ?>" class="ui small yellow button">
                                    <i class="exclamation triangle icon"></i> Perbaiki Produksi
                                </a>
                            <?php endif; ?>


                            <!-- Tombol hanya muncul jika status "Produksi Sudah Lengkap" -->
                            <?php if ($status === 'produksi sudah lengkap'): ?>
                                <button type="button" class="ui small green button">
                                    <i class="download icon"></i> Export Produksi
                                </button>
                            <?php endif; ?>
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
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<script>
    $(function() {
        // ---- DataTables init ----
        if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#roTable')) {
            $('#roTable').DataTable({
                paging: true,
                searching: true,
                info: false,
                lengthChange: false
            });
        }

        // ---- Modal Detail initialization ----
        $('#roDetailModal').modal({
            autofocus: false,
            observeChanges: true,
            onShow: function() {
                $('#roDetailError').hide().empty();
                $('#roDetailHeader').hide();
                $('#roDetailLines').hide();
                $('#roDetailLinesTitle').hide();
                $('#roDetailLines tbody').empty();
            },
            onVisible: function() {
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
                        $('#d_status').text(res.header.status_ro || ''); // Menampilkan status_ro
                        $('#d_from').text(res.header.from_dept || '');
                        $('#d_to').text(res.header.to_dept || '');
                        $('#d_tgl').text(res.header.date_ro || '');
                        $('#d_user').text(res.header.created_by || '');
                        $('#d_created').text(res.header.created_at || '');
                        $('#d_total_sizerun').text(res.header.total_sizerun || '0'); // Menampilkan total_sizerun
                        $('#roDetailHeader').show();
                    }

                    // Lines (items)
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

        $(document).ready(function() {
            // Menonaktifkan tombol berdasarkan status RO setelah tabel dimuat
            $('#roTable tbody tr').each(function() {
                var status_ro = $(this).find('td:eq(5)').text().trim().toLowerCase(); // Ambil status dari kolom ke-5 (Status)

                // Jika status bukan "Sudah Dikirim", disable tombol dan sembunyikan
                if (status_ro !== 'sudah dikirim') {
                    $(this).find('a[id^="createReportBtn-"]').prop('hidden', true); // Sembunyikan Create Report button
                    $(this).find('a[id^="exportReportBtn-"]').prop('hidden', true); // Sembunyikan Export Report button
                }
            });
        });

        // ---- Click on Detail button ----
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