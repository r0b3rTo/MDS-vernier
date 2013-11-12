<?php
    session_start();
    $Legend = "Administrar Encuestas";
    include "lib/cEncuestas.php";
    include "vHeaderEncuestas.php";
    extract($_GET);
    $all = true;
    //require_once './jsonrpcphp/includes/jsonRPCClient.php';
?>   

  <style type="text/css">
      @import "css/bootstrap.css";
      @import "css/dataTables.bootstrap.css";
    </style>

    <script type="text/javascript" charset="utf-8" src="js/DataTable/js/jquery.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/DataTable/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/DataTools/js/ZeroClipboard.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/DataTools/js/TableTools.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/dataTables.bootstrap.js"></script>

<!-- Codigo importante -->
<?php
  
  if ($LISTA_ENCUESTA['max_res']==0){
        echo "<br><br><br><br><br><br><p class='text-center text-info'>Hasta el momento no hay encuestas en el sistema.</p><br><br><br><br><br><br>";
  }else{
  ?>
       
  <div id="demo">
<table align="center" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example" width="100%">
  <thead>
    <tr>
      <th class="lsmallT"><small>Cargo evaluado por la encuesta</small></th>
      <th class="lsmallT"><small>Estado</small></th>
      <th class="lsmallT"><small>Fecha inicio</small></th>
      <th class="lsmallT"><small>Fecha fin</small></th>
      <th class="lsmallT"><small>Acción</small></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th class="lsmallT"><small>Cargo evaluado por la encuesta</small></th>
      <th class="lsmallT"><small>Estado</small></th>
      <th class="lsmallT"><small>Fecha inicio</small></th>
      <th class="lsmallT"><small>Fecha fin</small></th>
      <th class="lsmallT"><small>Acción</small></th>
    </tr>
  </tfoot>
          <tbody role="alert" aria-live="polite" aria-relevant="all">
	     
	  <!-- Listado de encuestas definidas -->
          <?php
	    if ($LISTA_ENCUESTA['max_res']>0){
            for ($i=0;$i<$LISTA_ENCUESTA['max_res'];$i++){
          ?>
          <tr class="<?php echo $color_tabla; ?>" >
	    <td class="center lsmallT" nowrap><small> <? echo $LISTA_CARGOS['Car']['nombre'][$i];?></small></td>     
	    <td class="center lsmallT" nowrap><small><? if (($LISTA_ENCUESTA['Enc']['estado'][$i])=='f') { echo "Encuesta inactiva"; } else { echo "Encuesta activa";}?></small></td>
	    <td class="center lsmallT" nowrap><small><? if ($LISTA_ENCUESTA['Enc']['fecha_ini'][$i]==NULL) { echo "Sin asignar";} else { echo $LISTA_ENCUESTA['Enc']['fecha_ini'][$i];}?></small></td>
	    <td class="center lsmallT" nowrap><small><? if ($LISTA_ENCUESTA['Enc']['fecha_fin'][$i]==NULL) { echo "Sin asignar";} else { echo $LISTA_ENCUESTA['Enc']['fecha_fin'][$i];}?></small></td>
	    <td class="center lsmallT" nowrap><small><? 
	      if (($LISTA_ENCUESTA['Enc']['estado'][$i])=='f') {
		echo '<a href="lib/cEncuestas?action=activar&id='; echo $LISTA_ENCUESTA['Enc']['id_encuesta_ls'][$i];echo '" title="Activar encuesta"><img src="./img/iconos/icon-off.png"></a>';
		} else {
		echo '<a href="lib/cEncuestas?action=desactivar&id='; echo $LISTA_ENCUESTA['Enc']['id_encuesta_ls'][$i];echo '" title="Desactivar encuesta"><img src="./img/iconos/icon-on.png"></a>';
		}
		echo '<a href="/vResumenEncuesta.php&id='; echo $LISTA_ENCUESTA['Enc']['id_encuesta_ls'][$i];echo '" title="Ver estadísticas"><img src="./img/iconos/watch.png" style="margin-left:4px;"></a>';
		?></small></td>
		

          </tr>

          <? } //cierre del for
	     } //cierre del if  
	  ?>
	     
	  </tbody>
	  
	  
	  
</table>
</div>

  </div>
<?php
}//cierra el else
include "vFooter.php";
?>
