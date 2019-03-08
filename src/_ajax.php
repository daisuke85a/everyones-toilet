<?php

require_once __DIR__ . '/config.php';

if ($_GET['request'] === 'confirmCleaningWithAjax') {

    date_default_timezone_set('Asia/Tokyo');

    try {
        $pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query('SELECT * from cleans WHERE kind = "last"');
        $lastCleanTime = $stmt->fetch(PDO::FETCH_ASSOC)["datetime"];
        echo "lastCleanTime =" . $lastCleanTime;

        $stmt = $pdo->query('SELECT * from cleans WHERE kind = "next"');
        $nextCleanTime = $stmt->fetch(PDO::FETCH_ASSOC)["datetime"];

        $lastClean = DateTime::createFromFormat('Y-m-d H:i:s', substr($lastCleanTime, 0, 19));
        echo "lastClean =";
        echo $lastClean->format('Y-m-d H:i:s');
        echo "  ";

        $nextClean = DateTime::createFromFormat('Y-m-d H:i:s', substr($nextCleanTime, 0, 19));
        echo "nextClean =";
        echo $nextClean->format('Y-m-d H:i:s');
        echo "  ";

        $now = new DateTime();
        echo "now =";
        echo $now->format('Y-m-d H:i:s');
        echo "  ";

        //現在時刻がシステムの次回掃除時間を超えている場合
        if ($now > $nextClean) {
            echo "now > nextClean";
            //前回掃除時間を次回掃除時間で更新する。
            $stmt = $pdo->prepare('UPDATE cleans SET datetime = :datetime WHERE kind ="last"');
            $stmt->bindParam(':datetime', $nextCleanTime, PDO::PARAM_STR);
            $stmt->execute();

            //次回掃除時間を１週間後に設定する
            $nextClean->add(new DateInterval('P7D'));
            $stmt = $pdo->prepare('UPDATE cleans SET datetime = :datetime WHERE kind ="next"');
            $stmt->bindParam(':datetime', $nextClean->format('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->execute();

        } else {
            echo "now < nextClean";
        }

        //ユーザー画面に掃除を表示するか、しないかフラグ
        $haveToClean = "false";

        //ユーザー画面に１回でも清掃画面が表示されたことがある場合
        if (!empty($_COOKIE["cleaningdatetime"])) {
            $userCleanDateTime = $_COOKIE["cleaningdatetime"];
            $userCleanDateTime = DateTime::createFromFormat( 'Y-m-d H:i:s', $userCleanDateTime );

            echo " beforeCleanDateTime=" . $userCleanDateTime->format('Y-m-d H:i:s');
            
            //前回ユーザー掃除日時より、最新システム掃除日時のほうが後　かつ
            //最新システム掃除時間より、今のほうが後　の場合
            echo "userCleanDateTime <  lastClean"; var_dump( $userCleanDateTime <  $lastClean );
            echo "lastClean < now"; var_dump( $lastClean < $now );

            if( ( $userCleanDateTime <  $lastClean ) && ( $lastClean < $now ) ){
               //ユーザー画面で清掃を実施する 
               $haveToClean = "true";
               echo "haveToClean = true";
            }
            else{
                echo "haveToClean = false"; 
            }
        //ユーザー画面に１回も清掃画面が表示されてない場合
        } else {
            //ユーザー画面で清掃を実施する 
            $haveToClean = "true";
            echo "haveToClean = true";
        }

    } catch (PDOException $e) {
        $str = $e->getMessage();
        $result = nl2br($str);
        echo $result;
        die();
    }

    // 接続を閉じる
    $pdo = null;

    // //ユーザーが１回でも清掃したことがある場合
    // if (!empty($_COOKIE["cleaningdatetime"])) {
    //     $beforeCleanDateTime = $_COOKIE["cleaningdatetime"];
    //     echo $beforeCleanDateTime;
    //     //ユーザーが未清掃の場合
    // } else {
    //     echo "";
    // }

    $now = new DateTime();
    setcookie("cleaningdatetime", $now->format('Y-m-d H:i:s'), time() + 24 * 3600 * 365); //現在時刻を1年保存する
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
