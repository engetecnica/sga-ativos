<div class="modal" tabindex="100" role="dialog" id="addObs">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-comments" aria-hidden="true"></i>&nbsp; Observação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="formObs" action="<?php echo base_url("ativo_interno/manutencao_obs_salvar/{$ativo->id_ativo_interno}/{$manutencao->id_manutencao}"); ?>" method="post" enctype="multipart/form-data">
                          <?php if(isset($obs) && isset($obs->id_obs)){?>
                            <input type="hidden" name="id_obs" id="id_obs" value="<?php echo $obs->id_obs; ?>">
                          <?php } ?>
                          <div class="row form-group">
                              <div class="col col-md-2">
                                  <label for="observacao" class=" form-control-label">Observação</label>
                              </div>
                              <div class="col-12 col-md-10">
                                  <textarea maxlength="300" required name="texto" id="texto" rows="9" placeholder="Sua Observações Aqui..." class="form-control"><?php if(isset($obs) && isset($obs->texto)){ echo trim($obs->texto); } ?></textarea>
                              </div>
                          </div>
                          <hr>
                          <div class="pull-left">
                              <button class="btn btn-primary">                                                    
                                  <i class="fa fa-comment "></i>&nbsp;
                                  <span id="submit-form">Salvar</span>
                              </button>
                              <button data-dismiss="modal" class="btn btn-secondary" type="button">                                                    
                                  <i class="fa fa-ban "></i>&nbsp;
                                  <span id="cancelar-form">Cancelar</span>
                              </button>   
                          </div>
                        </form>    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function addObs() {
            $("#addObs").modal({show: true})
            $("#id_obs").remove();
            $("#texto").val('');
        }
        function editObs(id, texto) {
            $("#addObs").modal({show: true})
            $('<input>').attr({
                type: 'hidden',
                id: 'id_obs',
                name: 'id_obs',
                value: id,
            }).appendTo("#formObs");
            $('#texto').val(texto)
        }
    </script>
</div>

