<?php 

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sample.csv"');

require_once '../bootstrap.php';

if ($_POST['relatorio_historico']) {
    
    $sql = "SELECT id, queue, sla, metric_id, created FROM dashboard.history
            WHERE queue in (".$_POST['queue'].") and metric_id in (".$_POST['metric_id'].")
            and created >= '".$_POST['data_inicio']."' and created <= '".$_POST['data_fim']."' ";    
    
    $sth = $conn->prepare($sql);
    $sth->execute();

    $user_CSV[0] = array('queue', 'sla', 'metrica', 'data_criacao');
    
    $i = 1;
    while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {    
        
        $user_CSV[$i] = array($row['queue'],$row['sla'],$row['metric_id'],$row['created']);
        
        $i++;
    } 
}else{
    
    $sql = "SELECT clid
          FROM qstats.queue_stats_mv q
          JOIN (
                SELECT max(id) AS id
                  FROM qstats.queue_stats_mv
                 WHERE clid > 100000
                 GROUP BY clid
               ) subselect ON subselect.id = q.id
           AND q.event = 'ABANDON'
            AND q.queue in (".$_POST['queue'].")
            and q.datetimeconnect >= '".$_POST['datetimeconnect']."' and q.datetimeend <= '".$_POST['datetimeend']."' ";    
//     var_dump($sql);
    $sth = $conn->prepare($sql);
    $sth->execute();
    
    $user_CSV[0] = array('Telefone');
    
    $i = 1;
    while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
        
        $user_CSV[$i] = array(
            
            $row['clid']
            
        );
        
        $i++;
    } 
    
}


$fp = fopen('php://output', 'wb');
foreach ($user_CSV as $line) {
    // though CSV stands for "comma separated value"
    // in many countries (including France) separator is ";"
    fputcsv($fp, $line, ',');
}
fclose($fp);
?>