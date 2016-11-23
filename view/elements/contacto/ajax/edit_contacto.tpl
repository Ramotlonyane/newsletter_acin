<form id="novoContacto" class="form-horizontal" onsubmit="return false;">
    <input type="hidden" name="mod" value="cont">
    <input type="hidden" name="op" value="edit_contacto_save">
    <input type="hidden" name="id" value="<?=$contacto['id']?>">
    <div class="form-group">
        <label for="idPlataforma" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
            <input type="text" name="email" class="form-control" placeholder="Email" value="<?=$contacto['email']?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="idPlataforma" class="col-sm-2 control-label">Lista</label>
        <div class="col-sm-10">
            <select name="idListas[]" onmousedown="this.value='';" onselect="DialogSubfolderLista(this.value);" id="idListas" class="form-control" multiple="multiple" title='Listas associadas'>
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

     <div class="form-group">
        <label for="idPlataforma" class="col-sm-2 control-label">Listas Subfolders:</label>
        <div class="col-sm-10">
            <select name="DialogSubfolderLista[]" id="DialogSubfolderLista" class="form-control" multiple="multiple" title='SubfolderListas associadas'>
                <?
                if($listas){
                    $DialogSubfolderListaSelecionadas=get_array_from_key_name($contacto['listas'],'id');
                    foreach ($listas as $c){
                        ?>
                        <option value="<?=$c['id']?>" <?=in_array($c['id'], $DialogSubfolderListaSelecionadas)?" selected ":"" ?> >
                            <?=$c['descricao']?>
                        </option>
                        <?
                    }
                }?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <label>
                <input type="checkbox" name="bBlacklist" value="1" <?=checked_value($contacto['bBlacklist'])?> > BlackList
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="button" class="btn btn-success" onclick="guardar_contato()" >Guardar</button>
            <button type="button" class="btn btn-danger" onclick="$.modal.close()">Cancelar</button>
        </div>
    </div>
</form>
<script type="text/javascript">
function DialogSubfolderLista(value){
    alert('TETS');
}

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
    $('#DialogSubfolderLista').selectpicker();
});
</script>
