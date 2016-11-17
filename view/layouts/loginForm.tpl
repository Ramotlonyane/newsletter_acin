<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=PLATAFORMA?></title>
    <base href="<?=BASE?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core CSS -->
    <link href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap/js/bootstrap.min.js"></script>

    <!-- Custom CSS -->
    <link href="css/default.css" rel="stylesheet">
    <script type="text/javascript">
    function login()
    {
      if( $("#inputEmail").val().trim()=="" ){
        alert("Insira o Utilizador!");
        return false;
      }
      var user=$("#inputEmail").val().trim();

      if( $("#inputPassword").val().trim()=="" ){
        alert("Insira a password!");
        return false;
      }

      var pass=$("#inputPassword").val().trim();
      $.ajax({
            type: "POST",
            url: "index.php",
            data: "mod=conf&op=login&user="+user+"&pass="+pass,
            success: function(resp)
            {
                try{
                  if(resp.sucesso=="1"){
                    window.location.href = "index.php";
                  }else{
                    throw "erro";
                  }
                }catch(e){
                  alert("Falha no login");
                }
            }
            });
    }
    $("form.form-signin").keyup(function(event){
      if(event.keyCode == 13){
        login();
      }
    });
    </script>
</head>
<body>
    <div class="container" style="width:300px">
      <form class="form-signin" role="form">
        <h2 class="form-signin-heading"><?=PLATAFORMA?></h2>
        <label for="inputEmail" class="sr-only">Utilizador</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Utilizador" required="" autofocus="">
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="">
        <button class="btn btn-lg btn-primary btn-block" type="button" onclick="login()">Login</button>
      </form>
    </div>
</body>
</html>
<style>
.form-signin >input, .form-signin >button{
  margin-bottom: 4px;
}
</style>
