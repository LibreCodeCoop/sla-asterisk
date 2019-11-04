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
    <?php
    require_once '../bootstrap.php';
    ?>

    <div class="main-panel full-width">
      <div class="content">
            <div class="card col-md-6">   
              <h5 class="card-title"><b>URA</b></h5>
              <div class="row">       
                <div class="col-sm-12">
                  <div class="card">
                    <!-- <div class="body"> -->
                      <canvas id="ura"></canvas>
                    <!-- </div> -->
                  </div>
                </div> 
              </div> 
            </div> 
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


atualizaUra = function() {
    $.get( "update.php?type=ura", function( data ) {
        var ctxB = document.getElementById("ura").getContext('2d');
        var myBarChart = new Chart(ctxB, {
            type: 'bar',
            data: data,
            options: {
                animation: false,
                legend: {display: false},
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        setTimeout(atualizaUra, 10000);
    });
}
atualizaUra();



});
  </script>



</body></html>
