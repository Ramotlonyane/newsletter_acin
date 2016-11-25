<!DOCTYPE html>
<html lang="en">
<head>
    <title>ACIN - Remover contacto</title>
    <base href="<?=BASE?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">


    <script src="js/jquery.js"></script>

    <!-- Bootstrap -->
    <link href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap/js/bootstrap.min.js"></script>


    <link href="css/default.css" rel="stylesheet">

    <script src="js/bootstrapselect/js/bootstrap-select.min.js"></script>
    <link href="js/bootstrapselect/css/bootstrap-select.min.css" rel="stylesheet">

    <script src="js/jqueryUI.1.11.2/jquery-ui.min.js"></script>
    <link href="js/jqueryUI.1.11.2/jquery-ui.min.css" rel="stylesheet">

    <script type="text/javascript" src="js/notificationBar/jquery.toast.js"></script>
    <link rel="stylesheet" href="js/notificationBar/jquery.toast.css" type="text/css"/>

    <script src="js/functions.js"></script>

    <script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
</head>
<body>
<section class="fullscreen background"
style="background-image:url('css/shutterstock_3000.jpg');"
 data-img-width="3000" data-img-height="1396">
    <div class="content-a">
        <div class="content-b">
            <div style="">
              <center>
                <table>
                  <tr>
                    <td>
                      <img src='css/icons/aviso.png' style="width:50px" />
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <? if(!$email['bBlacklist']){ ?>
                          Tem a certeza que deseja deixar de receber newsletters e de ser alertado sobre campanhas promocionais relativas a soluções comercializadas pela <b>ACIN iCloud Solutions?</b>
                      <? }else{ ?>
                          O seu email já foi removido da lista de contactos.
                      <? } ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <? if(!$email['bBlacklist']){ ?>
                      <button type="button" class="btn btn-default  btn-sm" onclick="remover_contacto()">Sim</button>
                      &nbsp;&nbsp;
                      <button type="button" class="btn btn-default  btn-sm" onclick="window.location.href='http://www.acin.pt'">Não</button>
                      <? } ?>
                    </td>
                  </tr>
                </table>
            </center>
            </div>
        </div>
    </div>
</section>
<div class="footer">© ACIN iCloud Solutions <?=date('Y')?> - Todos os direitos reservados | Direitos de Propriedade</div>
<script>
function remover_contacto()
{
  $.ajax({
      url: "newsletter.php",
      data: "<?=$_SERVER['QUERY_STRING']?>&remover=1",
      success: function(obj){
        try{
          if(obj.sucesso==1){
            alerta("Removido com sucesso!",function(){
              window.location.reload();
            });
          }else{
            throw "erro";
          }
        }catch(e){
          alerta("Ocorreu um erro ao remover o contacto. Por favor tente novamente ou envie um email para 'info@acin.pt' com a palavra remover.",function(){
              window.location.reload();
            });
        }
      },
      error: function() {
          alerta("Ocorreu um erro ao remover o contacto. Por favor tente novamente ou envie um email para 'info@acin.pt' com a palavra remover.",function(){
              window.location.reload();
            });
      }
  });
}


/* fix vertical when not overflow
call fullscreenFix() if .fullscreen content changes */
function fullscreenFix(){
    var h = $('body').height();
    // set .fullscreen height
    $(".content-b").each(function(i){
        if($(this).innerHeight() <= h){
            $(this).closest(".fullscreen").addClass("not-overflow");
        }
    });
}
$(window).resize(fullscreenFix);

/* resize background images */
function backgroundResize(){
    var windowH = $(window).height();
    $(".background").each(function(i){
        var path = $(this);
        // variables
        var contW = path.width();
        var contH = path.height();
        var imgW = path.attr("data-img-width");
        var imgH = path.attr("data-img-height");
        var ratio = imgW / imgH;
        // overflowing difference
        var diff = parseFloat(path.attr("data-diff"));
        diff = diff ? diff : 0;
        // remaining height to have fullscreen image only on parallax
        var remainingH = 0;
        if(path.hasClass("parallax")){
            var maxH = contH > windowH ? contH : windowH;
            remainingH = windowH - contH;
        }
        // set img values depending on cont
        imgH = contH + remainingH + diff;
        imgW = imgH * ratio;
        // fix when too large
        if(contW > imgW){
            imgW = contW;
            imgH = imgW / ratio;
        }
        //
        path.data("resized-imgW", imgW);
        path.data("resized-imgH", imgH);
        path.css("background-size", imgW + "px " + imgH + "px");
    });
}
$(window).resize(backgroundResize);
$(window).focus(backgroundResize);

$(document).ready(function(){
  backgroundResize();
  fullscreenFix();
});

</script>
<style>
.form-signin >input, .form-signin >button{
  margin-bottom: 4px;
}
.footerText {
  color: #B9B9B9;
  font-size: 12px;
  padding-bottom: 10px;
  text-align: center;
}
.buttons-blacklist{
  padding-top: 10px;
  text-align:right;
}
.buttons-blacklist>button{
  border-radius: 15px;
  padding: 3px 20px;
}
.panel-body {
  padding: 15px 20px;
}


/* background setup */
.background {
    background-repeat:no-repeat;
    /* custom background-position */
    background-position:50% 50%;
    /* ie8- graceful degradation */
    background-position:50% 50%9 !important;
}
/* fullscreen setup */
html, body {
    /* give this to all tags from html to .fullscreen */
    height:100%;
}
.fullscreen,
.content-a {
    width:100%;
    min-height:100%;
}
.content-b>div{
  background:#337AB7;
  background: rgba(51, 122, 183, 0.87);
  height:185px;
  vertical-align:middle;
  color: white;
  text-align: center;
}
.content-b>div table{
  width: 440px;
  text-align: center;
}
.content-b>div td{
  text-align: center;
  padding-top: 10px;

}

.not-fullscreen,
.not-fullscreen .content-a,
.fullscreen.not-overflow,
.fullscreen.not-overflow .content-a {
    height:100%;
    overflow:hidden;
}

/* content centering styles */
.content-a {
    display:table;
}
.content-b {
    display:table-cell;
    position:relative;
    vertical-align:middle;
    text-align:center;
    padding-top: 150px;
}
.content-b .btn{
background-color: transparent;
color: white;
border-radius: 15px;
width: 60px;
}
.footer{
  position: absolute;
  bottom: 0;
  text-align: center;
  width: 100%;
  color: #ADADAD;
  display: none;
}
</style>
</body>
</html>
