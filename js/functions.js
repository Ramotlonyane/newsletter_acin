
$.ajaxSetup({
  type: "POST"
});

function roundToDecimal(number,decimal) {
	var zeros = new String( 1.0.toFixed(decimal) );
	zeros = zeros.substr(2);
	var mul_div = parseInt( "1"+zeros );
	var increment = parseFloat( "."+zeros+"01" );
	if( ( (number * (mul_div * 10)) % 10) >= 5 )
	  { number += increment; }
	return Math.round(number * mul_div) / mul_div;
}

function roundToDecimal2(number) {
	return roundToDecimal(number,2);
}


function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;
}

function utf8_decode ( str_data ) {
    // Converts a UTF-8 encoded string to ISO-8859-1
    //
    // version: 1004.2314
    // discuss at: http://phpjs.org/functions/utf8_decode    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +      input by: Aman Gupta
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Norman "zEh" Fuchs
    // +   bugfixed by: hitwork    // +   bugfixed by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: utf8_decode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'
     var tmp_arr = [], i = 0, ac = 0, c1 = 0, c2 = 0, c3 = 0;

    str_data += '';

    while ( i < str_data.length ) {        c1 = str_data.charCodeAt(i);
        if (c1 < 128) {
            tmp_arr[ac++] = String.fromCharCode(c1);
            i++;
        } else if ((c1 > 191) && (c1 < 224)) {            c2 = str_data.charCodeAt(i+1);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
            i += 2;
        } else {
            c2 = str_data.charCodeAt(i+1);            c3 = str_data.charCodeAt(i+2);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }
    }
    return tmp_arr.join('');
}
function unserialize(data) {
    // http://kevin.vanzonneveld.net
    // +     original by: Arpad Ray (mailto:arpad@php.net)
    // +     improved by: Pedro Tainha (http://www.pedrotainha.com)
    // +     bugfixed by: dptr1988
    // +      revised by: d3x
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +        input by: Brett Zamir (http://brett-zamir.me)
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: Chris
    // +     improved by: James
    // +        input by: Martin (http://www.erlenwiese.de/)
    // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: Le Torbi
    // +     input by: kilops
    // +     bugfixed by: Brett Zamir (http://brett-zamir.me)
    // -      depends on: utf8_decode
    // %            note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %            note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *       example 1: unserialize('a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}');
    // *       returns 1: ['Kevin', 'van', 'Zonneveld']
    // *       example 2: unserialize('a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}');
    // *       returns 2: {firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'}

    var that = this;
    var utf8Overhead = function(chr) {
        // http://phpjs.org/functions/unserialize:571#comment_95906
        var code = chr.charCodeAt(0);
        if (code < 0x0080) {
            return 0;
        }
        if (code < 0x0800) {
             return 1;
        }
        return 2;
    };


    var error = function (type, msg, filename, line){throw new that.window[type](msg, filename, line);};
    var read_until = function (data, offset, stopchr){
        var buf = [];
        var chr = data.slice(offset, offset + 1);
        var i = 2;
        while (chr != stopchr) {
            if ((i+offset) > data.length) {
                error('Error', 'Invalid');
            }
            buf.push(chr);
            chr = data.slice(offset + (i - 1),offset + i);
            i += 1;
        }
        return [buf.length, buf.join('')];
    };
    var read_chrs = function (data, offset, length){
        var buf;

        buf = [];
        for (var i = 0;i < length;i++){
            var chr = data.slice(offset + (i - 1),offset + i);
            buf.push(chr);
            length -= utf8Overhead(chr);
        }
        return [buf.length, buf.join('')];
    };
    var _unserialize = function (data, offset){
        var readdata;
        var readData;
        var chrs = 0;
        var ccount;
        var stringlength;
        var keyandchrs;
        var keys;

        if (!offset) {offset = 0;}
        var dtype = (data.slice(offset, offset + 1)).toLowerCase();

        var dataoffset = offset + 2;
        var typeconvert = function(x) {return x;};

        switch (dtype){
            case 'i':
                typeconvert = function (x) {return parseInt(x, 10);};
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
            break;
            case 'b':
                typeconvert = function (x) {return parseInt(x, 10) !== 0;};
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
            break;
            case 'd':
                typeconvert = function (x) {return parseFloat(x);};
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
            break;
            case 'n':
                readdata = null;
            break;
            case 's':
                ccount = read_until(data, dataoffset, ':');
                chrs = ccount[0];
                stringlength = ccount[1];
                dataoffset += chrs + 2;

                readData = read_chrs(data, dataoffset+1, parseInt(stringlength, 10));
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 2;
                if (chrs != parseInt(stringlength, 10) && chrs != readdata.length){
                    error('SyntaxError', 'String length mismatch');
                }

                // Length was calculated on an utf-8 encoded string
                // so wait with decoding
                //readdata = that.utf8_decode(readdata);
            break;
            case 'a':
                readdata = {};

                keyandchrs = read_until(data, dataoffset, ':');
                chrs = keyandchrs[0];
                keys = keyandchrs[1];
                dataoffset += chrs + 2;

                for (var i = 0; i < parseInt(keys, 10); i++){
                    var kprops = _unserialize(data, dataoffset);
                    var kchrs = kprops[1];
                    var key = kprops[2];
                    dataoffset += kchrs;

                    var vprops = _unserialize(data, dataoffset);
                    var vchrs = vprops[1];
                    var value = vprops[2];
                    dataoffset += vchrs;

                    readdata[key] = value;
                }

                dataoffset += 1;
            break;
            default:
                error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype);
            break;
        }
        return [dtype, dataoffset - offset, typeconvert(readdata)];
    };

    return _unserialize((data+''), 0)[2];
}



function validaremail(email) //Valida Email
{
	myRegExp = /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i;
        return myRegExp.test(email);
}




/*Função responsável por encaminhar o browser para link recebido*/
function redirect(link)
{
    window.location.href = "index.php?" + link;
}


function hiddenElement(idElement)
{
    $('#' + idElement).fadeOut("slow");
}

function showElement(idElement)
{
    $('#' + idElement).fadeIn("slow");
}

/**Função responsável por encontrar a posiçao de um elemento no documento*/
function posicaoActual($arg)
{
      var $curTop=0,$curLeft=0,$curWidth=0; // Essa, cria as variáveis de largura, left e top
       if($arg.offsetParent) {
        do {
               $curLeft += $arg.offsetLeft;
               $curTop += $arg.offsetTop;
         }
         while($arg = $arg.offsetParent)
         posLeft = $curLeft;
         posTop = $curTop;
       }

}

function goBottom()
{
    for(var i=0; i <  (window.innerHeight); i=i+10)
    {
         window.scroll(0, i);
    }
}


function createFormConfirm()
{
    $("body").append("<div id='confirm_div' style='display: none;'><a href='#' title='Fechar' class='modalCloseX simplemodal-close'>x</a><div class='header'><span>Confirmar</span></div><p class='message'></p><div class='buttons'><div class='no simplemodal-close'>NÃO</div><div class='yes'>SIM</div></div></div>");
}

function createFormSimple()
{
    $("#basicModalContent");
    $("body").append("<div id='basicModalContent' style='display:none'></div>");
}

function createModalForm(html)
{
    var count=$(".modal_form_count").length;
    if( $(".ui-page-active").length > 0 ) {
    	$("body").append("<div data-enhance=\"false\" data-role=\"none\" id='basicModalContent"+count+"' class='modal_form_count' style='display:none'></div>");
    }
    else {
    	$("body").append("<div id='basicModalContent"+count+"' class='modal_form_count' style='display:none'></div>");
    }
    $("#basicModalContent"+count).html(html);
    return "basicModalContent"+count;
}



function validarBiNif(bi1, bi2)
{
     var n_bi = (bi1.value).replace(/ /, '');
     if(bi2 != undefined) {
         n_bi+= bi2.value;
     }
     while(n_bi.length < 9) {
        n_bi = "0" + n_bi;
     }
     n_bi = n_bi.toString();
     var result = 0;
     for(var i=0; i < n_bi.length; i++) {

         result= result + parseInt(n_bi.charAt(i))*(9-i);

     }
     if((result % 11)==0) {
        return true;
     }
     else {
         return false;
     }
}


function number_format( number, decimals, dec_point, thousands_sep ){
    var n = number, prec = decimals;
    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);
    var sep = (typeof thousands_sep == "undefined") ? ',' : thousands_sep;
    var dec = (typeof dec_point == "undefined") ? '.' : dec_point;

    var s = (prec > 0) ? n.toFixed(prec) : Math.round(n).toFixed(prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;

    var abs = Math.abs(n).toFixed(prec);
    var _, i;

    if (abs >= 1000) {
        _ = abs.split(/\D/);
        i = _[0].length % 3 || 3;

        _[0] = s.slice(0,i + (n < 0)) +
              _[0].slice(i).replace(/(\d{3})/g, sep+'$1');

        s = _.join(dec);
    } else {
        s = s.replace('.', dec);
    }

    return s;
}
function str_replace (search, replace, subject, count) {
    // Replaces all occurrences of search in haystack with replace
    //
    // version: 1008.1718
    // discuss at: http://phpjs.org/functions/str_replace
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Gabriel Paderni
    // +   improved by: Philip Peterson
    // +   improved by: Simon Willison (http://simonwillison.net)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   bugfixed by: Anton Ongson
    // +      input by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    tweaked by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   input by: Oleg Eremeev
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Oleg Eremeev
    // %          note 1: The count parameter must be passed as a string in order
    // %          note 1:  to find a global variable in which the result will be given
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'
    var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
            f = [].concat(search),
            r = [].concat(replace),
            s = subject,
            ra = r instanceof Array, sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }

    for (i=0, sl=s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j=0, fl=f.length; j < fl; j++) {
            temp = s[i]+'';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length-s[i].length)/f[j].length;}
        }
    }
    return sa ? s : s[0];
}
function num_format(num)
{
    return  number_format(num,2,',','.');
}
function num_unformat(num)
{
    num=str_replace('.','',num);
    num=str_replace(',','.',num);
    return num;
}
String.prototype.replaceAll = function(de, para){
    var str = this;
    var pos = str.indexOf(de);
    while (pos > -1){
		str = str.replace(de, para);
		pos = str.indexOf(de);
	}
    return (str);
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}
function getCookie (name) {
    var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
function isInt(x) {
   var y=parseInt(x);
   if (isNaN(y)) return false;
   return x==y && x.toString()==y.toString();
}
function hidePDFframe()
{
    $('iframe.pdf_iframe:visible').addClass('pdf_iframe_hidden');
    $("iframe.pdf_iframe_hidden").css('position','absolute');
    $("iframe.pdf_iframe_hidden").css('left','-20000px');
}
function showPDFframe()
{
    $('iframe.pdf_iframe_hidden').removeClass('pdf_iframe_hidden');
    $("iframe.pdf_iframe:visible").css('position','relative');
    $("iframe.pdf_iframe:visible").css('left','0');
}

if($.ui!==undefined)
{
    $.extend($.ui.dialog.prototype.options, {
    position: { my: "center", at: "center", of: window },
	width: 640,
	minHeight:125,
    height:'auto',
	modal: true,
    zIndex: 3999,
    show: "fade",
    beforeClose: function(event, ui) {
    },
    close: function(event, ui) {
        $(this).dialog( "destroy" );
        $(this).remove();
        if($("div:ui-dialog:last:visible").length==0){
            showPDFframe();
        }
    },
    open: function(event, ui) {
            hidePDFframe();
            $('.ui-corner-all').removeClass('ui-corner-all');
    }
});
}

function serialize(mixed_value){
    // Returns a string representation of variable (which can later be unserialized)
    //
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/serialize
    // +   original by: Arpad Ray (mailto:arpad@php.net)
    // +   improved by: Dino
    // +   bugfixed by: Andrej Pavlovic
    // +   bugfixed by: Garagoth
    // +      input by: DtTvB (http://dt.in.th/2008-09-16.string-length-in-bytes.html)
    // +   bugfixed by: Russell Walker (http://www.nbill.co.uk/)
    // +   bugfixed by: Jamie Beck (http://www.terabit.ca/)
    // +      input by: Martin (http://www.erlenwiese.de/)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
    // +   improved by: Le Torbi (http://www.letorbi.de/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
    // +   bugfixed by: Ben (http://benblume.co.uk/)
    // -    depends on: utf8_encode
    // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
    // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
    // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
    var _utf8Size = function (str) {
        var size = 0,
            i = 0,
            l = str.length,
            code = '';
        for (i = 0; i < l; i++) {
            code = str.charCodeAt(i);
            if (code < 0x0080) {
                size += 1;
            } else if (code < 0x0800) {
                size += 2;
            } else {
                size += 3;
            }
        }
        return size;
    };
    var _getType = function (inp) {
        var type = typeof inp,
            match;
        var key;

        if (type === 'object' && !inp) {
            return 'null';
        }
        if (type === "object") {
            if (!inp.constructor) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };
    var type = _getType(mixed_value);
    var val, ktype = '';

    switch (type) {
    case "function":
        val = "";
        break;
    case "boolean":
        val = "b:" + (mixed_value ? "1" : "0");
        break;
    case "number":
        val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
        break;
    case "string":
        val = "s:" + _utf8Size(mixed_value) + ":\"" + mixed_value + "\"";
        break;
    case "array":
    case "object":
        val = "a";
/*
            if (type == "object") {
                var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
                if (objname == undefined) {
                    return;
                }
                objname[1] = this.serialize(objname[1]);
                val = "O" + objname[1].substring(1, objname[1].length - 1);
            }
            */
        var count = 0;
        var vals = "";
        var okey;
        var key;
        for (key in mixed_value) {
            if (mixed_value.hasOwnProperty(key)) {
                ktype = _getType(mixed_value[key]);
                if (ktype === "function") {
                    continue;
                }

                okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                vals += this.serialize(okey) + this.serialize(mixed_value[key]);
                count++;
            }
        }
        val += ":" + count + ":{" + vals + "}";
        break;
    case "undefined":
        // Fall-through
    default:
        // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
        val = "N";
        break;
    }
    if (type !== "object" && type !== "array") {
        val += ";";
    }
    return val;
}
if($.datepicker!==undefined)
{
    jQuery(function($){
    	$.datepicker.regional['pt'] = {closeText: 'Fechar',
                    prevText: '&#x3c;Anterior',
                    nextText: 'Pr&oacute;ximo&#x3e;',
                    currentText: 'Hoje',
                    monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho',
                    'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
                    'Jul','Ago','Set','Out','Nov','Dez'],
                    dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
                    dayNamesShort: ['Do','Seg','Ter','Qua','Qui','Sex','Sab'],
                    dayNamesMin: ['Do','Seg','Ter','Qua','Qui','Sex','Sab'],
                    weekHeader: 'Sm',
                    dateFormat: 'yy-mm-dd',
                    firstDay: 1,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: ''};
    	$.datepicker.setDefaults($.datepicker.regional['pt']);
    });
}
function IsValidNIF(nif)
{
    var c;
    var checkDigit = 0;

    //Verifica se é nulo, se é numérico e se tem 9 dígitos
    if(nif != null && /^\d+$/.test(nif) && nif.length == 9)
    {
        //Obtem o primeiro número do NIF
        c = nif.charAt(0);

        //Verifica se o primeiro número é (1, 2, 5, 6, 8 ou 9)
        if(c == '1' || c == '2' || c == '5' || c == '6'|| c == '7' || c == '8' || c == '9')
        {
            //Calculo do Digito de Controle
            checkDigit = c * 9;
            var i = 0;
            for(i = 2; i <= 8; i++)
            {
                checkDigit += nif.charAt(i-1) * (10-i);
            }
            checkDigit = 11 - (checkDigit % 11);

            //Se o digito de controle é maior que dez, coloca-o a zero
            if(checkDigit >= 10)
                checkDigit = 0;

            //Compara o digito de controle com o último numero do NIF
            //Se igual, o NIF é válido.
            if(checkDigit == nif.charAt(8))
                return true;
        }
    }
    return false;
}
function modalClose()
{
    $.modal.close();
}
function RoundToDecimal(number){
    var zeros = new String( 1.0.toFixed(2) );
    zeros = zeros.substr(2);
    var mul_div = parseInt( "1"+zeros );
    var increment = parseFloat( "."+zeros+"01" );
    if( ( (number * (mul_div * 10)) % 10) >= 5 )
      { number += increment; }
    return Math.round(number * mul_div) / mul_div;
}
$.fn.selectRange = function(start, end) {
    if(!end) end = start;
    return this.each(function() {
        if (this.setSelectionRange) {
            this.focus();
            this.setSelectionRange(start, end);
        } else if (this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    });
};
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function listPages(page,pages,http,nReg,funct)
{
    if(!page){page=1;}
    functHtml1="";
    functHtml2="";
    pages=parseInt(pages);
    if(pages<=1)
    {
       return;
    }
        html="";
      if( nReg!=""){
        html=html+"<div style='float:left'><span style='text-align:left' >"+nReg+" Registo(s)&nbsp;&nbsp;&nbsp;</span></div>";
      }
      if(pages>1){
            if(page>2){
                 if(funct!=""){
                    functHtml1=" onclick='"+funct+"(1)' ";
                    functHtml2=" onclick='"+funct+"("+parseInt(page-1)+")' ";
                 }
            html=html+ " <a class='arrow_ll' href='"+http+"&page=1' "+functHtml1+" ></a> "+
                "<a class='arrow_l' href='"+http+"&page="+parseInt(page-1)+"' "+functHtml2+" ></a> ";
             }
             for(i=1;(i<pages && i<4);i++)
             {
                 if(funct!=""){
                    functHtml=" onclick='"+funct+"("+parseInt(i)+")' ";
                 }
                 if(page!=i){
                    html=html+ " <a href='"+http+"&page="+parseInt(i)+"' "+functHtml+" >"+parseInt(i)+"</a>";
                 }else{
                    html=html+" <span>"+parseInt(i)+"</span>";
                 }
             }
             if(i<=page-5 ){
                html=html+" <span class='more'>...</span> ";
                i=page-5;
             }
              for(i;(i<=page+5 && i<pages);i++)
             {
                 if(funct!=""){
                    functHtml=" onclick='"+funct+"("+parseInt(i)+")' ";
                 }
                 if(page!=i){
                     html=html+ " <a href='"+http+"&page="+parseInt(i)+"' "+functHtml+" >"+parseInt(i)+"</a>";
                 }else{
                     html=html+" <span >"+parseInt(i)+"</span>";
                 }
             }
             if(i<(pages-3)){
                html=html+" <span class='more'>...</span> ";
                i=pages-3;
             }
             for(i;(i<=pages);i++)
             {
                if(funct!=""){
                   functHtml=" onclick='"+funct+"("+parseInt(i)+")' ";
                }
               if(page!=i){
                    html=html+ " <a href='"+http+"&page="+parseInt(i)+"' "+functHtml+" >"+parseInt(i)+"</a>";
               }else{
                    html=html+" <span >"+parseInt(i)+"</span>";
               }
             }

             if(page<pages-1){
                 if(funct!=""){
                    functHtml1=" onclick='"+funct+"("+parseInt(page+1)+")' ";
                    functHtml2=" onclick='"+funct+"("+parseInt(pages)+")' ";
                 }
                 html=html+ " <a class='arrow_r'  href='"+http+"&page="+parseInt(page+1)+"' "+functHtml1+" ></a>"+
                          "<a  class='arrow_rr'  href='"+http+"&page="+parseInt(pages)+"' "+functHtml2+" ></a>";
             }
      }
      return html;
}
function loadingDiv(jElement)
{
	height=jElement.height();
	if(height<50){
		height=110;
	}
	jElement.html("<div class='loading' style='height:"+height+"px'/> ");
}



/**
 * Permite ler os parametros do URL
 * @param  {[type]} name [description]
 * @return {[type]}      [description]
 */
$.urlParam = function(name){
    var results = new RegExp('[\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.href);

    if( !results )
    	return "";
    else
    	return results[1];
}

function isEmpty(e)
{
    switch(e) {
        case "":
        case 0:
        case "0":
        case null:
        case false:
        case undefined:
        case "undefined":
        case typeof this == "undefined":
            return true;
        default : return false;
    }
}
function empty(e)
{
	return isEmpty(e);
}

function validar_browser()
{
	if( $.browser.msie==true && $.browser.version<=8 )
	{
		return false;
	}else{
		return true;
	}
}
function alerta_browser_antigo()
{
	html="<div style='height:308px;overflow:hidden'><img src='css/images/popup_browser.png' /><a  href='http://www.google.com/intl/pt-PT/chrome/browser/' target='_blank' title='Chrome' style='position:relative;width:90px;height:80px;top:-110px;left:260px;display:inline-block'></a><a href='http://www.mozilla.org/pt-PT/firefox/new/' target='_blank' title='FireFox' style='position:relative;width:80px;height:80px;top:-110px;left:255px;display:inline-block'></a></div>";
	var idModal=createModalForm(html);
    $("#"+idModal).dialog({
                title: "Erro",
                position: { my: "center", at: "center", of: window },
    			height: 'auto',
                width:550,
    			modal: true,
                show: "fade",
                resizable:false
    	});
}

function showMsg(htmlMessage)
{
	showSuccessNotification( htmlMessage );
}
function showError(htmlMessage)
{
	showWarningNotification(htmlMessage);
}
function showSuccessNotification( htmlMessage )
{
	$.toast('<img src="css/icons/alertasup_check.png" class="iconNotificationBar">'+htmlMessage,{
		sticky: false,
		alert:"right",
		type: 'success',
		duration: 2500,
	});
}

function showWarningNotification( htmlMessage )
{
	$.toast('<img src="css/icons/alertasup_aviso.png" class="iconNotificationBar"><div class="toastText">'+htmlMessage+'</div>',{
		sticky: true,
		type: 'success',
		duration: 1500,
	});
}

function showMessageNotification( htmlMessage )
{
	$.toast('<img src="css/icons/alertasup_mensagem.png" class="iconNotificationBar" style="padding-top: 5px;"><div class="toastText">'+htmlMessage+'</div>',{
		sticky: true,
		type: 'success',
		duration: 1500,
	});
}
/*
$.ui.dialog.prototype.options.autoReposition = true;
$( window ).resize(function() {
	fix_dialog_height();
});
function fix_dialog_height(){

	$( ".ui-dialog-content:visible" ).each(function() {
		//ajustar o max-height
		maxHeight=$(window).height()-80;
		if(maxHeight<100){
			maxHeight=250;
		}
		$(this).css({'max-height': maxHeight, 'overflow-y': 'auto'});
		var dialog = $( this ).data( "dialog" );
		if ( dialog.options.autoReposition ) {
			dialog.option( "position", dialog.options.position );//reajustar ao centro caso especificado
		}
	});
}
$.ui.dialog.prototype.options.open=fix_dialog_height;
*/

function showDialog(optn,data)
{
	if(optn.data!=undefined && data==undefined){
		data=optn.data;
	}

	if(optn.open!=undefined){
		optn._open=optn.open;
		optn.open=undefined;
	}

 	var idModal = "";
	defaultsOptions={
						title: "iMED",
		                position: { my: "center", at: "center", of: window },
		    			height: 'auto',
		                width:550,
		    			modal: true,
		                show: "fade",
		                json:false,
		                resizable:false,
            			autoResize:true,
					    open: function(event, ui) {
							if(typeof(options_dialog._open)=="function"){
								options_dialog._open(event, ui);
							}
					    }
		            };
	options_dialog = $.extend(true, {}, defaultsOptions, optn);
 	var width=   options_dialog.width;
 	var height= width*0.4;
 	height=height.toFixed(0);
 	if(!isNumber(height)){
 		height="250px";
 	}else{
 		height=height+"px";
 	}
	if(!isNumber(width)){
 		width="400px";
 	}else{
 		width=width+"px";
 	}

	$.ajax({
	    beforeSend: function(){
			var loadingWrapper='<div style="width:'+width+';height:'+height+';" id="loading" class="loadingModal"> \
								    	<img src="css/icons/loading.gif"><br> \
								   <span>A carregar...</span> \
								</div>';
		    idModal=createModalForm(loadingWrapper);
		    $("#"+idModal).dialog({title:options_dialog.title,width:width,position:{ my: "center", at: "center", of: window },resizable:false});
		    this.idModal=idModal;
	    },
	    url: "index.php",
        data: data,
	    success: function(results){
	    		idModal=this.idModal;
	    		if($("#"+idModal+":visible").length==0){
	    			return false;//o dialog de loading foi fechado neste caso ignorar a resposta
	    		}
	    		$('#'+idModal).dialog("close");
	    		if(options_dialog.json){//resposta em formato json
	    			try{
	    				if(results.sucesso==1){
	    					var idModal=createModalForm(results.html);
		    				$("#"+idModal).dialog(options_dialog);
	    				}else{
	        				throw "erro";
	        			}
	    			}catch(e){
	    				showError("Erro ao processar o pedido.");
	    			}
	    		}else{
	    			idModal=createModalForm(results);
		    		$("#"+idModal).dialog(options_dialog);
	    		}
	    },
	    error: function() {
	    	$('#'+idModal).dialog("close");
	    	showError("Erro ao processar o pedido.");
	    }
	});
}
$.ui_block = function () {
	if($("#mask_blockUI").length==1){
		$("#mask_blockUI").fadeIn();
	}else{
		$("body").append('<div id="mask_blockUI"><img src="css/loading.gif"/></div>');
	}
};
$.ui_unblock = function () {
	$("#mask_blockUI").fadeOut();
}
$.blockUI=function (){
	$.ui_block();
}
$.unblockUI=function (){
	$.ui_unblock();
}
(function($) {
    $.fn.ajaxLoad = function(options) {
        $element=this;

        if(typeof(options)=="string"){
            options.data=options;
        }

        if(options.beforeSend!=undefined){
            options._beforeSend=options.beforeSend;
            options.beforeSend=undefined;
        }
        if(options.success!=undefined){
            options._success=options.success;
            options.success=undefined;
        }
        if(options.error!=undefined){
            options._error=options.error;
            options.error=undefined;
        }
        if(options.loading!=undefined){
            options.loading=true;
        }
        if(options.json==undefined){
            options.json=false;
        }

        defaultsOptions={
                            type: "POST",
                            url: "index.php",
                            beforeSend: function(obj){
                                if(options.loading){
                                loadingDiv($element);
                                }
                                if(typeof(options._beforeSend)=="function"){
                                    options._beforeSend(obj);
                                }
                            },
                            success:function (obj){
                                if(options.json){
                                    try{
                                        if(obj.sucesso==1){
                                            $element.html(obj.html);
                                        }else{
                                            throw "erro";
                                        }
                                    }catch(e){
                                        showError("Erro ao processar pedido");
                                    }
                                }else{
                                    $element.html(obj);
                                }
                                if(typeof(options._success)=="function"){
                                    options._success(obj);
                                }
                            },
                            error:function(obj){
                                $element.html("");
                                if(typeof(options._error)=="function"){
                                    options._error(obj);
                                }
                            }
                        };
        options = $.extend(true, {}, defaultsOptions, options);
        $.ajax(options);
        return $element;
    };
}(jQuery));
function ajax(options)
{
	if(options.beforeSend!=undefined){
		options._beforeSend=options.beforeSend;
		options.beforeSend=undefined;
	}
	if(options.success!=undefined){
		options._success=options.success;
		options.success=undefined;
	}
	if(options.error!=undefined){
		options._error=options.error;
		options.error=undefined;
	}
	if(options.loading!=undefined){
		options.loading=true;
	}

	defaultsOptions={
		            	type: "POST",
						url: "index.php",
	    				beforeSend: function(obj){
	    					if(options.loading){
	    						$.ui_block();
	    					}
							if(typeof(options._beforeSend)=="function"){
								options._beforeSend(obj);
							}
	    				},
						success:function (obj){
			                $.ui_unblock();
							if(typeof(options._success)=="function"){
								options._success(obj);
							}
						},
			            error:function(obj){
			                $.ui_unblock();
							if(typeof(options._error)=="function"){
								options._error(obj);
							}
			            }
		            };
	options = $.extend(true, {}, defaultsOptions, options);
	$.ajax(options);
}
$.extend({
			alert: function (message , options)
			{
				if(typeof(options)=="string"){
					title=options;
					options=object;
					options.title=title;
				}

				if(typeof(options)=="object")
				{
					if(options.close!=undefined){
						options._close=options.close;
						options.close=undefined;
					}
				}

				defaultsOptions={
					buttons:
	                	{ "Ok": function()
	                		{
	                			if(typeof(options._close)=="function"){
									options._close();
								}
	                			$(this).remove();
	                		}
	                	},
	                close: function (event, ui) {
            			if(typeof(options._close)=="function"){
							options._close();
						}
	                	$(this).remove();
	                },
					title: "Alerta",
	                resizable: false,
	                modal: true,
	                width: 360,
	                zindex: 2147483647,
	                minWidth:220,
	                maxWidth:700
	            };

				options = $.extend(true, {}, defaultsOptions, options);

	            var idModal=createModalForm('<div class="alerta">'+message+'</div>');
	            $("#"+idModal).dialog( options );
			}
		});
function dias_diferenca(data)
{
	var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
	var firstDate = new Date(data);
	var secondDate = new Date();

	return  Math.round(((firstDate.getTime() - secondDate.getTime())/(oneDay)));
}
function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
function alerta(message, callbackFalse,top) {


    if(top===undefined){
        top='center';
    }else{
        top='top';
    }

    $("#conteudo_flutuante_confirm");
    $("body").append("<div id='conteudo_flutuante_confirm' title='Informação' style='display:none'></div>");
    var html="<table width='100%'><tr><td style='text-align:left;  margin-left:0px; width:30px' ></td><td>";
        html+=(message);
        html+="</td></tr></table>";
    $("#conteudo_flutuante_confirm").html(html);
    $("#conteudo_flutuante_confirm").dialog({
            position: { my: "center", at: "center", of: window },
            height: 'auto',
            width:400,
            modal: true,
            resizable:false,
        });

        $("#conteudo_flutuante_confirm").dialog({ buttons: [
        {   text: "OK",
            click: function() {
                    if ($.isFunction(callbackFalse)) {
                    callbackFalse.apply();
                }
                $( this ).dialog( "close" );
            }
        }
    ] });

}
$.modal = function () {
    };
$.modal.close = function () {
    if( $.mobile == undefined ) {
        $("#tiptip_holder").remove();
       $("div:ui-dialog:last:visible").dialog("close");
    }
    else {
        $("."+$.mobile.activePage).dialog("close");
    }
};

function confirm(message, callback,callbackFalse) {
    $("#conteudo_flutuante_confirm");
    $("body").append("<div id='conteudo_flutuante_confirm' title='Confirma&ccedil;&atilde;o?' style='display:none'></div>");
    var html="<table width='100%'><tr><td style='min-height: 40px; display: block;padding-left:10px;line-height:16px'>";
        html+=message;
        html+="</td></tr></table>";
    $("#conteudo_flutuante_confirm").html(html);
    $("#conteudo_flutuante_confirm").dialog({
            position: { my: "center", at: "center", of: window },
            height: 160,
            width:480,
            modal: true,
            resizable:false
//            buttons: {
//              "Sim": function() {
//                  if ($.isFunction(callback)) {
//                      callback.apply();
//                  }
//                  $( this ).dialog( "close" );
//              },
//              "Não": function() {
//                  if ($.isFunction(callbackFalse)) {
//                      callbackFalse.apply();
//                  }
//                  $( this ).dialog( "close" );
//              }
//          }
        });
    $("#conteudo_flutuante_confirm").dialog({ buttons: [
        {   text: "Sim",
            click: function() {
                    if ($.isFunction(callback)) {
                    callback.apply();
                }
                $( this ).dialog( "close" );
            }
        },{   html: "N&atilde;o",
            click: function() {
                if ($.isFunction(callbackFalse)) {
                    callbackFalse.apply();
                }
                $( this ).dialog( "close" );
            }
        }
    ] });
}
