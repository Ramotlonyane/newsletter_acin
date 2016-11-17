<form id="novaPlataforma" class="form-horizontal" onsubmit="return false;">
    <input type="hidden" name="mod" value="plat">
    <input type="hidden" name="op" value="edit_plataforma_save">
    <input type="hidden" name="id" value="<?=$plataforma['id']?>">
    <div class="form-group">
        <label class="col-sm-4 control-label">Plataforma</label>
        <div class="col-sm-8">
            <input type="text" name="plataforma" class="form-control" placeholder="Plataforma" value="<?=$plataforma['plataforma']?>" />
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-4 control-label">Nome envio</label>
        <div class="col-sm-8">
            <input type="text" name="nomeEnvio" class="form-control" placeholder="Nome envio" value="<?=$plataforma['nomeEnvio']?>" />
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-4 control-label">Email envio</label>
        <div class="col-sm-8">
            <input type="text" name="emailEnvio" class="form-control" placeholder="Email envio" value="<?=$plataforma['emailEnvio']?>" />
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-4 control-label">Link</label>
        <div class="col-sm-8">
            <input type="text" name="link" class="form-control" placeholder="Link" value="<?=$plataforma['link']?>" />
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-4 control-label">Template</label>
        <div class="col-sm-8">
            <textarea name="templateConteudo" class="form-control" placeholder="Template" ><?=$plataforma['templateConteudo']?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <button type="button" class="btn btn-success" onclick="guardar_plataforma()" >Guardar</button>
            <button type="button" class="btn btn-danger" onclick="$.modal.close()">Cancelar</button>
        </div>
    </div>
</form>
<script type="text/javascript">
function guardar_plataforma()
{
    ajax({
        data:$("#novaPlataforma").serialize(),
        loading:true,
        success:function (obj){
            try{
                if(obj.sucesso==1){
                    pesquisa();
                    $.modal.close();
                }else{
                    throw "erro";
                }
            }catch(e){
                showError("Erro ao processar pedido");
            }
        }
    })
}
$(document).ready(function(){
    $('#idListas').selectpicker();
});
</script>
