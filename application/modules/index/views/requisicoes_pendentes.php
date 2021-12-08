<div class="col-12">
    <div class="top-campaign">
        <h3 class="title-3">Requisições Pendentes</h3>
        <p>Até <?php echo $this->formata_data_hora(date('Y-m-d H:i:s', strtotime('now'))); ?></p><br>
        <?php if (!empty($requisicoes_pendentes)) { ?>
        <table class="table table-responsive table-borderless table-striped table-earning">
            <thead>
                <th scope="col" width="40%">Requisição ID / Solicitante</th> 
                <th scope="col" width="40%">Destino</th>
                <th scope="col" width="40%">Data Inclusão</th>
                <th scope="col" width="40%">Status</th>
                <th scope="col" width="40%">Detalhes</th>
            </thead>
            <tbody>
                <?php
                 foreach($requisicoes_pendentes as $requisicao) {   
                     $solicitante = ucwords($requisicao->solicitante); 
                     $status = $this->status($requisicao->status);;
                ?>
                <tr>
                    <td>
                        <a  href="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>">
                            <?php echo "{$requisicao->id_requisicao} - {$solicitante}";?>
                        </a>
                    </td>
                    <td>
                        <?php echo "{$requisicao->destino}";?>
                    </td>
                    <td>
                        <?php echo $this->formata_data($requisicao->data_inclusao); ?>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}") ?>">Mais Detalhes</a>
                    </td>
                </tr>
                <?php }  ?>
            </tbody>
        </table>
    
        <div class="row">
            <div class="col-12 col-md-8 m-t-20"> 
                <?php echo count($requisicoes_pendentes); ?> 
                De 
                <?php echo $requisicoes_pendentes_total; ?> 
                Requisições Pendentes
            </div>
            <a class="col-12 offset-md-1 col-md-3 m-t-20 btn btn-sm btn-outline-secondary"  href="<?php echo base_url("ferramental_requisicao/"); ?>" >Ver Todas</a> 
        </div>
        <?php } else { ?>
            <p>Nenhuma requisicão pendênte</p>
        <?php } ?>
    </div>
</div>