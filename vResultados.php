<?php
    session_start();
    $Legend = "Resultados de la Evaluación";
    include "lib/cResultados.php";
    include "vHeader.php";
    extract($_GET);
    extract($_POST);
    $all = true;
    date_default_timezone_set('America/Caracas');
?>  

<link href="js/jQuery-Visualize/css/basic.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/jQuery-Visualize/js/enhance.js"></script>		
<script type="text/javascript">
  // Run capabilities test
  enhance({
  loadScripts: [
    'js/jQuery-Visualize/js/excanvas.js',
    'https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js',
    'js/jQuery-Visualize/js/visualize.jQuery.js',
    'js/jQuery-Visualize/js/example.js'
  ],
  loadStyles: [
    'js/jQuery-Visualize/css/visualize.css',
    'js/jQuery-Visualize/css/visualize-light.css'
  ]
  });   
</script> 


  <!--ENCABEZADO REPORTE DE RESULTADOS-->
  <br>
  <p class="lsmall muted"> Datos del trabajador evaluado</p>
  <br>
  <div class="row">
    <div class="span1" align="right">
      <img src="./img/iconos/user-new.jpg" class="img-circle">
    </div>
    <div class="span11">
    <blockquote>
       <p><?echo $NOMBRE?></p>
       <p class="lsmall">C.I. <? echo $CEDULA?></p>
       <p class="lsmall"><? echo $CARGO?></p>
       <p class="lsmall"><? echo $UNIDAD?></p>

    </blockquote>
    </div>
  </div>
  <br>
  
  <!--RESULTADOS PARA LA SECCION DE COMPETENCIAS-->
  <p class="lead"><small>Evaluación de competencias</small></p>
  <p class="lsmall muted"> Resultados obtenidos para la evaluación de competencias</p>
  
    <div class="row">
    <div class="span1"></div>
    <div class="span10"><br>
    
    <table class="tabla_competencias" style="display: none">
    <caption>Prueba de título</caption>
    <thead><tr>
	<td></td>
	<?php for($i=0; $i<$LISTA_COMPETENCIAS['max_res']; $i++){ ?>
	  <th scope="row">C<? echo $i+1;?></th>
	<?php } ?>
    </tr></thead>
    
    <tbody>
      <tr>
	    <th scope="col">Auto-evaluación</th>
	    <?php for($i=0; $i<$LISTA_COMPETENCIAS['max_res']; $i++){
	      switch($LISTA_COMPETENCIAS['Preg']['resultado'][$i]){
	      case 'Siempre':
		echo  '<td>3</td>';
		break;
	      case 'Casi siempre':
		echo  '<td>2</td>';
		break;
	      case 'Pocas veces':
		echo  '<td>1</td>';
		break;
	      case 'Nunca':
		echo  '<td>0</td>';
		break;
	      }//cierra switch
	    }?>
      </tr>
      <tr>
	  <th scope="col">Evaluación del supervisor inmediato</th>
	  <?php 
	    $n=count($LISTA_EVALUADORES['Eva']['id_encuestado']);
	    for($i=0; $i<count($PROMEDIO_EVALUADORES['re_competencia']); $i++){
	      echo '<td>'.($PROMEDIO_EVALUADORES['re_competencia'][$i]/$n).'</td>';
	    }
	  ?>
      <td>3</td><!--Truco para definir el máximo-->
      </tr>
      
    </tbody>
    </table>
    
    </div> <!--Cierre span10-->
    <div class="span1"></div>
    </div><br><br><!-- Cierre row-->
    
    <!--Tabla de detalles-->
    <table class="table table-hover" style="margin-left: 0;">
      <thead>
	<tr>
	  <th class="lsmallT" style="border-top: 1px solid #dddddd"><small>Competencia</small></th>
	  <th class="lsmallT" style="border-top: 1px solid #dddddd">
	    <small>Resultado auto-evaluación</small>
	    <span style="font-size:8px; padding-left:8px; background:#62c462;">&nbsp;</span>
	  </th>
	  <? for ($j=0; $j<count($LISTA_EVALUADORES['Eva']['nombre']); $j++){
	    echo "<th class='lsmallT' style='border-top: 1px solid #dddddd'>";
	    echo "<small>Resultado evaluación<br>(".$LISTA_EVALUADORES['Eva']['nombre'][$j].") </small>";
	    echo "<span style='font-size:8px; padding-left:8px; background:#0088cc;'>&nbsp;</span></th>";
	  } ?>
	  <th class="lsmallT" style="border-top: 1px solid #dddddd"><small>Resultado esperado</small></th>
	</tr>
      </thead>
      <tbody role="alert" aria-live="polite" aria-relevant="all">   
      <!-- Listado de evaluaciones finalizadas -->
      <?php
	for ($i=0;$i<$LISTA_COMPETENCIAS['max_res'];$i++){
      ?>
	<tr class="<?php echo $color_tabla; ?>" >
	  <!--Competencia-->
	  <td class="center lsmallT" nowrap><small><? echo $LISTA_COMPETENCIAS['Preg']['titulo'][$i]." (C".($i+1).")";?></small></td>  
	  <!--Resultado auto-evaluación-->
	  <td class="center lsmallT" nowrap><small><? echo $LISTA_COMPETENCIAS['Preg']['resultado'][$i]?></small></td>    
	  <!--Resultado de las evaluaciones-->
	  <? for ($j=0; $j<count($LISTA_EVALUADORES['Eva']['id_encuestado']); $j++){
	    echo "<td class='center lsmallT' nowrap><small>".$LISTA_EVALUADORES['Eva']['re_competencia'][$j][$i]."</small></td>";
	  } ?>
	  <!--Resultado esperado-->
	  <td class="center lsmallT" nowrap><small><? echo "Siempre"?></small></td>
	</tr>
      <? } //cierre del for
      ?>   
      </tbody>
    </table>
    <!--FIN DE LA SECCION DE COMPETENCIAS-->
    
    <!--RESULTADOS PARA LA SECCION DE FACTORES-->
    <br>
    <p class="lead"><small>Evaluación de factores</small></p>
    <p class="lsmall muted"> Resultados obtenidos para la evaluación de factores desempeño</p>
    <div class="row">
    <div class="span1"></div>
    <div class="span10">
    <br>
    <table class="tabla_factores" style="display: none">
    <caption>Prueba de título</caption>
    <thead>
      <tr>
	<td></td>
	<?php
	  for($i=0; $i<$LISTA_FACTORES['max_res']; $i++){
	?>
	  <th scope="row">F<? echo $i+1;?></th>
	<?php
	  }
	?>
      </tr>
    </thead>
    <tbody>
	<tr>
	    <th scope="col">Auto-evaluación</th>
	    <?php
	      for($i=0; $i<$LISTA_FACTORES['max_res']; $i++){
		switch($LISTA_FACTORES['Preg']['resultado'][$i]){
		  case 'Excelente':
		    echo  '<td>3</td>';
		    break;
		  case 'Sobre lo esperado':
		    echo  '<td>2</td>';
		    break;
		  case 'En lo esperado':
		    echo  '<td>1</td>';
		    break;
		  case 'Por debajo de lo esperado':
		    echo  '<td>0</td>';
		    break;
		}//cierra switch
	      }//cierra iteración
	    ?>
	</tr>
	<tr>
	  <th scope="col">Evaluación del supervisor inmediato</th>
	  <?php 
	    $n=count($LISTA_EVALUADORES['Eva']['id_encuestado']);
	    for($i=0; $i<count($PROMEDIO_EVALUADORES['re_factor']); $i++){
	      echo '<td>'.($PROMEDIO_EVALUADORES['re_factor'][$i]/$n).'</td>';
	    }
	  ?>
	<td>3</td><!--Truco para definir el máximo-->
	</tr>
	
    </tbody>
    </table>
    </div> <!--Cierre span10-->
    <div class="span1"></div>
    </div><br><br> <!-- Cierre row-->
    
    <!--Tabla de detalles-->
    <table class="table table-hover" style="margin-left: 0;">
      <thead>
	<tr>
	  <th class="lsmallT" style="border-top: 1px solid #dddddd"><small>Factor de desempeño</small></th>
	  <th class="lsmallT" style="border-top: 1px solid #dddddd">
	    <small>Resultado auto-evaluación</small>
	    <span style="font-size:8px; padding-left:8px; background:#62c462;">&nbsp;</span>
	  </th>
	  <? for ($j=0; $j<count($LISTA_EVALUADORES['Eva']['nombre']); $j++){
	    echo "<th class='lsmallT' style='border-top: 1px solid #dddddd'>";
	    echo "<small>Resultado evaluación<br>(".$LISTA_EVALUADORES['Eva']['nombre'][$j].") </small>";
	    echo "<span style='font-size:8px; padding-left:8px; background:#0088cc;'>&nbsp;</span></th>";
	  } ?>
	  <th class="lsmallT" style="border-top: 1px solid #dddddd"><small>Resultado esperado</small></th>
	</tr>
      </thead>
      <tbody role="alert" aria-live="polite" aria-relevant="all">   
      <!-- Listado de evaluaciones finalizadas -->
      <?php
	for ($i=0;$i<$LISTA_FACTORES['max_res'];$i++){
      ?>
	<tr class="<?php echo $color_tabla; ?>" >
	  <!--Competencia-->
	  <td class="center lsmallT" style="width: 40%"><small><? echo $LISTA_FACTORES['Preg']['titulo'][$i]." (F".($i+1).")";?></small></td>  
	  <!--Resultado auto-evaluación-->
	  <td class="center lsmallT" nowrap><small><? echo $LISTA_FACTORES['Preg']['resultado'][$i]?></small></td>    
	  <!--Resultado de las evaluaciones-->
	  <? for ($j=0; $j<count($LISTA_EVALUADORES['Eva']['id_encuestado']); $j++){
	    echo "<td class='center lsmallT' nowrap><small>".$LISTA_EVALUADORES['Eva']['re_factor'][$j][$i]."</small></td>";
	  } ?>
	  <!--Resultado esperado-->
	  <td class="center lsmallT" nowrap><small><? echo "Excelente"?></small></td>
	</tr>
      <? } //cierre del for
      ?>   
      </tbody>
    </table>
    <!--FIN DE LA SECCION DE FACTORES-->
   
<?
include "vFooter.php";
?>
