<?php
    session_start();
    include "lib/cNotificaciones.php";
    $Legend = "Notificaciones del sistema";
    include "vHeader.php";
    extract($_GET);
    extract($_POST);
    $all = true;
    date_default_timezone_set('America/Caracas');
?>  

  <br><br>
    <? if ($LISTA_NOTIFICACIONES['max_res']){?>
      <div align="center">
	
	        <!--Tabla de notificaciones-->
		<table class="table table-hover" style="margin-left: 0; max-width: 900px;">
		  <thead>
		    <tr>
		      <th class="lsmallT"><small>Notificación</small></th>
		      <th class="lsmallT"><small>Mensaje</small></th>
		      <th class="lsmallT"><small>Acción</small></th>
		    </tr>
		  </thead>
		  
		  <tbody role="alert" aria-live="polite" aria-relevant="all">   
		  <?php
		    for ($i=0;$i<$LISTA_NOTIFICACIONES['max_res'];$i++){
		  ?>
		    <tr class="<?php echo $color_tabla; ?>" >
		      <!--Notificación-->
		      <td class="center lsmallT" ><small><? echo $LISTA_NOTIFICACIONES['Not']['notificacion'][$i];?></small></td>  
		      <!--Mensaje-->
		      <td class="center lsmallT" ><small><? echo $LISTA_NOTIFICACIONES['Not']['mensaje'][$i];?></small></td>   
		      <!--Acción-->
		      <td class="center lsmallT" nowrap>
			<a href='./vResultados.php?token_ls=<? echo $LISTA_NOTIFICACIONES['Not']['token_ls_per'][$i];?>' title='Revisar evaluación' ><img src='./img/iconos/visible-16.png' style='margin-left:5px;'></a>
			<a href='./lib/cNotificaciones.php?action=check&id=<?echo $LISTA_NOTIFICACIONES['Not']['id'][$i];?>' title='Marcar como leída'><img src='./img/iconos/check-16.png' style='margin-left:5px;'></a>
		    </td>
		    </tr>
		  <? } //cierre del for
		  ?>   
		  </tbody>
		</table>
		<!--Fin de la tabla de notificaciones-->
		
      </div><!--Fin del contenedor-->

    <? } else {}?>
    

   
<?
include "vFooter.php";
?>
