<?php

$contents = $_POST['contents'];
$x = $_POST['x'];
$y = $_POST['y'];


if (!isset($_POST['contents']) || !isset($_POST['x']) || !isset($_POST['y'])) {
    throw new \Exception('[create] title not set!');
}

try {
    //TODO:MAMP環境から本番環境に書き換え必要
    //    $pdo = new PDO('mysql:dbname=everyone-toilet;host=localhost;charset=utf8', 'root', 'root');
    $pdo = new PDO('mysql:dbname=everyone-toilet;host=localhost;charset=utf8', 'root', 'root');
 
    $stmt = $pdo->prepare("INSERT INTO graffitis (
  contents, x, y
) VALUES (:contents, :x, :y)");

    $stmt->bindParam(':contents', $_POST['contents'], PDO::PARAM_STR);
    $stmt->bindValue(':x', $_POST['x'], PDO::PARAM_INT);
    $stmt->bindValue(':y', $_POST['y'], PDO::PARAM_INT);

    $stmt->execute();

} catch (PDOException $e) {
    $str = $e->getMessage();
    $result = nl2br($str);
    echo $result;
    die();
}

// 接続を閉じる
$pdo = null;

$str = "AJAX REQUEST SUCCESS" . " contens=" . $contents . " x=" . $x . " y=" . $y;
$result = nl2br($str);
echo $result;
