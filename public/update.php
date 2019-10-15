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

if ($_GET['type'] == 'tma') {
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
       AND ST.datetime >= DATE_SUB(NOW(), INTERVAL 60 second)
     GROUP BY config.metric_id
    QUERY
        );
    $sth->execute([$_GET['queue'], $_GET['type']]);
    
    $row = $sth->fetch(\PDO::FETCH_ASSOC);
    if ($row) {
        $data['donnut']['label'] = $row['name'];
        $data['donnut']['setting'] = $row['setting'];
        $data['donnut']['atual'] = $row['atual'];
    } else {
        
        $sth = $conn->prepare(
            <<<QUERY
    SELECT config.sla AS setting,
           0 AS atual,
           0 as name
      FROM config
      JOIN metric
        ON metric.id = config.metric_id
       AND config.queue = ?
       AND metric.name = ?
    QUERY
            );
        $sth->execute([$_GET['queue'], $_GET['type']]);
        $row = $sth->fetch(\PDO::FETCH_ASSOC);
        $data['donnut']['label'] = $row['name'];
        $data['donnut']['setting'] = $row['setting'];
        $data['donnut']['atual'] = $row['atual'];
    }
} elseif ($_GET['type'] == 'tme') {
    $sth = $conn->prepare(
        <<<QUERY
    SELECT config.sla AS setting,
           SUM(info1)/count(*) AS atual,
           ROUND(SUM(info1)/count(*),2) as name
      FROM qstats.queue_stats_mv ST
      JOIN config
        ON config.queue = ST.queue
       AND config.queue = ?
      JOIN metric
        ON metric.id = config.metric_id
       AND metric.name = ?
     WHERE (event = 'COMPLETECALLER' OR event = 'COMPLETEAGENT')
       AND ST.datetime >= DATE_SUB(NOW(), INTERVAL 60 second)
     GROUP BY config.metric_id
    QUERY
        );
    $sth->execute([$_GET['queue'], $_GET['type']]);
    
    $row = $sth->fetch(\PDO::FETCH_ASSOC);
    if ($row) {
        $data['donnut']['label'] = $row['name'];
        $data['donnut']['setting'] = $row['setting'];
        $data['donnut']['atual'] = $row['atual'];
        
    } else {
        
        $sth = $conn->prepare(
            <<<QUERY
    SELECT config.sla AS setting,
           0 AS atual,
           0 as name
      FROM config
      JOIN metric
        ON metric.id = config.metric_id
       AND config.queue = ?
       AND metric.name = ?
    QUERY
            );
        $sth->execute([$_GET['queue'], $_GET['type']]);
        $row = $sth->fetch(\PDO::FETCH_ASSOC);
        $data['donnut']['label'] = $row['name'];
        $data['donnut']['setting'] = $row['setting'];
        $data['donnut']['atual'] = $row['atual'];
    }
} elseif ($_GET['type'] == 'Fila') {
    $sth = $conn->prepare(
        <<<QUERY
    SELECT config.sla AS setting,
           0 AS atual,
           0 as name
      FROM config
      JOIN metric
        ON metric.id = config.metric_id
       AND config.queue = ?
       AND metric.name = ?
    QUERY
        );
    $sth->execute([$_GET['queue'], $_GET['type']]);
    
    $row = $sth->fetch(\PDO::FETCH_ASSOC);
    
    $content = shell_exec('curl http://10.8.0.232:15000');
    preg_match_all('/(?P<queue>\d+) has (?P<calls>\d+) calls/', $content, $matches);
    foreach ($matches['queue'] as $key => $queue) {
        if ($queue == $_GET['queue']) {
            
            $data['donnut']['label'] = $row['name'];
            $data['donnut']['setting'] = $row['setting'];
            $data['donnut']['atual'] = $matches['calls'][$key];
        }
    }
    if (!$data['donnut']) {
        $data['donnut']['label'] = $row['name'];
        $data['donnut']['setting'] = $row['setting'];
        $data['donnut']['atual'] = $row['atual'];
    }
}

header('Content-Type: application/json');
echo json_encode($data);