<?php

require_once __DIR__ . '/config.php';
date_default_timezone_set('Asia/Tokyo');

if ($_GET['request'] === 'confirmCleaningWithAjax') {

    try {
        $pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query('SELECT * from cleans WHERE kind = "last"');
        $lastCleanTime = $stmt->fetch(PDO::FETCH_ASSOC)["datetime"];

        $stmt = $pdo->query('SELECT * from cleans WHERE kind = "next"');
        $nextCleanTime = $stmt->fetch(PDO::FETCH_ASSOC)["datetime"];

        $lastClean = DateTime::createFromFormat('Y-m-d h:i:s',substr($lastCleanTime,0,19));
        echo "lastClean =";
        echo $lastClean->format('Y-m-d h:i:s');
        echo "  ";

        $nextClean = DateTime::createFromFormat('Y-m-d h:i:s',substr($nextCleanTime,0,19));
        echo "nextClean =";
        echo $nextClean->format('Y-m-d h:i:s');
        echo "  ";

        $now = new DateTime();
        echo "now =";
        echo $now->format('Y-m-d h:i:s');
        echo "  ";

        if( $now > $nextClean ){
            echo "now > nextClean";
        }
        else{
            echo "now < nextClean";
        }



    } catch (PDOException $e) {
        $str = $e->getMessage();
        $result = nl2br($str);
        echo $result;
        die();
    }

    // 接続を閉じる
    $pdo = null;

    //ユーザーが１回でも清掃したことがある場合
    if (!empty($_COOKIE["cleaningdatetime"])) {
        $beforeCleanDateTime = $_COOKIE["cleaningdatetime"];
        echo $beforeCleanDateTime;
    //ユーザーが未清掃の場合
    } else {
        echo "";
    }

    setcookie("cleaningdatetime", time(), time() + 24 * 3600 * 365); //現在時刻を1年保存する
    //setcookie("cleaningdatetime", time(), time() -1 ); //クッキーを削除する(テスト用コード)

} else {
    $contents = $_GET['contents'];
    $x = $_GET['x'];
    $y = $_GET['y'];
    $room = $_GET['room'];

    if (!isset($_GET['contents']) || !isset($_GET['x']) || !isset($_GET['y'])) {
        throw new \Exception('[create] title not set!');
    }

    try {
        $pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("INSERT INTO graffitis (
  contents, x, y , room ,clean
) VALUES (:contents, :x, :y , :ROOM, :clean)");

        $stmt->bindParam(':contents', $_GET['contents'], PDO::PARAM_STR);
        $stmt->bindValue(':x', $_GET['x'], PDO::PARAM_INT);
        $stmt->bindValue(':y', $_GET['y'], PDO::PARAM_INT);
        $stmt->bindValue(':ROOM', $_GET['room'], PDO::PARAM_INT);
        $stmt->bindValue(':clean', 0, PDO::PARAM_INT);

        $stmt->execute();

    } catch (PDOException $e) {
        $str = $e->getMessage();
        $result = nl2br($str);
        echo $result;
        die();
    }

// 接続を閉じる
    $pdo = null;

    $str = "AJAX REQUEST SUCCESS" . " contens=" . $contents . " x=" . $x . " y=" . $y . " room=" . $room;
    $result = nl2br($str);
    echo $result;

}
