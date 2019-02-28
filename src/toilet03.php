<!DOCTYPE html>
<html lang="ja">
  <head>
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
        可能かどうかはわかりませんが、画面の幅が足りない時横スクロールしたいです〜
        ああああああああああああああああああああいいいいいいいいいいいいいいいいいいい
        うううううううううううううううううううええええええええええええええええええ
        おおおおおおおおおおおおおおおおおおおお
        <?php
            require_once __DIR__ . '/config.php';

            $pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
            $stmt = $pdo->query("SELECT * FROM graffitis ");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $contents = $row["contents"];
                $x = $row["x"];
                $y = $row["y"];
                
                echo '<p style="position: absolute; top: ' . $y . 'px; left: ' . $x . 'px;">' .  $contents . '</p>';

            }

          ?>
      </section>
      <section class="room-floor">
        <img src="images/toilet03.png" alt="3番目の個室のトイレ">
        <a class="room-floor-btn" href="index.html">トイレから出る</a>
      </section>
    </main>
    <footer>
      &copy;みんなのトイレ
    </footer>
    <script src="script.js"></script>
  </body>

</html>