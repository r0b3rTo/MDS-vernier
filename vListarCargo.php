<?php
    session_start();
    include "lib/cVerCargo.php";
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
                "sTitle": "lista_cargo",
                "sButtonText": "CSV <img src='img/iconos/csv_hover.png' width='20' height='20' border=0 />"
              },
              {
                "sExtends": "pdf",
                "sTitle": "lista_cargo",
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
                <strong>Registro Exitoso!</strong> Los datos del rol se borraron con &eacute;xito.
            </div>";
    }
  ?>
  <div class="well span9 offset1" align="center">
    <?
      if (!isAdmin())
        echo "<a href='vCargo.php' class='btn btn-info'>Registrar Nuevo</a><br><br>";
    ?>
    <div class="row">
      <div class="span9">
        <p class="text-center"><strong style="color:#06F">Sugerencia:</strong> <small>Se le recomienda utilizar el campo de "B&uacute;squeda" y seleccionar 
            sobre las columnas de su preferencia para organizar las entidades en forma ascendente o descendente. Si desea ordenarlo en 
            funci&oacute;n a m&aacute;s de un campo, debe presionar la tecla "SHIFT" y darle a la(s) columnas.</small>
        </p>
      </div>

    </div>
       
          <div id="demo">
<table align="center" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example" width="100%">
  <thead>
    <tr>
      <th>C&oacute;digo</th>
      <th>Nombre</th>
      <th>Organizaci&oacute;n</th>
      <th>Familia</th>
      <th>Acci&oacute;n</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th>C&oacute;digo</th>
      <th>Nombre</th>
      <th>Organizaci&oacute;n</th>
      <th>Familia</th>
      <th>Acci&oacute;n</th>
    </tr>
  </tfoot>
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
                echo "<a href='vCargo.php?action=edit&id=";
                echo $LISTA_CARG['Carg']['id'][$i]."' rel='tooltip' title='Editar'><img src='img/iconos/edit.gif' width='20' height='20' border=0 /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a href='vCargo.php?action=copy&id=";
                echo $LISTA_CARG['Carg']['id'][$i]."' rel='tooltip' title='Copiar'><img src='img/iconos/edit-copy.png' width='20' height='20' border=0 /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a data-toggle='modal' data-data='Sebuah Data' href='#confirm' data-url='lib/cCargo.php?action=delete&id=";
                echo $LISTA_CARG['Carg']['id'][$i]."' rel='tooltip' title='Eliminar' onclick='return confirmar()'><img src='img/iconos/eliminar.gif'/></a>&nbsp;&nbsp;&nbsp;"; 
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
