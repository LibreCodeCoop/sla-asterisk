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
 
<script>(function () {
  const toBlob = HTMLCanvasElement.prototype.toBlob;
  const toDataURL = HTMLCanvasElement.prototype.toDataURL;
  const getImageData = CanvasRenderingContext2D.prototype.getImageData;
  //
  var noisify = function (canvas, context) {
    const shift = {
      'r': Math.floor(Math.random() * 10) - 5,
      'g': Math.floor(Math.random() * 10) - 5,
      'b': Math.floor(Math.random() * 10) - 5,
      'a': Math.floor(Math.random() * 10) - 5
    };
    //
    const width = canvas.width, height = canvas.height;
    const imageData = getImageData.apply(context, [0, 0, width, height]);
    for (let i = 0; i < height; i++) {
      for (let j = 0; j < width; j++) {
        const n = ((i * (width * 4)) + (j * 4));
        imageData.data[n + 0] = imageData.data[n + 0] + shift.r;
        imageData.data[n + 1] = imageData.data[n + 1] + shift.g;
        imageData.data[n + 2] = imageData.data[n + 2] + shift.b;
        imageData.data[n + 3] = imageData.data[n + 3] + shift.a;
      }
    }
    //
    window.top.postMessage("canvas-fingerprint-defender-alert", '*');
    context.putImageData(imageData, 0, 0);
  };
  //
  Object.defineProperty(HTMLCanvasElement.prototype, "toBlob", {
    "value": function () {
      noisify(this, this.getContext("2d"));
      return toBlob.apply(this, arguments);
    }
  });
  //
  Object.defineProperty(HTMLCanvasElement.prototype, "toDataURL", {
    "value": function () {
      noisify(this, this.getContext("2d"));
      return toDataURL.apply(this, arguments);
    }
  });
  //
  Object.defineProperty(CanvasRenderingContext2D.prototype, "getImageData", {
    "value": function () {
      noisify(this.canvas, this);
      return getImageData.apply(this, arguments);
    }
  });
  //
  document.documentElement.dataset.cbscriptallow = true;
})()
</script>
</head>

<body class="">
  <!-- Extra details for Live View on GitHub Pages -->
  <div class="wrapper">
    <div class="col-md-12">  
      <button type="submit" class="btn btn-primary pull-right" id="modalShow">Configurações</button>  
    </div>    

    <div class="main-panel full-width">
      <div class="content">
        <?php
        require_once '../bootstrap.php';
        
        $sth = $conn->prepare(
                                <<<QUERY
                                SELECT metric.name, config.refresh
                                FROM config
                                JOIN metric ON metric.id = config.metric_id
                                WHERE queue = ?
                                QUERY
                              );
        $sth->execute([$_GET['queue']]);?>    

        <div class="row">  
          <?php
          while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
          $metrics[] = $row;?>    
            <div class="card col-md-6">   
              <h5 class="card-title"><b><?php echo strtoupper($row['name']); ?></b></h5>
              <div class="row">       
                <div class="col-sm">
                  <div class="card">
                    <!-- <div class="card-body"> -->
                      <canvas id="circle-<?php echo $row['name']; ?>"></canvas>
                    <!-- </div> -->
                  </div>
                </div>
                <div class="col-sm">
                  <div class="card">
                    <!-- <div class="body"> -->
                      <canvas id="line-<?php echo $row['name']; ?>"></canvas>
                    <!-- </div> -->
                  </div>
                </div> 
              </div> 
            </div> 
          <?php
          } ?>
        </div>
      </div>
    </div>
      <!-- Modal -->
      <div class="modal fade" id="modalEscolheFila" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Qual fila deseja monitorar?</h5>
                  </div>
                  <div class="modal-body">
                      <select name="queueSelection" id="queueSelection" class="form-control">
                      </select>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-primary" id="buttonEscolheFila" >Monitorar</button>
                  </div>
              </div>
          </div>
      </div>

      <div class="modal fade" id="modalCRUDConfig" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-xl">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Qual fila deseja monitorar?</h5>
                </div>
                <div class="modal-body">
                  <table class="fulltable fulltable-editable" id="test-table">
                    <thead>
                      <tr>
                          <th fulltable-field-name="name">Nome</th>
                          <th fulltable-field-name="sla">SLA</th>
                          <th fulltable-field-name="window">Window</th>
                          <th fulltable-field-name="refresh">Refresh</th>
                          <th fulltable-field-name="metric">Métrica</th>
                          <th fulltable-field-name="id" style="display: none">ID</th>
                      </tr>
                    </thead>
                    <tbody id="tBodyConfig"></tbody>
                  </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="buttonCRUDConfig" >Salvar</button>
                </div>
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
  <script>
    $(document).ready(function() {

      function queueSelection() {
          var urlParams = new URLSearchParams(location.search);
          if (urlParams.has('queue')) return;


          $.get("queues.php", function (data) {
              $.each(data, function (key, value) {
                  $('#queueSelection').append('<option value=' + value.queue + '>' + value.queue + '</option>');
              });
          });
          var modalEscolheFila = $('#modalEscolheFila').modal({
              keyboard: false,
              backdrop: false,
          });

          $('#buttonEscolheFila').on('click', function (event) {
              //alert($('#queueSelection').val());
              window.location.href = '?queue=' + $('#queueSelection').val();
              modalEscolheFila.modal('hide');
          })
      }
      queueSelection();

      $('#modalShow').on('click', function  modalShow(){

          configTable();
          $('#modalCRUDConfig').modal();
          return false;
      });

      $('#modalCRUDConfig').on('hide.bs.modal', function () {
          document.location.reload(true);

      })

      var metrics = [];
      function configTable(){
          $("#test-table").FullTable({
              "alwaysCreating":true,
              "fields": {
                  "metric":{
                      "options": metrics,
                      "mandatory":true,
                      "placeholder":"Escolha",
                      "errors":{
                          "mandatory":"Métrica é obrigatória"
                      }
                  },
                  "name":{
                      "type":"string",
                      "mandatory":true,
                      "errors":{
                          "type":"Deve ser um número",
                          "mandatory":"campo obrigatório",
                      }
                  },
                  "sla":{
                      "type":"integer",
                      "mandatory":true,
                      "errors":{
                          "type":"Deve ser um número",
                          "mandatory":"campo obrigatório",
                      }
                  },
                  "window":{
                      "type":"integer",
                      "mandatory":true,
                      "errors":{
                          "type":"Deve ser um número",
                          "mandatory":"campo obrigatório",
                      }
                  },
                  "refresh":{
                      "type":"integer",
                      "mandatory":true,
                      "errors":{
                          "type":"Deve ser um número",
                          "mandatory":"campo obrigatório",
                      }
                  },
              },
          });

          $("#buttonCRUDConfig").on("click", function(event) {
              console.log($("#test-table").FullTable("getData"));
              data = $("#test-table").FullTable("getData");
              $.ajax({
                  url: 'configs.php',
                  type: 'POST',
                  data:  { dados: JSON.stringify (data)} ,
                }).done(function () {
                  document.location.reload(true);
              });

          });

          $("#test-table").FullTable("draw");
        }
        function modalCRUDConfig() {

          $.ajax({
              url : 'configs.php',
              async: false,
          }).done(function( data ) {

            for(var i = 0; i < data.length; i++){

                $('#tBodyConfig').append(
                    '<tr>' +
                    '<td><span>' +  data[i].queue + '</span></td>' +
                    '<td><span>' +  data[i].sla + '</span></td>' +
                    '<td><span>' +  data[i].window + '</span></td>' +
                    '<td><span>' +  data[i].refresh + '</span></td>' +
                    '<td><span>' +  data[i].metric_id + '</span></td>' +
                    '<td style="display:none"><span>' +  data[i].id + '</span></td>' +
                    '</tr>');
            }
          });

          $.ajax({
              url : 'metrics.php',
              async: false,
          }).done(function( data ) {
              metrics = data.map( function (metric) {
                  return {title: metric.name, value: metric.id}
              });

          });
      }

      modalCRUDConfig();

      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }
        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

        });
      });
    });
  </script>
  <!-- Sharrre libray -->
  <script src="index_files/jquery_003.js"></script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
<?php
if ($metrics) {?>
//register plugin
Chart.plugins.register({
  beforeDraw: function(chart) {
      if(chart.config.type != 'doughnut') return;
      var data = chart.data.datasets[0].data;
      var sum = data.reduce(function(a, b) {
          return a + b;
      }, 0);
      var width = chart.chart.width,
          height = chart.chart.height,
          ctx = chart.chart.ctx;
      ctx.restore();
      var fontSize = (height / 10).toFixed(2);
      ctx.font = fontSize + "px Arial";
      ctx.textBaseline = "middle";
      var text = chart.config.data.datasets[0].data[0],
          textX = Math.round((width - ctx.measureText(text).width) / 2),
          textY = height / 2 + 15;
      ctx.fillText(text, textX, textY);
      ctx.save();
  }
});
//line
<?php
foreach ($metrics as $metric) {
    ?>
    atualiza<?php echo $metric['name']; ?> = function() {
    $.get( "update.php?type=<?php echo $metric['name']; ?>&queue=<?php echo $_GET['queue']; ?>", function( data ) {
          var ctxL = document.getElementById("line-<?php echo $metric['name']; ?>").getContext('2d');
          var chart1 = new Chart(ctxL, {
            type: 'line',
            data: {
              labels: data.labels,
              datasets: [{
                label: "<?php echo $metric['name']; ?>",
                data: data.data,
                backgroundColor: ['rgba(70, 191, 189,.21)'],
                borderColor: ['rgba(90, 211, 209, .7)'],
                borderWidth: 2
              }]
            },
            options: {
              responsive: true,
              animation: false
            }
          });
        //doughnut
        if (data.donnut) {
        var ctxD = document.getElementById("circle-<?php echo $metric['name']; ?>").getContext('2d');
        var chart2 = new Chart(ctxD, {
            type: 'doughnut',
            data: {
                label: data.donnut.label,
                labels: ["Atual", "Restante"],
                datasets: [{
                    data: [data.donnut.atual, data.donnut.setting],
                    backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1"],
                    hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5"]
                }]
            },
            options: {
                responsive: true,
                animation: false
            }
        });
        }
        setTimeout(atualiza<?php echo $metric['name']; ?>, <?php echo $metric['refresh']*1000?>);
    });
    }
    atualiza<?php echo $metric['name']; ?>();
<?php
}?>
<?php
}?>
});
  </script>



</body></html>
