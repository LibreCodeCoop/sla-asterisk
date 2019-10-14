<?php

require_once '../bootstrap.php';

$sth = $conn->prepare(
    <<<QUERY
SELECT TIME_FORMAT(created, "%H:%i") AS date,
       sla
  FROM history
 WHERE queue = 610
   AND created >= '2019-10-14 18:35:30'
QUERY
);
$sth->execute();
while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
    $data['labels'][] = $row['date'];
    $data['data'][] = $row['sla'];
}

$sth->execute([1,2,3]);

header('Content-Type: application/json');
echo json_encode($data);