

<?php

$ilustration_img = null;
if (isset($ilustration)) {
    if (is_array($ilustration) && count($ilustration) > 0) {
        $ilustration_img = $ilustration[rand(0, count($ilustration) - 1)];
    } else {
        $ilustration_img = $ilustration;
    }
}

$styles = [
    "btn" => "background: #fd9e0f; color: #FFFFFF; font-weight: 400; font-size: 25px; padding: 20px 35px;
    text-decoration:none; border-radius: 5px; margin: 10px; cursor: pointer;",
    "table" => "border: solid 1px #0002; border-radius: 8px; margin: 0 auto;",
    "thead" => "background-color: #002; color: #FFF; font-size: 16px; text-align: center; font-weight: bold;",
    "tr_td_th" => "padding: 20px;",
    "first_th" => "{_['tr_td_th']} border-radius: 5px 0 0 0;",
    "last_th" => "{_['tr_td_th']} border-radius: 0 5px 0 0;",
    "tr" => "font-size: 16px; text-align: center; padding: 20px;",
    "tr2" => "{_['tr']} background: #FFE;",
];

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Engetecnica - App</title>
</head>
<body style="padding: 0px; margin:0; font-family: sans-serif; font-size: 18px;">

<div style="padding: 40px; background: #eee; text-align: center;">

<!-- 
<img src="<?php echo base_url("assets/images/icon/logo.png");?>">
<br> -->

<h1 style="color: #fd9e0f; font-size: 55px; margin-bottom: 0;">Engetecnica</h1>
<h2 style="color: #003; font-size: 35px; margin-top: 0;"><?php echo $assunto; ?></h2><br>

<?php if ($ilustration_img) {?>
    <img style="width: 60vh;" src="<?php echo base_url("assets/images/ilustrations/{$ilustration_img}.svg");?>">
    <br>
<?php } ?>
<br>
<br>
<br>

<!-- content -->
<div style="padding: 40px; color: #002;">



<!-- btn ->  style="background: #fd9e0f; color: #FFFFFF; font-weight: 400; font-size: 25px; padding: 20px 35px;
    text-decoration:none; border-radius: 5px; margin: 10px; cursor: pointer;"  -->

<!-- 
<h2>Manuteções</h2>
<br>
<table style="border: solid 1px #0002; border-radius: 8px; margin: 0 auto;">
    <thead style="background-color: #002; color: #FFF; font-size: 16px; text-align: center; font-weight: bold;">
        <tr style="padding: 20px;">
            <th style="padding: 20px; border-radius: 5px 0 0 0;" >Manutenção ID</th>
            <th style="padding: 20px;" >Veículo ID</th>
            <th style="padding: 20px;" >Marca/Modelo</th>
            <th style="padding: 20px;" >Placa</th>
            <th style="padding: 20px;" >Fornecedor</th>
            <th style="padding: 20px;" >Tipo Manutenção</th>
            <th style="padding: 20px;" >Data Manutenção</th>
            <th style="padding: 20px; border-radius: 0 5px 0 0;" >Data Vencimento</th>
        </tr>
    </thead>
    <tbody>
        
        <tr style="font-size: 16px; text-align: center;">
            <td style="padding: 20px;">1</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">00/00/0000 </td>
            <td style="padding: 20px;">00/00/0000</td>
        </tr>

        <tr style="background: #FFE; font-size: 16px; text-align: center;">
            <td style="padding: 20px;">2</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">00/00/0000 </td>
            <td style="padding: 20px;">00/00/0000</td>
        </tr>

        <tr style="font-size: 16px; text-align: center; padding: 20px;"> 
            <td style="padding: 20px;">3</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">00/00/0000 </td>
            <td style="padding: 20px;">00/00/0000</td>
        </tr>

        <tr style="background: #FFE; font-size: 16px; text-align: center; padding: 20px;">
            <td style="padding: 20px;">4</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">abcd 123450</td>
            <td style="padding: 20px;">00/00/0000 </td>
            <td style="padding: 20px;">00/00/0000</td>
        </tr>
    </tbody>
</table> -->