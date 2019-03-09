<?php
require_once __DIR__ . '/config.php';

//トップページを経由せずに直接アクセスした場合は
if (SERVER_PATH !== $_SERVER['HTTP_REFERER']) {
    // トップページへリダイレクトする
    $url = SERVER_PATH;
    header('Location: ' . $url, true, 302);

    // すべての出力を終了
    exit;
} else {
    // echo "OK";
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-113487092-5"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-113487092-5');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>みんなのトイレ</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@daisuke7924" />
    <meta name="twitter:creator" content="@daisuke7924" />
    <meta property="og:url" content="https://everyones-toilet.tokyo/toilet03.php" />
    <meta property="og:title" content="みんなのトイレ" />
    <meta property="og:description" content="ちょっと一人になりたいとき。泣きたいとき。ほ、っと一息つきたいとき。人はトイレにいくのです。" />
    <meta property="og:image" content="https://everyones-toilet.tokyo/images/ogp.png" />
</head>

<body>
    <main class="room">
        <section class="room-wall">
            <?php
require_once __DIR__ . '/config.php';

$pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);

$stmt = $pdo->query('SELECT * from cleans WHERE kind = "last"');
$lastCleanTime = $stmt->fetch(PDO::FETCH_ASSOC)["datetime"];
$stmt = $pdo->prepare('SELECT * FROM graffitis WHERE ROOM = ? AND datetime > ?');
$stmt->execute(array(3, $lastCleanTime));

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $contents = $row["contents"];
    $x = $row["x"];
    $y = $row["y"];

    echo '<p style="position: absolute; top: ' . $y . 'px; left: ' . $x . 'px;">' . $contents . '</p>';

}

?>
        </section>
        <section class="room-floor">
            <img class="toilet" src="images/toilet03.png" alt="3番目の個室のトイレ">
            <div class="room-floor-btn-area">
                <a class="sns-btn active-btn"
                    href="http://twitter.com/intent/tweet?text=3番目の個室に入りました%0a&hashtags=みんなのトイレ&url=https://everyones-toilet.tokyo"
                    onclick="window.open(this.href, 'tweetwindow', 'width=550, height=450,personalbar=0,toolbar=0,scrollbars=1,resizable=1'); return false;">
                    トイレ報告
                </a>
                <a href="index.html">トイレから出る</a>
            </div>
        </section>
    </main>
    <footer>
        &copy;みんなのトイレ
    </footer>
    <script src="script.js"></script>
</body>

</html>