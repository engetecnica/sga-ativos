<?php $situacao = $this->get_situacao($row->situacao, 'DESCARTADO', 'secondary'); ?>
<span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>