<?php

require_once '../bootstrap.php';

$sth = $conn->prepare(
    <<<QUERY
SELECT TIME_FORMAT(created, "%H:%i") AS date,
       history.sla
  FROM history
  JOIN config
    ON config.queue = history.queue
   AND config.metric_id = history.metric_id
  JOIN metric
    ON metric.id = history.metric_id
 WHERE config.queue = ?
   AND history.created >= DATE_SUB(NOW(), INTERVAL config.window second)
   AND metric.name = ?
QUERY
);
$sth->execute([$_GET['queue'], $_GET['type']]);
while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
    $data['labels'][] = $row['date'];
    $data['data'][] = $row['sla'];
}

$sth = $conn->prepare(
    <<<QUERY
SELECT config.sla AS setting,
       SUM(info2)/count(*) AS atual,
       ROUND(SUM(info2)/count(*),2) as name
  FROM qstats.queue_stats_mv ST
  JOIN config
    ON config.queue = ST.queue
   AND config.queue = ?
  JOIN metric
    ON metric.id = config.metric_id
   AND metric.name = ?
 WHERE (event = 'COMPLETECALLER' OR event = 'COMPLETEAGENT')
   AND ST.datetime >= DATE_SUB(NOW(), INTERVAL 60*60*12*30 second)
 GROUP BY config.metric_id
QUERY
    );
$sth->execute([$_GET['queue'], $_GET['type']]);

$row = $sth->fetch(\PDO::FETCH_ASSOC);
header('Content-Type: application/json');
$data['donnut']['label'] = $row['name'];
$data['donnut']['setting'] = $row['setting'];
$data['donnut']['atual'] = $row['atual'];
echo json_encode($data);