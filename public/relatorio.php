<!--
=========================================================
 Material Dashboard - v2.1.1
=========================================================

 Product Page: https://www.creative-tim.com/product/material-dashboard
 Copyright 2019 Creative Tim (https://www.creative-tim.com)
 Licensed under MIT (https://github.com/creativetimofficial/material-dashboard/blob/master/LICENSE.md)

 Coded by Creative Tim

Gráficos

https://mdbootstrap.com/docs/jquery/javascript/charts/

=========================================================

 The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html data-cbscriptallow="true" class="perfect-scrollbar-off" lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
      <link rel="apple-touch-icon" sizes="76x76" href="https://demos.creative-tim.com/material-dashboard/assets/img/apple-icon.png">
      <link rel="icon" type="image/png" href="https://demos.creative-tim.com/material-dashboard/assets/img/favicon.png">
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Material Dashboard by Creative Tim https://demos.creative-tim.com/material-dashboard/examples/dashboard.html</title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport">
  <!-- Extra details for Live View on GitHub Pages -->
  <!-- Canonical SEO -->
  <link rel="canonical" href="https://www.creative-tim.com/product/material-dashboard">
  <!--  Social tags      -->
  <meta name="keywords" content="creative tim, html dashboard, html css dashboard, web dashboard, bootstrap 4 dashboard, bootstrap 4, css3 dashboard, bootstrap 4 admin, material dashboard bootstrap 4 dashboard, frontend, responsive bootstrap 4 dashboard, free dashboard, free admin dashboard, free bootstrap 4 admin dashboard">
  <meta name="description" content="Material Dashboard is a Free Material Bootstrap Admin with a fresh, new design inspired by Google's Material Design.">
  <!-- Schema.org markup for Google+ -->
  <meta itemprop="name" content="Material Dashboard by Creative Tim">
  <meta itemprop="description" content="Material Dashboard is a Free Material Bootstrap Admin with a fresh, new design inspired by Google's Material Design.">
  <meta itemprop="image" content="https://s3.amazonaws.com/creativetim_bucket/products/50/opt_md_thumbnail.jpg">
  <!-- Twitter Card data -->
  <meta name="twitter:card" content="product">
  <meta name="twitter:site" content="@creativetim">
  <meta name="twitter:title" content="Material Dashboard by Creative Tim">
  <meta name="twitter:description" content="Material Dashboard is a Free Material Bootstrap Admin with a fresh, new design inspired by Google's Material Design.">
  <meta name="twitter:creator" content="@creativetim">
  <meta name="twitter:image" content="https://s3.amazonaws.com/creativetim_bucket/products/50/opt_md_thumbnail.jpg">
  <!-- Open Graph data -->
  <meta property="fb:app_id" content="655968634437471">
  <meta property="og:title" content="Material Dashboard by Creative Tim">
  <meta property="og:type" content="article">
  <meta property="og:url" content="https://demos.creative-tim.com/material-dashboard/examples/dashboard.html">
  <meta property="og:image" content="https://s3.amazonaws.com/creativetim_bucket/products/50/opt_md_thumbnail.jpg">
  <meta property="og:description" content="Material Dashboard is a Free Material Bootstrap Admin with a fresh, new design inspired by Google's Material Design.">
  <meta property="og:site_name" content="Creative Tim">
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="index_files/css.css">
  <link rel="stylesheet" href="index_files/font-awesome.css">
  <!-- CSS Files -->
  <link href="index_files/material-dashboard.css" rel="stylesheet">
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="index_files/demo.css" rel="stylesheet">
  <link href="index_files/custom.css" rel="stylesheet">
  <script type="text/javascript" charset="UTF-8" src="index_files/common.js"></script>
  <script type="text/javascript" charset="UTF-8" src="index_files/util.js"></script>
  <script type="text/javascript" charset="UTF-8" src="index_files/AuthenticationService.Authenticate"></script>
 
 

</head>

<body class="">
  <!-- Extra details for Live View on GitHub Pages -->
  <div class="wrapper">

    <div class="main-panel full-width">
      <div class="content">
        <?php
        require_once '../bootstrap.php';
        ?>

        <div class="row">              
          	<form action="/relatorio_download.php" method="post" name="relatorio_historico">
           	<table class="table table-bordered" id="tabela_relatorio">
                <thead>
                  	<tr>
                      <th>
                            <?php 
                            $sth = $conn->prepare('SELECT DISTINCT queue, descr FROM dashboard.history JOIN asterisk.queues_config ON queue = extension order by queue;');
                            $sth->execute();
                            $array = $sth->fetchAll(\PDO::FETCH_ASSOC);
                            $queues = array_column($array, 'queue');
                            $values = implode(",", $queues);
                            $sth->execute();
                            ?>
                            <select name="queue">
                            	<option value="<?php echo $values;?>">ESCOLHA A QUEUE</option>
                            	<?php while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {?>
                      				<option value="<?php echo $row['queue'];?>"><?php echo $row['queue'].' - '.$row['descr'];?></option>
                  				<?php } ?>
                  			</select>                    
                      </th>                      
                      <th>
                            <?php 
                            $sth = $conn->prepare('SELECT metric_id, metric.name FROM history JOIN metric ON metric.id = history.metric_id GROUP BY metric_id, metric.name order by metric_id;');
                            $sth->execute();    
                            $array = $sth->fetchAll(\PDO::FETCH_ASSOC);
                            $values = implode(",", array_column($array, 'metric_id'));
                            $sth->execute();
                            ?>
                            <select name="metric_id">
                            	<option value="<?php echo $values;?>">ESCOLHA A MÉTRICA</option>
                            	<?php foreach ($array as $row) {?>
                      				<option value="<?php echo $row['metric_id'];?>"><?php echo $row['name'];?></option>                   
                  				<?php } ?>
                  			</select>                    
                      </th>                      
                      <th>
                            <?php 
                            $sth = $conn->prepare('SELECT DISTINCT DATE_FORMAT(created, "%d/%M/%Y %H") AS created_formated, DATE_FORMAT(created, "%Y-%m-%d") AS created FROM dashboard.history order by created;');
                            $sth->execute();                            
                            ?>
                            <select name="data_inicio" required>
                            	<option value="">ESCOLHA A DATA INÍCI  O</option>
                            	<?php while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {?>
                      				<option value="<?php echo $row['created'].":00:00";?>"><?php echo $row['created_formated'];?></option>                   
                  				<?php } ?>
                  			</select>                    
                      </th>  
                      <th>
                            <?php 
                            $sth = $conn->prepare('SELECT DISTINCT DATE_FORMAT(created, "%d/%M/%Y %H") AS created_formated, DATE_FORMAT(created, "%Y-%m-%d") AS created FROM dashboard.history order by created;');
                            $sth->execute();
                            ?>
                            <select name="data_fim" required>
                            <option value="">ESCOLHA A DATA FIM</option>
                            	<?php while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {?>
                      				<option value="<?php echo $row['created'].":00:00";?>"><?php echo $row['created_formated'];?></option>                   
                  				<?php } ?>
                  			</select>                    
                      </th>
                      <th>
                      	<input type="submit" name="relatorio_historico" value="Gerar Relatório"/>
                      </th>                        
                  	</tr>
                </thead>
            </table>                
            </form>      	
        </div>
        
        
      </div>
    </div>
 


  <!--   Core JS Files   -->
  <script src="index_files/jquery.js"></script>

</body></html>
