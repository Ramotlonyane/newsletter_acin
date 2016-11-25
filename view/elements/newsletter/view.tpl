<div class="title">
	Newsletter: <?=$newsletter['plataforma']?> <?=$newsletter['descricao']?>
</div>
<form id="new" class="form-horizontal" onsubmit="return false;">
	<input type="hidden" id="idNewsletter" value="<?=$newsletter['id']?>">
	<div class="form-group">
		<label class="col-sm-2 control-label">Plataforma</label>
		<div class="col-sm-10">
			<span ><?=$newsletter['plataforma']?></span>
		</div>
	</div>
	<? if(!empty($newsletter['descricao'])){?>
	<div class="form-group">
		<label class="col-sm-2 control-label">Descrição</label>
		<div class="col-sm-10">
			<span ><?=$newsletter['descricao']?></span>
		</div>
	</div>
	<? } ?>
	<div class="form-group">
		<label class="col-sm-2 control-label">Assunto</label>
		<div class="col-sm-10">
			<span ><?=$newsletter['assunto']?></span>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Contactos</label>
		<div class="col-sm-10" >
			<?
			if($newsletter['contactos']){
				foreach ($newsletter['contactos'] as $c) {
					echo $c['descricao']."(".$c['nContactos'].") ";
				}
			}?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Anexos</label>
		<div class="col-sm-10">
			<ul class="list-unstyled">
			<?
			if($newsletter['ficheiros']){
				foreach ($newsletter['ficheiros'] as $f) {
					if($f['bAnexo']){
						?>
						<li><?=$f['nome']?>
							<a target='_blank' href='file.php?id=<?=$f['id']?>&hash=<?=sha1($f['caminho'])?>' >
		    					<img src='css/icons/view.png' />
		    				</a>
						</li>
						<?
					}
				}
			}?>
			</ul>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Conteudo</label>
		<div class="col-sm-10">
			<?
			$link=URL."/newsletter.php?id=".$newsletter['id']."&hash=".$newsletter['hash'];
			?>
			<a href="<?=$link?>" target="_blank"><button type="button" class="btn btn-default  btn-sm" >Previsualizar</button></a>
			<a href="<?=$link?>&code=1" target="_blank"><button type="button" class="btn btn-default  btn-sm" >Código</button></a>
			<button  type="button" class="btn btn-default  btn-sm" onclick="testarEmail()">Testar</button>
		</div>
	</div>
	<? if(!empty($newsletter['observacoes'])){?>
		<div class="form-group">
			<label for="assunto" class="col-sm-1 control-label">Observação</label>
			<div class="col-sm-11">
				<?=$newsletter['observacoes']?>
			</div>
		</div>
	<? } ?>
		<div class="form-group">
			<label for="assunto" class="col-sm-2 control-label">Estado</label>
			<div class="col-sm-10">
				<?=$newsletter['estado']?>
				<? if($newsletter['idEstado']=="2"){?>
					<a id="cancelarBTN" onclick="cancelarNewsletter()">(Cancelar)</a>
				<? } ?>
			</div>
		</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Envio</label>
		<div class="col-sm-10">
			<div id="progressbar"><div class="progress-label">Loading...</div></div>
		</div>
		<iframe id="envio_iframe" src="" style="display:none;width:0px;height:0px"></iframe>
		<div class="col-sm-1">
			<button id="enviarBtn" style="" type="button" class="btn btn-success  btn-sm" onclick="enviar_newsletter()">Enviar</button>
			<button id="pararBtn" style="display:none" type="button" class="btn btn-danger  btn-sm" onclick="parar_newsletter()">Parar</button>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-11">
			Processado:
			<span class="nProcessados"></span> /
			<span class="nContactos"></span>
			<br/>
			Sucesso: <span class="nEnvioSucesso"></span><br/>
			Erro: <span class="nEnvioErro"></span>
		</div>
	</div>
</form>
<div class="title">
	Relatório
</div>
<div>
	<table id="relatorio" class="table table-striped table-bordered" width="100%" cellspacing="0">
		<thead>
            <tr>
                <th>Tipo</th>
                <th>Nome</th>
                <th>Visualizações</th>
            </tr>
        </thead>
        <tbody>
        	<?php foreach($relatorio as $linha){?>
            <tr>
                <td><?=$linha["tipo"]?></td>
                <td><?=$linha["nome"]?></td>
                <td><?=$linha["visualizacoes"]?></td>
            </tr>
            <?php } ?>
        </tbody>
	</table>
</div>
<script type="text/javascript">

function showLinks(idNewsletter)
{
	showDialog({
		title:"Links da newsletter",
		data:"mod=news&op=showLinks&idNewsletter="+idNewsletter,
		width:600,
		json:true
	});
}
function testarEmail()
{
	showDialog({
		title:"Testar email",
		data:"mod=news&op=testarEmailForm&id=<?=$newsletter['id']?>",
		width:600,
		json:true
	});
}
function update_dados_envio(obj)
{
	try{
		$(".nProcessados").html(obj.nProcessados);
		$(".nContactos").html(obj.nContactos);
		$(".nEnvioSucesso").html(obj.nEnvioSucesso);
		$(".nEnvioErro").html(obj.nEnvioErro);


    	var per = (dados_envio.nProcessados/dados_envio.nContactos);
    	per=per * 100;
    	if(dados_envio.nContactos==0){
    		per="100";
    	}
    	progressbar.progressbar( "value", parseInt(per) );

	}catch(e){
		console.log(e);
	}
}

var progressbar = $( "#progressbar" );
var progressLabel = $( ".progress-label" );
var bEnviando = false;
var dados_envio={
	nProcessados:<?=$newsletter['envio']['nProcessados']?>,
	nContactos:<?=$newsletter['envio']['nContactos']?>,
	nEnvioSucesso:<?=$newsletter['envio']['nEnvioSucesso']?>,
	nEnvioErro:<?=$newsletter['envio']['nEnvioErro']?>
}
function enviar_newsletter()
{
	bEnviando=true;
	$("#enviarBtn").hide();
	$("#pararBtn").show();
	_enviar_iframe();
}
function parar_newsletter()
{
	bEnviando=false;
	$("#pararBtn").hide();
	$("#enviarBtn").show();
    progressLabel.text( progressbar.progressbar( "value" ) + "%" );
}
function _enviar_iframe()
{
	if(dados_envio.nProcessados==dados_envio.nContactos){
		bEnviando=false;
		$("#pararBtn").hide();
		$("#enviarBtn").hide();
	}
	if(bEnviando){
		var idNewsletter=$("#idNewsletter").val();
		$("#envio_iframe").attr('src',"index.php?mod=news&op=envio_newsletter&nEmails=30&id="+idNewsletter);
	}
}
function _envio_sucesso()
{
	dados_envio.nProcessados++;
	dados_envio.nEnvioSucesso++;
	update_dados_envio(dados_envio);
}
function _envio_erro()
{
	dados_envio.nProcessados++;
	dados_envio.nEnvioErro++;
	update_dados_envio(dados_envio);
}

$(document).ready(function(){
	progressbar.progressbar({
      value: false,
      change: function() {
      	text="";
      	if(bEnviando){
      		text=" (enviando...)";
      	}
        progressLabel.text( progressbar.progressbar( "value" ) + "%"+text );
      },
      complete: function() {
        progressLabel.text( "Enviado!" );

		$("#cancelarBTN").hide();
		$("#pararBtn").hide();
		$("#enviarBtn").hide();
      }
    });
	update_dados_envio(dados_envio);
	<? if($newsletter['idEstado']!="2"){ ?>
		$("#pararBtn").hide();
		$("#enviarBtn").hide();
	<? } ?>

	$('#relatorio').DataTable({
		language: {
			url: 'js/datatables/dataTables.portuguese.json'
		}
	});
});

function cancelarNewsletter()
{
	confirm("Deseja cancelar o envio desta newsletter?",function(){
		var idNewsletter=$("#idNewsletter").val();
		ajax({
			data:"mod=news&op=cancelarNewsletter&id="+idNewsletter
			,success:function (obj){
				try{
					if(obj.sucesso==1){
						redirect('mod=news&op=view&id='+idNewsletter);
					}
				}catch(e){}
			}
		});
	});
}

</script>
 <style type="text/css">
.form-horizontal .control-label{
	padding-top: 0;
}
</style>
