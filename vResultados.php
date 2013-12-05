<?php
    session_start();
    $Legend = "Resultados de la Evaluación";
    include "lib/cResultados.php";
    include "vHeader.php";
    //require_once "lib/phpChart_Lite/conf.php";
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



  <!--RESULTADOS PARA LA SECCION DE COMPETENCIAS-->
  <p class="lead"><small>Evaluación de competencias</small></p>
  <p class="lsmall muted"> Resultados obtenidos para la evaluación de competencias</p>
  
    <div class="row">
    <div class="span1"></div>
    <div class="span10">
    <br>
    <table class="tabla_competencias" style="display: none">
    <caption>Prueba de título</caption>
    <thead>
      <tr>
	<td></td>
	<?php
	  for($i=1; $i<$LISTA_COMPETENCIAS['max_res']-2; $i++){
	?>
	  <th scope="row">C<? echo $i;?></th>
	<?php
	  }
	?>
      </tr>
    </thead>
    <tbody>
	<tr>
	    <th scope="col">Auto-evaluación</th>
	    <?php
	      for($i=1; $i<$LISTA_COMPETENCIAS['max_res']-2; $i++){
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
	      }//cierra iteración
	    ?>
	</tr>

    </tbody>
    </table>
    </div> <!--Cierre span10-->
    <div class="span1"></div>
    </div> <!-- Cierre row-->
    
    <!--RESULTADOS PARA LA SECCION DE FACTORES-->
    <!--ARREGLAR!!! URGENTE!!!-->
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
	  for($i=1; $i<$LISTA_FACTORES['max_res']-5; $i++){
	?>
	  <th scope="row">F<? echo $i;?></th>
	<?php
	  }
	?>
      </tr>
    </thead>
    <tbody>
	<tr>
	    <th scope="col">Auto-evaluación</th>
	    <?php
	      for($i=1; $i<$LISTA_FACTORES['max_res']-5; $i++){
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

    </tbody>
    </table>
    </div> <!--Cierre span10-->
    <div class="span1"></div>
    </div> <!-- Cierre row-->
    
<?
include "vFooter.php";
?>
