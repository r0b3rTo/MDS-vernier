<?php
    session_start();
    $Legend = "Importar encuesta de Limesurvey";
    include "lib/cImportarEncuesta.php";
    include "vHeader.php";
    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/Caracas');
?>

  <style type="text/css">
     @import "css/bootstrap.css";
  </style>


<script type="text/javascript">
    $(document).ready(function(){
        $("#newSurvey").validate({
            submitHandler : function(form) {
                bootbox.dialog('¿Esta seguro de continuar?', [{
                         'label':'No',
                         'class':'btn'
                        },
                        {
                         'label':'Sí',
                         'class':'btn',
                         'callback':function() {
                                return form.submit();
                         }
                        }]);
            },
            rules:{
                encuesta:"required",
                car:"required",
                unidad: "required",
            },
            messages: {
                encuesta:"Campo Requerido.",
                car:"Campo Requerido.",
                unidad: "Campo Requerido.",
            },
            errorClass: "help-inline"
        })
    });
    
</script>


<?
  if(!(isset($_GET['action']) && $_GET['action']=='pesos')) { 
?>
    <!-- Formulario-->
    <div class="well" align="center">
      <p class='muted'><small>Por favor escoja la encuesta que desea importar y la familia de cargos y unidad asociadas a la nueva encuesta de evaluación.</small></p><br>
      <form id="newSurvey" class="form-horizontal" method="post" action="lib/cImportarEncuesta.php?action=import" >
	  
      <div class="row">
	  <div class="span3"></div>
	  
	  <div class="span4">
	  


	    <div class="control-group">
		<label class="control-label">Encuesta de evaluación</label>
		<div class="controls">
			<select style="width:200px" id="encuesta" name="encuesta" class="select2" data-size="auto">
			    <?
				for ($i=0; $i<count($ENCUESTAS_LS['id_encuesta_ls']); $i++){
				    echo "<option value=".$ENCUESTAS_LS['id_encuesta_ls'][$i].">".$ENCUESTAS_LS['nombre'][$i]."</option>";
				}
			    ?>
			</select>
		</div>
	    </div> 
	    
	    <div class="control-group">
		<label class="control-label">Familia de cargos</label>
		<div class="controls">
			<select style="width:200px" id="car" name="car" class="select2  car-sel" data-size="auto">
			    <?
				while (list($key, $val) = each($CAR_ID)){
				    echo "<option value=".$key.">".$val."</option>";
				}
			    ?>
			</select>
		</div>
	    </div> 
	    
	    <div class="control-group">
		<label class="control-label">Unidad</label>
		<div class="controls">
			<select style="width:200px" id="unidad" name="unidad" class="select2" data-size="auto">
			    <?
				while (list($key, $val) = each($UNIDAD_ID)){
				    echo "<option value=".$key.">".$val."</option>";
				}
			    ?>
			</select>
		</div>
	    </div>

	  </div> <!--cierre span4-->

      </div> <!--cierre row-->

      <button type="submit" id="confirmButton" class="btn btn-success" >Importar</button>
      <a href="?" class="btn">Cancelar</a>	
      
      </form>
    </div>


<?
  } else {
?>

  
  <div class="span2"></div>
  <div class="span7">
    
    <!--Sección de factores-->
    <br><p class="lead"><small>Pesos de factores</small></p>
    <p class='muted'><small>Por favor ingrese los pesos asociados a los factores de esta evaluación. Recuerde que el rango de valores posibles es: 0.0 - 1.0</small></p>
    
    <div class="well" style="background-color:#fff">
    <form id="newWeight" class="form-horizontal" method="post" action="lib/cImportarEncuesta.php?action=set&id_encuesta=<? echo $_GET['id_encuesta'];?>" >
    <table class="table table-hover" >
    
	<thead>
	    <tr>
	      <th class="lsmallT"><small>Pregunta</small></th>
	      <th class="lsmallT"><small>Peso</small></th>
	    </tr>
	</thead>

	<tbody role="alert" aria-live="polite" aria-relevant="all">
	  <!-- Encuestas del usuario -->
	  <?php
	    for ($i=0;$i<$LISTA_PREGUNTA['max_res'];$i++){
	  ?>
	      <tr>
		<? if ($LISTA_PREGUNTA['Preg']['id_pregunta_root_ls'][$i]==NULL) { ?> 
		  <td class="center lsmallT" ><small><? echo $LISTA_PREGUNTA['Preg']['titulo'][$i] ?></small></td>
		  <td class="center lsmallT" style="width: 30px" nowrap>
		    <input type="text" required name="peso_<?echo $LISTA_PREGUNTA['Preg']['id_pregunta'][$i]?>" id="peso_<?echo $i?>" data-format="d.d" value="-" maxLength="3" style="width: 30px;" readonly/>
		  </td>
		  </td> 
		<? } else { ?>
		  <td class="center lsmallT" ><small>&nbsp;&nbsp;&nbsp;&nbsp;&rarr;<? echo $LISTA_PREGUNTA['Preg']['titulo'][$i] ?></small></td>
		  <td class="center lsmallT" style="width: 30px" nowrap>
		    <input type="text" required name="peso_<?echo $LISTA_PREGUNTA['Preg']['id_pregunta'][$i]?>" id="peso_<?echo $i?>" data-format="d.d" value="<?if ($LISTA_PREGUNTA['Preg']['peso'][$i]!=NULL) echo $LISTA_PREGUNTA['Preg']['peso'][$i]; ?>" placeholder="0.0" maxLength="3" style="width: 30px;" />
		  </td>
		  </td> 		
		<? }?>
	      </tr>
	  <? } //cierre del for
	  ?>
	</tbody>

    </table>
    </div>
    <div align="center">
      <button type="submit" id="confirmButton" class="btn">Finalizar</button>  
    </div>
    </form>
   </div> <!--Cierre span5-->
      

<?
  } //cierrre else (definir pesos)
?>



<?php
include "vFooter.php";
?>
