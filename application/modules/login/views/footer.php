</div>
</main>

<!-- Jquery JS-->
<script src="<?php echo base_url('assets'); ?>/vendor/jquery-3.6.0.min.js"></script>

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