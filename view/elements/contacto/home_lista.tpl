<?  $html="index.php?".str_ireplace('page=','',$_SERVER['QUERY_STRING']);
    listPages($_REQUEST['page'],$lista['n_pages'],"#",'','pesquisa');?>
<table class="table" >
	<tr>
		<th>Email</th>
		<th>Listas</th>
		<th>Sub Listas</th>
		<th>Blacklist</th>
		<th>Erro Envio</th>
		<th></th>
	</tr>
	<?
	if($lista['dados']){
		foreach ($lista['dados'] as $n) {
			?>
			<tr>
				<td><?=$n['email']?></td>
				<td><?=$n['listas']?></td>
				<td><?=$n['subfolder']?></td>
				<td>
					<? if($n['bBlacklist']){ ?>
						<img src="css/icons/alert.png">
					<? } ?>
				</td>
				<td>
					<? if($n['bErroEnvio']){ ?>
						<img src="css/icons/alert.png">
					<? }?>
				</td>
				<td>
					<a title="Editar"  onclick="editContacto(<?=$n['id']?>)">
						<img src="css/icons/edit.png"/>
					</a>
					<a title="Editar" onclick="removeEmail(<?=$n['id']?>)">
						<img src="css/icons/remove.png"/>
					</a>
				</td>
			</tr>
			<?
		}
	}else{ ?>
	<tr>
		<td colspan="5">
			Sem resultados
		</td>
	</tr>
	<? } ?>
</table>
<script type="text/javascript">
	function removeEmail(id)
	{
		confirm("Do you really want to remove this contact?",function(){
			ajax({
				data:"mod=cont&op=delete_email&id="+id,
				success:function(resp){
					window.location.reload();
				}
			});
		});
	}

</script>