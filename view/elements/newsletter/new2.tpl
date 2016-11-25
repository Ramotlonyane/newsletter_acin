<div class="title">
	Nova newsletter: <?=$newsletter['plataforma']?> <?=$newsletter['descricao']?>
</div>
<form class="form-horizontal new2" onsubmit="return false;">
	<input type="hidden" name="mod" value="news">
	<input type="hidden" name="op" value="save_new2">
	<input type="hidden" name="id" value="<?=$newsletter['id']?>">
	<div class="form-group">
		<div class="col-sm-12" style="text-align:right">
			<button type="button" class="btn btn-primary  btn-sm" onclick="showLinks(<?=$newsletter['id']?>)">Links</button>
			<button type="button" class="btn btn-primary  btn-sm" onclick="testarEmail()">Testar</button>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-12">
			<textarea class="conteudo" name="conteudo" id="conteudo"><?=$newsletter['conteudo']?></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-12">
	  		<!--<button type="button" class="btn btn-warning" onclick="wizardNewsletter(1)" >Anterior</button>-->
	  		<button type="button" class="btn btn-warning anterior" >Anterior</button>
	  		<button type="button" class="btn btn-primary" onclick="wizardNewsletter(0)" >Guardar</button>
	  		<? if($_SESSION['bAprovarNewsletter']){?>
	  		<button type="button" class="btn btn-success" onclick="aprovarNewsletter()">Aprovar</button>
	  		<? } ?>
		</div>
	</div>
</form>
<script type="text/javascript">
<?
$fontsize_formats="";
for($i = 4;$i<120; $i++) {
	$fontsize_formats.="{$i}px ";
}
$fontsize_formats=trim($fontsize_formats);
?>
$(document).ready(function() {
	tinymce.init({
	    selector: "textarea.conteudo",
	    theme: "modern",
	    language: "pt_PT",
	    relative_urls : false,
		remove_script_host : false,
		fontsize_formats: "<?=$fontsize_formats?>",
	    //width: 300,
	    valid_children : "+body[style],style,+style",
	    height: 300,
	    plugins: [
	         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
	         "searchreplace visualblocks visualchars code fullscreen insertdatetime nonbreaking",
	         "save table directionality paste textcolor colorpicker"
	   ],
	   content_css: "css/normalize.css",
	   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview code fullpage | forecolor backcolor fontselect fontsizeselect"
	 });
});


	$("button.anterior").click(function(){
		 $('.new_two').hide();
		 $('.edit_newsletter_tab').show();
 		 $('.new_newsletter_tab').show();
         $(".myTab > li.edit_newsletter_tab > a").trigger('click');
         $(".myTab > li.new_newsletter_tab > a").trigger('click');
	});

function wizardNewsletter(passo)
{
	//set textarea value
	var conteudo=window.parent.tinymce.get('conteudo').getContent();
	$(".conteudo").val(conteudo);
	ajax({
		data:$("form.new2:visible").serialize(),
		success:function (obj){
			try{
				if(obj.sucesso==1){
					if(passo==0){
						redirect('mod=news&op=home');
					}else if(passo==1){
						redirect('mod=news&op=new1&id=<?=$newsletter['id']?>');
					}
				}else{
					throw "erro";
				}
			}catch(e){
				showError("Erro ao processar pedido");
			}
		}
	});
}
function showLinks(idNewsletter)
{
	showDialog({
		title:"Links da newsletter",
		data:"mod=news&op=showLinks&idNewsletter="+idNewsletter,
		width:800,
		json:true
	});
}
function insertImage(link)
{
	var img="<img src='"+link+"' >";
	window.parent.tinymce.get('conteudo').execCommand('mceInsertContent', false, img);
	$.modal.close();
}
function insertLink(nome,link)
{
	var a="<a href='"+link+"' >"+nome+"</a>";
	window.parent.tinymce.get('conteudo').execCommand('mceInsertContent', false, a);
	$.modal.close();
}
function testarEmail()
{
	var conteudo=window.parent.tinymce.get('conteudo').getContent();
	$(".conteudo").val(conteudo);

	showDialog({
		title:"Testar email",
		data:$("form.new2").serialize()+"&op=testarEmail",
		width:600,
		json:true
	});
}
<? if($_SESSION['bAprovarNewsletter']){ ?>
function aprovarNewsletter()
{
	var conteudo=window.parent.tinymce.get('conteudo').getContent();
	$(".conteudo").val(conteudo);
	showDialog({
		title:"Aprovar newsletter para envio",
		data:$("form.new2").serialize()+"&op=aprovarNewsletterForm",
		json:true
	});
}
<? } ?>



</script>
 <style type="text/css">
#mceu_2-open,#mceu_20-open,#mceu_21-open{
	padding-top: 2px;
	padding-bottom: 2px;
}
</style>
