<?php 
require_once '../bootstrap.php';

if(isset($_POST['type'])) {
    $prefix = $_POST['type'];
    switch($_POST['type']) {
        case 'operador':
            $sth = $conn->prepare(<<<QUERY
                SELECT name,
                       CASE WHEN name LIKE '% %'
                            THEN CONCAT(SUBSTRING_INDEX(name, ' ', 1), ' ', SUBSTRING_INDEX(name, ' ', -1))
                            ELSE name END AS operador,
                       number as login,
                       'receptivo' as ativdade,
                       null AS celula,
                       null AS horario_login,
                       null AS descanso_1,
                       null AS lanche,
                       null AS descanso_2,
                       null AS tc_login
                  FROM call_center.agent
                QUERY
            );
            $sth->execute();
            break;
        case 'consolidado_chamadas':
            $sth = $conn->prepare(<<<QUERY
                SELECT qsm.queue AS celula,
                       DATE_FORMAT(qsm.`datetime`, '%Y-%m-%d') AS data,
                       'receptivo' AS atividade,
                       COUNT(*) AS recebidas_ura,
                       SUM(CASE WHEN event IN ('COMPLETECALLER', 'COMPLETEAGENT') THEN 1 ELSE 0 END) AS atendidas_dac,
                       SUM(CASE WHEN event = 'ABANDON' THEN 1 ELSE 0 END) AS abandonadas_cliente,
                       0 AS abandonadas_operacao,
                       ROUND(SUM(CASE WHEN event IN ('COMPLETECALLER', 'COMPLETEAGENT') THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS pca,
                       ROUND(SUM(CASE WHEN info2 <= 20 THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS nivel_servico,
                       SUM(CASE WHEN event IN ('COMPLETECALLER', 'COMPLETEAGENT') AND info2 <= 20 THEN 1 ELSE 0 END) atd_20s,
                       SUM(CASE WHEN event IN ('COMPLETECALLER', 'COMPLETEAGENT') AND info2 BETWEEN 21 AND 60 THEN 1 ELSE 0 END) atd_21s_60s,
                       SUM(CASE WHEN event IN ('COMPLETECALLER', 'COMPLETEAGENT') AND info2 > 60 THEN 1 ELSE 0 END) aband_61s,
                       SUM(CASE WHEN event = 'ABANDON' AND info2 <= 20 THEN 1 ELSE 0 END) aband_20s,
                       SUM(CASE WHEN event = 'ABANDON' AND info2 BETWEEN 21 AND 60 THEN 1 ELSE 0 END) aband_21s_60s,
                       SUM(CASE WHEN event = 'ABANDON' AND info2 > 60 THEN 1 ELSE 0 END) aband_61s,
                       SEC_TO_TIME(COALESCE(ROUND(SUM(info2) / SUM(CASE WHEN info2 > 0 THEN 1 ELSE 0 END)), 0)) AS TMA,
                       SEC_TO_TIME(COALESCE(ROUND(SUM(info1) / COUNT(*)), 0)) AS tme,
                       SEC_TO_TIME(COALESCE(
                            ROUND(
                                (
                                    SUM(info2) +
                                    SUM(CASE WHEN event IN ('COMPLETECALLER', 'COMPLETEAGENT') THEN 30 ELSE 0 END)
                                ) /
                                SUM(CASE WHEN event IN ('COMPLETECALLER', 'COMPLETEAGENT') THEN 1 ELSE 0 END
                            )
                       ), 0)) AS tmo
                  FROM qstats.queue_stats_mv qsm
                 WHERE event IN ('ABANDON', 'COMPLETECALLER', 'COMPLETEAGENT')
                   AND clid > 10000
                   AND qsm.`datetime` BETWEEN ? AND ?
                 GROUP BY data, celula
                 ORDER BY celula, data
                QUERY
            );
            $sth->execute([$_POST['data_inicio'], $_POST['data_fim']]);
            break;
        case 'operacao':
            $sth = $conn->prepare(<<<QUERY
                SELECT
                       qsmv.queue AS celula,
                       CASE WHEN name LIKE '% %'
                            THEN CONCAT(SUBSTRING_INDEX(name, ' ', 1), ' ', SUBSTRING_INDEX(name, ' ', -1))
                            ELSE name END AS operador,
                       number as login,
                       DATE_FORMAT(qsmv.`datetime`, '%Y-%m-%d') AS data,
                       DATE_FORMAT(primeiro_login.datetime, '%Y-%m-%d %H:%i:%s') AS primeiro_login,
                       DATE_FORMAT(ultimo_logoff.datetime, '%Y-%m-%d %H:%i:%s') AS ultimo_logoff,
                       SUM(CASE WHEN event IN ('COMPLETECALLER', 'COMPLETEAGENT') THEN 1 ELSE 0 END) AS qtde_atendidas_receptivo,
                       0 AS qtde_atendidas_ativo,
                       0 AS qtde_chamadas_discadas_ativo,
                       SEC_TO_TIME(COALESCE(ROUND(SUM(info2) / SUM(CASE WHEN info2 > 0 THEN 1 ELSE 0 END)), 0)) AS tempo_medio_conversacao_receptivo,
                       SEC_TO_TIME(0) AS tempo_medio_conversacao_ativo,
                       SEC_TO_TIME(0) AS tempo_medio_atendimento_receptivo,
                       SEC_TO_TIME(0) AS tempo_medio_atendimento_ativo,
                       SEC_TO_TIME(UNIX_TIMESTAMP(ultimo_logoff.datetime) - UNIX_TIMESTAMP(primeiro_login.datetime) - SUM(info2)) AS tempo_total_ociosidade,
                       SEC_TO_TIME(0) AS tempo_total_pausa,
                       SEC_TO_TIME(0) AS tempo_total_pausa_improdutiva,
                       SEC_TO_TIME(0) AS tempo_total_pausa_produtiva,
                       SEC_TO_TIME(UNIX_TIMESTAMP(ultimo_logoff.datetime) - UNIX_TIMESTAMP(primeiro_login.datetime)) AS tempo_total_logado,
                       SEC_TO_TIME(SUM(info2)) AS tempo_total_falado
                  FROM qstats.queue_stats_mv qsmv
                  LEFT JOIN call_center.agent a ON qsmv.agent = CONCAT(type,'/',`number`)
                  LEFT JOIN (
                        SELECT MIN(s_qsmv.`datetime`) AS datetime, DATE_FORMAT(s_qsmv.`datetime`, '%Y-%m-%d') AS date, s_qsmv.agent
                          FROM qstats.queue_stats_mv s_qsmv
                         WHERE s_qsmv.event = 'AGENTLOGIN'
                           AND s_qsmv.agent <> ''
                         GROUP BY s_qsmv.agent, date
                       ) primeiro_login
                    ON DATE_FORMAT(qsmv.`datetime`, '%Y-%m-%d') = primeiro_login.date
                   AND qsmv.agent = primeiro_login.agent
                  LEFT JOIN (
                        SELECT MAX(s_qsmv.`datetime`) AS datetime, DATE_FORMAT(s_qsmv.`datetime`, '%Y-%m-%d') AS date, s_qsmv.agent
                          FROM qstats.queue_stats_mv s_qsmv
                         WHERE s_qsmv.event = 'AGENTLOGOFF'
                           AND s_qsmv.agent <> ''
                         GROUP BY s_qsmv.agent, date
                       ) ultimo_logoff
                    ON DATE_FORMAT(qsmv.`datetime`, '%Y-%m-%d') = ultimo_logoff.date
                   AND qsmv.agent = ultimo_logoff.agent
                 WHERE qsmv.agent <> '' AND qsmv.agent <> 'NONE' AND qsmv.queue <> 'NONE'
                   AND qsmv.`datetime` BETWEEN ? AND ?
                 GROUP BY data, celula, qsmv.agent
                 ORDER BY celula, data, a.name
                QUERY
            );
            $sth->execute([$_POST['data_inicio'], $_POST['data_fim']]);
            break;
        case 'volumetria':
            $sth = $conn->prepare(<<<QUERY
                SELECT
                       qsmv.queue AS celula,
                       DATE_FORMAT(qsmv.`datetime`, '%Y-%m-%d') AS data,
                       CONCAT(
                           DATE_FORMAT(qsmv.`datetime`, '%H'),
                           ':',
                           CASE WHEN DATE_FORMAT(qsmv.`datetime`, '%i') >= '30' THEN '30' ELSE '00' END
                       ) AS hora,   
                       CONCAT(
                           DATE_FORMAT(qsmv.`datetime`, '%H'),
                           ':',
                           CASE WHEN DATE_FORMAT(qsmv.`datetime`, '%i') >= '30' THEN '30' ELSE '00' END,
                           ' - ',
                           DATE_FORMAT(qsmv.`datetime`, '%H'),
                           ':',
                           CASE WHEN DATE_FORMAT(qsmv.`datetime`, '%i') >= '30' THEN '59' ELSE '29' END
                       ) AS hora_dac,
                       COUNT(*) AS qtd_recebidas,
                       SUM(CASE WHEN event IN ('COMPLETECALLER', 'COMPLETEAGENT') THEN 1 ELSE 0 END) AS qtd_atendidas,
                       SEC_TO_TIME(COALESCE(ROUND(SUM(info2) / SUM(CASE WHEN info2 > 0 THEN 1 ELSE 0 END)), 0)) AS TMA,
                       SEC_TO_TIME(COALESCE(ROUND(SUM(info1) / COUNT(*)), 0)) AS TME,
                       SEC_TO_TIME(COALESCE(
                            ROUND(
                                (
                                    SUM(info2) +
                                    SUM(CASE WHEN event IN ('COMPLETECALLER', 'COMPLETEAGENT') THEN 30 ELSE 0 END)
                                ) /
                                SUM(CASE WHEN event IN ('COMPLETECALLER', 'COMPLETEAGENT') THEN 1 ELSE 0 END
                            )
                       ), 0)) AS TMO,
                       SUM(CASE WHEN event = 'ABANDON' THEN 1 ELSE 0 END) AS qtd_abandonadas,
                       COUNT(DISTINCT qsmv.agent) AS qtd_operadores,
                       SEC_TO_TIME(0) AS tempo_falado_ativo,
                       SEC_TO_TIME(SUM(info2)) AS tempo_falado_receptivo
                  FROM qstats.queue_stats_mv qsmv
                 WHERE qsmv.queue <> 'NONE'
                   AND event IN ('ABANDON', 'COMPLETECALLER', 'COMPLETEAGENT')
                   AND clid > 10000
                   AND qsmv.`datetime` BETWEEN ? AND ?
                 GROUP BY data, celula, hora
                 ORDER BY celula, data, hora
                QUERY
            );
            $sth->execute([$_POST['data_inicio'], $_POST['data_fim']]);
            break;
        case 'pausas':
            
            $sth = $conn->prepare(
                <<<QUERY
                SELECT a.queue,
                       a.agent,
                       a.event,
                       ? AS min_date
                  FROM qstats.queue_stats_mv a
                  JOIN (
                        SELECT qs.agent,
                               max(datetime) AS `max`,
                               qs.queue
                          FROM qstats.queue_stats_mv qs
                         WHERE qs.event IN ('PAUSE', 'UNPAUSE')
                           AND qs.queue <> ''
                           AND qs.datetime < ?
                         GROUP BY qs.queue, qs.agent
                       ) b
                    ON b.agent = a.agent
                   AND b.max = a.`datetime`
                   AND b.queue = a.queue
                 WHERE a.event = 'PAUSE'
                QUERY
            );
            $sth->execute([$_POST['data_inicio'], $_POST['data_inicio']]);
            while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
                $before[$row['queue']][$row['agent']] = $row;
            }
            $sth = $conn->prepare(
                <<<QUERY
                SELECT a.queue, a.`datetime`, a.agent, a.event
                  FROM qstats.queue_stats_mv a
                  LEFT JOIN qstats.queue_stats_mv b
                    ON a.agent = b.agent AND a.datetime = b.datetime AND b.event IN ('AGENTLOGIN', 'AGENTLOGOFF')
                 WHERE (b.event = 'AGENTLOGOFF' OR b.id IS NULL)
                   AND a.event IN ('PAUSE', 'UNPAUSE')
                   AND a.`datetime` BETWEEN ? AND ?
                 ORDER BY a.queue, a.agent, a.`datetime` ASC
                QUERY
            );
            $sth->execute([$_POST['data_inicio'], $_POST['data_fim']]);
            $rows = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $lastAgent = [];
            $totalAgents = [];
            foreach ($rows as $i => $row) {
                if (!isset($lastAgent[$row['queue']]) || $lastAgent[$row['queue']] != $row['agent']) {
                    $lastAgent[$row['queue']] = $row['agent'];
                    if (!isset($totalAgents[$row['queue']])) {
                        $totalAgents[$row['queue']] = 1;
                    } else {
                        $totalAgents[$row['queue']]++;
                    }
                    if (!isset($totalTime[$row['queue']])) {
                        $totalTime[$row['queue']] = 0;
                    }
                    if ($row['event'] == 'UNPAUSE') {
                        if ( isset( $before[$row['queue']][$row['agent']] ) ) {
                            $min = new DateTime($before[$row['queue']][$row['agent']]);
                            $max = new DateTime($row['datetime']);
                            $totalTime[$row['queue']]+= $max->getTimestamp() - $min->getTimestamp();
                        }
                    } else {
                        $min = new DateTime($row['datetime']);
                        $max = new DateTime($rows[$i+1]['datetime']);
                        $totalTime[$row['queue']]+= $max->getTimestamp() - $min->getTimestamp();
                    }
                } elseif (!isset($rows[$i+1]) || (isset($rows[$i+1]) && $rows[$i+1]['agent'] != $row['agent'])) {
                    if ($row['event'] == 'PAUSE') {
                        $min = new DateTime($row['datetime']);
                        $max = new DateTime();
                        $totalTime[$row['queue']]+= $max->getTimestamp() - $min->getTimestamp();
                    }
                } else {
                    if ($row['event'] == 'PAUSE') {
                        $min = new DateTime($row['datetime']);
                        $max = new DateTime($rows[$i+1]['datetime']);
                        $totalTime[$row['queue']]+= $max->getTimestamp() - $min->getTimestamp();
                    }
                }
            }
            break;
        case 'analitico_ligacoes':
            $sth = $conn->prepare(<<<QUERY
                SELECT qsmv.queue,
                       'receptivo' AS atividade,
                       qsmv.clid AS telefone_cliente,
                       DATE_FORMAT(qsmv.datetimeconnect, '%Y-%m-%d %H:%i:%s') AS inicio_ligacao,
                       DATE_FORMAT(qsmv.datetimeend, '%Y-%m-%d %H:%i:%s') AS final_ligacao,
                       TIMEDIFF(qsmv.datetimeend, qsmv.datetimeconnect) AS duracao,
                       CONCAT(id_empresa.name,'-',pd_empresa.opcao,',',id_agente.name,'-',pd_agente.opcao) AS pesquisa_satisfacao_cliente
                  FROM qstats.queue_stats_mv qsmv
                  LEFT JOIN kaf.pooldata pd_empresa
                    ON qsmv.clid = pd_empresa.numero
                   AND pd_empresa.`data` BETWEEN qsmv.datetimeend AND DATE_ADD(qsmv.datetimeend, INTERVAL 80 second)
                   AND SUBSTRING(pd_empresa.menu, 5) = 13
                  LEFT JOIN asterisk.ivr_details id_empresa
                    ON SUBSTRING(pd_empresa.menu, 5) = id_empresa.id
                  LEFT JOIN kaf.pooldata pd_agente
                    ON qsmv.clid = pd_agente.numero
                   AND pd_agente.`data` BETWEEN qsmv.datetimeend AND DATE_ADD(qsmv.datetimeend, INTERVAL 80 second)
                   AND SUBSTRING(pd_agente.menu, 5) = 14
                  LEFT JOIN asterisk.ivr_details id_agente
                    ON SUBSTRING(pd_agente.menu, 5) = id_agente.id
                 WHERE qsmv.queue <> 'NONE'
                   AND event IN ('ABANDON', 'COMPLETECALLER', 'COMPLETEAGENT')
                   AND clid > 10000
                   AND qsmv.`datetime` BETWEEN ? AND ?
                QUERY
            );
            $sth->execute([$_POST['data_inicio'], $_POST['data_fim']]);
            break;
        case 'ura':
            $sth = $conn->prepare(<<<QUERY
                SELECT *
                  FROM (
                        -- Completa
                        SELECT DATE_FORMAT(md.`data`, '%Y-%m-%d %H:%i:%s') AS data_ligacao,
                               qsm.clid AS telefone,
                               CONCAT(id.name, '-',md.opcao) AS ura,
                               qsm.queue,
                               'Completa' AS status
                          FROM qstats.queue_stats_mv qsm
                          JOIN kaf.menudata md ON md.numero = qsm.clid
                          JOIN asterisk.ivr_details id
                            ON SUBSTRING(md.menu, 5) = id.id
                          WHERE clid <> ''
                            AND qsm.event LIKE 'COMPLETE%'
                            AND clid > 10000
                           AND md.`data` BETWEEN ? AND ?
                          UNION
                        -- Abandono na fila
                        SELECT DATE_FORMAT(md.`data`, '%Y-%m-%d %H:%i:%s') AS data_ligacao,
                               qsm.clid AS telefone,
                               CONCAT(id.name, '-',md.opcao) AS ura,
                               qsm.queue,
                               'Abandono fila' AS status
                          FROM qstats.queue_stats_mv qsm
                          JOIN kaf.menudata md ON md.numero = qsm.clid
                          JOIN asterisk.ivr_details id
                            ON SUBSTRING(md.menu, 5) = id.id
                         WHERE clid <> ''
                           AND event = 'ABANDON'
                           AND clid > 10000
                           AND md.`data` BETWEEN ? AND ?
                         UNION
                        -- Abandono na URA
                        SELECT DATE_FORMAT(cdr.calldate, '%Y-%m-%d %H:%i:%s') AS data_ligacao,
                               cdr.src AS telefone,
                               id.name AS ura,
                               null AS queue,
                               'Abandono ura' AS status
                          FROM asteriskcdrdb.cdr
                          JOIN asterisk.ivr_details id
                            ON SUBSTRING(dcontext, 5) = id.id
                         WHERE dcontext LIKE 'ivr-%'
                           AND id.name NOT LIKE '%Pesquisa%'
                           AND cdr.src > 10000
                           AND cdr.calldate BETWEEN ? AND ?
                       ) x
                 ORDER BY x.queue, x.data_ligacao
                QUERY
            );
            $sth->execute([$_POST['data_inicio'], $_POST['data_fim'],$_POST['data_inicio'], $_POST['data_fim'],$_POST['data_inicio'], $_POST['data_fim']]);
            break;
    }
} else {
    if ($_POST['relatorio_historico']) {
        $prefix = 'historico';
        $sql = "SELECT h.queue AS fila, c.descr AS nome_fila, h.sla, m.name AS metrica, h.created AS data_criacao
                FROM dashboard.history h
                JOIN asterisk.queues_config c ON h.queue = c.extension
                JOIN  dashboard.metric m ON h.metric_id = m.id
                WHERE queue in (".$_POST['queue'].") and metric_id in (".$_POST['metric_id'].")
                and created >= '".$_POST['data_inicio']."' and created <= '".$_POST['data_fim']."' ";
        $sth = $conn->prepare($sql);
        $sth->execute();
    } else {
        $prefix = 'abandono';
        $sql = "SELECT clid AS telefone
              FROM qstats.queue_stats_mv q
              JOIN (
                    SELECT max(id) AS id
                      FROM qstats.queue_stats_mv
                     WHERE clid > 100000
                     GROUP BY clid
                   ) subselect ON subselect.id = q.id
               AND q.event = 'ABANDON'
                AND q.queue in (".$_POST['queue'].")
                and q.datetimeconnect >= '".$_POST['datetimeconnect']."' and q.datetimeend <= '".$_POST['datetimeend']."' ";
        $sth = $conn->prepare($sql);
        $sth->execute();
    }
}

$line = $sth->fetch(\PDO::FETCH_ASSOC);
if ($line) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$prefix.'_'.date('Ymd_His').'.csv"');
    $fp = fopen('php://output', 'wb');
    fputcsv($fp, array_keys($line), ',');
    fputcsv($fp, $line, ',');
    while ($line = $sth->fetch(\PDO::FETCH_ASSOC)) {
        fputcsv($fp, $line, ',');
    }
    fclose($fp);
} else {
    $sth->debugDumpParams();
    echo "nenhum registro encontrado para o filtro informado";
}