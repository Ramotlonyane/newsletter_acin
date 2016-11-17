<?
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<script>
<?
if(isset($dados)){ ?>
    var dados=<?=json_encode($dados)?>;
    parent.update_dados_envio(dados);
    parent._enviar_iframe();
<? }else{
        if($res){ ?>
            parent._envio_sucesso();
    <? }else{ ?>
            parent._envio_erro();
    <? }
}
?>
</script>

</body>
</html>
