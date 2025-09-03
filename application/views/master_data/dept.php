<!-- Begin Page Content -->
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <?= $this->session->flashdata('message'); ?>

    <div class="ui stackable grid">
        <div class="eight wide column">
            <div class="ui segment">
                <h4 class="ui header"><i class="plus icon"></i> Tambah Departemen</h4>

                <form class="ui form" id="deptForm">
                    <div class="two fields">
                        <div class="field">
                            <label>Kode Dept</label>
                            <input type="text" id="kode_dept" placeholder="CTH: DEPT-PRD" maxlength="252" style="text-transform: uppercase;">
                        </div>
                        <div class="field">
                            <label>Nama Dept</label>
                            <input type="text" id="dept_name" placeholder="CTH: PRODUCTION" maxlength="252" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <button type="submit" class="ui green button"><i class="save icon"></i> Simpan</button>
                    <button type="button" class="ui button" id="btnClear"><i class="eraser icon"></i> Bersihkan</button>
                </form>

                <div class="ui hidden message" id="formMsg"></div>
            </div>
        </div>

        <div class="eight wide column">
            <div class="ui segment">
                <h4 class="ui header"><i class="list icon"></i> Daftar Departemen</h4>
                <table class="ui celled table" id="deptTable">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Kode Dept</th>
                            <th>Nama Dept</th>
                            <th style="width:110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody><!-- filled by JS --></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<script>
    $(function() {
        // Force UPPERCASE saat mengetik (data yang terkirim juga uppercase)
        $('#kode_dept, #dept_name').on('input', function() {
            this.value = this.value.toUpperCase();
        });

        let dt = null;

        function toast($el, ok, msg) {
            $el.removeClass('hidden').removeClass('positive negative')
                .addClass(ok ? 'positive' : 'negative')
                .html(msg);
            setTimeout(() => $el.addClass('hidden'), 3000);
        }

        function initDT() {
            if (dt) {
                try {
                    dt.destroy();
                } catch (e) {}
            }
            dt = $('#deptTable').DataTable({
                paging: true,
                searching: true,
                info: false,
                lengthChange: false,
                order: [
                    [3, 'desc']
                ]
            });
        }

        function loadList() {
            const url = "<?= base_url('master/dept_list'); ?>";
            // Cache-buster
            const bustUrl = url + (url.indexOf('?') === -1 ? '?' : '&') + '_=' + Date.now();

            // Hancurkan DataTable kalau sudah ada
            if ($.fn.DataTable.isDataTable('#deptTable')) {
                $('#deptTable').DataTable().clear().destroy();
            }

            $.ajax({
                url: bustUrl,
                type: 'GET',
                dataType: 'json',
                cache: false, // penting
                success: function(resp) {
                    const rows = resp.data || [];
                    let tb = '';
                    rows.forEach((r, i) => {
                        tb += `
          <tr>
            <td>${i+1}</td>
            <td>${r.kode_dept}</td>
            <td>${r.dept_name}</td>
            <td>
              <button class="ui tiny red button btnDel" data-id="${r.id_dept}">
                <i class="trash icon"></i> Delete
              </button>
            </td>
          </tr>`;
                    });
                    $('#deptTable tbody').html(tb);

                    // Re-init DataTable setelah tbody diisi
                    $('#deptTable').DataTable({
                        paging: true,
                        searching: true,
                        info: false,
                        lengthChange: false,
                        order: [
                            [3, 'desc']
                        ]
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Gagal memuat daftar departemen.');
                }
            });
        }


        // Create
        $('#deptForm').on('submit', function(e) {
            e.preventDefault();
            const kode = ($('#kode_dept').val() || '').trim().toUpperCase();
            const name = ($('#dept_name').val() || '').trim().toUpperCase();

            if (!kode || !name) {
                toast($('#formMsg'), false, 'Kode dan Nama Departemen wajib diisi.');
                return;
            }

            $.ajax({
                url: "<?= base_url('master/dept_create'); ?>",
                type: "POST",
                data: JSON.stringify({
                    kode_dept: kode,
                    dept_name: name
                }),
                contentType: "application/json",
                success: function() {
                    toast($('#formMsg'), true, 'Departemen berhasil ditambahkan.');
                    $('#deptForm')[0].reset();
                    loadList();
                },
                error: function(xhr) {
                    let err = 'Gagal menyimpan data.';
                    try {
                        err = (JSON.parse(xhr.responseText).error) || err;
                    } catch (e) {}
                    toast($('#formMsg'), false, err);
                }
            });
        });

        // Clear
        $('#btnClear').on('click', function() {
            $('#deptForm')[0].reset();
        });

        // Soft delete
        $(document).on('click', '.btnDel', function() {
            const id = $(this).data('id');
            if (!confirm('Hapus departemen ini? (soft delete)')) return;

            $.post("<?= base_url('master/dept_delete'); ?>", {
                id_dept: id
            }, function() {
                loadList(); // reload fresh
            }).fail(function(xhr) {
                let err = 'Gagal menghapus.';
                try {
                    err = (JSON.parse(xhr.responseText).error) || err;
                } catch (e) {}
                alert(err);
            });
        });
        // first load
        loadList();
    });
</script>