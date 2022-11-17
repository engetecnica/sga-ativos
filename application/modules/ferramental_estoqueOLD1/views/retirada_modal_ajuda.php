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
            <p class="m-t-30"><b>1</b> - <b>Detalhar Itens</b> essa ação consiste em mostrar uma lista detalhada dos itens 
            retirados. Também pode vizualizar individualmente cada item e suas respectivas unidades clicando no nome do item na tabela 'Itens' na 
            tela de "Detalhes Da Retirada".
            </p>

            <p class="m-t-30"><b>2</b> - <b>Liberar Retirada</b> essa ação seleciona o itens e marca os mesmo como liberados** para a retirada,
            ou seja, a partir de agora esses itens serão reservados para a retirada em questão. 
            Essa ação será disponibilizada quando o status da retirada estiver <b class="badge badge-<?php echo $this->status_by_name('pendente')['class'];?>">Pendênte</b>.
            ** Em algumas situações quando para o funcionário existem retirada(s) pendente(s) de devolução, será solicitada a autorização de um administrador para a dar sequência ao processo,
            Se o usuário for do tipo Almoxarifado o status da retirada após liberada será <b class="badge badge-<?php echo $this->status_by_name('aguardandoautorizacao')['class'];?>">Aguardando Autorizacao</b> 
            caso contrário ou quando autorizado, passará a ser <b class="badge badge-<?php echo $this->status_by_name('liberado')['class'];?>">Liberado</b>
            </p>

            <p class="m-t-30"><b>3</b> - <b>Marcar como Entregues</b> essa ação marca os itens da retirada como entregues ao funcioário, 
            passando o status da retirada para <b class="badge badge-<?php echo $this->status_by_name('recebido')['class'];?>">Recebido</b> e dos 
            itens como  <b class="badge badge-<?php echo $this->status_by_name('emoperacao')['class'];?>">Em Operação</b>. 
            <br>***Nesse momento ainda será permitido anexar o Termo de responsabilidade.
            </p>

            <p class="m-t-30"><b>4</b> - <b>Marcar como Devolvidos</b> essa ação marca os itens da retirada como devolvidos pelo funcioário, 
            passando o status da retirada para <b class="badge badge-<?php echo $this->status_by_name('devolvido')['class'];?>">Devolvido</b>.<br>
            Na tela <i>Devolver Itens Da Retirada</i> o campo <i>Situação</i> na segunda tabela define o estado de cada unidade do item no momento,
            se selecionado <i>Recebido</i> deduz se que o item não será devolvido no momento, se marcados como Devolvido ou Devolvido (Com Defeito) 
            os itens passarão se status para <b class="badge badge-<?php echo $this->status_by_name('emestoque')['class'];?>">Em Estoque</b> ou 
            <b class="badge badge-<?php echo $this->status_by_name('comdefeito')['class'];?>">Com Defeito</b>
             respectivamente, e por fim as unidades dos itens voltam ao estoque para reutilização.
            <br>***A partir desse momento, não será permitido anexar o Termo de responsabilidade.
            </p>

            <p class="m-t-30"><b>5</b> - <b>Editar</b> ou <b>Excluir</b> Como o nome sugere, edita ou exclui uma retirada, 
                Essas ações serão disponibilizadas quando o status da retirada estiver <b class="badge badge-<?php echo $this->status_by_name('pendente')['class'];?>">Pendênte</b> e 
                será permitida a execução por qualquer tipo de usuário (Administrador ou Almoxarifado) desde que esteja alocado na mesa obra do funcionário de acordo com os cadastros de usuários e funcionário.
            </p>


            <p class="m-t-30"><b>6</b> - <b>Imprimir Termo de Resp.</b> Como o nome sugere, faz download de um pdf contendo um Termo de Responsabilidade sobre os itens retirados. 
                O almoxarife deve executar a ação, imprimir o termo, preecher devidamente com assinatura do responsável e do solicitante, após anexar*** no campo de arquivo <i>Anexar Termo</i> e clicar em <i>Anexar</i>.
                Executar essa ação só será permitida quando o status da retirada estiver <b class="badge badge-<?php echo $this->status_by_name('liberado')['class'];?>">Liberado</b>
            </p>

            <p class="m-t-30"><b>7</b> - <b>Ver Termo de Resp.</b> Como o nome sugere, vizualiza o Termo de Responsabilidade devidamente preechido e anexado.
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