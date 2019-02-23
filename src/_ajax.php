<?php
header('Content-type: text/plain; charset= UTF-8');


$contents = $_POST['contents'];
$x = $_POST['x'];
$y = $_POST['y'];

//TODO:データベースに座標データを書き込む

$str = "AJAX REQUEST SUCCESS" . " contens=" . $contents . " x=". $x . " y=" . $y;
$result = nl2br($str);
echo $result;
?>