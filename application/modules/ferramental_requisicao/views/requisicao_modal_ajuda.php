<div class="modal" tabindex="100" role="dialog" id="ajudaModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-question-circle"></i> Ajuda</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p class="m-t-30"><b>1</b> - Para <b>Liberar Requisição</b> ou <b>Recusar Requisição</b> dos itens solicitados, a mesma deve ter o status atual 
                <b class="badge badge-<?php echo $this->status_by_name('pendente')['class'];?>">Pendênte</b>.<br> Há a possibiliadade de especificar o número de unidades do mesmo item a ser
                liberadas modificando o valor númerico de acordo com o desejado na coluna <i>Liberar</i> da tabela <i>Itens</i>.
            </p>
            
            <p class="m-t-30"><b>2</b> - Para <b>Enviar para Transferência</b> e confirmar a transferência dos itens solicitados ou **devolvidos, a mesma deve ter o status atual 
                <b class="badge badge-<?php echo $this->status_by_name('liberado')['class'];?>">Liberado</b>, 
                Recomendado executar a ação somente após a saída para transporte.
                <br>
                ** Itens podem ser automaticamente devolvidos quando recebidos, porém marcados como 
                <b class="badge badge-<?php echo $this->status_by_name('devolvido')['class'];?>">Devolvido</b> ou 
                <b class="badge badge-<?php echo $this->status_by_name('comdefeito')['class'];?>">Com Defeito</b></p>
            
            <p class="m-t-30"><b>3</b> - <b>Receber Devoluções</b> , confirma a recepção de itens devolvidos ou com defeito oriundos de uma Requisição anteriormente realizada,
            a mesma deve ter o status atual <b class="badge badge-<?php echo $this->status_by_name('emtransito')['class'];?>">Em Trânsito</b>.</p>
            
            <p class="m-t-30"><b>4</b> - Para <b>Excluir</b> uma Requisição de itens, a mesma deve ter o status atual 
                <b class="badge badge-<?php echo $this->status_by_name('pendente')['class'];?>">Pendênte</b>
                e somente o usuário solicitante ou um administrador poderão executar essa ação.
            </p>
            
            <p class="m-t-30"><b>5</b> - Para **<b>Receber</b> itens de uma Requisição, a mesma deve ter o status atual 
                <b class="badge badge-<?php echo $this->status_by_name('emtransito')['class'];?>">Em Trânsito</b>
                e somente o usuário solicitante ou um administrador poderão executar essa ação.
                <br>
                Essa ação conclui o processo de transferência entre obras e se tudo correu bem, o status deve mudar para 
                <b class="badge badge-<?php echo $this->status_by_name('recebido')['class'];?>">Recebido</b>.
            </p>
            
            <p class="m-t-30"><b>6</b> - Para <b>Solicitar Itens Não Inclusos</b> de uma requisição anteriormente não transferidos ou liberados por algum motivo, a mesma deve ter o status atual 
                <b class="badge badge-<?php echo $this->status_by_name('recebidoparcialmente')['class'];?>">Recebido Parcialmente</b> ou 
                <b class="badge badge-<?php echo $this->status_by_name('recusado')['class'];?>">Recusado</b>.
            </p>
            <br><hr>
            <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                <i class="fa fa-info-circle"></i> <strong>Fique atento </strong> às menssagens de notificação apoś cada ação.
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Entendi</button>
        </div>
        </div>
    </div>
</div>