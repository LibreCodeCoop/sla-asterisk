<?php
require_once 'bootstrap.php';

// TMO
$sth = $conn->prepare(
    <<<QUERY
    INSERT INTO history (queue, sla, metric_id)
    SELECT c.queue,
           COALESCE((SUM(info2)+count(*)*30)/count(*), 0) AS sla,
           c.metric_id
      FROM config c
      LEFT JOIN qstats.queue_stats_mv ST
        ON c.queue = ST.queue
       AND c.metric_id = 1
       AND ST.event IN ('COMPLETECALLER', 'COMPLETEAGENT')
       AND ST.datetime >= DATE_SUB(NOW(), INTERVAL c.window second)
     WHERE c.metric_id = 1
       AND c.id IS NOT NULL
     GROUP BY c.queue
    QUERY
);
$sth->execute();

// TMA
$sth = $conn->prepare(
    <<<QUERY
    INSERT INTO history (queue, sla, metric_id)
    SELECT c.queue,
           COALESCE(SUM(info2)/count(*), 0) AS sla,
           c.metric_id
      FROM config c
      LEFT JOIN qstats.queue_stats_mv ST
        ON c.queue = ST.queue
       AND c.metric_id = 1
       AND ST.event IN ('COMPLETECALLER', 'COMPLETEAGENT')
       AND ST.datetime >= DATE_SUB(NOW(), INTERVAL c.window second)
     WHERE c.metric_id = 1
       AND c.id IS NOT NULL
     GROUP BY c.queue
    QUERY
);
$sth->execute();

// TME
$sth = $conn->prepare(
    <<<QUERY
    INSERT INTO history (queue, sla, metric_id)
    SELECT c.queue,
           COALESCE(SUM(info1)/count(*), 0) AS sla,
           c.metric_id
      FROM config c
      LEFT JOIN qstats.queue_stats_mv ST
        ON c.queue = ST.queue
       AND c.metric_id = 2
       AND ST.event IN ('COMPLETECALLER', 'COMPLETEAGENT')
       AND ST.datetime >= DATE_SUB(NOW(), INTERVAL c.window second)
     WHERE c.metric_id = 2
       AND c.id IS NOT NULL
     GROUP BY c.queue
    QUERY
);
$sth->execute();

echo date('Y-m-d H:i:s'). "\tdone\n";