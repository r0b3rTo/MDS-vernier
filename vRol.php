<?php
    session_start();
    $Legend = "Datos de rol";
    include "lib/cVerRol.php";
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
                sup: "required",
                cod: "required",
                desc: "required",
                obs: "required",
                clave: "required"                
            },
            messages: {
                name:"Campo Requerido.",
                sup: "Campo Requerido.",
                cod: "Campo Requerido.",
                desc: "Campo Requerido.",
                obs: "Campo Requerido."
            },

            errorClass: "help-inline"

        });
        <? if (isset($_GET['id'])){
            echo "$('.org-sel').select2('val', '".$LISTA_ROL['Rol']['id_org']['0']."');";
            echo "$('.fam-sel').select2('val', '".$LISTA_ROL['Rol']['id_fam']['0']."');";
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
    <div class="well" align="center">
        <form id="newOrg" class="form-horizontal" method="post" 
            <?  if (isset($_GET['action']) && $_GET['action']=='edit') echo 'action="lib/cRol.php?action=edit&id='.$_GET['id'].'"'; 
                else echo 'action="lib/cRol.php?action=add"' ?> >
            <div class="row">
            <div class="span2"></div>
            <div class="span4">
            <div class="control-group">
                <label class="control-label">Nombre del Rol</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-briefcase"></i></span>
                        <input type="text" class="input-xlarge" id="name" name="name" value="<? if(isset($_GET['id'])) echo $LISTA_ROL['Rol']['nombre']['0'];?>" placeholder="Rol" <? if (isset($_GET['view'])) echo 'disabled' ?>>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Rol</label>
                <div class="controls">
                    <select style="width:200px" id="org" name="org" class="select2 show-tick org-sel" data-size="auto" <? if (isset($_GET['view'])) echo 'disabled' ?>>
                        <?
                            while (list($key, $val) = each($ORG_ID)){
                                echo "<option value=".$key.">".$val."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Familia de Rol</label>
                <div class="controls">
                    <select style="width:200px" id="fam" name="fam" class="select2 show-tick fam-sel" data-size="auto" <? if (isset($_GET['view'])) echo 'disabled' ?>>
                        <?
                            while (list($key, $val) = each($FAM_ID)){
                                echo "<option value=".$key.">".$val."</option>";
                            }
                        ?>
                    </select>

                </div>
            </div>
            <div class="control-group ">
                <label class="control-label">C&oacute;digo</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-th-list"></i></span>
                        <input type="text" class="input-xlarge" id="cod" name="cod" value="<? if(isset($_GET['id'])) echo $LISTA_ROL['Rol']['codigo']['0'];?>" placeholder="C&oacute;digo" <? if (isset($_GET['view'])) echo 'disabled' ?>>
                    </div>
                </div>
            </div>
            <div class="control-group ">
                <label class="control-label">Clave</label>
                <div class="controls">
                        <div class="btn-group" data-toggle-name="clav" data-toggle="buttons-radio" >
                            <button type="button" value="t" class="btn <? if (isset($_GET['view']) & ($LISTA_ROL['Rol']['clave']['0']=='f')) echo 'disabled' ?>" data-toggle="button">Si</button>
                            <button type="button" value="f" class="btn <? if (isset($_GET['view']) & ($LISTA_ROL['Rol']['clave']['0']=='t')) echo 'disabled' ?>" data-toggle="button">No</button>
                        </div>
                        <input type="hidden" id="clav" name="clav" value="<? if(isset($_GET['id'])) echo $LISTA_ROL['Rol']['clave']['0']; else echo "f"?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Descripci&oacute;n</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-edit"></i></span>
                        <textarea class="input-xlarge" rows="3" id="desc" name="desc" placeholder="Descripci&oacute;n" <? if (isset($_GET['view'])) echo 'disabled' ?>><? if(isset($_GET['id'])) echo $LISTA_ROL['Rol']['descripcion']['0'];?></textarea>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Funci&oacute;n</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-edit"></i></span>
                        <textarea class="input-xlarge" rows="3" id="obs" name="obs" placeholder="Funciones"<? if (isset($_GET['view'])) echo 'disabled' ?>><? if(isset($_GET['id'])) echo $LISTA_ROL['Rol']['funciones']['0'];?></textarea>
                    </div>
                </div>
            </div>

            <div class="control-group">
                    <div class="row">
                    <div class="span5"></div>
                    <div class="span6">
                    <p>
                    <a class="btn btn-info" href="vListarRol.php">Listar Roles</a>
                    <?  if (isset($_GET['view'])) 
                            echo '<a href="?action=edit&id='.$_GET['id'].'" class="btn btn-warning">Editar</a>' ;
                        else 
                            echo '<input class="btn" type="reset" value="Borrar">
                                  <button type="submit" id="confirmButton" class="btn btn-success" >Registrar</button>';
                    ?>
                    </p>
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
