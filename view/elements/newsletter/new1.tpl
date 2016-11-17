
<div class="title">
	Nova newsletter
</div>
<form id="new1" class="form-horizontal new1" onsubmit="return false;">
	<input type="hidden" name="mod" value="news">
	<input type="hidden" name="op" value="save_new1">
	<input type="hidden" name="id" value="<?=$newsletter['id']?>">
	<div class="form-group">
		<label for="idPlataforma" class="col-sm-1 control-label">Plataforma</label>
		<div class="col-sm-11">
			<select name="idPlataforma" class="form-control">
				<option></option>
				<?
				if($plataformas){
					foreach ($plataformas as $p){
						?>
				<option value="<?=$p['id']?>" <?=select_value($newsletter['idPlataforma'],$p['id'])?> ><?=$p['plataforma']?></option>
						<?
					}
				}?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="descricao" class="col-sm-1 control-label">Descrição</label>
		<div class="col-sm-11">
			<input type="text" name="descricao" class="form-control" placeholder="Descrição" value="<?=$newsletter['descricao']?>" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-1 control-label">Contactos</label>
		<div class="col-sm-11 lista_contatos">
			<select name="idContactos[]" id="idContactos" class="form-control idContactos" multiple="multiple" title='Contactos a enivar'>
				<?
				if($contactos){
					$idContactosSelecionados=get_array_from_key_name($newsletter['contactos'],'id');
					foreach ($contactos as $c){
						?>
						<option value="<?=$c['id']?>" <?=in_array($c['id'], $idContactosSelecionados)?" selected ":"" ?> >
							<?=$c['descricao']?> (<?=$c['nContactos']?>)
						</option>
						<?
					}
				}?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="assunto" class="col-sm-1 control-label">Assunto</label>
		<div class="col-sm-11">
			<input type="text" class="form-control" name="assunto" placeholder="Assunto" value="<?=$newsletter['assunto']?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-1 control-label">Ficheiros</label>
		<div id="container" class="col-sm-11">
			<button type="button" id="importarFiles" class="btn btn-default  btn-sm">Importar</button>
			Imagens ou PDF's.
			<div id="filelist">
			O seu browser não é compativel!!
			</div>
			<ul id="listFicheiros" class="listFicheiros">
			<?
			if($newsletter['ficheiros']){
				foreach ($newsletter['ficheiros'] as $f) {
					?>
					<li id='file<?=$f['id']?>'>
	    				<input type='checkbox' value='<?=$f['id']?>' name='anexos[]' <?=checked_value($f['bAnexo'])?> /> [Anexo]
	    				<?=$f['nome']?>
	    				<a target='_blank' href='file.php?id=<?=$f['id']?>&hash=<?=sha1($f['caminho'])?>' >
	    					<img src='css/icons/view.png' />
	    				</a>
	    				<img src='css/icons/remove.png' class='removeFicheiroNewsletter' refid='<?=$f['id']?>' />
		    		</li>
					<?
				}
			}
			?>
			</ul>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-11">
	  		<!--<button type="button" class="btn btn-primary" onclick="wizardNewsletter(true)">Guardar</button>
	  		<button type="button" class="btn btn-success" onclick="wizardNewsletter(false)">Seguinte</button>-->
	  		<button type="button" class="btn btn-primary" onclick="new_guardar()">Guardar</button>
	  		<button type="button" id="<?=$newsletter['id']?>" class="btn btn-success new_seguinte" >Seguinte</button>
		</div>
	</div>
</form>

<script type="text/javascript">
$(document).ready(function() {
	$('.idContactos').selectpicker();
});
function getAtiveTab()
{
	return $(".tab:visible");
}

function wizardNewsletter(guardar)
{	
	tab=getAtiveTab();
	ajax({
		data:tab.find("form.new1").serialize(),
		success:function (obj){
			try{
				if(obj.sucesso==1){
					if(guardar){
						redirect('mod=news&op=home');
					}else{
						redirect('mod=news&op=new2&id=<?=$newsletter['id']?>');
					}
				}else{
					throw "error";
				}
			}catch(e){
				showError("Erro ao processar pedido");
			}
		}
	});
}

function new_guardar()
{

		ajax({
		data:$("form.new1:visible").serialize(),
		success:function (obj){
			try{
				if(obj.sucesso==1){	
						redirect('mod=news&op=home');
						window.location.reload();
				}else{
					throw "erro";
				}
			}catch(e){
				showError("Erro ao processar pedido");
			}
		}
	});
}

$("button.new_seguinte").click(function(){
			event.preventDefault();

	var iditem = $(this).attr('id');
	id = 1;
	if ($(".myTab > li#closetab"+id).length != 0) {
            $(".myTab > li#closetab"+id+" > a").trigger('click');
        }else{
			ajax({
				data:"index.php?mod=news&op=new2&id="+iditem,
				success:function(result){
					descricao="New";
					 $('ul.myTab li:first-child').after('<li class="new_two" id="closetab' + id+ '"><a style="background-color: #d3d3d3;" href="#tab' +id+ '" role="tab" data-toggle="tab">'+descricao+'&nbsp;&nbsp;&nbsp;<button style="color:red;" type="button" class="btn btn-xs removeRequestTab" style="background: none;" id="'+id+'">X</button></a>');
                    $('#edit-newsletter').after('<div id="tab' + id+ '" class="tab-pane fade">'+result+'</div>');
                    $(".myTab > li#closetab"+id+" > a").trigger('click');
                    $('.new_newsletter_tab').hide();
                    $('.edit_newsletter_tab').hide();	  
				}
			});	
			id++;
		}
});


var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'importarFiles', // you can pass in id...
	container: document.getElementById('container'), // ... or DOM Element itself
	url : 'index.php',
	multipart_params :{
		"mod":"news",
		"op":"importFicheiro",
		"idNewsletter":"<?=$newsletter['id']?>"
	},
	flash_swf_url : 'js/plupload/Moxie.swf',
	silverlight_xap_url : 'js/plupload/Moxie.xap',

	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Image files", extensions : "jpg,gif,png"},
			{title : "PDF files", extensions : "pdf"}
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('filelist').innerHTML = '';
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
				uploader.start();
			});
		},

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		},

		Error: function(up, err) {
			console.log(err);
		}
		,
		FileUploaded: function(up, file,obj){
			var res;
			$("div#"+file.id).remove();
		    try {
		    	res = eval('(' + obj.response + ')');
		    	if(res.sucesso){
		    		var li="<li id='file"+res.id+"'>"+
		    				"<input type='checkbox' value='"+res.id+"' name='anexos[]' /> [Anexo] "+
		    				res.nome+
		    				"&nbsp; <a target='_blank' href='file.php?id="+res.id+"&hash="+res.hash+"' > "+
		    					"<img src='css/icons/view.png' /> "+
		    				"</a>"+
		    				"&nbsp;<img src='css/icons/remove.png' class='removeFicheiroNewsletter' refid='"+res.id+"' />"+
		    				"</li>";
		    		$(".listFicheiros").append(li);
		    	}
		    }catch(err){

		    }
		}
	}
});
uploader.init();
$(document).ready(function(){

	$( "ul.listFicheiros " ).on( "click", ".removeFicheiroNewsletter", function() {
	  event.stopPropagation();
		$element=$($(this).get(0));
		var idFicheiro=$element.attr('refid');
		ajax({
			data : "mod=news&op=removerFicheiro&idNewsletter=<?=$newsletter['id']?>&id="+idFicheiro,
			success:function (obj){
				try{
					if(obj.sucesso==1){
						$("#file"+idFicheiro).remove();
					}else{
						throw "erro";
					}
				}catch(e){
					showError("Erro ao processar pedido");
				}
			}
		});
	});
});
</script>
 <style type="text/css">
.lista_contatos > .btn-group{
	width: 100%;
}
.lista_contatos > .btn-group >button{
	width: 100%;
	text-align: left;
}
.removeFicheiroNewsletter:hover{
	cursor: pointer;
}
</style>
