<?php
    session_start();
    $Legend = "Estadísticas";
    include "lib/cVerEstadisticas.php";
    include "vHeader.php";
    extract($_GET);
    extract($_POST);
    $all = true;
    date_default_timezone_set('America/Caracas');
?>
  <style type="text/css">
      @import "css/bootstrap.css";
      @import "css/dataTables.bootstrap.css";
    </style>

    <script type="text/javascript" charset="utf-8" src="js/DataTable/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/dataTables.bootstrap.js"></script>
    
    <script type="text/javascript" charset="utf-8">
      $(document).ready( function () {
        $('.lista').dataTable( {
          "sDom": "<'row-fluid'<'span6'><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
          } );
      } );
    </script>


<script type="text/javascript">
    function showDiv(id) {
      var e = document.getElementById(id);
      if(e.style.display == 'block')
          e.style.display = 'none';
      else
          e.style.display = 'block';
    }
</script>
    

     <?php  
      if (isset($_GET['periodo'])){
      ?>
      
	  <br><p class="lead"><small>Proceso de evaluación de <?echo $nombre_periodo;?></small></p>
	  <p class="lsmall muted"> Para visualizar los detalles del estado de las evaluaciones <i>haga click en la barra de progreso</i> del aspecto de su interés. Si desea esconder los detalles tan solo debe hacer click en la barra de nuevo</p><br>
	  
	  
	  <!-- Inicio sección: Evaluaciones finalizadas -->
	  <p class="lsmall"> Porcentaje de evaluaciones finalizadas</p>
	  <?php
	    $total = $LISTA_EVALUACION["max_res"];
	    if (isset($LISTA_FINALIZADA)){
	      $subtotal= count($LISTA_FINALIZADA['tipo']);
	    } else {
	      $subtotal= 0;
	    }
	    $porcentaje=round(($subtotal/$total)*100);
	  ?>
	  <a title="<? echo $porcentaje.'% ('.$subtotal.' de '.$total.' evaluaciones)';?>" onclick="showDiv('finalizadas')" style="cursor: pointer; text-decoration: none;">
	    <div class="progress" style="height: 20px;">
	      <div class="progress-bar bar-success" role="progressbar" aria-valuenow="<?echo $porcentaje?>" aria-valuemin="0" aria-valuemax="100" style="width: <?echo $porcentaje?>%; height: 100%;">
		<span class="sr-only">&nbsp;</span>
	      </div>
	    </div>
	  </a>
	  <!-- Tabla de evaluaciones finalizadas -->
	  <div id="finalizadas" align="center" style="display: none;" class="well">
	  <?php
	   if (!(isset($LISTA_FINALIZADA))){
	    echo "<br><br><br><br><br><br><p class='text-center text-info lsmall'>Hasta el momento no se ha finalizado ninguna evaluación.</p><br><br><br><br><br><br>";
	   } else {
	  ?>
	  <table align="center" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered lista" id="example" width="100%">
	    <thead>
	      <tr>
		<th class="lsmallT"><small>Tipo de evaluación</small></th>
		<th class="lsmallT"><small>Nombre del evaluador</small></th>
		<th class="lsmallT"><small>Nombre del evaluado</small></th>
		<th class="lsmallT"><small>Fecha de finalización</small></th>
		<th class="lsmallT"><small>Dirección IP</small></th>
	      </tr>
	    </thead>
	    <tfoot>
	      <tr>
		<th class="lsmallT"><small>Tipo de evaluación</small></th>
		<th class="lsmallT"><small>Nombre del evaluador</small></th>
		<th class="lsmallT"><small>Nombre del evaluado</small></th>
		<th class="lsmallT"><small>Fecha de finalización</small></th>
		<th class="lsmallT"><small>Dirección IP</small></th>
	      </tr>
	    </tfoot>
	    <tbody role="alert" aria-live="polite" aria-relevant="all">   
	    <!-- Listado de evaluaciones finalizadas -->
	    <?php
	      for ($i=0;$i<count($LISTA_FINALIZADA['tipo']);$i++){
	    ?>
	      <tr class="<?php echo $color_tabla; ?>" >
		<!--Tipo de evaluación-->
		<td class="center lsmallT" nowrap><small><? if ($LISTA_FINALIZADA['tipo'][$i]=="autoevaluacion") echo "Autoevaluación"; else echo "Regular";?></small></td>  
		<!--Nombre del evaluador-->
		<td class="center lsmallT" nowrap><small><? echo $LISTA_FINALIZADA['nombre_evaluador'][$i];?></small></td>    
		<!--Nombre del evaluado-->
		<td class="center lsmallT" nowrap><small><? echo $LISTA_FINALIZADA['nombre_evaluado'][$i];?></small></td>
		<!--Fecha-->
		<td class="center lsmallT" nowrap><small><? echo $LISTA_FINALIZADA['fecha'][$i];?></small></td>
		<!--Dirección IP-->
		<td class="center lsmallT" nowrap><small><? echo $LISTA_FINALIZADA['ip'][$i];?></small></td>
	      </tr>http://stackoverflow.com/questions/4982701/best-way-to-store-ip-in-database
	    <? } //cierre del for
	    ?>   
	    </tbody>
	  </table>
	  <?
	  } //cierre del if  
	  ?>
	  </div> <!-- Fin de la tabla-->
	  <!-- Fin sección: Evaluaciones finalizadas -->
	  
	  <!-- Inicio sección: Evaluaciones en proceso -->
	  <p class="lsmall"> Porcentaje de evaluaciones en proceso</p>
	  <?php
	    $total = $LISTA_EVALUACION["max_res"];
	    if (isset($LISTA_EN_PROCESO)){
	      $subtotal_en_proceso= count($LISTA_EN_PROCESO['tipo']);
	    } else {
	      $subtotal_en_proceso= 0;
	    }
	    $porcentaje=round(($subtotal_en_proceso/$total)*100);
	  ?>
	  <a title="<? echo $porcentaje.'% ('.$subtotal_en_proceso.' de '.$total.' evaluaciones)';?>" onclick="showDiv('en_proceso')" style="cursor: pointer; text-decoration: none;">
	    <div class="progress" style="height: 20px;">
	      <div class="progress-bar bar-warning" role="progressbar" aria-valuenow="<?echo $porcentaje?>" aria-valuemin="0" aria-valuemax="100" style="width: <?echo $porcentaje?>%; height: 100%;">
		<span class="sr-only">&nbsp;</span>
	      </div>
	    </div>
	  </a>
	  <!-- Tabla de evaluaciones en proceso -->
	  <div id="en_proceso" align="center" style="display: none;" class="well">
	    <?php 
	      if (!(isset($LISTA_EN_PROCESO))){
	    echo "<br><br><br><br><br><br><p class='text-center text-info lsmall'>Hasta el momento no hay ninguna evaluación en proceso.</p><br><br><br><br><br><br>";
	      } else {
	    ?>
		<table align="center" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered lista" id="example" width="100%">
		  <thead>
		    <tr>
		      <th class="lsmallT"><small>Tipo de evaluación</small></th>
		      <th class="lsmallT"><small>Nombre del evaluador</small></th>
		      <th class="lsmallT"><small>Nombre del evaluado</small></th>
		      <th class="lsmallT"><small>Fecha de último intento</small></th>
		      <th class="lsmallT"><small>Dirección IP</small></th>
		    </tr>
		  </thead>
		  <tfoot>
		    <tr>
		      <th class="lsmallT"><small>Tipo de evaluación</small></th>
		      <th class="lsmallT"><small>Nombre del evaluador</small></th>
		      <th class="lsmallT"><small>Nombre del evaluado</small></th>
		      <th class="lsmallT"><small>Fecha de último intento</small></th>
		      <th class="lsmallT"><small>Dirección IP</small></th>
		    </tr>
		  </tfoot>
		  <tbody role="alert" aria-live="polite" aria-relevant="all">   
		  <!-- Listado de evaluaciones en proceso -->
		  <?php
		    for ($i=0;$i<count($LISTA_EN_PROCESO['tipo']); $i++){
		  ?>
		    <tr class="<?php echo $color_tabla; ?>" >
		      <!--Tipo de evaluación-->
		      <td class="center lsmallT" nowrap><small><? if ($LISTA_EN_PROCESO['tipo'][$i]=="autoevaluacion") echo "Autoevaluación"; else echo "Regular";?></small></td>  
		      <!--Nombre del evaluador-->
		      <td class="center lsmallT" nowrap><small><? echo $LISTA_EN_PROCESO['nombre_evaluador'][$i];?></small></td>    
		      <!--Nombre del evaluado-->
		      <td class="center lsmallT" nowrap><small><? echo $LISTA_EN_PROCESO['nombre_evaluado'][$i];?></small></td>
		      <!--Fecha de último intento-->
		      <td class="center lsmallT" nowrap><small><? echo $LISTA_EN_PROCESO['fecha'][$i];?></small></td>
		      <!--Dirección IP-->
		      <td class="center lsmallT" nowrap><small><? echo $LISTA_EN_PROCESO['ip'][$i];?></small></td>
		    </tr>
		  <? } //cierre del for 
		  ?>   
		  </tbody>
		</table>
		<?
		} //cierre del if 
		?>
	  </div> <!-- Fin de la tabla-->
	  <!-- Fin sección: Evaluaciones en proceso -->

	  	  
	  <!-- Inicio sección: Evaluaciones pendientes -->
	  <?php 
	    if (!(isset($LISTA_PENDIENTE))){
	    echo "<br><br><br><br><br><br><p class='text-center text-info lsmall'>No hay evaluaciones pendientes.</p><br><br><br><br><br><br>";
	    } else {
	  ?>
	  <p class="lsmall"> Porcentaje de evaluaciones pendientes</p>
	  <?php
	    if (isset($LISTA_PENDIENTE)){
	      $subtotal_pendiente= count($LISTA_PENDIENTE['tipo']);
	    } else {
	      $subtotal_pendiente= 0;
	    }
	    $porcentaje=round(($subtotal_pendiente/$total)*100);
	  ?>
	  <a title="<? echo $porcentaje.'% ('.$subtotal_pendiente.' de '.$total.' evaluaciones)';?>" onclick="showDiv('pendientes')" style="cursor: pointer; text-decoration: none;">
	    <div class="progress" style="height: 20px;">
	      <div class="progress-bar bar-danger" role="progressbar" aria-valuenow="<?echo $porcentaje?>" aria-valuemin="0" aria-valuemax="100" style="width: <?echo $porcentaje?>%; height: 100%;">
		<span class="sr-only">&nbsp;</span>
	      </div>
	    </div>
	  </a>
	  <!-- Tabla de evaluaciones pendientes -->
	  <div id="pendientes" align="center" style="display: none;" class="well">
	  <table align="center" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered lista" id="example" width="100%">
	    <thead>
	      <tr>
		<th class="lsmallT"><small>Tipo de evaluación</small></th>
		<th class="lsmallT"><small>Nombre del evaluador</small></th>
		<th class="lsmallT"><small>Nombre del evaluado</small></th>
	      </tr>
	    </thead>
	    <tfoot>
	      <tr>
		<th class="lsmallT"><small>Tipo de evaluación</small></th>
		<th class="lsmallT"><small>Nombre del evaluador</small></th>
		<th class="lsmallT"><small>Nombre del evaluado</small></th>
	      </tr>
	    </tfoot>
	    <tbody role="alert" aria-live="polite" aria-relevant="all">   
	    <!-- Listado de encuestas definidas -->
	    <?php
	      for ($i=0;$i<count($LISTA_PENDIENTE['tipo']);$i++){
	    ?>
	      <tr class="<?php echo $color_tabla; ?>" >
		<!--Tipo de evaluación-->
		<td class="center lsmallT" nowrap><small><? if ($LISTA_PENDIENTE['tipo'][$i]=="autoevaluacion") echo "Autoevaluación"; else echo "Regular";?></small></td>  
		<!--Nombre del evaluador-->
		<td class="center lsmallT" nowrap><small><? echo $LISTA_PENDIENTE['nombre_evaluador'][$i];?></small></td>    
		<!--Nombre del evaluado-->
		<td class="center lsmallT" nowrap><small><? echo $LISTA_PENDIENTE['nombre_evaluado'][$i];?></small></td>
	      </tr>
	    <? } //cierre del for
	    } //cierre del else (lista no vacía)
	    ?>   
	    </tbody>
	  </table>
	  </div> <!-- Fin de la tabla-->
	  <!-- Fin sección: Evaluaciones pendientes -->
	  
	  <!-- Inicio sección: Evaluaciones supervisadas -->
	  <p class="lsmall"> Porcentaje de evaluaciones supervisadas</p>
	  <a title="20% (2 de 6 evaluaciones)" onclick="showDiv('supervisadas')" style="cursor: pointer; text-decoration: none;">
	  <div class="progress" style="height: 20px;">
	    <div class="progress-bar bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%; height: 100%;">
	      <span class="sr-only">&nbsp;</span>
	    </div>
	  </div>
	  </a>
	  <!-- Tabla de evaluaciones supervisadas -->
	  <div id="supervisadas" align="center" style="display: none;" class="well">
	  <table align="center" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered lista" id="example" width="100%">
	    <thead>
	      <tr>
		<th class="lsmallT"><small>Nombre del supervisor</small></th>
		<th class="lsmallT"><small>Nombre del evaluador</small></th>
		<th class="lsmallT"><small>Nombre del evaluado</small></th>
		<th class="lsmallT"><small>Fecha de aceptación</small></th>
		<th class="lsmallT"><small>Dirección IP</small></th>
	      </tr>
	    </thead>
	    <tfoot>
	      <tr>
		<th class="lsmallT"><small>Nombre del supervisor</small></th>
		<th class="lsmallT"><small>Nombre del evaluador</small></th>
		<th class="lsmallT"><small>Nombre del evaluado</small></th>
		<th class="lsmallT"><small>Fecha de aceptación</small></th>
		<th class="lsmallT"><small>Dirección IP</small></th>
	      </tr>
	    </tfoot>
	    <tbody role="alert" aria-live="polite" aria-relevant="all">   
	    <!-- Listado de evaluaciones supervisadas -->
	    <?php

	      //for ($i=0;$i<count($LISTA_SUPERVISADA['nombre_supervisor']);$i++){
	    ?>
	      <tr class="<?php echo $color_tabla; ?>" >
		<!--Nombre del supervisor-->
		<td class="center lsmallT" nowrap><small>Campo 1</small></td>    
		<!--Nombre del evaluador-->
		<td class="center lsmallT" nowrap><small>Campo 2</small></td>
		<!--Nombre del evaluado-->
		<td class="center lsmallT" nowrap><small>Campo 3</small></td>
		<!--Fecha de aceptación-->
		<td class="center lsmallT" nowrap><small>Campo 4</small></td>
		<!--Dirección IP-->
		<td class="center lsmallT" nowrap><small>Campo 5</small></td>
	      </tr>
	    <? //} //cierre del for
	    ?>   
	    </tbody>
	  </table>
	  </div> <!-- Fin de la tabla-->
	  <!-- Fin sección: Evaluaciones supervisadas -->
	  
	<?php
	}//cierra el else (periodo isset)
	include "vFooter.php";
	?>