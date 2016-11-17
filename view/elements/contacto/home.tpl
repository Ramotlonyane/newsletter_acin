			<div style="text-align:right;padding-bottom:10px">
				<a onclick="editLista()" ><button type="button" class="btn btn-default">Nova lista</button></a>
				<a onclick="editContacto()" ><button type="button" class="btn btn-default">Novo contato</button></a>
				<a onclick="importarCSV()" ><button type="button" class="btn btn-default">Importar CSV</button></a>
				<a onclick="exportarCSV()" ><button type="button" class="btn btn-default">Exportar CSV</button></a>
			</div>

			<form id="formPesquisa" class="form-inline">
				<input type="hidden" name="mod" value="cont">
				<input type="hidden" name="op" value="list_contatos">
				<input type="hidden" name="page" value="1">

				<div class="form-group">
      				<label for="email">Email:</label>
      				<input name="email" class="form-control">
    			</div>

    			<div class="form-group">
      				<label for="email">Listas:</label>
      				<select name="idLista" class="form-control">
					<option></option>
					<?
					if($listas){
						foreach ($listas as $p){
							?>
					<option value="<?=$p['id']?>"><?=$p['descricao']?></option>
							<?
						}
					}?>
				</select>
    			</div>
	
				<label><input type="checkbox" name="bBlacklist" value="1"> Blacklist</label>
				<label title="Ultimo email enviado deu erro"><input type="checkbox" name="bErroEnvio" value="1"> Erro envio</label>
				<input type="button" value="pesquisa" onclick="pesquisa()">
			</form><br/>

			<div id="listaPesquisa">
				<?
				include "home_lista.tpl"
				?>
			</div>
<script type="text/javascript">
function pesquisa(event, page)
{
	if (event) {
		event.preventDefault();
	}

	if(isEmpty(page)){
		page=1;
	}
	$("form#formPesquisa input[name=page]").val(page);
	$("#listaPesquisa").ajaxLoad({
		data:$("#formPesquisa").serialize()
	});
}
function editContacto(id)
{
	var edit="";
	if(!isEmpty(id)){
		edit="&id="+id;
	}
	showDialog({
		title:"Novo contacto",
		data:"mod=cont&op=edit_contacto"+edit,
		width:400,
		json:true
	});
}
function editLista(id)
{
	var edit="";
	if(!isEmpty(id)){
		edit="&id="+id;
	}
	showDialog({
		title:"Nova lista",
		data:"mod=cont&op=edit_lista"+edit,
		width:400,
		json:true
	});
}
function importarCSV()
{
	showDialog({
		title:"Importar lista",
		data:"mod=cont&op=importar_csv",
		width:500,
		json:true
	});
}
function exportarCSV()
{
	var request=$("#formPesquisa").serialize();
	request=request+"&op=exportarCSV";
	redirect(request);
}
</script>
