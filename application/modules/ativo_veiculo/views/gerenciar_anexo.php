

<?php if(isset($item)) { ?>
    <div class="row form-group">
        <div class="col col-md-2">
            <label for="$anexo" class=" form-control-label"><?php echo $label;?></label>
        </div>
        <?php if(isset($item->$anexo) && !empty($item->$anexo)) { ?>
        <div class="col-12 col-md-2">
            <div class="btn-grou m-t-20 " role="group">
                <button id="btnGroup<?php echo $anexo;?>Anexo" type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Gerenciar Anexo
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroup<?php echo $anexo;?>Anexo">
                    <a class="dropdown-item" target="_black" href="<?php echo base_url("assets/uploads/{$anexo}/{$item->$anexo}"); ?>">Visualizar</a>
                    <a class="dropdown-item" download href="<?php echo base_url("assets/uploads/{$anexo}/{$item->$anexo}"); ?>">Baixar</a>
                </div>
            </div>
        </div>
        <div class="col col-md-1"></div>
        <?php } ?>
        <div class="col-12 col-md-6">
            <input type="file" id="<?php echo $anexo;?>" name="<?php echo $anexo;?>" class="form-control" accept="application/pdf, image/*, application/vnd.ms-excel" style="margin-bottom: 5px;"> 
            <small size='2'>Formato aceito: <strong>*.PDF, *.XLS, *.XLSx, *.JPG, *.PNG, *.JPEG, *.GIF</strong></small>
            <small size='2'>Tamanho Máximo: <strong><?php echo $upload_max_filesize;?></strong></small>
        </div>
    </div>
<?php } ?>


<?php if(!isset($item)) { ?>
    <div class="row form-group">
        <div class="col col-md-2">
            <label for="$anexo" class=" form-control-label"><?php echo $label;?></label>
        </div>
        <div class="col-12 col-md-9">
            <input type="file" id="<?php echo $anexo;?>" name="<?php echo $anexo;?>" class="form-control" accept="application/pdf, image/*, application/vnd.ms-excel" style="margin-bottom: 5px;"> 
            <small size='2'>Formato aceito: <strong>*.PDF, *.XLS, *.XLSx, *.JPG, *.PNG, *.JPEG, *.GIF</strong></small>
            <small size='2'>Tamanho Máximo: <strong><?php echo $upload_max_filesize;?></strong></small>
        </div>
    </div>
<?php } ?>