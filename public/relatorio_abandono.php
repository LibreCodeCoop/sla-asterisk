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
    <title></title>
    <link href="index_files/bootstrap-datetimepicker/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="index_files/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
	<script type="text/javascript" src="index_files/bootstrap-datetimepicker/jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
	<script type="text/javascript" src="index_files/bootstrap-datetimepicker/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="index_files/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
	<script type="text/javascript" src="index_files/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>    
</head>

<body class="">
  <!-- Extra details for Live View on GitHub Pages -->
  <div class="wrapper">
    <div class="main-panel full-width">
      <div class="content">
        <?php
        require_once '../bootstrap.php';
        ?>
          	<form action="/relatorio_download.php" method="post" name="relatorio_abandono">
           	<table class="table table-bordered" id="tabela_relatorio">
                <thead>
                  	<tr>
                      <th>
                            <?php 
                            $sth = $conn->prepare("SELECT DISTINCT s.queue, c.descr FROM qstats.queue_stats_mv s JOIN asterisk.queues_config c ON s.queue = c.extension WHERE s.queue <> 'NONE'");
                            $sth->execute();
                            ?>
                            <select class="form-control" name="queue" required>
                            	<option value="">Escolha a fila</option>
                            	<?php while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {?>
                      				<option value="<?php echo $row['queue'];?>"><?php echo $row['queue'].' - '.$row['descr'];?></option>                   
                  				<?php } ?>
                  			</select>          
                  			<br>          
                      </th>                                           
                      <th>          
                        <div class="input-group date form_datetime" data-link-field="dtp_input1">
                            <input class="form-control" size="20" type="text" value="Escolha a data" readonly>
        					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
        				<input type="hidden" id="dtp_input1" value="" name="datetimeconnect"><br/>                                       
                      </th>                                                                    
                      <th>          
                        <div class="input-group date form_datetime" data-link-field="dtp_input2">
                            <input class="form-control" size="20" type="text" value="Escolha a data" readonly>
        					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
        				<input type="hidden" id="dtp_input2" value="" name="datetimeend"><br/>                                       
                      </th>
                                             
                      <th>
                      	<input class="form-control" type="submit" name="relatorio_abandono" value="Gerar Relatório"/>
                      	<br>
                      </th>
                  	</tr>
                </thead>
            </table>    
            </form>   
               	
        </div>                
      </div>
    </div>
 
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'pt-BR',
        showWeekDays: false,
//         weekStart: 0,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
        format: 'dd/mm/yyyy h:ii'
        
    });
</script> 


</body></html>
