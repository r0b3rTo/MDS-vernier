<?php
    session_start();
    $Legend = "Datos de persona";
    include "lib/cVerPersona.php";
    include "vHeader.php";
    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/Caracas');
?>  
<script type="text/javascript">
    $(document).ready(function(){
        $("#newPer").validate({
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

        $("#newSup").validate({
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

        $("#newCar").validate({
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

        <?  if (isset($_GET['id'])){
                echo "$('.org-sel').select2('val', '".$LISTA_PER['Per']['unidad']['0']."');";
            }

            if (isset($LISTA_PER_CAR) && $LISTA_PER_CAR['max_res']>0){
                echo "$('.car-sel').select2('val', '".$LISTA_PER_CAR['Per_Car']['id_car']['0']."');";
            }

            if (isset($LISTA_PER_EVA) && $LISTA_PER_EVA['max_res']>0){
                echo "$('.eva-sel').select2('val', '".$LISTA_PER_EVA['Per_Eva']['id_eva']['0']."');";
            }

            if (isset($LISTA_PER_SUP) && $LISTA_PER_SUP['max_res']>0){
                echo "$('.sup-sel').select2('val', '".$LISTA_PER_SUP['Per_Sup']['id_sup']['0']."');";
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
<div class="tabbable"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Persona</a></li>
    <li><a href="#tab2" data-toggle="tab">Cargo</a></li>
    <li><a href="#tab3" data-toggle="tab">Evaluador</a></li>
    <li><a href="#tab4" data-toggle="tab">Supervisor Jerárquico</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
        <!-- Formulario primera pestaña, Crear Persona-->
      <div class="well" align="center">
        <form id="newPer" class="form-horizontal" method="post" 
            <?  if (isset($_GET['action']) && $_GET['action']=='edit') echo 'action="lib/cPersona.php?action=edit&id='.$_GET['id'].'"'; 
                else echo 'action="lib/cPersona.php?action=add"' ?> >
            <input type="hidden" id="id" name="id" value="<? if (isset($_GET['id'])) echo $_GET['id']; ?>"/>
            <div class="row">
            <div class="span2"></div>
            <div class="span4">
            <div class="control-group">
                <label class="control-label">Nombre</label>
                <div class="controls">
                    <div class='input-prepend'>
                        <span class='add-on'><i class='icon-user's></i></span>
                        <input required type="text" class="input-xlarge" id="name" name="name" value="<? if(isset($_GET['id'])) echo $LISTA_PER['Per']['nombre']['0']; ?>" placeholder="Nombre" <? if (isset($_GET['view'])) echo 'disabled' ?>>
                    </div>
                
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Apellido</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-user"></i></span>
                        <input  required  type="text" class="input-xlarge" id="lname" name="lname" value="<? if(isset($_GET['id'])) echo $LISTA_PER['Per']['apellido']['0'];?>" placeholder="Apellido" <? if (isset($_GET['view'])) echo 'disabled' ?>>
                    </div>
                </div>
            </div>
            <div class="control-group ">
                <label class="control-label">C&eacute;dula</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-th-list"></i></span>
                        <input  required  id="ced" name="ced" type="text" class="input-xlarge bfh-phone" data-format="dddddddd" data-number="<? if(isset($_GET['id'])) echo $LISTA_PER['Per']['cedula']['0'];?>" placeholder="12345678" <? if (isset($_GET['view'])) echo 'disabled'; ?>>
                    </div>
                </div>
            </div>
            <div class="control-group ">
                <label class="control-label">G&eacute;nero</label>
                <div class="controls">
                        <div class="btn-group" data-toggle-name="sex" data-toggle="buttons-radio" >
                            <button type="button" value="F" class="btn <? if (isset($_GET['view']) & ($LISTA_PER['Per']['sexo']['0']=='M')) echo 'disabled' ?>" data-toggle="button">Femenino</button>
                            <button type="button" value="M" class="btn <? if (isset($_GET['view']) & ($LISTA_PER['Per']['sexo']['0']=='F')) echo 'disabled' ?>" data-toggle="button">Masculino</button>
                        </div>
                        <input type="hidden" id="sex" name="sex" value="<? if(isset($_GET['id'])) echo $LISTA_PER['Per']['sexo']['0']; else echo "F"?>" />
                </div>
            </div>
            <?/*  if (!isset($_GET['action']) || $_GET['action'] !== 'edit'){
                echo "
                    <div class='control-group'>
                        <label class='control-label'>Fecha Nacimiento</label>
                        <div class='controls'>
                            <div class='input-prepend date datepicker' data-date='"; if(isset($_GET['id'])) echo $LISTA_PER['Per']['fecha_nac']['0']; else echo date('d-m-Y'); echo "' data-date-language='es' data-date-today-Btn='true' data-date-start-View='2' data-date-today-Highlight='true' data-date-autoclose='true' data-date-format='dd-mm-yyyy'>
                                <span class='add-on'><i class='icon-calendar'></i></span>
                                <input size='12' id='fnac' name='fnac' class='input-xlarge' type='text' value='"; if(isset($_GET['id'])) echo $LISTA_PER['Per']['fecha_nac']['0']; else echo date('d-m-Y'); echo "'"; echo "disabled readonly>
                            </div>
                        </div>
                    </div>
                    ";
                }*/
            ?>
            <div class="control-group">
                <label class="control-label">Email</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-envelope"></i></span>
                        <input type="email" required class="input-xlarge" id="email" name="email" value="<? if(isset($_GET['id'])) echo $LISTA_PER['Per']['email']['0'];?>" placeholder="Direcci&oacute;n de Correo" <? if (isset($_GET['view'])) echo 'disabled' ?>>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Unidad adscrita</label>
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
            <?
                if (isset($_GET['id'])) {
                    echo "
            <div class='control-group'>
                <label class='control-label'>Tipo de personal</label>
                <div class='controls'>
                    <div class='input-prepend'>
                        <span class='add-on'><i class='icon-comment'></i></span>
                        <input type='text' class='input-xlarge' value='".$tipo."' disabled>
                    </div>
                </div>
            </div>

            <div class='control-group'>
                <label class='control-label'>Sede</label>
                <div class='controls'>
                    <div class='input-prepend'>
                        <span class='add-on'><i class='icon-comment'></i></span>
                        <input  type='text' class='input-xlarge' value='".$sede."' disabled>
                    </div>
                </div>
            </div>
                        ";
                }
            ?>
            <div class="control-group">
                    <div class="row">
                    <div class="span5"></div>
                    <div class="span6">
                    <p>
                    <a class="btn btn-info" href="vListarPersonas.php">Listar Personas</a>
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
    </div>
    <div class="tab-pane" id="tab2">

    <!-- Formulario segunda pestaña, Asociar Persona Cargo-->
    <div class="well" align="center">
        <form id="newCar" class="form-horizontal" method="post" action="lib/cPersona.php?action=add_car" >
            <input type="hidden" id="id" name="id" value="<? if (isset($_GET['id'])) echo $_GET['id']; ?>"/>
            <div class="row">
            <div class="span2"></div>
            <div class="span4">

            <?

            if (isset($_GET['action']) && $_GET['action'] == "edit"){

                if(isset($LISTA_PER_CAR) && $LISTA_PER_CAR['max_res']>0 && $LISTA_PER_CAR['Per_Car']['id_car']['0']!=0){
                    echo "
            <p class='lsmall muted'>Al cambiar el cargo es necesario guardar la fecha fin del cargo anterior, nombre y fecha del nuevo cargo. Por defecto aparece la fecha actual.</p>
            <div class='control-group'>
                <label class='control-label'>Fecha Fin viejo cargo</label>
                <div class='controls'>
                    <div class='input-prepend date datepicker'".date('d-m-Y')."' data-date-language='es' data-date-today-Btn='true' data-date-start-View='2' data-date-today-Highlight='true' data-date-autoclose='true' data-date-format='dd-mm-yyyy'>
                        <span class='add-on'><i class='icon-calendar'></i></span>
                        <input size='12' id='fin' name='fin' class='input-xlarge' type='text' value='".date('d-m-Y')."' readonly>
                    </div>
                </div>
            </div>
                    ";
                }

            }
            ?>

            <div class="control-group">
                <label class="control-label">Nuevo cargo</label>
                <div class="controls">
                        <select style="width:200px" id="car" name="car" class="select2  car-sel" data-size="auto" <? if (isset($_GET['view'])) echo 'disabled' ?>>
                            <?
                                while (list($key, $val) = each($CAR_ID)){
                                    echo "<option value=".$key.">".$val."</option>";
                                }
                            ?>
                        </select>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Fecha Inicio nuevo cargo</label>
                <div class="controls">
                    <div class="input-prepend date datepicker" data-date="<? echo date("d-m-Y") ?>" data-date-language="es" data-date-today-Btn="true" data-date-start-View="2" data-date-today-Highlight="true" data-date-autoclose="true" data-date-format="dd-mm-yyyy">
                        <span class="add-on"><i class="icon-calendar"></i></span>
                        <input size="12" id="fech" name="fech" class="input-xlarge" type="text" value="<? echo date("d-m-Y") ?>" <? if (isset($_GET['view'])) echo 'disabled' ?>  readonly>
                    </div>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Observaci&oacute;n</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-edit"></i></span>
                        <textarea class="input-xlarge" rows="3" id="obs" name="obs" placeholder="Observaciones"<? if (isset($_GET['view'])) echo 'disabled' ?>><? if(isset($LISTA_PER_CAR) && $LISTA_PER_CAR['max_res']>0) echo $LISTA_PER_CAR['Per_Car']['observacion']['0'];?></textarea>
                    </div>
                </div>
            </div>

            <div class="control-group">
                    <div class="row">
                    <div class="span5"></div>
                    <div class="span6">
                    <p>
                    <?                         
                        if (isset($_GET['id']) & !isset($_GET['view'])) {
                    
                            echo '<a class="btn btn-info" href="vListarCargosPersona.php?id='.$_GET["id"].'">Histórico de Cargos</a>
                                  <input class="btn" type="reset" value="Borrar">
                                  <button type="submit" id="confirmButton" class="btn btn-success" >Registrar</button>';
                        }else if (isset($_GET['id']) & isset($_GET['view'])){
                            echo '<a class="btn btn-info" href="vListarCargosPersona.php?id='.$_GET["id"].'">Histórico de Cargos</a>
                                    <a href="?action=edit&id='.$_GET['id'].'" class="btn btn-warning">Editar</a>' ;
                        } else{
                            echo '<button type="submit" id="confirmButton" class="btn btn-success" disabled>Registrar</button>';
                            echo ' Debe registrar una Persona';
                        }
                    ?>
                    </p>
                    </div>
                    </div>
            </div>
            </div>

            </div>

        </form>

    </div>
    </div>
    <div class="tab-pane" id="tab3">

        <!-- Formulario tercera pestaña, Asociar Evaluador a Persona-->
   <div class="well" align="center">
        <form id="newEva" class="form-horizontal" method="post" action="lib/cPersona.php?action=add_eval" >
            <input type="hidden" id="id" name="id" value="<? if (isset($_GET['id'])) echo $_GET['id']; ?>"/>
            <div class="row">
            <div class="span2"></div>
            <div class="span4">
            <div class="control-group">
                <label class="control-label">Evaluador</label>
                <div class="controls">
                        <select style="width:200px" id="eval" name="eval" class="select2  eva-sel" data-size="auto" <? if (isset($_GET['view'])) echo 'disabled' ?>>
                            <?
                                while (list($key, $val) = each($EVA_ID)){
                                    echo "<option value=".$key.">".$val."</option>";
                                }
                            ?>
                        </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Observaci&oacute;n</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-edit"></i></span>
                        <textarea class="input-xlarge" rows="3" id="obs" name="obs" placeholder="Observaciones"<? if (isset($_GET['view'])) echo 'disabled' ?>><? if(isset($LISTA_PER_EVA) && $LISTA_PER_EVA['max_res']>0) echo $LISTA_PER_EVA['Per_Eva']['observacion']['0'];?></textarea>
                    </div>
                </div>
            </div>

            <div class="control-group">
                    <div class="row">
                    <div class="span5"></div>
                    <div class="span6">
                    <p>
                    <a class="btn btn-info" href="vListarPersonas.php">Listar Personas</a>
                    <?                         
                        if (isset($_GET['id']) & !isset($_GET['view'])) {
                            echo '<input class="btn" type="reset" value="Borrar">
                                  <button type="submit" id="confirmButton" class="btn btn-success" >Registrar</button>';
                        }else if (isset($_GET['id']) & isset($_GET['view'])){
                            echo '<a href="?action=edit&id='.$_GET['id'].'" class="btn btn-warning">Editar</a>' ;
                        } else{
                            echo '<button type="submit" id="confirmButton" class="btn btn-success" disabled>Registrar</button>';
                            echo ' Debe registrar una Persona';
                        }
                    ?>
                    </p>
                    </div>
                    </div>
            </div>
            </div>

            </div>

        </form>

    </div>
    </div>

    <div class="tab-pane" id="tab4">

        <!-- Formulario cuarta pestaña, Asociar Supervisor a Persona-->
    <div class="well" align="center">
        <form id="newSup" class="form-horizontal" method="post" action="lib/cPersona.php?action=add_sup" >
            <input type="hidden" id="id" name="id" value="<? if (isset($_GET['id'])) echo $_GET['id']; ?>"/>
            <div class="row">
            <div class="span2"></div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Supervisor Jerárquico</label>
                    <div class="controls">
                            <select style="width:200px" id="sup" name="sup" class="select2  sup-sel" data-size="auto" <? if (isset($_GET['view'])) echo 'disabled' ?>>
                                <?
                                    while (list($key, $val) = each($SUP_ID)){
                                        echo "<option value=".$key.">".$val."</option>";
                                    }
                                ?>
                            </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Observaci&oacute;n</label>
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-edit"></i></span>
                            <textarea class="input-xlarge" rows="3" id="obs" name="obs" placeholder="Observaciones"<? if (isset($_GET['view'])) echo 'disabled' ?>><? if(isset($LISTA_PER_SUP) && $LISTA_PER_SUP['max_res']>0) echo $LISTA_PER_SUP['Per_Sup']['observacion']['0'];?></textarea>
                        </div>
                    </div>
                </div>

                <div class="control-group">
                        <div class="row">
                        <div class="span5"></div>
                        <div class="span6">
                        <p>
                        <a class="btn btn-info" href="vListarPersonas.php">Listar Personas</a>
                        <?                         
                            if (isset($_GET['id']) & !isset($_GET['view'])) {
                                echo '<input class="btn" type="reset" value="Borrar">
                                      <button type="submit" id="confirmButton" class="btn btn-success" >Registrar</button>';
                            }else if (isset($_GET['id']) & isset($_GET['view'])){
                                echo '<a href="?action=edit&id='.$_GET['id'].'" class="btn btn-warning">Editar</a>' ;
                            } else{
                                echo '<button type="submit" id="confirmButton" class="btn btn-success" disabled>Registrar</button>';
                                echo ' Debe registrar una Persona';
                            }
                        ?>
                        </p>
                        </div>
                        </div>
                </div>
            </div>

            </div>

        </form>

    </div>
    </div>
  </div>
</div>

<?php
include "vFooter.php";
?>
