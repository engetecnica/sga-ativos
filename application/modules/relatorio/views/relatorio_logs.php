<page>
    <style media="all">
        <?php echo $css; ?>
    </style>

    <h1>Logs</h1>
    <p>Relatório de Logs SGA, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>

    <table class="tabela">
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Módulo</th>
                <th>Ação</th>
                <th>Histórico</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($relatorio as $i => $logs) { ?>
            <tr>
                <td><?php echo $logs->id_usuario;?></td>
                <td><?php echo $logs->id_modulo; ?></td>
                <td><?php echo $logs->acao; ?></td>
                <td><?php echo $logs->historico;?> </td>
                <td><?php echo date('d/m/Y H:i:s', strtotime($logs->created_at)); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <footer>
        <img src="<?php echo $footer; ?>">        
    </footer>
</page>
