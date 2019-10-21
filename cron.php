<?php
require_once 'bootstrap.php';

// TMO
$sth = $conn->prepare(
    <<<QUERY
    SELECT a.queue,
           a.agent,
           a.event,
           b.min_date
      FROM qstats.queue_stats_mv a
      JOIN (
            SELECT qs.agent,
                   max(datetime) AS `max`,
                   qs.queue,
                   DATE_SUB(NOW(), INTERVAL config.window second) AS min_date
              FROM qstats.queue_stats_mv qs
              JOIN config
                ON config.queue = qs.queue
               AND config.metric_id = 3
               AND qs.datetime < DATE_SUB(NOW(), INTERVAL config.window second)
             WHERE qs.event IN ('PAUSE', 'UNPAUSE')
             GROUP BY qs.queue, qs.agent
           ) b
        ON b.agent = a.agent
       AND b.max = a.`datetime`
       AND b.queue = a.queue
     WHERE a.event = 'PAUSE'
    QUERY
);
$sth->execute();
while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
    $before[$row['queue']][$row['agent']] = $row['min_date'];
}

$sth = $conn->prepare(
    <<<QUERY
    SELECT a.queue, a.`datetime`, a.agent, a.event
      FROM config c
      JOIN qstats.queue_stats_mv a
        ON c.metric_id = 3
       AND c.queue = a.queue
       AND a.event IN ('PAUSE', 'UNPAUSE')
       AND a.`datetime` >= DATE_SUB(NOW(), INTERVAL c.window second)
      LEFT JOIN qstats.queue_stats_mv b ON a.agent = b.agent AND a.datetime = b.datetime AND b.event IN ('AGENTLOGIN', 'AGENTLOGOFF')
     WHERE (b.event = 'AGENTLOGOFF' OR b.id IS NULL)
     ORDER BY a.queue, a.agent, a.`datetime` ASC
    QUERY
);
$sth->execute();
$rows = $sth->fetchAll(\PDO::FETCH_ASSOC);
$lastAgent = [];
$totalAgents = [];
foreach ($rows as $i => $row) {
    if (!isset($lastAgent[$row['queue']]) || $lastAgent[$row['queue']] != $row['agent']) {
        $lastAgent[$row['queue']] = $row['agent'];
        if (!isset($totalAgents[$row['queue']])) {
            $totalAgents[$row['queue']] = 1;
        } else {
            $totalAgents[$row['queue']]++;
        }
        if (!isset($totalTime[$row['queue']])) {
            $totalTime[$row['queue']] = 0;
        }
        if ($row['event'] == 'UNPAUSE') {
            if ( isset( $before[$row['queue']][$row['agent']] ) ) {
                $min = new DateTime($before[$row['queue']][$row['agent']]);
                $max = new DateTime($row['datetime']);  
                $totalTime[$row['queue']]+= $max->getTimestamp() - $min->getTimestamp();
            }
        } else {
            $min = new DateTime($row['datetime']);
            $max = new DateTime($rows[$i+1]['datetime']);
            $totalTime[$row['queue']]+= $max->getTimestamp() - $min->getTimestamp();
        }
    } elseif (!isset($rows[$i+1]) || (isset($rows[$i+1]) && $rows[$i+1]['agent'] != $row['agent'])) {
        if ($row['event'] == 'PAUSE') {
            $min = new DateTime($row['datetime']);
            $max = new DateTime();
            $totalTime[$row['queue']]+= $max->getTimestamp() - $min->getTimestamp();
        }
    } else {
        if ($row['event'] == 'PAUSE') {
            $min = new DateTime($row['datetime']);
            $max = new DateTime($rows[$i+1]['datetime']);
            $totalTime[$row['queue']]+= $max->getTimestamp() - $min->getTimestamp();
        }
    }
}

$sth = $conn->prepare(
    <<<QUERY
    SELECT queue
      FROM config
     WHERE metric_id = 3
    QUERY
);
$sth->execute();
while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
    $sth = $conn->prepare(
        <<<QUERY
        INSERT INTO history (queue, sla, metric_id)
        VALUES (?, ?, 3);
        QUERY
    );
    $queue = $row['queue'];
    if (isset($totalAgents[$queue])) {
        $sth->execute([$queue, $totalTime[$queue] / $totalAgents[$queue]]);
    } else {
        $sth->execute([$queue, 0]);
    }
}

// Fila
$content = shell_exec('curl http://10.8.0.232:15000');
preg_match_all('/(?P<queue>\d+) has (?P<calls>\d+) calls/', $content, $matches);
foreach ($matches['queue'] as $key => $queue) {
    $sth = $conn->prepare(
        <<<QUERY
        INSERT INTO history (queue, sla, metric_id) VALUES (?, ?, ?)
        QUERY
    );
    $sth->execute([$queue, $matches['calls'][$key], 4]);
}

// TMA
$sth = $conn->prepare(
    <<<QUERY
    INSERT INTO history (queue, sla, metric_id)
    SELECT COALESCE(c.queue, c2.queue) AS queue,
           COALESCE(SUM(info2)/count(*), 0) AS sla,
           COALESCE(c.metric_id, c2.metric_id) AS metric_id
      FROM config c2
      LEFT JOIN config c
        ON c.id = c2.id
       AND c.metric_id = 1
      JOIN qstats.queue_stats_mv ST
        ON c.queue = ST.queue
     WHERE c2.metric_id = 1
       AND (
            event IN ('COMPLETECALLER', 'COMPLETEAGENT')
            AND ST.datetime >= DATE_SUB(NOW(), INTERVAL c.window second)
           ) OR c2.id IS NOT NULL
     GROUP BY c.queue, c2.queue
    QUERY
);
$sth->execute();

// TME
$sth = $conn->prepare(
    <<<QUERY
    INSERT INTO history (queue, sla, metric_id)
    SELECT COALESCE(c.queue, c2.queue) AS queue,
           COALESCE(SUM(info1)/count(*), 0) AS sla,
           COALESCE(c.metric_id, c2.metric_id) AS metric_id
      FROM config c2
      LEFT JOIN config c
        ON c.id = c2.id
       AND c.metric_id = 2
      JOIN qstats.queue_stats_mv ST
        ON c.queue = ST.queue
     WHERE c2.metric_id = 2
       AND (
            event IN ('COMPLETECALLER', 'COMPLETEAGENT')
            AND ST.datetime >= DATE_SUB(NOW(), INTERVAL c.window second)
           ) OR c2.id IS NOT NULL
     GROUP BY c.queue, c2.queue
    QUERY
);
$sth->execute();

echo date('Y-m-d H:i:s'). "\tdone\n";