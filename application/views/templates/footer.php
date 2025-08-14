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

<!-- jQuery -->
<script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap core JavaScript -->
<script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Semantic UI -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
<script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>


<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>

<script>
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
    $(document).ready(function() {
        $('#editMenuModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');

            var modal = $(this);
            modal.find('#edit-id-menu').val(id);
            modal.find('#edit-menu-name').val(name);
            modal.find('#edit-id-submenu').val(id);
            modal.find('#edit-submenu-name').val(name);
            modal.find('#edit-id-role').val(id);
            modal.find('#edit-role-name').val(name);
        });
    });
    // Save Semantic UI dropdown to a new function name so Bootstrap won't override it
    $.fn.semanticDropdown = $.fn.dropdown;

    // Initialize search dropdown when modal is opened
    $('#newSubMenuModal').on('shown.bs.modal', function() {
        $('#menu_name_dropdown').semanticDropdown({
            fullTextSearch: true,
            clearable: true
        });
    });
</script>

</body>

</html>