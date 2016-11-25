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
        <label class="col-sm-3 control-label">Sub Lista</label>
        <div class="col-sm-9">
            <input type="text" name="subListaName" class="form-control" placeholder="Sub Lista Name" value="<?=$lista['subListaName']?>" />
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="button" class="btn btn-success" onclick="guardar_lista()" >Guardar</button>
            <button type="button" class="btn btn-danger" onclick="$.modal.close()">Cancelar</button>
        </div>
    </div>
</form>
<script type="text/javascript">
function guardar_lista()
{
    var email=$("#novaLista input[name=descricao]").val();
    var sublista=$("#novaLista input[name=subListaName]").val();

    if(empty(email) || empty(sublista)){
        alerta("Introduza uma Lista or Sub Lista!");
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
</script>
