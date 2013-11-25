<?php
    session_start();
    $Legend = "Procesos de Evaluación";
    include "lib/cEvaluaciones.php";
    include "vHeader.php";
    extract($_GET);
    extract($_POST);
    $all = true;
    date_default_timezone_set('America/Caracas');
?>   

<script type="text/javascript">
    $(document).ready(function(){
        $("#newProcess").validate({
            submitHandler : function(form) {
                bootbox.dialog('¿Está seguro de continuar?', [{
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
                periodo:"required",
                ini: "required",
                fin: "required"
            },
            messages: {
                periodo:"Campo Requerido.",
                ini:"Campo Requerido.",
                fin:"Campo Requerido."
            },

            errorClass: "help-inline"
        });
    });
</script>    

     <?php
      if (isset($_GET['action']) && $_GET['action']=='try'){
     ?>
	<!-- Formulario Nuevo proceso de evaluación-->
	<div class="well" align="center">
	    <form id="newProcess" class="form-horizontal" method="post" action="lib/cEvaluaciones.php?action=add" >
		<div class="row">
		<div class="span2"></div>
		<div class="span4">
		  <div class="control-group">
		      <label class="control-label">Periodo del proceso de evaluación</label>
		      <div class="controls">
			<p class='lsmall muted' style="width:300px" align="justify">Por favor seleccione el periodo correspondiente al nuevo proceso de evaluación.</p>
			      <select class="select2" id="periodo" name="periodo"  size=1 data-size="auto">
				<option value="Febrero <?echo date("Y")?>"> Febrero <?echo date("Y")?></option>
				<option value="Octubre <?echo date("Y")?>"> Octubre <?echo date("Y")?></option>
				<!--Temporales solo para prueba-->
				<option value="Febrero 2014"> Febrero 2014</option>
				<option value="Octubre 2014"> Octubre 2014</option>
			      </select>
		      </div>
		  </div>

		  <div class="control-group">
		      <label class="control-label">Fecha de inicio del proceso</label>
		      <div class="controls">
			  <div class="input-prepend date datepicker" data-date="<? echo $ULTIMA_FECHA ?>" data-date-language="es" data-date-start-View="2" data-date-autoclose="true" data-date-format="dd-mm-yyyy" data-date-start-Date="<? echo $ULTIMA_FECHA;?>">
			      <span class="add-on"><i class="icon-calendar"></i></span>
			      <input size="12" id="ini" name="ini" class="input-xlarge" type="text" value="<? echo $ULTIMA_FECHA ?>">
			  </div>
		      </div>
		  </div>
		  
		<div class="control-group">
		      <label class="control-label">Fecha de finalización del proceso</label>
		      <div class="controls">
			  <p class='lsmall muted' style="width:300px" align="justify">Recuerde que el tiempo de duración <i>por defecto</i> del proceso de evaluación es de dos (2) semanas. Asegúrese de escoger el tiempo apropiado</p>
			  <? $date = strtotime($ULTIMA_FECHA); $date = strtotime("+14 day", $date); ?>
			  <div class="input-prepend date datepicker" data-date="<? echo date("d-m-Y", $date) ?>" data-date-language="es"  data-date-start-View="2" data-date-autoclose="true" data-date-format="dd-mm-yyyy" data-date-start-Date="<? echo $ULTIMA_FECHA ?>">
			      <span class="add-on"><i class="icon-calendar"></i></span>
			      <input size="12" id="fin" name="fin" class="input-xlarge" type="text" value="<? echo date("d-m-Y", $date) ?>" >
			  </div>
		      </div>
		  </div>
		</div>
		</div>
		
	    <button type="submit" id="confirmButton" class="btn" >Aceptar</button>
	    <a href="?" class="btn">Cancelar</a>	
	    </form>
	    
	   
	</div>
      <?php
	} else {
      ?>
	<!-- Listado de los procesos de evaluación registrados en el sistema -->
	<?php
	if ($LISTA_EVALUACION['max_res']==0){
	  echo "<br><br><br><br><br><br><p class='text-center text-info'>Hasta el momento no se han registrado procesos de evaluación en el sistema.</p><br><br><br><br><br><br>";
	}else{
	?>
	  <div id="demo" align="center">
	  <table align="center" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example" width="100%">
	    <thead>
	      <tr>
		<th class="lsmallT"><small>Periodo del proceso de evaluación</small></th>
		<th class="lsmallT"><small>Fecha inicio</small></th>
		<th class="lsmallT"><small>Fecha fin</small></th>
		<th class="lsmallT"><small>Evaluaciones<br>pendientes</small></th>
		<th class="lsmallT"><small>Evaluaciones<br>en proceso</small></th>
		<th class="lsmallT"><small>Evaluaciones<br>finalizadas</small></th>
		<th class="lsmallT"><small>Evaluaciones<br>supervisadas</small></th>
		<th class="lsmallT"><small>Estado</small></th>
		<th class="lsmallT"><small>Acción</small></th>
	      </tr>
	    </thead>
	    <tfoot>
	      <tr>
		<th class="lsmallT"><small>Periodo del proceso de evaluación</small></th>
		<th class="lsmallT"><small>Fecha inicio</small></th>
		<th class="lsmallT"><small>Fecha fin</small></th>
		<th class="lsmallT"><small>Evaluaciones<br>pendientes</small></th>
		<th class="lsmallT"><small>Evaluaciones<br>en proceso</small></th>
		<th class="lsmallT"><small>Evaluaciones<br>finalizadas</small></th>
		<th class="lsmallT"><small>Evaluaciones<br>supervisadas</small></th>
		<th class="lsmallT"><small>Estado</small></th>
		<th class="lsmallT"><small>Acción</small></th>
	      </tr>
	    </tfoot>
	    <tbody role="alert" aria-live="polite" aria-relevant="all">   
	    <!-- Listado de encuestas definidas -->
	    <?php
	      if ($LISTA_EVALUACION['max_res']>0){
	      for ($i=0;$i<$LISTA_EVALUACION['max_res'];$i++){
	    ?>
	      <tr class="<?php echo $color_tabla; ?>" >
		<!--Periodo del proceso de evaluación-->
		<td class="center lsmallT" nowrap><small><? echo $LISTA_EVALUACION['Proc']['periodo'][$i];?></small></td>
		<!--Fecha inicio-->
		<td class="center lsmallT" nowrap><small><? echo $LISTA_EVALUACION['Proc']['fecha_ini'][$i];?></small></td>  
		<!--Fecha fin-->
		<td class="center lsmallT" nowrap><small><? echo $LISTA_EVALUACION['Proc']['fecha_fin'][$i];?></small></td> 
		<!--Evaluaciones pendientes-->
		<td class="center lsmallT" nowrap><small>
		  <a href="" title="<? echo "".$LISTA_EVALUACION["Proc"]["pendiente"][$i]." de ".$LISTA_EVALUACION["Proc"]["total"][$i]." evaluaciones";?>">
		    <? if ($LISTA_EVALUACION["Proc"]["total"][$i]>0){
		      echo round((($LISTA_EVALUACION["Proc"]["pendiente"][$i]/$LISTA_EVALUACION["Proc"]["total"][$i])*100));
		    } else {
		      echo 0;
		    }
		    ?>%
		  </a></small>
		</td>  
		<!--Evaluaciones en proceso-->
		<td class="center lsmallT" nowrap><small>
		  <a href="" title="<? echo "".$LISTA_EVALUACION["Proc"]["en_proceso"][$i]." de ".$LISTA_EVALUACION["Proc"]["total"][$i]." evaluaciones";?>">
		    <? if ($LISTA_EVALUACION["Proc"]["total"][$i]>0){
		      echo round((($LISTA_EVALUACION["Proc"]["en_proceso"][$i]/$LISTA_EVALUACION["Proc"]["total"][$i])*100));
		    } else {
		      echo 0;
		    }
		    ?>%
		  </a></small>
		</td> 
		<!--Evaluaciones finalizadas-->
		<td class="center lsmallT" nowrap><small>
		  <a href="" title="<? echo "".$LISTA_EVALUACION["Proc"]["finalizada"][$i]." de ".$LISTA_EVALUACION["Proc"]["total"][$i]." evaluaciones";?>">
		    <? if ($LISTA_EVALUACION["Proc"]["total"][$i]>0){
		      echo round((($LISTA_EVALUACION["Proc"]["finalizada"][$i]/$LISTA_EVALUACION["Proc"]["total"][$i])*100));
		    } else {
		      echo 0;
		    }
		    ?>%
		  </a></small>
		</td> 
		<!--Evaluaciones supervisadas-->
		<td class="center lsmallT" nowrap><small>
		  <a href="" title="<? echo "".$LISTA_EVALUACION["Proc"]["supervisada"][$i]." de ".$LISTA_EVALUACION["Proc"]["total"][$i]." evaluaciones";?>">
		    <? if ($LISTA_EVALUACION["Proc"]["total"][$i]>0){
		      echo round((($LISTA_EVALUACION["Proc"]["supervisada"][$i]/$LISTA_EVALUACION["Proc"]["total"][$i])*100));
		    } else {
		      echo 0;
		    }
		    ?>%
		  </a></small>
		</td>    
		<!--Estado del proceso de evaluación-->
		<td class="center lsmallT" nowrap><small>
		  <? if ($LISTA_EVALUACION['Proc']['total'][$i]==0){
		    echo "Por empezar";
		  } else if($LISTA_EVALUACION['Proc']['actual'][$i]=='t'){
		    echo "En proceso";
		  } else {
		    echo "Culminada";
		  }?></small>
		</td>
		<!--Acciones-->
		<td class="center lsmallT" nowrap>
		  <a href="?editar" title="Cambiar fecha fin" >
		    <img src="./img/iconos/edit.png" style="width:20px; margin-left:5px;"></a>
		  <a href="./vEstadisticas?periodo=<?echo $LISTA_EVALUACION['Proc']['id'][$i]; ?>" title="Ver estadísticas" >
		    <img src="./img/iconos/watch.png" style="width:20px; margin-left:5px;"></a>
	      </td>
	      </tr>
	    <? } //cierre del for
	    } //cierre del if  
	    ?>   
	    </tbody>
	  </table>
	  </div>
	<?php
	}//cierra el else (lista no vacia)
	?>
	  <div align="center">
	  <a href="?action=try" class="btn btn-success">Iniciar nuevo proceso</a>	 
	  <br><br><br>
	  </div>
	<?
	}//cierra el else (no add)
	include "vFooter.php";
	?>