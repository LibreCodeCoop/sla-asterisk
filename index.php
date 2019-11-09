<?php

if(!isset($_GET['type'])) {
}
switch($_GET['type']) {
    case 'dash_lt_tma_tme_tmo_fila':
        header("Location: http://".$_SERVER["HTTP_HOST"].':'.getenv('PORT'));
        break;
    case 'dash_lt_report_history':
        header("Location: http://".$_SERVER["HTTP_HOST"].':'.getenv('PORT').'/relatorio.php');
        break;
    case 'dash_lt_report_callback':
        header("Location: http://".$_SERVER["HTTP_HOST"].':'.getenv('PORT').'/relatorio_abandono.php');
        break;
    case 'dash_lt_report_csv':
        header("Location: http://".$_SERVER["HTTP_HOST"].':'.getenv('PORT').'/relatorio/index.php');
        break;
}