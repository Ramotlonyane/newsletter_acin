<table>
	<tr>
		<th>Name</th>
		<th></th>
	</tr>
	<?
	if($templates){
		foreach($templates as $n) {
			?>
			<tr>
				<td></td>
				<td>
					<a id="<?=$n['id']?>" target="_blank" href="newsletter.php?id=<?=$n['id']?>&hash=<?=$n['hash']?>"><img src="css/icons/Preview.png"/></a>
					<a id="<?=$n['id']?>" onclick="copy_newsletter(<?=$n['id']?>)" estado="<?=$n['estado']?>" data="<?=$n['data']?>" descricao="<?=$n['descricao']?>" plataforma="<?=$n['plataforma']?>" class="copynewletter" href="#" ><img src="css/icons/copy01.png"/></a>
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





<!--<table>
  <tr>
      
  </tr>
</table>

<?php
foreach($templates as $news) {
  ?>
<div  style='width:400px; height:400px; display: inline-block;'>
  <iframe src="index.php?mod=news&op=conteudo&id=<?=$news['id']?>" style='width:100%; height:100%;'></iframe>
</div>
<?
}
?>-->
