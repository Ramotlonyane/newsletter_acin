<form id="novoContacto" class="form-horizontal" onsubmit="return false;">
    <input type="hidden" name="mod" value="cont">
    <input type="hidden" name="op" value="edit_contacto_save">
    <input type="hidden"  name="id" value="<?=$contacto['id']?>">
    <div class="form-group">
        <label for="email" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
            <input type="text" name="email" class="form-control email" placeholder="Email" value="<?=$contacto['email']?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="idListas" class="col-sm-2 control-label">Lista</label>
        <div class="col-sm-10">
            <select name="idListas[]" onmousedown="this.value='';" onchange="SubfolderLista(this.value);" id="idListas" class="form-control idListas" multiple="multiple" title='Listas associadas'>
                <?
                if($listas){
                    $idListasSelecionadas=get_array_from_key_name($contacto['listas'],'id');
                    foreach ($listas as $c){
                        ?>
                        <option value="<?=$c['id']?>" <?=in_array($c['id'], $idListasSelecionadas)?" selected ":"" ?> >
                            <?=$c['descricao']?>
                        </option>
                        <?
                    }
                }?>
            </select>
        </div>
    </div>

     <div style="display: none;" class="form-group dialog_subfolder">
        <label for="DialogSubfolderLista" class="col-sm-2 control-label">Subfolders:</label>
        <div class="col-sm-10">
            <select name="DialogSubfolder" id="DialogSubfolderLista" class="form-control dialog_subfolderList" title='SubfolderListas associadas'>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <label>
                <input class="bBlacklist" type="checkbox" name="bBlacklist" value="" <?=checked_value($contacto['bBlacklist'])?> > BlackList
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
           <!--   <button type="button" class="btn btn-success" onclick="guardar_contato()" >Guardar</button>-->
          <button type="button" class="btn btn-success saveContact">Guardar</button>
            <button type="button" class="btn btn-danger" onclick="$.modal.close()">Cancelar</button>
        </div>
    </div>
</form>
<script type="text/javascript">

function SubfolderLista(value){
            $.ajax({
        type: "POST",
        dataType: "json",
        url: "index.php?",
        data:"mod=cont&op=subfolder&idContactList=" + value,
        success:function(result){
            if (result.success) {
                $("select.dialog_subfolderList").append("<option value=''></option>");
                $.each(result.response, function(key, value)  {
                    $("select.dialog_subfolderList").append('<option value=' + value.id + '>' + value.name + '</option>');
                });
            }
        }
    });
    $("div.dialog_subfolder").show();
}

 $("#novoContacto button.saveContact").on('click',function(){

    var id  =  $("#novoContacto input[name=id]").val();;
    var email_lista         =  $("#novoContacto input[name=email]").val();
    var idContactos         =  $("#novoContacto select.idListas").val();
    var idSubfolder         =  $("#novoContacto select.dialog_subfolderList").val();
    var bBlacklist          =  $("#novoContacto input.bBlacklist").val();

    if(empty(email_lista)){
        alerta("Introduza um email!");
        return false;
    }
       if(!validateEmail(email_lista)){
        alerta("Introduza um email correto!");
        return false;
    }
     $.ajax({
        type: "POST",
        dataType: "json",
        url: "index.php?mod=cont&op=edit_contacto_save",
        data:{email_lista:email_lista, idContactos:idContactos, idSubfolder:idSubfolder, id:id},
        success:function (obj){
            window.location.reload();
        }
    })


});
function guardar_contato()
{
    var email=$("#novoContacto input[name=email]").val();
    if(empty(email)){
        alerta("Introduza um email!");
        return false;
    }
    if(!validateEmail(email)){
        alerta("Introduza um email correto!");
        return false;
    }
    ajax({
        data:$("#novoContacto").serialize(),
        loading:true,
        success:function (obj){
            try{
                if(obj.sucesso==1){
                    pesquisa();
                    $.modal.close();
                }else{
                    alerta("Erro ao registar verifique se o email já existe!");
                }
            }catch(e){
                showError("Erro ao processar pedido");
            }
        }
    })
}
$(document).ready(function(){
    $('#idListas').selectpicker();
    //$('#DialogSubfolderLista').selectpicker();   
});
</script>
