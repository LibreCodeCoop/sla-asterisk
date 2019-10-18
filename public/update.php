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
       history.sla AS atual,
       ROUND(history.sla) AS name
  FROM history
  JOIN config
    ON config.queue = history.queue
   AND config.metric_id = history.metric_id
   AND config.queue = ?
  JOIN metric
    ON metric.id = config.metric_id
   AND metric.name = ?
 ORDER BY created DESC
 LIMIT 1
QUERY
);
$sth->execute([$_GET['queue'], $_GET['type']]);

$row = $sth->fetch(\PDO::FETCH_ASSOC);
$data['donnut']['label'] = $row['name'];
$data['donnut']['setting'] = $row['setting'];
$data['donnut']['atual'] = $row['atual'];

header('Content-Type: application/json');
echo json_encode($data);