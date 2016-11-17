<form id="aprovarForm" class="form-horizontal" onsubmit="return false;">
	<input type="hidden" name="mod" value="news">
	<input type="hidden" name="op" value="aprovarNewsletter">
	<input type="hidden" name="id" value="<?=$newsletter['id']?>">
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
		<label class="col-sm-2 control-label">Contactos</label>
		<div class="col-sm-10">
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
			<button style="display:none" type="button" class="btn btn-default  btn-sm" onclick="testarEmail()">Testar</button>
		</div>
	</div>
	<div class="form-group">
		<label for="assunto" class="col-sm-2 control-label">Observação</label>
		<div class="col-sm-10">
			<textarea class="form-control" name="observacoes"></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
	  		<button type="button" class="btn btn-success" onclick="aprovar_newsletter()" >Aprovar</button>
	  		<button type="button" class="btn btn-danger" onclick="$.modal.close()">Cancelar</button>
		</div>
	</div>
</form>
<script type="text/javascript">
function aprovar_newsletter()
{
	ajax({
		data:$("#aprovarForm").serialize(),
		loading:true,
		success:function (obj){
			try{
				if(obj.sucesso==1){
					redirect('mod=news&op=view&id=<?=$newsletter['id']?>');
				}else{
					throw "erro";
				}
			}catch(e){
				showError("Erro ao processar pedido");
			}
		}
	})
}

</script>
