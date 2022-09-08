<?php $nivel = $this->get_usuario_nivel($row->nivel);?>
<span class="badge badge-<?php echo $nivel['class']; ?>"><?php echo $row->nivel_nome; ?></span>