<form id="importarCSVform" class="form-horizontal" target="iframe_importarcsv"  method="post" enctype="multipart/form-data">
    <input type="hidden" name="mod" value="cont">
    <input type="hidden" name="op" value="importar_csv_save">
    <input type="hidden" name="id" value="<?=$lista['id']?>">
    <div class="form-group">
        <label class="col-sm-3 control-label">Ficheiro</label>
        <div class="col-sm-9">
            <input type="file" name="csv" >
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <pre>Apenas é permitido importar ficheiros csv ou txt (uma linha por contacto) espaços e ; são ignorados</pre>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Lista</label>
        <div class="col-sm-9">
            <select name="idLista" onmousedown="this.value='';" onchange="importsubfolderList(this.value);" class="form-control">
                <option></option>
                <?
                if($listas){
                    foreach ($listas as $l) {
                        ?>
                        <option value="<?=$l['id']?>"><?=$l['descricao']?> (<?=$l['nContactos']?>)</option>
                        <?
                    }
                }?>
            </select>
            <label title="Se selecionado a lista importada é adicionada a lista selecionada. Caso contrário a lista é limpa e depois é feito a importação.">
                <input type="checkbox" checked="" name="bAddLista" value="1"> Adicionar a lista
            </label>
        </div>
    </div>

    <div style="display: none;" class="form-group import_subfolder">
        <label class="col-sm-3 control-label">Sub Lista</label>
        <div class="col-sm-9">
            <select name="idFolderLista" class="form-control import_sublist">
                <option></option>

            </select>
        </div>
    </div>

    <div class="form-group">
        <div id="formButtons" class="col-sm-offset-3 col-sm-9">
            <button type="button" class="btn btn-success" onclick="importar_lista()" >Importar</button>
            <button type="button" class="btn btn-danger" onclick="$.modal.close()">Cancelar</button>
        </div>
        <div id="formResults" class="col-sm-12" style="display:none">
            <div id="progressbar"><div class="progress-label">Loading...</div></div>
            Processado:
            <span class="nProcessado">0</span> /
            <span class="nLinhas"></span>
            <br/>
            Sucesso: <span class="nSucesso">0</span><br/>
            Erro: <span class="nErros">0</span>
            <div style="display:none;width:100%;height:130px;overflow:auto;" id="erroLog"></div>
        </div>
    </div>
</form>
<iframe id="iframe_importarcsv" name="iframe_importarcsv" style="display:none;width:0;height:0"></iframe>

<script type="text/javascript">
var dados_impportacao=null;
var progressbar = $( "#progressbar" );
var progressLabel = $( ".progress-label" );

function importsubfolderList(value){
    $('select.import_sublist').empty();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "index.php?",
        data:"mod=cont&op=subfolder&idContactList=" + value,
        success:function(result){
            if (result.success) {
                $("select.import_sublist").append("<option value=''></option>");
                $.each(result.response, function(key, value)  {
                    $("select.import_sublist").append('<option value=' + value.id + '>' + value.name + '</option>');
                    //$(this).closest("option").find(".subfolderList").append('<option value=' + value.id + '>' + value.name + '</option>');
                });
            }
        }
    });
    $("div.import_subfolder").show();
}


function set_import_nLines(nLinhas)
{
    dados_impportacao={
        nLinhas:nLinhas,
        nProcessado:0,
        nErros:0,
        nSucesso:0
    };
    $(".nLinhas").html(nLinhas);
    progressbar.progressbar({
      value: false,
      change: function() {
        progressLabel.text( progressbar.progressbar( "value" ) + "%" );
      },
      complete: function() {
        progressLabel.text( "Importado!" );
        if(dados_impportacao.nErros==0){
            $.modal.close();
            showMsg("Importado com sucesso!");
        }
        pesquisa();
      }
    });
    progressbar.progressbar( "value", 0 );
    $("#formButtons").hide();
    $("#formResults").show();
    $.ui_unblock();
}
function update_progessbar()
{
    $(".nProcessado").html(dados_impportacao.nProcessado);
    $(".nErros").html(dados_impportacao.nErros);
    $(".nSucesso").html(dados_impportacao.nSucesso);
    var per = (dados_impportacao.nProcessado/dados_impportacao.nLinhas);
    per=per * 100;
    if(dados_impportacao.nLinhas==0){
        per="100";
    }
    progressbar.progressbar( "value", parseInt(per) );
}
function import_sucesso()
{
    dados_impportacao.nProcessado++;
    dados_impportacao.nSucesso++;
    update_progessbar();
}
function import_erro(erro)
{
    dados_impportacao.nProcessado++;
    dados_impportacao.nErros++;
    $("#erroLog").show();
    $("#erroLog").prepend(erro+"<br/>");
    update_progessbar();
}
function importar_lista()
{
    var file=$("#importarCSVform input[name=csv]").val();
    if(empty(file)){
        alerta("Introduza um ficheiro .csv/.txt!");
        return false;
    }
    $.ui_block();
    $("#importarCSVform").submit();
}
</script>
<style type="text/css">
.progress-label{
    left: 40%;
}
</style>
