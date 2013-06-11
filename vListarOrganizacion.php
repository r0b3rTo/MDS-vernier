<?php
    session_start();
    $Legend = "Organización";
    include "lib/cVerOrganizacion.php";
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
                "sTitle": "lista_empresas",
                "sButtonText": "CSV <img src='img/iconos/csv_hover.png' width='20' height='20' border=0 />"
              },
              {
                "sExtends": "pdf",
                "sTitle": "lista_empresas",
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

  if ($LISTA_ORG['max_res']==0){
            if(!isAdmin())
                echo "<br><br><br><br><br><br><br><br><div align='center' >Hasta el momento no has realizado ninguna solicitud.</div><br><br><br><br><br><br><br><br>";
            else
                echo "<br><br><br><br><br><br><br><br><div align='center' >Hasta el momento no han realizado ninguna solicitud.</div><br><br><br><br><br><br><br><br>";
      
  }else{
  ?>
  <div class="well span9 offset1" align="center">
    <?
      if (!isAdmin())
        echo "<a href='vOrganizacion.php' class='btn btn-info'>Registrar Nueva</a><br><br>";
    ?>
    <div class="row">
      <div class="span9">
        <p class="text-center muted lsmall"><strong style="color:#06F">Sugerencia:</strong> <small>Se le recomienda utilizar el campo de "B&uacute;squeda" y seleccionar 
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
      <th class="lsmallT"><small>Superior</small></th>
      <th class="lsmallT"><small>Acci&oacute;n</small></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th class="lsmallT"><small>C&oacute;digo</small></th>
      <th class="lsmallT"><small>Nombre</small></th>
      <th class="lsmallT"><small>Superior</small></th>
      <th class="lsmallT"><small>Acci&oacute;n</small></th>
    </tr>
  </tfoot>
          <tbody role="alert" aria-live="polite" aria-relevant="all">
          <?php
            for ($i=0;$i<$LISTA_ORG['max_res'];$i++){
          ?>
          <tr class="<?php echo $color_tabla; ?>" >
          <td class="center lsmallT" nowrap><small><?php echo $LISTA_ORG['Org']['codigo'][$i]?></small></td>
          <td class="center lsmallT"><small><a <? echo "href='vOrganizacion.php?view&id=".$LISTA_ORG['Org']['id'][$i]."'" ?>><? echo $LISTA_ORG['Org']['nombre'][$i]?></a></small></td>
          <td class="center lsmallT"><small><a <? echo "href='vOrganizacion.php?view&id=".$LISTA_ORG['Org']['idsup'][$i]."'" ?>><? echo $ORG_ID[$LISTA_ORG['Org']['idsup'][$i]]?></a></small></td>
          <td class="center lsmallT" nowrap>
            <?
                echo "<a href='vOrganizacion.php?action=edit&id=";
                echo $LISTA_ORG['Org']['id'][$i]."' rel='tooltip' title='Editar'><img src='img/iconos/edit.gif' width='20' height='20' border=0 /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a href='vOrganizacion.php?action=copy&id=";
                echo $LISTA_ORG['Org']['id'][$i]."' rel='tooltip' title='Copiar'><img src='img/iconos/edit-copy.png' width='20' height='20' border=0 /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a data-toggle='modal' data-data='Sebuah Data' href='#confirm' data-url='lib/cOrganizacion.php?action=delete&id=";
                echo $LISTA_ORG['Org']['id'][$i]."' rel='tooltip' title='Eliminar' onclick='return confirmar()'><img src='img/iconos/eliminar.gif'/></a>&nbsp;&nbsp;&nbsp;"; 
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
