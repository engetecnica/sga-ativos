<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url("ativo_interno/manutencao_editar/{$ativo->id_ativo_interno}/{$manutencao->id_manutencao}#obs"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>Voltar as Observações</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25"><?php echo $ativo->nome; ?></h2>

                    <div class="card">
                      <div class="card-header">
                          <?php 
                            if(isset($manutencao) && isset($manutencao->id_manutencao)){
                              echo  "Editar observação de manutenção";
                            } else {
                              echo  'Nova observação de manutenção';
                            } 
                          ?>
                        </div>
                        <div class="card-body">

                          <form action="<?php echo base_url("ativo_interno/manutencao_obs_salvar/{$ativo->id_ativo_interno}/{$manutencao->id_manutencao}"); ?>" method="post" enctype="multipart/form-data">
                            <?php if(isset($obs) && isset($obs->id_obs)){?>
                              <input type="hidden" name="id_obs" id="id_obs" value="<?php echo $obs->id_obs; ?>">
                            <?php } ?>

                            <div class="row form-group">
                                <div class="col col-md-2">
                                    <label for="observacao" class=" form-control-label">Observação</label>
                                </div>
                                <div class="col-12 col-md-10">
                                    <textarea name="texto" id="texto" rows="9" placeholder="Sua Observações Aqui..." class="form-control">
                                    <?php if(isset($obs) && isset($obs->texto)){ echo trim($obs->texto); } ?>
                                    </textarea>
                                </div>
                            </div>

                            <hr>
                            <div class="pull-left">
                                <button class="btn btn-primary">                                                    
                                    <i class="fa fa-comment "></i>&nbsp;
                                    <span id="submit-form">Salvar</span>
                                </button>
                                <a href="<?php echo base_url("ativo_interno/manutencao_editar/{$ativo->id_ativo_interno}/{$manutencao->id_manutencao}#obs");?>">
                                <button class="btn btn-info" type="button">                                                    
                                    <i class="fa fa-ban "></i>&nbsp;
                                    <span id="cancelar-form">Cancelar</span>
                                </button>                                                
                                </a>
                            </div>
                          </form>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p>Copyright © <?php echo date("Y"); ?>. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
