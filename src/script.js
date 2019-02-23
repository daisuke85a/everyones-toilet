$(function() {
  console.log("hello");
  var num = 0;

  $(".room-wall").on("click", function(e) {
    console.log("screen=" + e.screenX + "," + e.screenY);
    console.log("page=" + e.pageX + "," + e.pageY);
    console.log("client=" + e.clientX + "," + e.clientY);
    console.log("offset=" + e.offsetX + "," + e.offsetY);

    //動的にUIを追加
    // divの子要素の最後に追加
    //$('.room-wall').append('<p class="add" style="position: absolute; top: ' + e.offsetY +'px; left: ' + e.offsetX + 'px;">にゃーん</p>');

    num++;
    $(".room-wall").append(
      '<form action"" id="new_todo_form_' +
        num +
        '"><input type="text" id="new_todo_' +
        num +
        '" placeholder="落書きできるよ〜" style="position: absolute; top: ' +
        e.offsetY +
        "px; left: " +
        e.offsetX +
        'px;"></form>'
    );

    $("#new_todo_" + num).focus();

    //入力フォームでEnterが押されたら呼ばれる関数
    $("#new_todo_form_" + num).on("submit", function(e) {
      event.preventDefault(); //submitが実行されると、画面が必ず更新されるというブラウザの仕様をキャンセルする

      console.log("submit #new_todo_form_" + num);
      //ここにajaxを入れてphpにデータを渡す予定
      console.log($("#new_todo_form_" + num).val());

      console.log($("#new_todo_" + num).val());

      //ajax処理
      //TODO:本当は.serialize()を使ってフォームのデータを送りたいけどできない。今はvalの値で送っている。
      $.post(
        "https://httpbin.org/post",
        $("#new_todo_" + num).val()
      ).done(function(data) {
        console.log(data.form);
      });

      //画面のリフレッシュを防ぐためにreturn falseする
      return false;
    });

  });
});
