<form id="novaLista" class="form-horizontal" onsubmit="return false;">
    <input type="hidden" name="mod" value="cont">
    <input type="hidden" name="op" value="edit_lista_save">
    <input type="hidden" name="id" value="<?=$lista['id']?>">
    <div class="form-group">
        <label class="col-sm-3 control-label">Lista</label>
        <div class="col-sm-9">
            <input type="text" name="descricao" class="form-control" placeholder="Lista Name" value="<?=$lista['descricao']?>" />
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">Existing Sub</label>
        <div class="col-sm-9">
            <select name="idSub_Lista"  class="form-control idSub_Lista">
                <option></option>
                <?
                    if($sub_listas){
                        foreach ($sub_listas as $p){
                            ?>
                    <option value="<?=$p['id']?>"><?=$p['name']?></option>
                    <?
                }
            }?></select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Nova Sub-Lista</label>
        <div class="col-sm-9">
            <input type="text" name="novaSublista" class="form-control" placeholder="Nova Sub-Lista"/>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">

            <button type="button" class="btn btn-success" onclick="guardar_lista()" >Guardar</button>
            <button type="button" class="btn btn-success" onclick="nova_sublista()" >Nova Sub-Lista</button>
            <button type="button" class="btn btn-danger" onclick="$.modal.close()">Cancelar</button>
        </div>
    </div>
</form>
<script type="text/javascript">
function guardar_lista()
{
    var descricao       =$("#novaLista input[name=descricao]").val();
    var sublista        =$("#novaLista input[name=subListaName]").val();
    var idSub_Lista     =$("#novaLista select[name=idSub_Lista]").val();

    if(empty(descricao)){
        alerta("Introduza uma Lista or Existing Sub-Lista!");
        return false;
    }
    ajax({
        data:$("#novaLista").serialize(),
        loading:true,
        success:function (obj){
            try{
                if(obj.sucesso==1){
                    redirect('mod=cont&op=home');
                    $.modal.close();
                }else{
                    throw ""
                }
            }catch(e){
                showError("Erro ao processar pedido");
            }
        }
    })
}
function nova_sublista(){
    var novaSublista  =  $("#novaLista input[name=novaSublista]").val();
    if (empty(novaSublista)) {
        alerta("Nova Sub-Lista Field is required!!!");
        return false;
    }
        $.ajax({
        type: "POST",
        dataType: "json",
        url: "index.php?mod=cont&op=nova_sublista",
        data:{novaSublista:novaSublista},
        success:function(result){
            if (result) {
                alerta("New Sub-list is Inserted Successfully");
            }
            
        }
    });
}
</script>
