<!DOCTYPE html>
<html data-cbscriptallow="true" class="perfect-scrollbar-off" lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="index_files/css.css">
  <link rel="stylesheet" href="index_files/font-awesome.css">
  <!-- CSS Files -->
  <link href="index_files/material-dashboard.css" rel="stylesheet">
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="index_files/custom.css" rel="stylesheet">
</head>

<body class="">

  <!-- Extra details for Live View on GitHub Pages -->
  <div class="wrapper">
    <div class="col-md-12">  
      <button type="submit" class="btn btn-primary pull-right" id="modalShow">Configurações</button>  
    </div>
    <?php
    require_once '../bootstrap.php';
    if(isset($_GET['queue'])) {
        $sth = $conn->prepare(
            <<<QUERY
             SELECT descr
               FROM asterisk.queues_config
              WHERE extension = ?
            QUERY
            );
        $sth->execute([$_GET['queue']]);
        $row = $sth->fetch(\PDO::FETCH_ASSOC);
        if($row) {
            ?>
            <div class="text-center">
             <h1><?php echo $row['descr']; ?></h1>
            </div><?php
        }
    }?>

    <div class="main-panel full-width">
      <div class="content">
        <?php
        $sth = $conn->prepare(
            <<<QUERY
            SELECT metric.name, config.refresh
            FROM config
            JOIN metric ON metric.id = config.metric_id
            WHERE queue = ?
            ORDER BY metric.order
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
                <div class="col-sm-4">
                  <div class="card" style="height: 96%">
                    <!-- <div class="card-body"> -->
                      <canvas id="circle-<?php echo $row['name']; ?>" style="height: 100%"></canvas>
                    <!-- </div> -->
                  </div>
                </div>
                <div class="col-sm-8">
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

  <!-- FullTable -->
  <script src="index_files/fulltable/jquery.fulltable.js"></script>
  <link rel="stylesheet" type="text/css" href="index_files/fulltable/jquery.fulltable.css"/>


  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.10/js/mdb.min.js"></script>
  <script>
    $(document).ready(function() {
        function queueSelection() {
            var urlParams = new URLSearchParams(location.search);
            if (urlParams.has('queue')) return;


            $.get("queues.php", function (data) {
                $.each(data, function (key, value) {
                    $('#queueSelection').append('<option value=' + value.queue + '>' + value.nome + '</option>');
                });
            });
            var modalEscolheFila = $('#modalEscolheFila').modal({
                keyboard: false,
                backdrop: false,
            });

            $('#buttonEscolheFila').on('click', function (event) {
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
                        "type":"integer",
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
    $(document).ready(function() {
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

      var totalSeconds = chart.config.data.datasets[0].data[0];
      hours = Math.floor(totalSeconds / 3600);
      totalSeconds %= 3600;
      minutes = Math.floor(totalSeconds / 60);
      seconds = totalSeconds % 60;
      var text = (("00" + minutes).slice(-2)) + ":" + (("00" + seconds).slice(-2));
      if (hours) {
          text = (("00" + hours).slice(-2)) + ":" +  text
      }

      var textX = Math.round((width - ctx.measureText(text).width) / 2),
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
                    borderWidth: 2,
                    pointRadius:0
                  }]
                },
                options: {
                  responsive: true,
                  animation: false,
                  legend: {display: false}
                }
              });
            //doughnut
            if (data.donnut) {
                var ctxD = document.getElementById("circle-<?php echo $metric['name']; ?>").getContext('2d');
                var chart2 = new Chart(ctxD, {
                    type: 'doughnut',
                    data: {
                        label: data.donnut.label,
                        pointRadius: 0,
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