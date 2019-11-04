<?php

require_once '../bootstrap.php';

if ($_GET['type'] == 'ura') {
    $data = new StdClass();
    $data->datasets = [];
    $data->datasets[0] = new StdClass();
    $data->datasets[0]->backgroundColor = [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
    ];
    $data->datasets[0]->borderColor = [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
    ];
    $data->datasets[0]->borderWidth = 1;
    $data->datasets[0]->label =  'URA';
    $conn->query('USE asteriskcdrdb;');
    $sth = $conn->prepare(
        <<<QUERY
        SELECT id.name,
               COUNT(*) AS total
          FROM cdr
          JOIN asterisk.ivr_details id
            ON SUBSTRING(dcontext, 5) = id.id
         WHERE dcontext LIKE 'ivr-%'
           AND id.name NOT LIKE '%Pesquisa%'
           AND cdr.calldate > DATE_SUB(NOW(), INTERVAL 24 HOUR)
         GROUP BY id.name,
                  dcontext
        QUERY
        );
    $sth->execute();
    while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
        $data->labels[] = $row['name'];
        $data->datasets[0]->data[] = $row['total'];
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    return;
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
   AND history.created >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
   AND metric.name = ?
 ORDER BY metric.order, created
QUERY
);
$sth->execute([$_GET['queue'], $_GET['type']]);
while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
    $data['labels'][] = $row['date'];
    $data['data'][] = $row['sla'];
}

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
$data['donnut']['label'] = $row['name'];
$data['donnut']['setting'] = $row['setting'];
$data['donnut']['atual'] = $row['atual'];

header('Content-Type: application/json');
echo json_encode($data);