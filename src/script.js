$(function() {
  console.log("hello");
  var num = 0;

  $(".room-wall").on("click", function(e) {
    console.log("screen=" + e.screenX + "," + e.screenY);
    console.log("page=" + e.pageX + "," + e.pageY);
    console.log("client=" + e.clientX + "," + e.clientY);
    console.log("offset=" + e.offsetX + "," + e.offsetY);
    console.log("scrollLeft()=" + $(".room-wall").scrollLeft() );

    var offsetY = e.offsetY; 
    //room-wallは横スクロール対応しているため、scrollLeftを足している。（縦スクロールは対応していない。）
    var offsetX = e.offsetX + $(".room-wall").scrollLeft();

    console.log("offsetY=" + offsetY);
    console.log("offsetX=" + offsetX);

    //一つ前の落書きフォームが残っていたら削除する落書き入力フォームを削除
    if(document.getElementById("new_todo_form_" + num ) != null){
      document.getElementById("new_todo_form_" + num ).remove();
    }
    if(document.getElementById("fukidashi") != null){
      document.getElementById("fukidashi").remove();
    }

    num++;
    $(".room-wall").append(
      '<form action"" id="new_todo_form_' +
        num +
        '"><input type="text" id="new_todo_' +
        num +
        '" placeholder="落書きできるよ〜" style="position: absolute; top: ' +
        (Number(offsetY) - 0 ) +
        "px; left: " +
        (Number(offsetX - 8 ))  +
        'px;"></form>'
    );

    $(".room-wall").append(
      '<div id="fukidashi"; style="position: absolute; top: ' +
        (Number(offsetY) - 40) +
        "px; left: " +
        (Number(offsetX - 8 ))  +
        'px;"></form>'
    );


    console.log(e);


    $("#new_todo_" + num).focus();

    //入力フォームでEnterが押されたら呼ばれる関数
    $("#new_todo_form_" + num).on("submit", function(e) {
      event.preventDefault(); //submitが実行されると、画面が必ず更新されるというブラウザの仕様をキャンセルする

      console.log("submit #new_todo_form_" + num);
      console.log($("#new_todo_form_" + num).val());
      console.log($("#new_todo_" + num).val());

      //ajax処理
      
      $.ajax({
        url: "./_ajax.php", 
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

          //入力された落書きをp要素で追加。（勉強がてら、一部で生javasriptで書いている。設計上の意味はない。）
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
          //落書き入力フォームを削除
          document.getElementById("new_todo_form_" + num ).remove();
          document.getElementById("fukidashi").remove();
        });

      //画面のリフレッシュを防ぐためにreturn falseする
      return false;
    });
  });
});
