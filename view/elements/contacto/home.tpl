<table style="table-layout:fixed;width:100%">
<col style="width:180px"></col>
<col></col>
	<tr>
		<td style="vertical-align: top;padding-top:23px">
			<form id="formPesquisa">
			<input type="hidden" name="mod" value="cont">
			<input type="hidden" name="op" value="list_contatos">
			<input type="hidden" name="page" value="1">

			<label>Email:</label><br/>
			<input name="email" style="width:100%" class="form-control">

			<label>Listas:</label><br/>
			<select name="idLista" style="width:100%" class="form-control">
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
			<label><input type="checkbox" name="bBlacklist" value="1"> Blacklist</label>
			<br/>
			<label title="Ultimo email enviado deu erro"><input type="checkbox" name="bErroEnvio" value="1"> Erro envio</label>
			<br/><br/>
			<input type="button" value="pesquisa" onclick="pesquisa()">
			</form>
		</td>
		<td style="vertical-align: top;padding-left:5px;">
			<div style="text-align:right;padding-bottom:10px">
				<a onclick="editLista()" ><button type="button" class="btn btn-default">Nova lista</button></a>
				<a onclick="editContacto()" ><button type="button" class="btn btn-default">Novo contato</button></a>
				<a onclick="importarCSV()" ><button type="button" class="btn btn-default">Importar CSV</button></a>
				<a onclick="exportarCSV()" ><button type="button" class="btn btn-default">Exportar CSV</button></a>
			</div>
			<div id="listaPesquisa">
				<?
				include "home_lista.tpl"
				?>
			</div>
		</td>
	</tr>
</table>
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
