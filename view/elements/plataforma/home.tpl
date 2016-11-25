<table style="table-layout:fixed;width:100%">
<col style="width:180px"></col>
<col></col>
	<tr>
		<td style="vertical-align: top;padding-top:23px">
			<form id="formPesquisa">
			<input type="hidden" name="mod" value="plat">
			<input type="hidden" name="op" value="list">
			<input type="hidden" name="page" value="1">

			<label>Plataforma:</label><br/>
			<input name="plataforma" style="width:100%" class="form-control">

			<br/><br/>
			<input type="button" value="pesquisa" onclick="pesquisa()">
			</form>
		</td>
		<td style="vertical-align: top;padding-left:5px;">
			<div style="text-align:right;padding-bottom:10px">
				<a onclick="editPlataforma()" ><button type="button" class="btn btn-default">Nova plataforma</button></a>
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
function editPlataforma(id)
{
	var edit="";
	if(!isEmpty(id)){
		edit="&id="+id;
	}
	showDialog({
		title:"Nova plataforma",
		data:"mod=plat&op=edit_plataforma"+edit,
		width:400,
		json:true
	});
}
</script>
