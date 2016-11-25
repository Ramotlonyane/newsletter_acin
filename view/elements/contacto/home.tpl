			<div style="text-align:right;padding-bottom:10px">
				<a onclick="editLista()" ><button type="button" class="btn btn-default">Nova lista</button></a>
				<a onclick="editContacto()" ><button type="button" class="btn btn-default">Novo contato</button></a>
				<a onclick="importarCSV()" ><button type="button" class="btn btn-default">Importar CSV</button></a>
				<a onclick="exportarCSV()" ><button type="button" class="btn btn-default">Exportar CSV</button></a>
			</div>

			<form id="formPesquisa" class="form-horizontal col-md-2 formPesquisa">
				<input type="hidden" name="mod" value="cont">
				<input type="hidden" name="op" value="list_contatos">
				<input type="hidden" name="page" value="1">

				<div class="form-group">
      				<label for="email">Email:</label>
      				<input name="email" class="form-control">
    			</div>

    			<div class="form-group">
      				<label for="email">Listas:</label>
      				<select name="idLista" data-idlista="<?=$p['id']?>" onmousedown="this.value='';" onchange="subfolderList(this.value);" class="form-control idLista">
					<option></option>
					<?
					if($listas){
						foreach ($listas as $p){
							?>
					<option value="<?=$p['id']?>"><?=$p['descricao']?></option>
							<?
						}
					}?></select>
    			</div>

    			<div style="display: none;" class="form-group contact_subfolder">
      				<label for="email">Listas Subfolders:</label>
      				<select name="idSubfolderLista" class="form-control subfolderList">
						<option></option>
					</select>
    			</div>
	
				<label><input type="checkbox" name="bBlacklist" value="1"> Blacklist</label>
				<label><input type="checkbox" name="deleteLista" value="1"> Delete Lista</label>
				<label title="Ultimo email enviado deu erro"><input type="checkbox" name="bErroEnvio" value="1"> Erro envio</label>
				<input type="button" value="pesquisa" onclick="pesquisa()">
			</form><br/>

			<div id="listaPesquisa" class="col-md-10">
				<?
				include "home_lista.tpl"
				?>
			</div>
<script type="text/javascript">

function subfolderList(value){
	$('.subfolderList').empty();
	//alert(value);
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "index.php?",
		data:"mod=cont&op=subfolder&idContactList=" + value,
		success:function(result){
			if (result.success) {
				$("select.subfolderList").append("<option value=''></option>");
				$.each(result.response, function(key, value)  {
    				$("select.subfolderList").append('<option value=' + value.id + '>' + value.name + '</option>');
    				//$(this).closest("option").find(".subfolderList").append('<option value=' + value.id + '>' + value.name + '</option>');
				});
			}
		}
	});
	$("div.contact_subfolder").show();
}

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
		width:600,
		height:400,
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
