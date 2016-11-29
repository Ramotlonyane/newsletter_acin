<table>
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
?>