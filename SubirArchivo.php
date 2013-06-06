<?php
    session_start();
    if(isset($_GET['id']))
        include "lib/cVerPersona.php";
    else
        include "lib/cAutorizacion.php";
    include "vHeader.php";
    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/Caracas');
?>  
<script type="text/javascript">
    $(document).ready(function(){
        $("#newOrg").validate({
            submitHandler : function(form) {
                bootbox.dialog('Esta Seguro de continuar?', [{
                         'label':'No',
                         'class':'btn'
                        },
                        {
                         'label':'Si',
                         'class':'btn',
                         'callback':function() {
                                return form.submit();
                         }
                        }]);
            },
            rules:{
                name:"required",
                lname:"required",
                ced: "required",
                tel: "required",
                dir: "required",
                email:{
                    required:true,
                    email: true
                },              
            },
            messages: {
                name:"Campo Requerido.",
                lname:"Campo Requerido.",
                ced: "Campo Requerido.",
                tel: "Campo Requerido.",
                dir: "Campo Requerido.",
                email:{
                    required:"Campo Requerido",
                    email: "Formato de email incorrecto"
                },   
            },

            errorClass: "help-inline"

        });
        <? if (isset($_GET['id'])){
            //echo "$('.org-sel').selectpicker('val', '".$LISTA_ROL['Rol']['id_org']['0']."');";
            //echo "$('.fam-sel').selectpicker('val', '".$LISTA_ROL['Rol']['id_fam']['0']."');";
        }
        ?>
    });
</script>
<script>
$(function() {
    $('div.btn-group[data-toggle-name]').each(function() {
        var group = $(this);
        var form = group.parents('form').eq(0);
        var name = group.attr('data-toggle-name');
        var hidden = $('input[name="' + name + '"]', form);
        $('button', group).each(function() {
            var button = $(this);
            button.live('click', function() {
                hidden.val($(this).val());
            });
            if (button.val() == hidden.val()) {
                button.addClass('active');
            }
        });
    });
});
   </script>

  
    <legend>Cargar datos</legend>
<?   
    if (isset($_GET['success'])){
    echo "  <div class='alert alert-success'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                <strong>Registro Exitoso!</strong> Los datos se guardaron con &eacute;xito.
            </div>";
    }else if (isset($_GET['error'])) {
            echo "  <div class='alert alert-error'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                <strong>Hubo una falla en el Registro!</strong> Verifique que el archivo es correcto.
            </div>";
    }
?>
    <div class="well" align="center">
        <form method="post" enctype="multipart/form-data" id="newOrg" class="form-horizontal" action="lib/subir.php">
            <div class="row">
            <div class="span2"></div>
            <div class="span4">
            
            <div class="control-group">
                <label class="control-label">Base de Datos</label>
                <div class="controls">
                    
                <label class="radio">
                    <input type="radio" name="BD" id="optionsRadios1" value="Per" checked>
                        Persona
                </label>
                <label class="radio">
                    <input type="radio" name="BD" id="optionsRadios2" value="Org">
                        Organizaci&oacute;n
                </label>
                <label class="radio">
                    <input type="radio" name="BD" id="optionsRadios2" value="Car">
                        Cargo
                </label>
                <label class="radio">
                    <input type="radio" name="BD" id="optionsRadios2" value="Rol">
                        Rol
                </label>

                </div>
            </div>

            <div class="control-group ">
                <label class="control-label">Archivo CSV <img src='img/iconos/csv_hover.png' width='20' height='20' border=0 /></label>
                <div class="controls">
                    <input title="Seleccione el Archivo" type="file" class="input-xlarge" name="file" id="file">
                </div>
            </div>

            <div class="control-group">
                <div class="row">
                <div class="span5"></div>
                <div class="span6">
 
                <button type="submit" id="confirmButton" class="btn btn-success" >Subir</button>

                </div>
                </div>
            </div>
            </div>
            </div>
        </form>
    </div>


<?php
include "vFooter.php";
?>
