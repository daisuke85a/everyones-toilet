$(function() {
  console.log("hello");
  var num = 0;

  $(".room-wall").on("click", function(e) {
    console.log("screen=" + e.screenX + "," + e.screenY);
    console.log("page=" + e.pageX + "," + e.pageY);
    console.log("client=" + e.clientX + "," + e.clientY);
    console.log("offset=" + e.offsetX + "," + e.offsetY);
    console.log("scrollLeft()=" + $(".room-wall").scrollLeft() );

    //動的にUIを追加
    // divの子要素の最後に追加
    //$('.room-wall').append('<p class="add" style="position: absolute; top: ' + e.offsetY +'px; left: ' + e.offsetX + 'px;">にゃーん</p>');

    var offsetY = e.clientY; 
    var offsetX = e.clientX + $(".room-wall").scrollLeft();

    console.log("offsetY=" + offsetY);
    console.log("offsetX=" + offsetX);

    num++;
    $(".room-wall").append(
      '<form action"" id="new_todo_form_' +
        num +
        '"><input type="text" id="new_todo_' +
        num +
        '" placeholder="落書きできるよ〜" style="position: absolute; top: ' +
        offsetY +
        "px; left: " +
        offsetX  +
        'px;"></form>'
    );

    console.log(e);


    $("#new_todo_" + num).focus();

    //入力フォームでEnterが押されたら呼ばれる関数
    $("#new_todo_form_" + num).on("submit", function(e) {
      event.preventDefault(); //submitが実行されると、画面が必ず更新されるというブラウザの仕様をキャンセルする

      console.log("submit #new_todo_form_" + num);
      //ここにajaxを入れてphpにデータを渡す予定
      console.log($("#new_todo_form_" + num).val());

      console.log($("#new_todo_" + num).val());

      //ajax処理
      
      $.ajax({
        url: "./_ajax.php", //TODO:本当は自サーバーのphpファイルを対象に送りたい。今はダミーサーバーに送っている。
        type: "GET",
        data: {
          contents: $("#new_todo_" + num).val(),
          x: offsetX,
          y: offsetY
        }
      })
        // Ajaxリクエストが成功した時発動
        .done(data => {
          console.log("ajax done");
          console.log(data);

          var p = document.createElement('p');
          p.style= 'position: absolute; top: ' + offsetY + 'px; left: ' + offsetX + 'px;';
          p.textContent = $("#new_todo_" + num).val();
          document.getElementsByClassName("room-wall")[0].appendChild(p);

        })
        // Ajaxリクエストが失敗した時発動
        .fail(data => {
          console.log("ajax fail");
        })
        // Ajaxリクエストが成功・失敗どちらでも発動
        .always(data => {
          document.getElementById("new_todo_form_" + num ).remove();
        });

      //画面のリフレッシュを防ぐためにreturn falseする
      return false;
    });
  });
});
