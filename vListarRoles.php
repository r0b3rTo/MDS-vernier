<?php
    session_start();
    $Legend = "Rol";
    include "lib/cVerRol.php";
    include "vHeader.php";
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
    <script type="text/javascript" charset="utf-8">
      $(document).ready( function () {
        $('#example').dataTable( {
          "sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
          "oTableTools": {
            "aButtons": [
              {
                "sExtends": "copy",
                "sButtonText": "Copiar <img src='img/iconos/permiso.png' width='20' height='20' border=0 />"
              },
              {
                "sExtends": "csv",
                "sTitle": "lista_rol",
                "sButtonText": "CSV <img src='img/iconos/csv_hover.png' width='20' height='20' border=0 />"
              },
              {
                "sExtends": "pdf",
                "sTitle": "lista_rol",
                "sButtonText": "PDF <img src='img/iconos/pdf_hover.png' width='20' height='20' border=0 />"
              },
              {
                "sExtends": "print",
                "sButtonText": "Imprimir <img src='img/iconos/print_hover.png' width='20' height='20' border=0 />"
              },
            ]
          }
          } );
      } );
    </script>
        

<!-- Codigo importante -->
<?php

  if ($LISTA_ROL['max_res']==0){
      if(!isAdmin())
        echo "<br><br><br><br><br><br><p class='text-center text-info'>Hasta el momento no hay Rol registrado.</p><br><br><br><br><br><br>";
      else
        echo "<br><br><br><br><br><br><p class='text-center text-info'>Hasta el momento no hay Rol registrado.</p><br><br><br><br><br><br>";
  }else{
  ?> 
<div class="well span11">
    <?
      if (!isAdmin())
        echo "<div align='center'><a href='vRol.php' class='btn btn-info'>Registrar Nuevo</a></div>";
    ?>
    <div class="row">
      <div class="span11">
        <p class="text-center muted lsmallT"><strong style="color:#06F">Sugerencia:</strong> <small> Se le recomienda utilizar el campo de "B&uacute;squeda" y seleccionar 
            sobre las columnas de su preferencia para organizar las entidades en forma ascendente o descendente. Si desea ordenarlo en 
            funci&oacute;n a m&aacute;s de un campo, debe presionar la tecla "SHIFT" y darle a la(s) columnas.</small>
        </p>
      </div>

    </div>
       
          <div id="demo">
<table align="center" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example" width="100%">
  <thead>
    <tr>
      <th class="lsmallT"><small>C&oacute;digo</small></th>
      <th class="lsmallT"><small>Nombre</small></th>
      <th class="lsmallT"><small>Organizaci&oacute;n</small></th>
      <th class="lsmallT"><small>Familia</small></th>
      <th class="lsmallT"><small>Acci&oacute;n</small></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th class="lsmallT"><small>C&oacute;digo</small></th>
      <th class="lsmallT"><small>Nombre</small></th>
      <th class="lsmallT"><small>Organizaci&oacute;n</small></th>
      <th class="lsmallT"><small>Familia</small></th>
      <th class="lsmallT"><small>Acci&oacute;n</small></th>
    </tr>
  </tfoot>
          <tbody role="alert" aria-live="polite" aria-relevant="all">
          <?php
            for ($i=0;$i<$LISTA_ROL['max_res'];$i++){
          ?>
          <tr class="<?php echo $color_tabla; ?>" >
          <td class="center lsmallT" nowrap><small><?php echo $LISTA_ROL['Rol']['codigo'][$i]?></small></td>
          <td class="center lsmallT"><small><a <? echo "href='vRol.php?view&id=".$LISTA_ROL['Rol']['id'][$i]."'" ?>><? echo $LISTA_ROL['Rol']['nombre'][$i]?></a></small></td>
          <td class="center lsmallT"><small><a <? echo "href='vUnidad.php?view&id=".$LISTA_ROL['Rol']['id_org'][$i]."'" ?>><? echo $ORG_ID[$LISTA_ROL['Rol']['id_org'][$i]]?></a></small></td>
          <? if ($LISTA_ROL['Rol']['id_fam'][$i] == 0) {
            echo "<td class='center lsmallT'><small> ".$FAM_ID[$LISTA_ROL['Rol']['id_fam'][$i]]."</small></td>";
          }else {
            echo "<td class='center lsmallT'><small><a href='vFamiliaRol.php?view&id=".$LISTA_ROL['Rol']['id_fam'][$i]."' >".$FAM_ID[$LISTA_ROL['Rol']['id_fam'][$i]]."</a></small></td>";
          }
          ?>
          <td class="center lsmallT" nowrap>
            <?
                echo "<a href='vRol.php?action=edit&id=";
                echo $LISTA_ROL['Rol']['id'][$i]."' rel='tooltip' title='Editar'><img src='img/iconos/edit.gif' width='20' height='20' border=0 /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a href='vRol.php?action=copy&id=";
                echo $LISTA_ROL['Rol']['id'][$i]."' rel='tooltip' title='Copiar'><img src='img/iconos/edit-copy.png' width='20' height='20' border=0 /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a data-toggle='modal' data-data='Sebuah Data' href='#confirm' data-url='lib/cRol.php?action=delete&id=";
                echo $LISTA_ROL['Rol']['id'][$i]."' rel='tooltip' title='Eliminar' onclick='return confirmar()'><img src='img/iconos/eliminar.gif'/></a>&nbsp;&nbsp;&nbsp;"; 
            ?>
          </td>
          </tr>
          <? } ?>     
        </tbody>
</table>
      </div>
         
</div>

<?php
}//cierra el else

include "vFooter.php";
?>
