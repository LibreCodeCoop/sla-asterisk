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
        
        $sth = $conn->prepare('SELECT id, queue, sla, metric_id, created FROM dashboard.history LIMIT 10;');
//         var_dump($sth);
        $sth->execute([$_GET['queue']]);?>    

        <div class="row">              
          	
           	<table class="fulltable fulltable-editable" id="test-table">
                <thead>
                  	<tr>
                      <th fulltable-field-name="queue">QUEUE</th>
                      <th fulltable-field-name="sla">SLA</th>
                      <th fulltable-field-name="metrica">MÉTRICA</th>
                      <th fulltable-field-name="data_criacao">DATA CRIAÇÃO</th>
                  	</tr>
                </thead>
            	<tbody id="tBodyConfig">
                <?php while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {?>
                      <tr>
                      	<td><?php echo $row['queue'];?></td>
                      	<td><?php echo $row['sla'];?></td>
                      	<td><?php echo $row['metric_id'];?></td>
                      	<td><?php echo $row['created'];?></td>
                      </tr>                    
                  <?php } ?>
            	</tbody>
            </table>          	
        </div>
      </div>
    </div>
 


  <!--   Core JS Files   -->
  <script src="index_files/jquery.js"></script>
  <script src="index_files/popper.js"></script>
  <script src="index_files/bootstrap-material-design.js"></script>
  <script src="index_files/perfect-scrollbar.js"></script>
  <!-- Plugin for the momentJs  -->
  <script src="index_files/moment.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="index_files/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="index_files/jquery_005.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="index_files/jquery_002.js"></script>
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="index_files/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="index_files/bootstrap-datetimepicker.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="index_files/jquery_004.js"></script>
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="index_files/bootstrap-tagsinput.js"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="index_files/jasny-bootstrap.js"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="index_files/fullcalendar.js"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="index_files/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="index_files/nouislider.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="index_files/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="index_files/arrive.js"></script>
  <!-- Place this tag in your head or just before your close body tag. -->
  <script async="" defer="defer" src="index_files/buttons.js"></script>
  <!-- Chartist JS -->
  <script src="index_files/chartist.js"></script>
  <!--  Notifications Plugin    -->
  <script src="index_files/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="index_files/material-dashboard.js" type="text/javascript"></script>


  <!-- FullTable -->
  <script src="index_files/fulltable/jquery.fulltable.js"></script>
  <link rel="stylesheet" type="text/css" href="index_files/fulltable/jquery.fulltable.css"/>


  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.10/js/mdb.min.js"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="index_files/demo.js"></script>
 
 


</body></html>
