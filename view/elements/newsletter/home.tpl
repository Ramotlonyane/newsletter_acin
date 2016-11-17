<ul id="myTab" class="nav nav-pills tabs myTab">
	<li id="main-li" class="active"><a style="background-color: #d3d3d3;" data-toggle="tab" href="#newsletter" class="aaa">All Newsletter</a></li>
	<div style="text-align:right;padding-bottom:30px;">
		<button onclick="new_newsletter()" type="button" class="btn btn-default">Nova newsletter</button>
	</div>
</ul>
	
	<div class="tab-content">
		<div id="newsletter" class="tab-pane fade in active">

						<form id="formPesquisa">
						<input type="hidden" name="mod" value="news">
						<input type="hidden" name="op" value="list_news">
						<input type="hidden" name="page" value="1">
						<label>Plataforma:</label><br/>
						<select name="idPlataforma" style="width:20%" class="form-control">
							<option></option>
							<?
							if($plataformas){
								foreach ($plataformas as $p){
									?>
							<option value="<?=$p['id']?>"><?=$p['plataforma']?></option>
									<?
								}
							}?>
						</select>
						<br/>
						<input type="button" value="pesquisa" onclick="pesquisa()">
						</form>	<br/>					
						<div id="listaPesquisa" class="resultTable">
							<?
							include "home_lista.tpl"
							?>
						</div>	

		</div>
		<div id="edit-newsletter">
			
		</div>
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
function new_newsletter()
{
	id=1;
	if ($("#myTab > li#closetab"+id).length != 0) {
            $("#myTab > li#closetab"+id+" > a").trigger('click');
        }else{
			ajax({
				data:"index.php?mod=news&op=new1",
				success:function(result){
					descricao="New Newsletter";
					 $('ul#myTab li:first-child').after('<li class="new_newsletter_tab" id="closetab' + id+ '"><a style="background-color: #d3d3d3;" href="#tab' +id+ '" role="tab" data-toggle="tab">'+descricao+'&nbsp;&nbsp;&nbsp;<button style="color:red;" type="button" class="btn btn-xs removeRequestTab" style="background: none;" id="'+id+'">X</button></a>');
                    $('#edit-newsletter').after('<div id="tab' + id+ '" class="tab-pane fade">'+result+'</div>');
                    $("#myTab > li#closetab"+id+" > a").trigger('click');	  
				}
			});
			id++;	
		}
}
</script>
