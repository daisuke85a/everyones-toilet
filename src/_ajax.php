<?php

require_once __DIR__ . '/config.php';

$contents = $_GET['contents'];
$x = $_GET['x'];
$y = $_GET['y'];


if (!isset($_GET['contents']) || !isset($_GET['x']) || !isset($_GET['y'])) {
    throw new \Exception('[create] title not set!');
}

try {
    //TODO:MAMP環境から本番環境に書き換え必要
    //    $pdo = new PDO('mysql:dbname=everyone-toilet;host=localhost;charset=utf8', 'root', 'root');
    $pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
 
    $stmt = $pdo->prepare("INSERT INTO graffitis (
  contents, x, y
) VALUES (:contents, :x, :y)");

    $stmt->bindParam(':contents', $_GET['contents'], PDO::PARAM_STR);
    $stmt->bindValue(':x', $_GET['x'], PDO::PARAM_INT);
    $stmt->bindValue(':y', $_GET['y'], PDO::PARAM_INT);

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
