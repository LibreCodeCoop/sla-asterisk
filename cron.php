<?php
require_once 'bootstrap.php';

$sth = $conn->prepare(
<<<QUERY
INSERT INTO history (queue, sla, metric_id)
SELECT config.queue,
       SUM(info1)/count(*) AS sla,
       config.metric_id AS metric_id
  FROM qstats.queue_stats_mv ST
  JOIN config ON config.metric_id IN (?,?,?)
   AND config.queue = ST.queue
 WHERE (event = 'COMPLETECALLER' OR event = 'COMPLETEAGENT')
 GROUP BY config.metric_id
QUERY
);

$sth->execute([1,2,3]);

echo "done\n";