 
                </div>
            </div>
        </div>
    </div>
 
     <!-- Jquery JS-->
    <script src="<?php echo base_url('assets'); ?>/vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="<?php echo base_url('assets'); ?>/vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="<?php echo base_url('assets'); ?>/vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="<?php echo base_url('assets'); ?>/vendor/slick/slick.min.js">
    </script>
    <script src="<?php echo base_url('assets'); ?>/vendor/wow/wow.min.js"></script>
    <script src="<?php echo base_url('assets'); ?>/vendor/animsition/animsition.min.js"></script>
    <script src="<?php echo base_url('assets'); ?>/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="<?php echo base_url('assets'); ?>/vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="<?php echo base_url('assets'); ?>/vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="<?php echo base_url('assets'); ?>/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="<?php echo base_url('assets'); ?>/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo base_url('assets'); ?>/vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="<?php echo base_url('assets'); ?>/vendor/select2/select2.min.js">
    </script>

    <!-- Main JS-->
    <script src="<?php echo base_url('assets'); ?>/js/main.js"></script>

   <!-- Sweet Alert --> 
    <script src="<?php echo base_url('assets'); ?>/vendor/sweetalert/sweetalert2.min.js"></script>
    <script src="<?php echo base_url('assets'); ?>/js/jquery.mask.js"></script>



    <script>
        <?php if($this->session->flashdata('msg_success')==true){ ?>
        Swal.fire({
            title: 'Sucesso!',
            text: '<?php echo $this->session->flashdata('msg_success'); ?>',
            icon: 'success',
            confirmButtonText: 'Ok, fechar.'
        })
        <?php } ?>

        <?php if($this->session->flashdata('msg_erro')==true){ ?>
        Swal.fire({
            title: 'Erro!',
            text: '<?php echo $this->session->flashdata('msg_erro'); ?>',
            icon: 'error',
            confirmButtonText: 'Ok, fechar.'
        })
        <?php } ?>

    </script>    

</body>

</html>
<!-- end document-->