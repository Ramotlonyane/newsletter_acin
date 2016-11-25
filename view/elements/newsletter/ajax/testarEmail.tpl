<form id="testarEmailForm" class="form-horizontal" onsubmit="return false;">
	<input type="hidden" name="mod" value="news">
	<input type="hidden" name="op" value="enviar_email_teste">
	<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
	<div class="form-group">
		<label for="idPlataforma" class="col-sm-1 control-label">Email</label>
		<div class="col-sm-11">
			<input type="text" name="email" class="form-control" placeholder="Email" value="<?=$_SESSION['email']?>" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-11">
	  		<button type="button" class="btn btn-success" onclick="envio_email_testes()" >Enviar</button>
	  		<button type="button" class="btn btn-danger" onclick="$.modal.close()">Cancelar</button>
		</div>
	</div>
</form>
<script type="text/javascript">
function envio_email_testes()
{
	var email=$("#testarEmailForm input[name=email]").val();
	if(empty(email)){
		alerta("Introduza um email!");
		return false;
	}
	if(!validateEmail(email)){
		alerta("Introduza um email correto!");
		return false;
	}
	ajax({
		data:$("#testarEmailForm").serialize(),
		loading:true,
		success:function (obj){
			try{
				if(obj.sucesso==1){
					showMsg("Email enviado para "+email );
					$.modal.close();
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
