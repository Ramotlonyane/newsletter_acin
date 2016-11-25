
<form class="links-form" onsubmit="adicionarLink(event);">
	<input type="hidden" name="idNewsletter" value="<?=$newsletter['id']?>">
	<input name="nome" placeholder="Nome" class="form-control">
	<input name="url" placeholder="Url" class="form-control">
	<button type="submit" class="btn">Adicionar Link</button>
</form>


<table id="links" class="modal-links-table table table-striped table-bordered">
	<thead>
		<tr>
            <th>Nome</th>
            <th>Url</th>
            <th>Acções</th>
        </tr>
	</thead>
	<tbody>
		<tr>
			<? $link=URL."/newsletter.php?id=".$newsletter['id']."&hash=".$newsletter['hash']."&@hash_envio_newsletter@&"; ?>
            <td>Link para a newsletter</td>
            <td><input class="form-control input-link-select" value="<?=$link?>"/></td>
            <td>
            	<img src="css/icons/add.png" title="Inserir link em newsletter" onclick="insertLink('Link para a newsletter', '<?=$link?>')">
            	<img src="css/icons/copy.png" title="Copiar para área de transferência" onclick="copiarLinkDirecto('<?=$link?>')">
            	<img src="css/icons/view.png" title="Testar no browser" onClick="window.location='<?=$link?>';">
        	</td>
        </tr>
        <tr>
            <td>Link para não receber mais newsletters</td>
            <td><input class="form-control input-link-select" value="<?=URL."/newsletter.php?mod=blacklist&id=".$newsletter['id']."&hash=".$newsletter['hash']."&@hash_envio_newsletter@&"?>"/></td>
            <td>
            	<img src="css/icons/add.png" title="Inserir link em newsletter" onclick="insertLink('Link para não receber mais newsletters', '<?=$link?>')">
            	<img src="css/icons/copy.png" title="Copiar para área de transferência" onclick="copiarLinkDirecto('<?=$link?>')"/>
            </td>
        </tr>
        <?
		if($newsletter['ficheiros']){ // por alterar
			foreach ($newsletter['ficheiros'] as $f) {
				$link=URL."/file.php?id=".$f['id']."&hash=".sha1($f['caminho'])."&@hash_envio_newsletter@&";
			?>
				<tr>
					<td>Link para <?=$f['nome']?></td>
					<td><input class="form-control input-link-select" value="<?=$link?>"/></td>
					<td>
						<img src="css/icons/add.png" title="Inserir link em newsletter" onclick="insertLink('<?=$f['nome']?>', '<?=$link?>')">
		            	<img src="css/icons/copy.png" title="Copiar para área de transferência" onclick="copiarLinkDirecto('<?=$link?>')">
		            	<img src="css/icons/view.png" title="Visualizar" onClick="window.location='<?=$link?>';">
					</td>
				</tr>
			<?
			}
		}
		?>

		<? foreach ($newsletter["links"] as $link) { ?>
			<tr id="<?=$link['id']?>">
				<td><?=$link["nome"]?></td>
				<td><input class="form-control input-link-select" value="<?=$link["url"]?>" /></td>
				<td></td>
			</tr>
		<? } ?>
	</tbody>
</table>
<script>
$(".input-link-select").on( "click", "", function() {
	event.stopPropagation();
	$element=$($(this).get(0));
	$(this).get(0).select();

});

var dataTableObj;

$(document).ready(function(){
	dataTableObj = $('.modal-links-table');

	dataTableObj.DataTable({
		"autoWidth": false,
		"bLengthChange": false,
		"bFilter": false,
		language: {
			url: 'js/datatables/dataTables.portuguese.json'
		},
		"columnDefs": [
	    {
		    "targets": 2,
		    "createdCell": function (td, cellData, rowData, row, col) {
		    	var rowId = td.parentNode.id;

		    	var url = $(td.parentNode).find('input').val();

		    	if (rowId) {
		    		$(td).html("<img src=\"css/icons/add.png\" title=\"Inserir link em newsletter\" onclick=\"insertLink('" + rowData.nome
		    					+ "', '" + url + "')\">"
		    					+ "<img onClick=\"copiaLink('" + rowId +"')\" title=\"Copiar para área de transferência\" src=\"css/icons/copy.png\">"
		    					+ "<img onClick=\"removeLink('" + rowId + "')\" title=\"Remover link\" src=\"css/icons/remove.png\">"
		    					);
		    	}
	    	}
  		}
	  ],
	  "columns": [
	  	{
	  		width: "20%",
	  		data: "nome"
  	  	},
	  	{
	  		width: "65%",
	  		data: "url"
	  	},
	  	{
	  		width: "15%",
	  		data: "accoes"
	  	}
	  ]
	});
});

function adicionarLink(event) {
	event.preventDefault();

	var form 	 		 = $('.links-form');
	var nameForm 		 = form.find('[name="nome"]').val();
	var urlForm	 		 = form.find('[name="url"]').val();
	var idNewsletterForm = form.find('[name="idNewsletter"]').val();

	if (nameForm && urlForm) {

		if (urlForm.indexOf("http://") !== 0) {
			urlForm = "http://" + urlForm;
		}

		ajax({
			data: {
				mod: "news",
				op: "adicionar_link",
				id: idNewsletterForm,
				nome: nameForm,
				url: urlForm
			},
			type: "POST",
			success:function (link){
				if (link) {
					var dataTable = dataTableObj.DataTable();
					dataTable.row.add({
						DT_RowId: link.id,
						nome: nameForm,
						url: "<input class=\"form-control input-link-select\" value=\"" + link.url + "\"/>",
						accoes: ""
					}).draw();

					form.find('[name="nome"]').val('');
					form.find('[name="url"]').val('');
				}
			}
		});
	}
	return false;
}

var removerUrl = '<?=URL."/newsletter.php?mod=news&op=remover_link&id="?>';

function removeLink(id) {
	ajax({
		data: {
			mod: "news",
			op: "remover_link",
			id: id
		},
		success: function () {
			var dataTable = dataTableObj.DataTable();
			var row 	  = dataTable.row(dataTableObj.find('tr#' + id));
			row.remove().draw();
		}
	});
}

function copiaLink(id) {
	copiarLinkDirecto(dataTableObj.find('tr#' + id + ' input').val());
}

function copiarLinkDirecto(url) {
	window.prompt("Copiar para a área de transferência: Ctrl+C, Enter", url);
}
</script>
