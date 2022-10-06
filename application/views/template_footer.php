    <footer class="row">
        <div class="col-md-12">
            <div class="copyright">
                <p>Copyright © <?php echo date("Y"); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Modal Template Histórico de Veículo -->
    <div id="historico-veiculo" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Histórico do Veículo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body historico-veiculo-lista">

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS-->
    <script src="<?php echo base_url("assets/vendor/bootstrap-4.1/popper.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/bootstrap-4.1/bootstrap.min.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- Vendor JS       -->
    <script src="<?php echo base_url("assets/vendor/slick/slick.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/wow/wow.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/animsition/animsition.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/Chart.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/select2/select2.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/lodash.js"); ?>"></script>

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/vendor/dataTables/dataTables.min.css'); ?>"/>
    <script src="<?php echo base_url('assets/vendor/dataTables/dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/circle-progress/circle-progress.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/datatables.js'); ?>"></script>

    <!-- Jquery Mask-->
    <script src="<?php echo base_url("assets/js/jquery.mask.js"); ?>"></script>

    <!-- one-signal.js -->
    <script src="<?php echo base_url('assets/js/one-signal.js'); ?>"></script>

    <!-- Main JS-->
    <script src="<?php echo base_url("assets/js/main.js"); ?>"></script>

    <!-- template_user_scripts -->
    <?php require(__DIR__.'/template_user_scripts.php'); ?>

</body>
</html>
<!-- end document-->