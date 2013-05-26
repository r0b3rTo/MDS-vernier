<?php
    session_start();
    include "lib/cAutorizacion.php";
    include "vHeader.php";
    $all = true;
?>   

	<script language="javascript" type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery.tools.min.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery.easing.1.3.js"></script>
    
        
  <script type="text/javascript" language="javascript" src="js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
        

  <link href="js/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
  <link href="js/jqueryui/ui/impromptu.css" rel="stylesheet" type="text/css" media="screen" />
    
  <link rel="stylesheet" href="js/jqueryui/themes/redmond/jquery.ui.all.css">
  <link rel="stylesheet" href="js/jqueryui/demos.css">
        

<!-- Codigo importante -->
<?php

  include "lib/cVerCargo.php";

	if ($LISTA_CARG['max_res']==0){
            if(!isAdmin())
                echo "<br><br><br><br><br><br><br><br><div align='center' >Hasta el momento no has realizado ninguna solicitud.</div><br><br><br><br><br><br><br><br>";
            else
                echo "<br><br><br><br><br><br><br><br><div align='center' >Hasta el momento no han realizado ninguna solicitud.</div><br><br><br><br><br><br><br><br>";
			
	}else{
	?>
  <legend>Cargo</legend>  
  <?   
    if (isset($_GET['success'])){
      echo "  <div class='alert alert-success'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                <strong>Registro Exitoso!</strong> Los datos del cargo se borraron con &eacute;xito.
            </div>";
    }
  ?>
  <div class="well" align="center">
    <?
      if (!isAdmin())
        echo "<a href='vCargo.php' class='btn btn-info'>Registrar Nuevo</a><br><br>";
    ?>
    <div class="row">
      <div class="span1"></div>
      <div class="span11">
        <p class="text-center"><strong style="color:#06F">Sugerencia:</strong> <small>Se le recomienda utilizar el campo de "B&uacute;squeda" y seleccionar 
            sobre las columnas de su preferencia para organizar los Estudiantes en forma ascendente o descendente. Si desea ordenarlo en 
            funci&oacute;n a m&aacute;s de un campo, debe presionar la tecla "SHIFT" y darle a la(s) columnas.</small>
        </p>
      </div>

    </div>
       
      <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
          $('#example').dataTable({
            "sPaginationType": "full_numbers",
            "bJQueryUI": true,
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 15, 25, 50, 100 , -1], [10, 15, 25, 50, 100, "Todos"]],
            "bAutoWidth": true
          });
        });                        
      </script>  
      <!--@import "scripts/themes/smoothness/jquery-ui-1.8.4.custom.css";-->
      <style type="text/css" title="currentStyle">
      			@import "js/demo_page.css";
      			@import "js/demo_table.css";
            @import "js/jqueryui/themes/redmond/jquery-ui-1.8.20.custom.css";
      </style>
      <div class="datagrid">
      <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
      	<thead>
      		<tr>
            <th>C&oacute;digo</th>
            <th>Nombre</th>
      			<th>Organizaci&oacute;n</th>
            <th>Familia</th>
      			<th>Acci&oacute;n</th>
      		</tr>
      	</thead>
      	<tbody role="alert" aria-live="polite" aria-relevant="all">
          <?php
            for ($i=0;$i<$LISTA_CARG['max_res'];$i++){
          ?>
          <tr class="<?php echo $color_tabla; ?>" >
          <td class="center" nowrap><strong><?php echo $LISTA_CARG['Carg']['codigo'][$i]?></strong></td>
          <td class="center"><a <? echo "href='vCargo.php?view&id=".$LISTA_CARG['Carg']['id'][$i]."'" ?>><? echo $LISTA_CARG['Carg']['nombre'][$i]?></a></td>
          <td class="center"><a <? echo "href='vOrganizacion.php?view&id=".$LISTA_CARG['Carg']['id_org'][$i]."'" ?>><? echo $ORG_ID[$LISTA_CARG['Carg']['id_org'][$i]]?></a></td>
      		<td class="center"><a <? echo "href='vFamiliaCargo.php?view&id=".$LISTA_CARG['Carg']['id_fam'][$i]."'" ?>><? echo $FAM_ID[$LISTA_CARG['Carg']['id_fam'][$i]]?></a></td>
      		<td class="center" nowrap>
            <?
                echo "<a href='vCargo.php?view&id=";
                echo $LISTA_CARG['Carg']['id'][$i]."' rel='tooltip' title='Detalles'><img src='img/iconos/edit-find.png' width='30' height='30' border=0 /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a href='vCargo.php?action=edit&id=";
                echo $LISTA_CARG['Carg']['id'][$i]."' rel='tooltip' title='Editar'><img src='img/iconos/edit-find-replace.png' width='30' height='30' border=0 /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a href='vCargo.php?action=copy&id=";
                echo $LISTA_CARG['Carg']['id'][$i]."' rel='tooltip' title='Copiar'><img src='img/iconos/edit-copy.png' width='30' height='30' border=0 /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a data-toggle='modal' data-data='Sebuah Data' href='#confirm' data-url='lib/cCargo.php?action=delete&id=";
                echo $LISTA_CARG['Carg']['id'][$i]."' rel='tooltip' title='Eliminar' onclick='return confirmar()'><img src='img/iconos/mail-mark-not-junk.png' width='30' height='30' border=0 /></a>&nbsp;&nbsp;&nbsp;"; 
            ?>
          </td>
      		</tr>
          <? } ?>     
      	</tbody>
      	<tfoot>
      		<tr>
            <th>C&oacute;digo</th>
            <th>Nombre</th>
            <th>Organizaci&oacute;n</th>
            <th>Familia</th>
            <th>Acci&oacute;n</th>
      		</tr>
      	</tfoot>
      </table>
         
      </div>
</div>
<?php
}//cierra el else

include "vFooter.php";
?>
