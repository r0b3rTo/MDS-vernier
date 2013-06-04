<?
    session_start();
include "cListarEmpresas.php";
include_once("vHeader.php");
?>

<!-- Datatables -->
<script type="text/javascript" language="javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.tools.min.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
<!-- Tabletools -->
<script type="text/javascript" charset="utf-8" src="js/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="js/TableTools.js"></script>

<script>
function verificarEliminacion(empresa){
  if(confirm("¿Esta seguro de eliminar a la empresa seleccionada?")){
    location.href = "cEliminarEmpresa.php?id=".concat(empresa);
  }
}
</script>

<table width="502" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" class="parrafo" align="center">
	<span class="titular_negro"><br /> 

<? echo "EMPRESAS"; ?>

</span><br />
	<br /><br />

 <style>
.fdg_sortable {cursor:pointer;text-decoration:underline;color:#00f}
.alterRow {background-color:#dfdfdf}
</style>
	
        <style type="text/css" title="currentStyle">
			      @import "css/demo_page.css";
			      @import "css/demo_table.css";
            @import "js/jqueryui2/themes/redmond/jquery-ui-1.8.20.custom.css";
            @import "css/TableTools.css";
		</style>        
		
		<script type="text/javascript" charset="utf-8">
                var oTable = $(document).ready(function() { 
                                 $('#example').dataTable({
                                     "sPaginationType": "full_numbers",
                                     "bJQueryUI": true,
                                     "iDisplayLength": 10,
                                     "aLengthMenu": [[10, 15, 25, 50, 100 , -1], [10, 15, 25, 50, 100, "Todos"]],
                                     "bAutoWidth": true,
                                     "sDom": '<"H"Tfr>t<"F"ip>',
                                     "oTableTools": {
                                       "aButtons": [
                                         {
                                           "sExtends": "copy",
                                           "sButtonText": "Copiar"
                                         },
                                         {
                                           "sExtends": "csv",
                                           "sCharSet": "utf8",
                                           "sTitle": "lista_empresas",
                                           "mColumns": [1,2,3,4,5,6,7,8],
                                           "bSelectedOnly": true
                                         },
                                         {
                                           "sExtends": "xls",
                                           "sCharSet": "utf8",
                                           "sTitle": "lista_empresas",
                                           "sFileName": "*.xls",
                                           "mColumns": [1,2,3,4,5,6,7,8],
                                           "bSelectedOnly": true
                                         },
                                         {
                                           "sExtends": "pdf",
                                           "sCharSet": "utf8",
                                           "sTitle": "lista_empresas",
                                           "mColumns": [0,1,2,3,4,5,6,7,8],
                                           "bSelectedOnly": true
                                         },
                                         {
                                           "sExtends": "print",
                                           "sButtonText": "Imprimir",
                                           "bSelectedOnly": true 
                                         },
                                         {
                                           "sExtends": "select_all",
                                           "sButtonText": "Seleccionar todo" 
                                         },
                                         {
                                           "sExtends": "select_none",
                                           "sButtonText": "Anular selecciones" 
                                         }
                                       ],
                                     "sRowSelect": "multi"
                                     }
                                });
		           });                        
                </script>
		  <table width="100%" border="0" cellpadding="5" class="display" cellspacing="0" id="example">
		  <thead>
			<tr>
				<th></th>
            <th>C&oacute;digo</th>
            <th>Nombre</th>
            <th>Superior</th>
            <th>Acci&oacute;n</th>
			</tr>
			</thead>
			<tbody role="alert" aria-live="polite" aria-relevant="all">
		<?
			for ($i=0;$i<$LISTA_ORG['max_res'];$i++){
				$j=$i+1;
		?>
            
				<tr class="<?php echo $color_tabla; ?>">
				  <td><?=$j?></td>
          <td class="center" nowrap><strong><?php echo $LISTA_ORG['Org']['codigo'][$i]?></strong></td>
          <td class="center"><a <? echo "href='vOrganizacion.php?view&id=".$LISTA_ORG['Org']['id'][$i]."'" ?>><? echo $LISTA_ORG['Org']['nombre'][$i]?></a></td>
          <td class="center"><a <? echo "href='vOrganizacion.php?view&id=".$LISTA_ORG['Org']['idsup'][$i]."'" ?>><? echo $ORG_ID[$LISTA_ORG['Org']['idsup'][$i]]?></a></td>
          <td class="center" nowrap>
            <?
                echo "<a href='vOrganizacion.php?view&id=";
                echo $LISTA_ORG['Org']['id'][$i]."' rel='tooltip' title='Detalles'><img src='img/iconos/ver.png' /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a href='vOrganizacion.php?action=edit&id=";
                echo $LISTA_ORG['Org']['id'][$i]."' rel='tooltip' title='Editar'><img src='img/iconos/edit.gif' /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a href='vOrganizacion.php?action=copy&id=";
                echo $LISTA_ORG['Org']['id'][$i]."' rel='tooltip' title='Copiar'><img src='img/iconos/edit-copy.png' /></a> &nbsp;&nbsp;&nbsp;"; 
                echo "<a data-toggle='modal' data-data='Sebuah Data' href='#confirm' data-url='lib/cOrganizacion.php?action=delete&id=";
                echo $LISTA_ORG['Org']['id'][$i]."' rel='tooltip' title='Eliminar' onclick='return confirmar()'><img src='img/iconos/eliminar.gif' ></a>&nbsp;&nbsp;&nbsp;"; 
            ?>
          </td>
                                  
				</tr>
			<? } ?>
			</tbody>
			<tfoot>
      <tr>
        <th></th>
            <th>C&oacute;digo</th>
            <th>Nombre</th>
            <th>Superior</th>
            <th>Acci&oacute;n</th>
      </tr>
	</tfoot>
		   </table>
	  <? ?>  </td>
  </tr>
</table>
<?php include_once('vFooter.php'); ?>
