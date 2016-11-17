<?  $html="index.php?".str_ireplace('page=','',$_SERVER['QUERY_STRING']);
    listPages($_REQUEST['page'],$lista['n_pages'],"#",'','pesquisa');?>
<table class="table" >
	<tr>
		<th>Plataforma</th>
		<th>Nome envio</th>
		<th>Email envio</th>
		<th>Link</th>
		<th></th>
	</tr>
	<?
	if($lista['dados']){
		foreach ($lista['dados'] as $n) {
			?>
			<tr>
				<td><?=$n['plataforma']?></td>
				<td><?=$n['nomeEnvio']?></td>
				<td><?=$n['emailEnvio']?></td>
				<td>
					<? if($n['link']){?>
						<a href="http://<?=$n['link']?>" target='_blank'><?=$n['link']?></a>
					<? } ?>
				</td>
				<td>
					<a title="Editar" onclick="editPlataforma(<?=$n['id']?>)">
						<img src="css/icons/edit.png"/>
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
