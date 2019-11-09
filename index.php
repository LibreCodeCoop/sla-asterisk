<?php

if(isset($_GET['type'])) {
    switch($_GET['type']) {
        case 'ura':
            header("Location: http://".$_SERVER["HTTP_HOST"].':'.getenv('PORT').'/dash_ura.php');
            break;
        case 'relatorio_historico':
            header("Location: http://".$_SERVER["HTTP_HOST"].':'.getenv('PORT').'/relatorio.php');
            break;
        case 'relatorio_callback':
            header("Location: http://".$_SERVER["HTTP_HOST"].':'.getenv('PORT').'/relatorio_abandono.php');
            break;
        case 'relatorio_csv':
            header("Location: http://".$_SERVER["HTTP_HOST"].':'.getenv('PORT').'/relatorio/index.php');
            break;
    }
} else {
    header("Location: http://".$_SERVER["HTTP_HOST"].':'.getenv('PORT'));
}