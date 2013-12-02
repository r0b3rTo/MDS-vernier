<?php
    session_start();
    $Legend = "Administrar Encuestas de Limesurvey";
    include "lib/cEncuestas.php";
    include "vHeader.php";
    extract($_GET);
    $all = true;
?>   

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
      <th class="lsmallT"><small>Acción</small></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th class="lsmallT"><small>Cargo evaluado por la encuesta</small></th>
      <th class="lsmallT"><small>Estado</small></th>
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
    <td class="center lsmallT" nowrap><small><? 
      if (($LISTA_ENCUESTA['Enc']['estado'][$i])=='f') {
	echo '<a href="lib/cEncuestas?action=modificar&id='; echo $LISTA_ENCUESTA['Enc']['id_encuesta_ls'][$i];echo '" title="Editar pesos de la encuesta"> <img src="./img/iconos/edit-16.png" style="margin-left:5px;"></a></a>';
	echo '<a href="lib/cEncuestas?action=eliminar&id='; echo $LISTA_ENCUESTA['Enc']['id_encuesta_ls'][$i];echo '" title="Eliminar encuesta"><img src="./img/iconos/delete-16.png" style="margin-left:7px;"></a>';
	} else {
	echo 'No hay acciones disponibles';
	}
	?></small></td>
  </tr>

  <? } //cierre del for
      } //cierre del if  
  ?>
      
  </tbody>
	  
</table>
</div>

<?php
}//cierra el else
include "vFooter.php";
?>
