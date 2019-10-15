<?php
require_once 'bootstrap.php';

$content = shell_exec('curl http://10.8.0.232:15000');
preg_match_all('/(?P<queue>\d+) has (?P<calls>\d+) calls/', $content, $matches);
foreach ($matches['queue'] as $key => $queue) {
    
    $sth = $conn->prepare(
        <<<QUERY
INSERT INTO history (queue, sla, metric_id) VALUES (?, ?, ?)
QUERY
        );
    $sth->execute([$queue, $matches['calls'][$key], 4]);
}

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

echo date('Y-m-d H:i:s'). "\tdone\n";