<?php

require_once '../bootstrap.php';

$sth = $conn->prepare(
    <<<QUERY
SELECT CASE WHEN descr IS NOT NULL THEN concat(queue, ' - ', descr) ELSE queue END as nome,
       queue
FROM config
LEFT JOIN asterisk.queues_config aqc ON aqc.extension = config.queue
WHERE queue > 0
GROUP BY queue
QUERY
);
$sth->execute();
while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
    $data[] = $row;

}

$sth->execute();

header('Content-Type: application/json');
echo json_encode($data);