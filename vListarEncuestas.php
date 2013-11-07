<?php
    session_start();
    $Legend = "Listar Encuestas";
    include "lib/cListarEncuestas.php";
    include "vHeaderEncuestas.php";
    extract($_GET);

    $all = true;
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
  
  if ($ID_ENCUESTA['max_res']==0){
        echo "<br><br><br><br><br><br><p class='text-center text-info'>Hasta el momento no hay encuestas para el usuario.</p><br><br><br><br><br><br>";
  }else{
  ?>
       
  <div id="demo">
<table align="center" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example" width="100%">
  <thead>
    <tr>
      <th class="lsmallT"><small>Tipo de encuesta</small></th>
      <th class="lsmallT"><small>Persona a evaluar</small></th>
      <th class="lsmallT"><small>Enlace</small></th>
      <th class="lsmallT"><small>Estado</small></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th class="lsmallT"><small>Tipo de encuesta</small></th>
      <th class="lsmallT"><small>Persona a evaluar</small></th>
      <th class="lsmallT"><small>Enlace</small></th>
      <th class="lsmallT"><small>Estado</small></th>
    </tr>
  </tfoot>
          <tbody role="alert" aria-live="polite" aria-relevant="all">
	  
	  <!-- Encuesta de autoevaluación  -->
	  <tr class="<?php echo $color_tabla; ?>">
	    <td class="center lsmallT" nowrap><small>Encuesta de autoevaluación</small></td>     
	    <td class="center lsmallT" nowrap><small><? echo $NOM_USUARIO;?></small></td>
	    <td class="center lsmallT" nowrap><small><a href=<? echo $ID_ENCUESTA['Enc']['enlace'][0];echo"&id=";echo $_GET['id'];?>> Ir a la encuesta</a></small></td>
	    <td class="center lsmallT" nowrap><small>Por realizar</small></td>
          </tr>
          
	  <!-- Encuestas como supervisor -->
          <?php
	    if ($NOMBRE_SUP['max_res']>0){
            for ($i=0;$i<$NOMBRE_SUP['max_res'];$i++){
          ?>
          <tr class="<?php echo $color_tabla; ?>" >
	    <td class="center lsmallT" nowrap><small>Encuesta de supervisor</small></td>     
	    <td class="center lsmallT" nowrap><small><? echo $NOMBRE_SUP['Nom_Sup']['nombre'][$i];echo " ";echo $NOMBRE_SUP['Nom_Sup']['apellido'][$i];?></small></td>
	    <td class="center lsmallT" nowrap><small><a href=<? echo $ENCUESTA_SUP['Enc_Sup']['enlace'][$i];echo"&id=";echo $_GET['id'];?>> Ir a la encuesta</a></small></td>
	    <td class="center lsmallT" nowrap><small>Por realizar</small></td>
          </tr>
          <? } //cierre del for
	     } //cierre del if?>
	     
	  <!-- Encuestas como evaluador -->
          <?php
	    if ($NOMBRE_EVA['max_res']>0){
            for ($i=0;$i<$NOMBRE_EVA['max_res'];$i++){
          ?>
          <tr class="<?php echo $color_tabla; ?>" >
	    <td class="center lsmallT" nowrap><small>Encuesta de evaluador</small></td>     
	    <td class="center lsmallT" nowrap><small><? echo $NOMBRE_EVA['Nom_Eva']['nombre'][$i];echo " ";echo $NOMBRE_EVA['Nom_Eva']['apellido'][$i];?></small></td>
	    <td class="center lsmallT" nowrap><small><a href=<? echo $ENCUESTA_EVA['Enc_Eva']['enlace'][$i];echo"&id=";echo $_GET['id'];?>> Ir a la encuesta</a></small></td>
	    <td class="center lsmallT" nowrap><small>Por realizar</small></td>
          </tr>
          <? } //cierre del for
	     } //cierre del if?>
	     
	  </tbody>
</table>
      </div>
         

  </div>
<?php
}//cierra el else
include "vFooter.php";
?>
