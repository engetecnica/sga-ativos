<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ativo Externo - Gerar QRCODE</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>



<body>
    <table style="font-family: Arial">
        <tr>
            <td width="250px">
                <?php echo "<img src='" . $qrcode . "' width='250px'>"; ?>
            </td>
            <td>
                <h1><?php echo $dados->codigo; ?></h1>
                <p>
                    <b>Ativo Externo:</b> <?php echo $dados->nome; ?>
                    <br><br>
                </p>
                <p>
                    <b>Data: </b><?php echo date("d/m/Y", strtotime($dados->data_inclusao)); ?>
                    <br><br>
                </p>
            </td>
        </tr>
    </table>
</body>



</html>