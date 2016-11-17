<?php

/**
 * funcoes diversas
 * exemplos:
 * gerar password aleatoria
 */

/**
 * Escreve no ficheiro error log apenas em ambiente de desenvolvimento
 * @param  [type] $msg contém a mensagem de erro.
 * @return [type]      [description]
 */
function log_error($msg)
{
    if (SERVIDOR_DEV == 1)
    {
        $data = debug_backtrace();
        $fullPath = $data[0]['file'];
        $pos = strpos($fullPath, '/imed/') ? strpos($fullPath, 'imed/') : strpos($fullPath, 'igest/');
        $file = substr($fullPath, $pos, strlen($fullPath));
        $line = $data[0]['line'];

        //Se for array faz o dump.
        if (is_array($msg))
        {
            ob_start();
            var_dump($msg);
            $msg = ob_get_clean();
        }

        error_log($msg . "  in " . $file . " on line " . $line);
    }
}
function le($msg){
    if (SERVIDOR_DEV == 1)
    {
        $data = debug_backtrace();
        $fullPath = $data[0]['file'];
        $pos = strpos($fullPath, '/imed/') ? strpos($fullPath, 'imed/') : strpos($fullPath, 'igest/');
        $file = substr($fullPath, $pos, strlen($fullPath));
        $line = $data[0]['line'];

        //Se for array faz o dump.
        if (is_array($msg))
        {
            ob_start();
            var_dump($msg);
            $msg = ob_get_clean();
        }

        error_log($msg . "  in " . $file . " on line " . $line);
    }
}
/**
 * faz deubg de uma variavel
 *
 * @param mixed $var
 */
function vd($var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}
function num_format($num)
{
    return number_format($num, 2, ',', '.');
}
function num_unformat($num)
{
    $num = str_replace('.', '', $num);
    $num = str_replace(',', '.', $num);
    return $num;
}
function arredondado($numero, $decimais)
{
    $fator = pow(10, $decimais);
    return (round($numero * $fator) / $fator);
}

/**
 * retorna URL da aplicação
 *
 * @param string $params (optional)
 *
 * @return string
 */
function get_url($params = null)
{

    // encode & into &amp; (decode first to make sure there is no double encoding)
    $params = htmlspecialchars(htmlspecialchars_decode($params));

    // base url
    $url = URL . '/index.php';

    // add params?
    if (isset($params)) $url.= '?' . $params;

    // return
    return $url;
}

/**
 * cria um hash aleatória
 *
 * @param string $params
 *
 * @return string
 */
function redirect($params)
{

    // criar hash
    $url = $url = URL . '/index.php';
    if (isset($_SESSION['mod_phone']) && ($_SESSION['mod_phone']))
    {
        $url = str_ireplace("mobile/", "mobilephone", $url);
    }

    // add params?
    if (isset($params)) $url.= '?' . htmlspecialchars_decode($params);

    // redireccionar
    header('Location: ' . $url);

    // make sure script dies here
    die();
}

/**
 * mensagem de erro através do template error.tpl
 *
 * @param unknown_type $msg
 */
function display_fatal($msg, $params = null)
{

    // default url
    $url = get_url();

    // preparar template
    Reg::$out->assign('error_message', $msg);

    // escolher TPL para conteúdo
    Reg::$out->assign('content', 'error');

    // enviar mensagem
    Reg::$out->assign('type', 'error');
    Reg::$out->assign('message', $msg);
    Reg::$out->assign('url', $url);

    // mostrar template
    echo Reg::$out->display('layouts/message.tpl');

    // terminar script
    die();
}

/**
 * mensagem de erro através dos layout error.tpl
 *
 * @param string $msg
 * @param string $params (opcional) para onde direccionar os utilizadores através de um link
 */
function display_error($msg, $params = null)
{

    // default url
    $url = get_url($params);

    // preparar template
    Reg::$out->assign('error_message', $msg);

    // escolher TPL para conteúdo
    Reg::$out->assign('content', 'error');

    // enviar mensagem
    Reg::$out->assign('type', 'error');
    Reg::$out->assign('message', $msg);
    Reg::$out->assign('url', $url);

    // mostrar template
    echo Reg::$out->display('layouts/message.tpl');

    // terminar script
    die();
}

/**
 * mensagem de erro através dos layout message.tpl
 *
 * @param string $msg
 * @param string $params (opcional) para onde direccionar os utilizadores através de um link
 */
function display_message($msg, $params = null)
{

    // default url
    $url = get_url($params);

    // preparar template
    Reg::$out->assign('error_message', $msg);

    // escolher TPL para conteúdo
    Reg::$out->assign('content', 'error');

    // enviar mensagem
    Reg::$out->assign('type', 'info');
    Reg::$out->assign('message', $msg);
    Reg::$out->assign('url', $url);

    // mostrar template
    echo Reg::$out->display('layouts/message.tpl');

    // terminar script
    die();
}

/**
 * cria um hash aleatória
 *
 * @return string
 */
function make_hash()
{

    // criar hash
    $hash = md5(serialize($_REQUEST) . serialize($_SESSION) . mktime());

    // terminar script
    return $hash;
}

/**
 * cria uma password aleatória com o comprimento pedido
 *
 * @param integer $length
 *
 * @return string
 */
function make_password($length)
{

    // start with a blank password
    $password = "";

    // define possible characters
    $possible = "0123456789bcdfghjkmnpqrstvwxyz";

    // set up a counter
    $i = 0;

    // add random characters to $password until $length is reached
    while ($i < $length)
    {

        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);

        // we don't want this character if it's already in the password
        if (!strstr($password, $char))
        {
            $password.= $char;
            $i++;
        }
    }

    // done!
    return $password;
}

/** Miguel Quintal 2008
 *
 * função que faz um Resize ao uma imagem, basta mandar uma lagura uma
 * altura e um tamanho pertendido para a imagem
 */
function imageResize($largura, $altura, $tamanho)
{

    // verifica se a imagem tem mais largura que altura
    if ($largura > $altura)
    {

        // guarda na variável $percentagem o resultado do tamanho a dividir pela largura
        $percentagem = ($tamanho / $largura);
    }

    // se tiver mais altura que largura
    else
    {

        // guarda na variável $percentagem o resultado do tamanho a dividir pela altura
        $percentagem = ($tamanho / $altura);
    }

    // guarda na variável $largura o resultado da largura * a percentagem,
    // este resultado é sempre convertido em inteiro
    $largura = round($largura * $percentagem);

    // guarda na variável $largura o resultado da altura * a percentagem,
    // este resultado é sempre convertido em inteiro
    $altura = round($altura * $percentagem);

    // retorna a largura e altura que a imagem vai ter
    return "width=\"$largura\" height=\"$altura\"";
}

function img_ResizePub($target)
{
    if (file_exists(MEDICAMENTOS_IMAGENS . $target . ".gif"))
    {
        $imgsize = getimagesize(MEDICAMENTOS_IMAGENS . $target . ".gif");
        if ($imgsize[0] > MEDICAMENTOS_IMAGENS_WIDTH)
        {
            return " width=\"" . MEDICAMENTOS_IMAGENS_WIDTH . "\" ";
        }
        if ($imgsize[1] > MEDICAMENTOS_IMAGENS_HEIGHT)
        {
            return " height=\"" . MEDICAMENTOS_IMAGENS_HEIGHT . "\" ";
        }
    }
    return "";
}
function imageRestrict($image, $maxwidth = 150, $maxheight = 150)
{
    $str = "";
    list($width, $height) = getimagesize($image);
    if ($height > $maxheight)
    {
        $str.= ' height="' . $newheight . '" ';
    }

    if ($width > $maxwidth)
    {
        $str.= 'width="' . $width . '" ';
    }
    return $str;
}
function valid_password($text)
{
    if (strlen($text) < 8)
    {
        return "A password deve conter no minimo 8 caracteres";
    }
    if (strlen($text) > 16)
    {
        return "A password não deve ultrapassar os 16 caracteres";
    }
    if (is_numeric($text) || preg_match('%^[A-Za-z]+$%', $text))
    {
        return "A password deve conter letras e números";
    }
    return "";
}

/**
 * Retorna uma div com a paginação para navegar na listagem de dados
 * @param  [type] $page  página atual
 * @param  [type] $pages numero total de páginas
 * @param  [type] $http  link do botão
 * @param  string $nReg  número final de registos
 * @param  string $funct função javascript para ser executada
 * @return [html]        [description]
 */
function listPages($page, $pages, $http, $nReg = "", $funct = "")
{
    if (!$page)
    {
        $page = 1;
    }
    $functHtml1 = "";
    $functHtml2 = "";
    $pages = (int)$pages;
    if ($pages <= 1)
    {
        return;
    }
    $html = "";
    $html.= "<div class='n_pagina_div'>";
    if (!empty($nReg))
    {
        $html.= "<div style='float:left'><span style='text-align:left' >" . $nReg . " Registo(s)&nbsp;&nbsp;&nbsp;</span></div>";
    }
    if ($pages > 1)
    {
        if ($page > 2)
        {
            if ($funct != "")
            {
                $functHtml1 = " onclick='$funct(event, 1)' ";
                $functHtml2 = " onclick='$funct(event, " . intval($page - 1) . ")' ";
            }
            $html.= " <a class='arrow_ll' href='" . $http . "&page=1' $functHtml1 ></a>
                <a class='arrow_l' href='" . $http . "&page=" . intval($page - 1) . "' $functHtml2 ></a> ";
        }
        for ($i = 1; ($i < $pages && $i < 4); $i++)
        {
            if ($funct != "")
            {
                $functHtml = " onclick='$funct(event, " . intval($i) . ")' ";
            }
            if ($page != $i)
            {
                $html.= " <a href='" . $http . "&page=" . intval($i) . "' $functHtml >" . intval($i) . "</a>";
            } else
            {
                $html.= " <span>" . intval($i) . "</span>";
            }
        }
        if ($i <= $page - 5)
        {
            $html.= " <span class='more'>...</span> ";
            $i = $page - 5;
        }
        for ($i; ($i <= $page + 5 && $i < $pages); $i++)
        {
            if ($funct != "")
            {
                $functHtml = " onclick='$funct(event, " . intval($i) . ")' ";
            }
            if ($page != $i)
            {
                $html.= " <a href='" . $http . "&page=" . intval($i) . "' $functHtml >" . intval($i) . "</a>";
            } else
            {
                $html.= " <span >" . intval($i) . "</span>";
            }
        }
        if ($i < ($pages - 3))
        {
            $html.= " <span class='more'>...</span> ";
            $i = $pages - 3;
        }
        for ($i; ($i <= $pages); $i++)
        {
            if ($funct != "")
            {
                $functHtml = " onclick='$funct(event, " . intval($i) . ")' ";
            }
            if ($page != $i)
            {
                $html.= " <a href='" . $http . "&page=" . intval($i) . "' $functHtml >" . intval($i) . "</a>";
            } else
            {
                $html.= " <span >" . intval($i) . "</span>";
            }
        }

        if ($page < $pages - 1)
        {
            if ($funct != "")
            {
                $functHtml1 = " onclick='$funct(event, " . intval($page + 1) . ")' ";
                $functHtml2 = " onclick='$funct(event, " . intval($pages) . ")' ";
            }
            $html.= " <a class='arrow_r'  href='" . $http . "&page=" . intval($page + 1) . "' $functHtml1 ></a>
                          <a  class='arrow_rr'  href='" . $http . "&page=" . intval($pages) . "' $functHtml2 ></a>";
        }
    }
    $html.= "</div>";
    echo $html;
}

function icon_sexo($tipo)
{
    if ($tipo == "M")
    {
        echo "<img src='css/images/m.png' width='15px' />";
    } else
    {
        if ($tipo == "F")
        {
            echo "<img src='css/images/f.png' width='15px' />";
        }
    }
}

function utf8_encode_all($dat)
 // -- It returns $dat encoded to UTF8

{
    $aux = $dat;
    utf8_encode_deep($aux);
    return $aux;
}
function utf8_encode_deep(&$input)
{
    if (is_string($input))
    {
        $input = utf8_encode($input);
    } else if (is_array($input))
    {
        foreach ($input as & $value)
        {
            utf8_encode_deep($value);
        }

        unset($value);
    } else if (is_object($input))
    {
        $vars = array_keys(get_object_vars($input));

        foreach ($vars as $var)
        {
            utf8_encode_deep($input->$var);
        }
    }
}
function utf8_decode_all($dat)
 // -- It returns $dat decoded from UTF8

{
    if (is_string($dat)) return utf8_decode($dat);
    if (!is_array($dat)) return $dat;
    $ret = array();
    foreach ($dat as $i => $d) $ret[$i] = utf8_decode_all($d);
    return $ret;
}
function testIE6()
{
    if ((strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.') !== false))
    {
        return true;
    } else
    {
        return false;
    }
}
function numero_extenso($valor = 0, $maiusculas = false, $femenino = false, $moeda = false)
{

    if ($moeda)
    {
        $singular = array("centimo", "euro", "mil", "milh&#xE3;o", "bilh&#xE3;o", "trilh&#xE3;o", "quatrilh&#xE3;o");
        $plural = array("centimos", "euros", "mil", "milh&#xF5;es", "bilh&#xF5;es", "trilh&#xF5;es", "quatrilh&#xF5;es");
    } else
    {
        $singular = array("centimo", "", "mil", "milh&#xE3;o", "bilh&#xE3;o", "trilh&#xE3;o", "quatrilh&#xE3;o");
        $plural = array("centimos", "", "mil", "milh&#xF5;es", "bilh&#xF5;es", "trilh&#xF5;es", "quatrilh&#xF5;es");
    }

    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
    if (!$femenino)
    {
        $u = array("", "um", "dois", "tr&ecirc;s", "quatro", "cinco", "seis", "sete", "oito", "nove");
    } else
    {
        $u = array("", "uma", "duas", "tr&ecirc;s", "quatro", "cinco", "seis", "sete", "oito", "nove");
    }

    $z = 0;
    $rt = "";

    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);
    for ($i = 0; $i < count($inteiro); $i++) for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++) $inteiro[$i] = "0" . $inteiro[$i];

    $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
    for ($i = 0; $i < count($inteiro); $i++)
    {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
        $t = count($inteiro) - 1 - $i;
        $r.= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($valor == "000") $z++;
        elseif ($z > 0) $z--;
        if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) $r.= (($z > 1) ? " de " : "") . $plural[$t];
        if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
    }
    $rt = trim($rt);
    if (!$maiusculas)
    {
        return ($rt ? $rt : "zero");
    } else
    {

        if ($rt) $rt = ereg_replace(" E ", " e ", ucwords($rt));
        return strtoupper(($rt) ? ($rt) : "Zero");
    }
}
function calc_idade($p_strDate)
{
    list($Y, $m, $d) = explode("-", $p_strDate);
    return (date("md") < $m . $d ? date("Y") - $Y - 1 : date("Y") - $Y);
}
function calculateSize($size, $sep = ' ')
{
    $unit = null;
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    for ($i = 0, $c = count($units); $i < $c; $i++)
    {
        if ($size > 1024)
        {
            $size = $size / 1024;
        } else
        {
            $unit = $units[$i];
            break;
        }
    }

    return round($size, 2) . $sep . $unit;
}
function strtolowerPT($str)
{
    $str = strtolower($str);
    $charU = array('Á', 'À', 'Ã', 'É', 'È', 'Ê', 'Í', 'Ì', 'Ó', 'Ò', 'Ô');
    $charL = array('á', 'à', 'ã', 'é', 'è', 'ê', 'í', 'ì', 'ó', 'ò', 'ô');
    $str = str_ireplace($charU, $charL, $str);

    return $str;
}
function js_str($str)
{
    $str = nl2br($str);
    $str = ereg_replace("/\n\r|\r\n|\n|\r/", "", $str);
    $str = str_ireplace('\\', "\\\\", $str);
    $str = str_ireplace("'", "\'", $str);
    return $str;
}
function getDiaSemanaExtenso($data = null)
{
    if ($data == null || $data == '') $data = date('Y-m-d');
    $dataInt = strtotime($data);

    $array = array('', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sabado', 'Domingo');
    $dia = date('N', $dataInt);
    return $array[$dia];
}
function getMesExtenso($data = null)
{
    if ($data == null || $data == '') $data = date('Y-m-d');

    $dataInt = strtotime($data);

    $array = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
    $mes = date('n', $dataInt);
    return $array[$mes];
}
function getDataExtenso($fomat = 1, $data = null)
{
    if ($data == null || $data == '') $data = date('Y-m-d');
    $dataInt = strtotime($data);

    if ($fomat == 1)
    {
        $data = date(' j') . " de " . getMesExtenso($data) . " de " . date('Y', $dataInt);
    } elseif ($fomat == 2)
    {
        $data = getDiaSemanaExtenso($data) . date(' j', $dataInt) . " de " . getMesExtenso($data) . " de " . date('Y', $dataInt);
    }
    return $data;
}
function format_date($data = null, $format = 'completa')
{
    if ($data == null || $data == '') $data = date('Y-m-d');
    $dataInt = strtotime($data);

    if ($format == 'extenso')
    {
        $data = getDataExtenso(1, $data);
    } elseif ($format == 'extenso2')
    {
        $data = getDataExtenso(2, $data);
    } elseif ($format == 'completa')
    {
        $data = date('d/m/Y', $dataInt);
    } elseif ($format == 'dia_semana')
    {
        $data = getDiaSemanaExtenso($data);
    } elseif ($format == 'dia')
    {
        $data = date('j', $dataInt);
    } elseif ($format == 'mes')
    {
        $data = date('n', $dataInt);
    } elseif ($format == 'mes_extenso')
    {
        $data = getMesExtenso($data);
    } elseif ($format == 'ano')
    {
        $data = date('Y', $dataInt);
    }

    return $data;
}
function getShortDate($data = null)
{
    if ($data == null || $data == '') $data = date('Y-m-d');

    $dataInt = strtotime($data);

    $array = array('', 'Jan', 'Fev', 'Mar', 'Abr', 'Maio', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez');

    $mes = date('n', $dataInt);
    $mes = $array[$mes];
    $dia = date('j', $dataInt);

    if( date('Y') != date("Y",$dataInt) ){
        $msg=$mes." ".date("Y",$dataInt);
    }else{
        $msg=$dia." ".$mes;
    }
    return $msg;
}
function js2PhpTime($jsdate)
{
    if (preg_match('@(\d+)/(\d+)/(\d+)\s+(\d+):(\d+)@', $jsdate, $matches) == 1)
    {
        $ret = mktime($matches[4], $matches[5], 0, $matches[1], $matches[2], $matches[3]);
    } else if (preg_match('@(\d+)/(\d+)/(\d+)@', $jsdate, $matches) == 1)
    {
        $ret = mktime(0, 0, 0, $matches[1], $matches[2], $matches[3]);
    }
    return $ret;
}
function php2JsTime($phpDate)
{
    return date("m/d/Y H:i", $phpDate);
}
function php2JsDateTime($date)
{
    $date = strtotime($date);
    return date("Y-m-dTH:i:s", $date);
}
function php2MySqlTime($phpDate)
{
    return date("Y-m-d H:i:s", $phpDate);
}
function mySql2PhpTime($sqlDate)
{
    $arr = date_parse($sqlDate);
    return mktime($arr["hour"], $arr["minute"], $arr["second"], $arr["month"], $arr["day"], $arr["year"]);
}
function lim_Str($str, $numberChar)
{
    if (strlen($str) > $numberChar)
    {
        return substr($str, 0, $numberChar) . "...";
    } else
    {
        return $str;
    }
}

function createBackupFile($startLoc, $ext = "", $bHora = false, $prefix = "")
{
    if ($ext != "" && substr($ext, 0, 1) == ".")
    {
        $ext = substr($ext, 1);
    }
    $raiz = "";
    if (strlen($startLoc) > 0 && substr($startLoc, 0, 1) == "/")
    {
        $raiz = "/";
    }
    $dirs = explode('/', $startLoc);
    $startLoc = "";
    if ($dirs)
    {
        foreach ($dirs as $d)
        {
            if ($d != "")
            {
                if ($startLoc == "")
                {
                    $startLoc = $raiz . $d;
                } else
                {
                    $startLoc = $startLoc . "/" . $d;
                }
                if (!is_dir($startLoc))
                {
                    mkdir($startLoc, 0777);
                }
            }
        }
    }
    $dir = $startLoc;
    if (!is_dir($dir))
    {
        mkdir($dir, 0777);
    }
    $dir = $dir . "/" . date('Y');
    if (!is_dir($dir))
    {
        mkdir($dir, 0777);
    }
    $dir = $dir . "/" . date('m');
    if (!is_dir($dir))
    {
        mkdir($dir, 0777);
    }
    $dir = $dir . "/" . date('d');
    if (!is_dir($dir))
    {
        mkdir($dir, 0777);
    }
    if ($bHora)
    {
        $dir = $dir . "/" . date('H');
        if (!is_dir($dir))
        {
            mkdir($dir, 0777);
        }
    }
    $dir = $dir . "/";
    $file = $prefix . mt_rand() . ".$ext";
    while (is_file($dir . $file))
    {
        $file = $prefix . mt_rand() . ".$ext";
    }
    file_put_contents($dir . $file, '');
     //marcar o ficheiro para não ser utilizado novamente
    return $dir . $file;
}
function geraCheckDigitReferenciaMultibanco($entidade, $referencia, $valor)
{
    $valor = number_format($valor, 2);
    $valor = str_replace(',', '', $valor);
    $valor = str_replace('.', '', $valor);
    $valor = str_replace(' ', '', $valor);

    $wi[] = 51;
    $wi[] = 73;
    $wi[] = 17;
    $wi[] = 89;
    $wi[] = 38;
    $wi[] = 62;
    $wi[] = 45;
    $wi[] = 53;
    $wi[] = 15;
    $wi[] = 50;
    $wi[] = 5;
    $wi[] = 49;
    $wi[] = 34;
    $wi[] = 81;
    $wi[] = 76;
    $wi[] = 27;
    $wi[] = 90;
    $wi[] = 9;
    $wi[] = 30;
    $wi[] = 3;
    $wi[] = 10;
    $wi[] = 1;

    $valor = str_pad($valor, 8, "0", STR_PAD_LEFT);
    $referencia = str_pad($referencia, 7, "0", STR_PAD_LEFT);
    $entidade = str_pad($entidade, 5, "0", STR_PAD_LEFT);

    $composto = $entidade . $referencia . $valor;
    $total = 0;
    for ($i = 0; $i < strlen($composto); $i++)
    {
        $total = $total + ($composto[$i] * $wi[$i]);
    }

    $total = $total % 97;
    $total = 98 - $total;
    $total = str_pad($total, 2, "0", STR_PAD_LEFT);

    return $total;
}
function htmlPTchars($str)
{
    return str_replace(array("&lt;", "&gt;"), array("<", ">"), htmlentities($str));
}
function timeDiff($firstTime, $lastTime)
{

    // convert to unix timestamps
    $firstTime = strtotime($firstTime);
    $lastTime = strtotime($lastTime);

    // perform subtraction to get the difference (in seconds) between times
    $timeDiff = $lastTime - $firstTime;

    // return the difference
    return $timeDiff;
}
function firstWeekDaysOfMonth($year, $month)
{
    for ($i = 1; $i <= 7; $i++)
    {
        $data[date("w", mktime(0, 0, 0, $month, $i, $year)) ] = date("Y-m-d", mktime(0, 0, 0, $month, $i, $year));
    }
    return $data;
}

function search_value($array, $key, $value)
{
    $results = array();

    if (is_array($array))
    {
        if (isset($array[$key]) && $array[$key] == $value) $results[] = $array;

        foreach ($array as $subarray) $results = array_merge($results, search_value($subarray, $key, $value));
    }

    return $results;
}
function textToJS($text)
{
    $text = str_replace(array('"', "'", "\r", "\n", "\0"), array('\"', '\\\'', '\r', '\n', '\0'), $text);
    return '"' . $text . '"';
}
function curl_get($url, $params = "")
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/2.0");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
     // ex.: teste=fff&teste2=qqq&teste3=rr
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_REFERER, "http://www.imed.com.pt");
    $teste = curl_exec($ch);
}
function get_file_size($filename, $max = null, $system = 'si', $retstring = '%01.2f %s')
{
    $size = filesize($filename);

    // Pick units
    $systems['si']['prefix'] = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
    $systems['si']['size'] = 1000;
    $systems['bi']['prefix'] = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
    $systems['bi']['size'] = 1024;
    $sys = isset($systems[$system]) ? $systems[$system] : $systems['si'];

    // Max unit to display
    $depth = count($sys['prefix']) - 1;
    if ($max && false !== $d = array_search($max, $sys['prefix']))
    {
        $depth = $d;
    }

    // Loop
    $i = 0;
    while ($size >= $sys['size'] && $i < $depth)
    {
        $size/= $sys['size'];
        $i++;
    }

    return sprintf($retstring, $size, $sys['prefix'][$i]);
}
function range_number($number, $range)
{
    return intval($number / $range);
}
function range_10000($number)
{
    return range_number($number, 10000);
}

function nl2br_igest($str)
{
    $str = nl2br($str);
    $str = str_ireplace('\r\n', '<br/>', $str);
    return $str;
}

function validNIF($nif)
{

    //tempFix
    //if(is_int($nif)){
    $nif = "$nif";

    //}
    if ((!is_null($nif)) && (is_numeric($nif)) && (strlen($nif) == 9) && ($nif[0] == 1 || $nif[0] == 2 || $nif[0] == 5 || $nif[0] == 6 || $nif[0] == 7 || $nif[0] == 8 || $nif[0] == 9))
    {

        $dC = $nif[0] * 9;
        for ($i = 2; $i <= 8; $i++) $dC+= ($nif[$i - 1]) * (10 - $i);
        $dC = 11 - ($dC % 11);
        $dC = ($dC >= 10) ? 0 : $dC;
        if ($dC == $nif[8]) return TRUE;
    }
}

function object_utf8($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = new stdClass;
        foreach ($data as $key => $value)
        {
            $result->$key = object_utf8($value);
        }
        return $result;
    }
    return utf8_decode($data);
}
class iTimer
{
    protected $_start;
    protected $_end;
    public function __construct()
    {
        $this->_start = 0;
        $this->_end = 0;
        $this->start();
    }
    public function start()
    {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $this->_start = $mtime;
    }
    public function stop()
    {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $this->_end = $mtime;
        return $this->getTime();
    }
    public function getTime()
    {
        return ($this->_end - $this->_start);
    }
}
function validaNumeroUtente($numUtente)
{
    $numUtente = "$numUtente";
     //converter para string para utilizar $numUtente[$j];
    if (strlen($numUtente) == 9)
    {
        $k = 0;
        $digitoControlo = 0;
        for ($j = 0; $j < 8; $j++)
        {
            $valorAux = 0;
            $caracter = $numUtente[$j];
            $valor = intval($caracter);

            $valorAux = $valor + $k;
            if ($valorAux > 10)
            {
                $valorAux = $valorAux - 10;
            }
            $valorAux = $valorAux * 2;
            if ($valorAux > 11)
            {
                $valorAux = $valorAux - 11;
            }
            $k = $valorAux;
        }

        $digitoControlo = 11 - $k;
        $digitoControlo = $digitoControlo % 10;

        $caracteraux = $numUtente[8];
        $valorAux = intval($caracteraux);
        return ($valorAux == $digitoControlo);
    } else
    {
        return false;
    }
}
function printPDF($location)
{
    require_once "lib/PDFMerger/PDFMerger.php";
    $pdf = new PDFMerger();
    $pdf->addPDF($location, 'all');
    $pdf->autoPrint(true);
    $pdf->merge();
}
function intervaloMeses($data, $data2)
{
    $d1 = strtotime($data);
    $d2 = strtotime($data2);
    $min_date = min($d1, $d2);
    $max_date = max($d1, $d2);
    $i = 0;
    while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date)
    {
        $i++;
    }
    return $i;
}
function json_encode_iso8859($data)
{
    if (is_array($data) || is_object($data))
    {
        $islist = is_array($data) && (empty($data) || array_keys($data) === range(0, count($data) - 1));

        if ($islist)
        {
            $json = '[' . implode(',', array_map('json_encode_iso8859', $data)) . ']';
        } else
        {
            $items = Array();
            foreach ($data as $key => $value)
            {
                $items[] = json_encode_iso8859("$key") . ':' . json_encode_iso8859($value);
            }
            $json = '{' . implode(',', $items) . '}';
        }
    } elseif (is_string($data))
    {
        // Escape non-printable or Non-ASCII characters.
        // I also put the \\ character first, as suggested in comments on the 'addclashes' page.
        $string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
        $json = utf8_decode($string);/*
        $len = strlen($string);
        // Convert UTF-8 to Hexadecimal Codepoints.

        for ($i = 0; $i < $len; $i++)
        {

            $char = $string[$i];
            $c1 = ord($char);
            // Single byte;
            if ($c1 < 128)
            {
                $json.= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);
                continue;
            }
            // Double byte
            $c2 = ord($string[++$i]);
            if (($c1 & 32) === 0)
            {
                $json.= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);
                continue;
            }
            // Triple
            $c3 = ord($string[++$i]);
            if (($c1 & 16) === 0)
            {
                $json.= sprintf("\\u%04x", (($c1 - 224) << 12) + (($c2 - 128) << 6) + ($c3 - 128));
                continue;
            }
            // Quadruple
            $c4 = ord($string[++$i]);
            if (($c1 & 8) === 0)
            {
                $u = (($c1 & 15) << 2) + (($c2 >> 4) & 3) - 1;

                $w1 = (54 << 10) + ($u << 6) + (($c2 & 15) << 2) + (($c3 >> 4) & 3);
                $w2 = (55 << 10) + (($c3 & 15) << 6) + ($c4 - 128);
                $json.= sprintf("\\u%04x\\u%04x", $w1, $w2);
            }
        }*/
    } else
    {
        // int, floats, bools, null
        $json = strtolower(var_export($data, true));
    }
    return $json;
}
function json_encode_htmlencode($data)
{
    if (is_array($data) || is_object($data))
    {
        $islist = is_array($data) && (empty($data) || array_keys($data) === range(0, count($data) - 1));

        if ($islist)
        {
            $json = '[' . implode(',', array_map('json_encode_iso8859', $data)) . ']';
        } else
        {
            $items = Array();
            foreach ($data as $key => $value)
            {
                $items[] = json_encode_iso8859("$key") . ':' . json_encode_iso8859($value);
            }
            $json = '{' . implode(',', $items) . '}';
        }
    } elseif (is_string($data))
    {
        // Escape non-printable or Non-ASCII characters.
        // I also put the \\ character first, as suggested in comments on the 'addclashes' page.
        $string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
        $json = htmlspecialchars($string);
    } else
    {
        // int, floats, bools, null
        $json = strtolower(var_export($data, true));
    }
    return $json;
}
function msg($text)
{
    echo date('Y-m-d H:i:s') . ":" . $text . "<br/> \n";
    ob_flush();
    flush();
}
function utf8_encode_array($array)
{
    foreach ($array as $k => $v)
    {
        if (is_array($v))
        {
            $aux = null;
            foreach ($v as $k1 => $v1)
            {
                $aux[$k1] = utf8_encode($v1);
            }
            $array[$k] = $aux;
        } else
        {
            $array[$k] = utf8_encode($v);
        }
    }
    return $array;
}

function gerarDigitoDebitoDireto($entidade, $referencia)
{
    $referencia = str_pad($referencia, 9, "0", STR_PAD_LEFT);
    $entidade = str_pad($entidade, 6, "0", STR_PAD_LEFT);

    $codigo = $entidade . $referencia;

    $c = new IMED_ISO7064Mod97_10();
    $codigo = $c->checkCode($codigo);

    $entidade = str_pad("$codigo", 2, "0", STR_PAD_LEFT);
    return $codigo;
}

function show_pdf_from_docx($loc, $bPrint = 0)
{
    $file = pathinfo($loc);
    $file = $file['filename'] . ".pdf";
    $exec = "libreoffice --writer  --convert-to pdf --headless $loc";
    exec($exec);
    if ($bPrint)
    {
        require_once "lib/PDFMerger/PDFMerger.php";
        $pdf = new PDFMerger();
        $pdf->addPDF($file, 'all');
        $pdf->autoPrint(true);
        $pdf->merge('file', $file);
    }
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . $file . '"');
    readfile($file);
    unlink($file);
}


function show_html_from_docx($loc, $bPrint = 0)
{
    if(substr($loc,0,-5)!=".docx"){
        copy($loc, $loc.".docx");
        $loc=$loc.".docx";
    }
    $file = pathinfo($loc);
    $file = $file['filename'] . ".html";
    $exec = "libreoffice --writer  --convert-to html --headless $loc";
    $result=exec($exec);
    echo "<header><title>iMED - Prescrição eletrónica</title>";
    readfile($file);
    unlink($file);
}

class IMED_ISO7064Mod97_10
{

    function encode($str)
    {

        $c = $this->checkCode("$str");
        if ($c == 0)
        {
            return (int)"${str}00";
        } elseif ($c < 10)
        {
            return (int)"${str}0${c}";
        } else
        {
            return (int)"${str}${c}";
        }
    }
    function verify($str)
    {
        return ((($this->computeCheck("$str")) % 97) == 1);
    }

    function checkCode($str)
    {
        return (98 - ($this->computeCheck("${str}00") % 97));
    }

    function computeCheck($str)
    {
        $ai = 1;
        $ch = ord($str[strlen($str) - 1]) - 48;
        if ($ch < 0 || $ch > 9) return false;
        $check = $ch;
        for ($i = strlen($str) - 2; $i >= 0; $i--)
        {
            $ch = ord($str[$i]) - 48;
            if ($ch < 0 || $ch > 9) return false;
            $ai = ($ai * 10) % 97;
            $check+= ($ai * ((int)$ch));
        }
        return $check;
    }

    function getCheck($str)
    {
        return (int)substr("$str", strlen("$str") - 2);
    }

    function getData($str)
    {
        return (int)substr("$str", 0, strlen("$str") - 2);
    }
}
function remover_password_PDF($localizacaoAnexo, $password)
{

    //sudo apt-get install qpdf
    $aux = $localizacaoAnexo . "_decrypt";
    $teste = exec("qpdf --password=$password --decrypt $localizacaoAnexo $aux");
    if (file_exists($aux))
    {
        if (copy($aux, $localizacaoAnexo))
        {
            unlink($aux);
            return true;
        }
    } else
    {
        return false;
    }
}

/**
 * Retorna o número de dias entre duas datas
 * @param  [type] $startDate [description]
 * @param  [type] $endDate   [description]
 * @return [type]            [description]
 */
function daysBetween($date1, $date2, $positive=false)
{
    $start = strtotime( $date1 );
    $end   = strtotime( $date2 );

    $diff = ceil(($end - $start) / 86400);

    if($positive)
    {
        return abs( $diff );
    }
    else
    {
        return ($diff>0)?$diff:0;
    }
}


/**
 * Avalia se o utilizador em sessao e um medico
 * @return [type] [description]
 */
function validaMedicoSessao()
{
    if($_SESSION['medicoID'] && ($_SESSION['nivel']=="3" || $_SESSION['medico_gestor']) )
        return true;
    else
        return false;
}
function validaAssistenteSessao()
{
    if($_SESSION['nivel']=="2" || $_SESSION['medico_gestor'])
        return true;
    else
        return false;
}

function format_datetime($str)
{
    return substr($str,0,-3);
}
function retira_acentos($texto)
{
    $array1 = array( "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç" , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" );
    $array2 = array( "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" );
    return str_ireplace( $array1, $array2, $texto);
}
/**
 * Ordena um array de 2Dimensoes com base em uma key do subArray
 * @param  [type]  $records [array com os dados]
 * @param  [type]  $field   [campo para ser ordenado]
 * @param  boolean $reverse [Se true ordena DESC else ASC]
 * @return [type]           [description]
 */
function multiArraySort($records, $field, $reverse=false)
{
    $hash = array();
    $r=0;//preserva os elementos que tenham o $field igual.

    foreach($records as $record)
    {
        $key=utf8_encode($record[$field]);
        $key=retira_acentos($key).(++$r);
        $hash[$key] = $record;
    }

    ($reverse)? krsort($hash) : ksort($hash);

    $records = array();

    foreach($hash as $record)
    {
        $records []= $record;
    }

    return $records;
}

function morada_string($morada)
{
    if($morada['distrito']!="" || $morada['cidade']!="" || $morada['rua']!="" || $morada['nPorta']!="" || $morada['codPostal']!="")
    {
        $m=null;
        if($morada['pais']!="Portugal"){
            $m[]=$morada['pais'];
        }
        if($morada['distrito']!=""){
            $m[]=$morada['distrito'];
        }
        if($morada['cidade']!=""){
            $m[]=$morada['cidade'];
        }
        if($morada['rua']!=""){
            $m[]=$morada['rua'];
        }
        if($morada['nPorta']!=""){
            $m[]="Nº. ".$morada['nPorta'];
        }
        if($morada['codPostal']!=""){
            $m[]=$morada['codPostal'];
        }
        if(isset($m)){
            return implode(", ", $m);
        }
    }
    return "";
}
function diferenca_datas_extenso($dataInicial,$dataFinal)
{
    if(empty($dataFinal)){
        $dataFinal=date("Y-m-d");
    }

    $date1 = new DateTime($dataInicial);
    $date2 = new DateTime($dataFinal);

    $interval = $date1->diff($date2);

    $diff=null;
    if($interval->y>0){
        $msg=$interval->y." ";
        $msg.=($interval->y==1) ? "ano":"anos";
        $diff[]=$msg;
    }
    if($interval->m>0){
        $msg=$interval->m." ";
        $msg.=($interval->m==1) ? "mês":"meses";
        $diff[]=$msg;
    }
    if($interval->d>0){
        $msg=$interval->d." ";
        $msg.=($interval->d==1) ? "dia":"dias";
        $diff[]=$msg;
    }

    if($dataFinal==date("Y-m-d") && $dataInicial==$dataFinal){
        $msg="Hoje";
    }else if($dataFinal==date("Y-m-d") && $dataInicial==date("Y-m-d",strtotime(" -1 day"))){
        $msg="Ontem";
    }elseif($diff){
        $msg= implode(", ", $diff);
    }
    return $msg;
}
function remove_sepcial_html_characters($string)
{
    $string=str_ireplace("<","", $string);
    $string=str_ireplace(">","", $string);

    return $string;
}
function select_value($id1,$id2)
{
    if($id1==$id2){
        return " selected ";
    }
}
function checked_value($b)
{
    if($b){
        return " checked ";
    }
}
function get_array_from_key_name($array,$key)
{
    $a=array();
    if( is_array($array) ){
        foreach ($array as $value) {
           if(isset($value[$key])){
                $a[]=$value[$key];
           }
        }
    }
    return $a;
}
function exec_script_iframe($script)
{
    echo "<script>parent.{$script}</script>";
    ob_flush();
    flush();
}
?>
