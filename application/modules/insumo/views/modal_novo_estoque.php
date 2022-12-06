
<!-- Modal -->
<div class="modal fade" id="novo_estoque" tabindex="-1" role="dialog" aria-labelledby="novo_estoque_titulo"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?php echo base_url('insumo/salvar_estoque'); ?>" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Adicionar Quantidade ao Estoque</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <input type="hidden" name="id_insumo" id="id_insumo" value="">
                        <div class="form-group">
                            <label for="item-titulo" class="col-form-label">Item:</label>
                            <input type="text" class="form-control" id="item-titulo" readonly>
                        </div>
                        <div class="form-group">
                            <label for="item-quantidade" class="col-form-label">Quantidade:</label>
                            <input type="number" value="1" min="1" class="form-control" name="item-quantidade" id="item-quantidade">
                        </div>
                        <div class="form-group">
                            <label for="item-valor-unitario" class="col-form-label">Valor Unitário:</label>
                            <input type="text" value="0" min="1" class="form-control valor" name="item-valor-unitario" id="item-valor-unitario">
                        </div>
                        <div class="form-group">
                            <label for="item-data" class="col-form-label">Data de Inclusão:</label>
                            <input type="datetime-local" value="<?php echo date("Y-m-d H:i"); ?>" class="form-control" name="item-data" id="item-data">
                        </div>
                        <div class="form-group">
                            <label for="item-descricao" class="col-form-label">Observações:</label>
                            <textarea class="form-control" id="item-descricao"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar Estoque</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>