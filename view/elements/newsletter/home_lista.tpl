<?  $html="index.php?".str_ireplace('page=','',$_SERVER['QUERY_STRING']);
    listPages($_REQUEST['page'],$lista['n_pages'],"#",'','pesquisa');?>
<table class="table append" >
	<tr>
		<th>Plataforma</th>
		<th>Descrição</th>
		<th>Data</th>
		<th>Estado</th>
		<th></th>
	</tr>
	<?
	if($lista['dados']){
		foreach ($lista['dados'] as $n) {
			?>
			<tr>
				<td><?=$n['plataforma']?></td>
				<td><?=$n['descricao']?></td>
				<td><?=$n['data']?></td>
				<td><?=$n['estado']?></td>
				<td>
				<?
				switch($n['idEstado'])
				{
					case '1'://elaboração
					?>
						<a class="editnew" id="<?=$n['id']?>" descricao="<?=$n['descricao']?>" href="#" title="Editar">
							<img src="css/icons/edit.png"/>
						</a>
						<!--<a class="editnew2" id="<?=$n['id']?>" descricao="<?=$n['descricao']?>" href="index.php?mod=news&op=new1&id=<?=$n['id']?>" title="Editar">
							<img src="css/icons/edit.png"/>
						</a>-->
						<a href="#" onclick="news_remove(<?=$n['id']?>)"><img src="css/icons/remove.png"/></a>
						<a id="<?=$n['id']?>" target="_blank" href="newsletter.php?id=<?=$n['id']?>&hash=<?=$n['hash']?>"><img src="css/icons/Preview.png"/></a>
						<a id="<?=$n['id']?>" onclick="copy_news(<?=$n['id']?>)" estado="<?=$n['estado']?>" data="<?=$n['data']?>" descricao="<?=$n['descricao']?>" plataforma="<?=$n['plataforma']?>" class="copynewletter" href="#" ><img src="css/icons/copy01.png"/></a>
					</td>
					<?
					break;
					case '2'://aprovado
					case '3'://enviado
					case '4'://cancelado
					?>
						<a href="index.php?mod=news&op=view&id=<?=$n['id']?>" title="Ver">
							<img src="css/icons/view.png"/>
						</a>
					</td>
					<?
					break;
				}?>

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
	function news_remove(id)
	{
		confirm("Do you really want remove this?",function(){
			ajax({
				data:"mod=news&op=remove&id="+id,
				success:function(resp){
					window.location.reload();
				}
			});
		});
	}

	function copy_news(id)
	{
		ajax({
				data:"mod=news&op=copy&id="+id,
				success:function(resp){
					window.location.reload();
				}
			});
	}

	
	$("a.editnew").click(function(){

			event.preventDefault();
			var id       	    = $(this).attr('id');
			var descricao      	= $(this).attr('descricao');

			if ($(".myTab > li#closetab"+id).length != 0) {
            $(".myTab > li#closetab"+id+" > a").trigger('click');
        }else{
			ajax({
				data:"index.php?mod=news&op=new1&id="+id,
				success:function(result){
					 $('ul.myTab li:first-child').after('<li class="edit_newsletter_tab" id="closetab' + id+ '"><a style="background-color: #d3d3d3;" href="#tab' +id+ '" role="tab" data-toggle="tab">'+descricao+'&nbsp;&nbsp;<button style="color:red;" type="button" class="btn btn-xs removeRequestTab" style="background: none;" id="'+id+'">X</button></a>');
                    $('#edit-newsletter').after('<div id="tab' + id+ '" class="tab-pane fade">'+result+'</div>');
                    $(".myTab > li#closetab"+id+" > a").trigger('click');	  
				}
			});	
		}
	});


	    $(document).on("click", ".removeRequestTab", function(){
	    	var idelm       = $(this).attr('id');
        $('li#closetab'+idelm).fadeOut(0, function () {
            $(this).remove(); // Remove the <li></li> with a fadeout effect
            $('div.tab-content div#tab'+idelm).remove(); // Also remove the correct <div> inside <div class="tab-content">
            $(".aaa").trigger('click');
            window.location.reload();
        });
    });

		/*
		var id      		= $(this).attr('id');
		var plataforma      = $(this).attr('plataforma');
		var descricao      	= $(this).attr('descricao');
		var data      		= $(this).attr('data');
		var editLink		= "index.php?mod=news&op=new1&id="+id;
		var removeUrl       = "css/icons/remove.png";
		var previewUrl      = "css/icons/Preview.png";
		var editUrl      	= "css/icons/edit.png";
		var copyUrl      	= "css/icons/copy01.png";
		var estado      	= $(this).attr('estado');
		var counter 		= 1;

		var newRow = $('<tr><td>' +
            plataforma + ' (copy)</td><td>' +
            descricao + '</td><td>' +
            data + '</td><td>' +
            estado + '</td><td><a href="'+ editLink +'" title="Editar"><img src="'+ editUrl +'"/></a></td></tr>');
			counter++;
		jQuery('table.append').append(newRow);
		*/

</script>