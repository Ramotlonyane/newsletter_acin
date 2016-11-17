<?
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
$resposta=utf8_encode_all($resposta);
echo json_encode($resposta);
?>