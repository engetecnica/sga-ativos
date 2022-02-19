<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Engetecnica</title>
</head>
<body style="<?php echo $styles["body"]; ?>">
<?php if (!$ilustration) $ilustration = false;?>
<div style="<?php echo $styles["body > div"]; ?>">
    <img style="<?php echo $styles["layout-img-top"]; ?>" src="cid:header" onerror="this.src='<?php echo base_url('assets/images/docs/termo_header.png');?>'"><br>
    <?php if ($ilustration) {?>
        <img style="<?php echo $styles["ilustration"]; ?>" src="cid:ilustration" onerror="this.src='<?php echo base_url('assets/images/ilustrations/welcome.png');?>'">
    <?php } ?>
    <h1 style="<?php echo $styles["h1"]; ?>"><?php echo $assunto; ?></h1>
    <!-- start content -->
    <div style="<?php echo $styles["content"]; ?>">