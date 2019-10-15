<?php

require_once '../bootstrap.php';

$sth = $conn->prepare(
  <<<QUERY
SELECT id, name
FROM metric
QUERY
);
$sth->execute();
while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
  $data[] = $row;

}

$sth->execute();

header('Content-Type: application/json');
echo json_encode($data);