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
  
  if ($LISTA_ENCUESTA['max_res']==0){
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

	  <!-- Encuestas del usuario -->
          <?php
	    if ($LISTA_ENCUESTA['max_res']>0){
            for ($i=0;$i<$LISTA_ENCUESTA['max_res'];$i++){
          ?>
          <tr class="<?php echo $color_tabla; ?>" >
	    <td class="center lsmallT" nowrap><small><? 
	      if ($LISTA_ENCUESTA['Enc']['tipo'][$i]=="autoevaluacion") echo 'Encuesta de autoevaluación';
	      if ($LISTA_ENCUESTA['Enc']['tipo'][$i]=="supervisor") echo 'Encuesta como supervisor';
	      if ($LISTA_ENCUESTA['Enc']['tipo'][$i]=="evaluador") echo 'Encuesta como evaluador';
	    ?></small></td>     
	    <td class="center lsmallT" nowrap><small><? 
	      echo $LISTA_ENCUESTA['Enc']['nombre'][$i];echo " ";echo $LISTA_ENCUESTA['Enc']['apellido'][$i];
	    ?></small></td>
	    <td class="center lsmallT" nowrap><small><a href=<? 
	      echo"http://localhost/limesurvey/index.php?token=";echo  $LISTA_ENCUESTA['Enc']['token_ls'][$i]; echo "&sid=";echo $LISTA_ENCUESTA['Enc']['id_encuesta_ls'][$i];echo"&lang=es";?>> Ir a la encuesta</a></small></td>
	    <td class="center lsmallT" nowrap><small><?
	    echo $LISTA_ENCUESTA['Enc']['estado'][$i];
	    ?></small></td>
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
