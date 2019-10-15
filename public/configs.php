<?php

require_once '../bootstrap.php';

if(isset($_POST['dados']) && !empty($_POST['dados'])){

$request = json_decode($_POST['dados']);


  $conn->beginTransaction();
  //var_dump($request);die;

  $sth = $conn->prepare('DELETE FROM `config` WHERE 1=1');
  $sth->execute();

  $sth = $conn->prepare('INSERT INTO `config` (`queue`, `sla`, `window`, refresh, `metric_id`) VALUES(:queue, :sla, :window, :refresh, :metric_id)');

  for($i = 0; $i <= count($request); $i++) {
    $sth->execute([
      ':queue' => $request[$i]->name,
      ':sla' => (int)$request[$i]->sla,
      ':window' => (int)$request[$i]->window,
      ':refresh' => (int)$request[$i]->refresh,
      ':metric_id' => (int)$request[$i]->metric,
    ]);
  }

  $conn->commit();
 $data = json_encode(['affected' => $sth->rowCount() ]);

} else {
  $sth = $conn->prepare(
    <<<QUERY
SELECT id, queue, sla, window, refresh, metric_id
FROM config
QUERY
  );
  $sth->execute();
  while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
    $data[] = $row;

  }

  $sth->execute();
}
header('Content-Type: application/json');
echo json_encode($data);