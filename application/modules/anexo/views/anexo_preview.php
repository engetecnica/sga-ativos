
<?php 
    $preview_content = isset($preview_content_tag) ? $preview_content_tag : "td";
    $path = APPPATH."../assets/uploads/";
    $file = $path.$anexo;
?>

<?php echo "<{$preview_content} class=\"preview-content\" >"; ?>
    <a class="preview" target="_black" href="<?php echo base_url("assets/uploads/{$anexo}"); ?>">
        <?php
            if (file_exists($file) && explode('/', mime_content_type($file))[0] == "image") { 
        ?>
            <img src="<?php echo base_url("assets/uploads/{$anexo}");?>" loading="lazy" />
        <?php }  elseif(file_exists($file) && mime_content_type($file) == "application/pdf") { ?>
            <embed
                src="<?php echo base_url("assets/uploads/{$anexo}");?>"
                type="application/pdf"
                frameBorder="0"
                scrolling="auto"
                height="100%"
                width="110%"
                loading="lazy" 
            ></embed>
        <?php } else { ?>
            <i class="fa fa-file" aria-hidden="true"></i>
        <?php } ?>
    </a>
    <div class="preview-footer">
        <?php if (file_exists($file) ) {?>
            <a class="btn btn-sm btn-success" target="_black" href="<?php echo base_url("assets/uploads/{$anexo}"); ?>"><i class="fa fa-eye"></i></a>
            <a class="btn btn-sm btn-info" download href="<?php echo base_url("assets/uploads/{$anexo}"); ?>"><i class="fa fa-download"></i></a>
        <?php } ?>
    </div>
<?php echo "</{$preview_content}>"; ?>