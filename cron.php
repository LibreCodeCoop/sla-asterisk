<?php
require_once 'bootstrap.php';

/*
$conteudo = file_get_contents('http://10.8.0.232:15000');

return;*/

$sth = $conn->prepare(
    <<<QUERY
INSERT INTO history (queue, sla, metric_id)
SELECT config.queue,
       SUM(info2)/count(*) AS sla,
       config.metric_id AS metric_id
  FROM qstats.queue_stats_mv ST
  JOIN config ON config.metric_id = ?
   AND config.queue = ST.queue
 WHERE (event = 'COMPLETECALLER' OR event = 'COMPLETEAGENT')
   AND ST.datetime >= DATE_SUB(NOW(), INTERVAL 60 second)
 GROUP BY config.metric_id
QUERY
    );
$sth->execute([1]);


$sth = $conn->prepare(
    <<<QUERY
INSERT INTO history (queue, sla, metric_id)
SELECT config.queue,
       SUM(info1)/count(*) AS sla,
       config.metric_id AS metric_id
  FROM qstats.queue_stats_mv ST
  JOIN config ON config.metric_id = ?
   AND config.queue = ST.queue
 WHERE (event = 'COMPLETECALLER' OR event = 'COMPLETEAGENT')
   AND ST.datetime >= DATE_SUB(NOW(), INTERVAL 60 second)
 GROUP BY config.metric_id
QUERY
    );
$sth->execute([2]);


// $sth = $conn->prepare(
//     <<<QUERY
// SELECT `datetime`, agent, event
//   FROM qstats.queue_stats_mv ST
//   JOIN config ON config.metric_id = 3    
//    AND config.queue = ST.queue
//  WHERE event IN ('PAUSE', 'UNPAUSE')
//    AND ST.datetime >= DATE_SUB(NOW(), INTERVAL 60 second)
//  ORDER BY ST.agent, ST.`datetime`
// QUERY
//     );
// $sth->execute([3]);

echo date('Y-m-d H:i:s'). "\tdone\n";