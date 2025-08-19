<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Inventory ERP <?= date('Y'); ?></span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?= base_url('auth/logout') ?>">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS FIRST -->
<script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Keep references to Bootstrap plugins BEFORE loading Semantic UI -->
<script>
    // save Bootstrap's plugins
    var bootstrapModal = $.fn.modal;
    var bootstrapDropdown = $.fn.dropdown;
</script>

<!-- Now load Semantic UI JS -->
<script src="https://cdn.jsdelivr.net/npm/semantic-ui/dist/semantic.min.js"></script>

<script>
    // // Save Semantic UI plugins under conflict-free names
    // $.fn.semanticDropdown = $.fn.dropdown;
    // $.fn.semanticModal = $.fn.modal;

    // // Restore Bootstrap plugins to their default names
    // $.fn.modal = bootstrapModal; // so data-toggle="modal" keeps working
    // $.fn.dropdown = bootstrapDropdown; // keeps any Bootstrap dropdowns working

    // Initialize controls
    $(function() {
        // Conflict handling already done above

        // Initialize dropdowns when modal is shown
        $(document).on('shown.bs.modal', function(e) {
            var $modal = $(e.target);

            // Initialize Semantic UI dropdowns only once per element
            $modal.find('.ui.dropdown').each(function() {
                var $dd = $(this);
                if (!$dd.data('moduleDropdown')) {
                    $dd.semanticDropdown({
                        fullTextSearch: true,
                        clearable: true
                    });
                }
            });

            // Initialize checkboxes if you have any
            $modal.find('.ui.checkbox').checkbox();
        });
    });
</script>

<!-- (Optional) DataTables JS after everything else -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.semanticui.min.js"></script>


<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            // optional: enable Semantic UI styling
            "pagingType": "full_numbers"
        });
    });
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    $('.form-check-input').on('click', function() {
        const roleId = $(this).data('role'); // match HTML attribute
        const menuId = $(this).data('menu'); // match HTML attribute
        const isChecked = $(this).is(':checked') ? 1 : 0; // send true/false as int

        $.ajax({
            url: "<?= base_url('admin/changeaccess'); ?>",
            type: 'post',
            data: {
                roleId: roleId,
                menuId: menuId,
                isChecked: isChecked
            },
            success: function() {
                document.location.href = "<?= base_url('admin/roleaccess/') ?>" + roleId;
            }
        });
    });
</script>


</body>

</html>