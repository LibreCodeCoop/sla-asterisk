<?php 


header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sample.csv"');


require_once '../bootstrap.php';

$sql = "SELECT id, queue, sla, metric_id, created FROM dashboard.history
        WHERE queue in (".$_POST['queue'].") and metric_id in (".$_POST['metric_id'].")
        and created >= '".$_POST['data_inicio']."' and created <= '".$_POST['data_fim']."' ";


$sth = $conn->prepare($sql);
$sth->execute();
// var_dump($sth);
$user_CSV[0] = array('queue', 'sla', 'metrica', 'data_criacao');
// very simple to increment with i++ if looping through a database result
$i = 1;
while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {    
    
    $user_CSV[$i] = array($row['queue'],$row['sla'],$row['metric_id'],$row['created']);
//     $user_CSV[1] = array('Quentin', 'Del Viento', 34);
    $i++;
} 

// var_dump($user_CSV);


$fp = fopen('php://output', 'wb');
foreach ($user_CSV as $line) {
    // though CSV stands for "comma separated value"
    // in many countries (including France) separator is ";"
    fputcsv($fp, $line, ',');
}
fclose($fp);
?>