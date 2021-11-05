<?php
    $ilustration_img = null;
    if (isset($ilustration)) {
        if (is_array($ilustration) && count($ilustration) > 0) {
            $ilustration_img = $ilustration[rand(0, count($ilustration) - 1)];
        } else {
            $ilustration_img = $ilustration;
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Engetecnica - App</title>
</head>
<body style="<?php echo $styles["body"]; ?>">

<div style="<?php echo $styles["body > div"]; ?>">

<!-- 
<img src="<?php echo base_url("assets/images/icon/logo.png");?>">
<br> -->

<h1 style="<?php echo $styles["body > div > h1"]; ?>">Engetecnica</h1>
<h2 style="<?php echo $styles["body > div > h2"]; ?>"><?php echo $assunto; ?></h2><br>

<?php if ($ilustration_img) {?>
    <img style="<?php echo $styles["body > div > img"]; ?>" src="<?php echo base_url("assets/images/ilustrations/{$ilustration_img}.svg");?>">
    <br>
<?php } ?>
<br>
<br>
<br>

<!-- content -->
<div style="<?php echo $styles["content"]; ?>">