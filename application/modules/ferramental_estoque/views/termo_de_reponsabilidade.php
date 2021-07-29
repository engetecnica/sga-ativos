
 <!-- Bootstrap CSS--
 <link href="<?php echo base_url('assets'); ?>/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="print">

-- Main CSS--
<link href="<?php echo base_url('assets'); ?>/css/theme.css" rel="stylesheet" media="print">
-->
<style media="print">
  .termo {
    font-family: "Poppins", sans-serif;
  }
  .contrato h2{
    font-size: 1.2rem;
    text-align: left;
    text-justify: center;
  }
  .contrato h3{
    font-size:1rem;
  }
  .contrato p, .contrato li{
    font-size: .85rem;
  }
  .contrato li{
    font-size: .85rem;
  }

  .tabela {
    border: solid 1px #000;
    border-spacing: 0;
  }

  .tabela th,.tabela tr, .tabela td{
    border: solid .5px #000;
    border-spacing: 0;
  }

  .tabela th{
      font-size: 0.8rem;
      font-weight: lighter;
      padding: .5rem;
      background: #fd9e0f;
  }

  .tabela tr{

  }

  .tabela td {
    text-align: center;
  }

</style>


<div class="termo">

  <div class="header">
    <img src="<?echo $logo; ?>" alt="">
  </div>

  <div class="contrato">
    <h2>TERMO DE RESPONSABILIDADE PELA GUARDA E USO DE EQUIPAMENTO DE TRABALHO</h2>

    <p>
      Pelo presente instrumento particular e na melhor forma de direito, de um lado,
      <b>ENGETÉCNICA ENGENHARIA E CONSTRUÇÃO LTDA</b>, inscrita no CNPJ/MF sob o nº. 76.624.584/0001-38, com sede na João Bettega, 1160, CENTRO, CEP 81070-001, Município de CURITIBA, Estado do Paraná, doravante denominada <b>COMODANTE</b>.
      E, de outro lado, 
      FUNCIONÁRIO, contratado pela Engetecnica Engenharia e Construção Ltda na função de Eletricista FC, doravante denominada <b>COMODATÁRIO</b>.
    </p>

    <h3>CLÁUSULA PRIMEIRA – DAS DECLARAÇÕES</h3>
    <ul>
      <li>
        1.1	Declaro ter recebido da <b>COMODANTE</b>, à título de empréstimo, para uso em minhas funções operacionais, conforme determinado em lei, as ferramentas e euipamentos especificados neste termo de responsábilidade.
      </li>
      <li>
        1.2	O COMODATÁRIO compromete-se a zelar, cuidar, manter em ordem e perfeito funcionámento, todas as ferramentas e equipamentos disponibilizados para uso pela COMODANTE, durante todo período da execução de suas atividades.
      </li>
    </ul>


    <h3>CLÁUSULA SEGUNDA – DAS OBRIGAÇÕES E RESPONSABILIDADES</h3>
    <ul>
      <li>
        2.1	O COMODATÁRIO será responsabilizado em caso de danificar, extraviar, emprego inadequado, 
        e/ou mau uso. A COMODANTE fornecerá um novo equipamento ao COMODATÁRIO e 
        cobrará o valor de acordo com o custo do equipamento da mesma marca e modelo ou equivalente disponível no mercado.
      </li>
      <li>
        2.2	Em caso de dano e inutilização do equipamento por parte do COMODATÁRIO o mesmo, 
        deverá comunicar por escrito a COMODANTE apresentando o 
        equipamento danificado no prazo máximo de 24 horas. 
      </li>
      <li>
        2.3	Em caso de furto ou roubo, o COMODATÁRIO deverá apresentar o boletim de ocorrência,
          no qual informe detalhadamente os fatos e as circunstâncias do ocorrido. 
      </li>
      <li>
        2.4	Uma vez em posse do COMODATÁRIO ferramentas e equipamentos, 
        a COMODANTE poderá a qualquer momento e sem prévio aviso,
          realizar as inspeções e conferencias de todos os itens disponibilizados ao COMODATÁRIO.
      </li>
    </ul>


    <h3>CLÁUSULA TERCEIRA – DOS ITENS DISPONIBILIZADOS</h3>
    <ul>
      <li>
      3.1	Todos os itens da lista abaixo foram conferidos, testados e 
      recebidos pelo COMODATÁRIO sem qualquer defeito em funcionamento, 
      atendendo a todos os requisitos de segurança aplicáveis aos mesmos.
      </li>
    </ul>
  </div>

   <table class="tabela">
      <thead>
          <tr>
            <th>N. Item</th>
            <th>Quantidade</th>
            <th>Descrição do Material</th>
            <th>Modelo/Marca</th>
            <th>Valor Unitário</th>
            <th>Data de conferência</th>
            <th>Visto do Comodatário na retirada</th>
            <th>Visto do Comodatário na devolução</th>
          </tr>
      </thead>
      <tbody>
        <?php foreach($ativos as $num => $ativo){ print_r($ativo); ?>
          <tr>
            <td><?php echo ($num + 1); ?></td>
            <td><?php echo $ativo->count; ?></td>
            <td><?php echo $ativo->nome; ?></td>
            <td></td>
            <td><?php echo "R$ " .$ativo->valor; ?></td>
            <td> ____/____/______ </td>
            <td></td>
            <td></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
</div>