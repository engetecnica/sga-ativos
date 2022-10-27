

SELECT
    c1.id_ativo_veiculo,
    c1.id_interno_maquina,
    c1.veiculo,
    c1.marca,
    c1.modelo,
    c1.ano,
    c2.veiculo_custo,
    c2.id_ativo_veiculo_manutencao,
    max(c2.veiculo_horimetro_proxima_revisao) as veiculo_horimetro_proxima_revisao,
    max(c3.veiculo_horimetro) as veiculo_horimetro_atual,
    (max(c2.veiculo_horimetro_proxima_revisao)-max(c3.veiculo_horimetro)) as saldo,
    c4.operacao_alerta
FROM
    ativo_veiculo AS c1
    JOIN ativo_veiculo_manutencao AS c2 ON c2.id_ativo_veiculo = c1.id_ativo_veiculo    
    JOIN ativo_veiculo_operacao AS c3 ON ((`c3`.`id_ativo_veiculo` = `c1`.`id_ativo_veiculo` and MAX(c3.veiculo_horimetro)))
    JOIN configuracao AS c4 ON c4.id_configuracao=1
WHERE
    c1.tipo_veiculo = 'maquina'
     AND (c2.veiculo_horimetro_proxima_revisao-c3.veiculo_horimetro) > 0
GROUP BY c2.id_ativo_veiculo;

ORDER BY c2.id_ativo_veiculo_manutencao DESC

