<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//PT" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt">
<head>
    <title><?=PLATAFORMA?></title>
    <base href="<?=BASE?>">
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <script src="js/jquery.js"></script>

    <!-- Bootstrap -->
    <link href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap/js/bootstrap.min.js"></script>


    <link href="css/default.css" rel="stylesheet">

    <script src="js/bootstrapselect/js/bootstrap-select.min.js"></script>
    <link href="js/bootstrapselect/css/bootstrap-select.min.css" rel="stylesheet">

    <!-- plUpload -->
    <script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

    <script src="js/jqueryUI.1.11.2/jquery-ui.min.js"></script>
    <link href="js/jqueryUI.1.11.2/jquery-ui.min.css" rel="stylesheet">

    <script type="text/javascript" src="js/notificationBar/jquery.toast.js"></script>
    <link rel="stylesheet" href="js/notificationBar/jquery.toast.css" type="text/css"/>

    <script src="js/functions.js"></script>
<script src="js/custom.js"></script>

    <script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>

    <!-- datatables jquery -->
    <link rel="stylesheet" type="text/css" href="js/datatables/datatables.min.css"/>

    <script type="text/javascript" src="js/datatables/datatables.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-default" role="navigation">
      <div class="container">
        <ul class="nav navbar-nav" style="width:100%">
            <li class="">
              <a href="?mod=news&op=home">Newsletter</a>
            </li>
            <li class="">
              <a href="?mod=cont&op=home">Contactos</a>
            </li>
            <li class="">
              <a href="?mod=plat&op=home">Plataformas</a>
            </li>
            <li style="float:right">
              <a href="?mod=conf&op=logout">Sair</a>
            </li>
      </div>
    </nav>
    <div class="container" style="">
      <?
        include PATH."view/elements/$content.tpl";
      ?>
    </div>
</body>
</html>
