<?php

require_once '../bootstrap.php';

if ($_GET['type'] == 'Fila') {
    $interval = 1;
} else {
    $interval = 24;
}
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
   AND history.created >= DATE_SUB(NOW(), INTERVAL {$interval} HOUR)
   AND metric.name = ?
 ORDER BY metric.order, created
QUERY
);
$sth->execute([$_GET['queue'], $_GET['type']]);
while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
    $data['labels'][] = $row['date'];
    $data['data'][] = $row['sla'];
}

if ($_GET['type'] == 'Fila') {
    $sth = $conn->prepare(
        <<<QUERY
        SELECT c.sla AS setting FROM config c
         WHERE c.metric_id = ?
           AND c.queue = ?
        QUERY
    );
    $sth->execute([4, $_GET['queue']]);
    $row = $sth->fetch(\PDO::FETCH_ASSOC);
    $content = shell_exec('curl http://10.8.0.232:15000');
    preg_match_all('/(?P<queue>\d+) has (?P<calls>\d+) calls/', $content, $matches);
    foreach ($matches['queue'] as $key => $queue) {
        if ($queue != $_GET['queue']) {
            continue;
        }
        $row['name'] = $row['atual'] = $matches['calls'][$key] ?: 0;
        break;
    }
} else {
    $sth = $conn->prepare(
    <<<QUERY
    SELECT c.sla - COALESCE(history.sla, 0) AS setting,
           COALESCE(history.sla, 0) AS atual,
           COALESCE(ROUND(history.sla), 0) AS name
      FROM config c
      JOIN metric
        ON metric.id = c.metric_id
       AND c.queue = ?
       AND metric.name = ?
      LEFT JOIN history
        ON c.queue = history.queue
       AND c.metric_id = history.metric_id
     ORDER BY created DESC
     LIMIT 1
    QUERY
    );
    $sth->execute([$_GET['queue'], $_GET['type']]);

    $row = $sth->fetch(\PDO::FETCH_ASSOC);
}
$data['donnut']['label'] = $row['name'];
$data['donnut']['setting'] = $row['setting'];
$data['donnut']['atual'] = $row['atual'];

header('Content-Type: application/json');
echo json_encode($data);