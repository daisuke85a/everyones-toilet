function invalidEvent() {
  console.log("click invalid");
  event.preventDefault(); //イベントを無効化する
}

//aTagの有効/無効を切替する
function aTagClickChangeValid(valid) {
  var list = document.getElementsByTagName("a");

  if (valid == true) {
    console.log("aTagClickChangeValid true");
    for (var i = 0, len = list.length; i < len; i++) {
      list.item(i).removeEventListener("click", invalidEvent);

      // list.item(i).style.backgroundImage = 'url(images/toilet-open.png)';
      list.item(i).addEventListener("mouseover", function () {
        event.preventDefault();
        console.log("mouseover");
        this.style.backgroundImage = 'url(images/toilet-open.png)';
      })
      list.item(i).addEventListener("mouseenter", function () {
        event.preventDefault();
        console.log("mouseenter");
        this.style.backgroundImage = 'url(images/toilet-open.png)';
      })

      //mouseが領域から外れたらドアを閉じる
      list.item(i).addEventListener("mouseout", function () {
        event.preventDefault();
        console.log("mouseout");
        this.style.backgroundImage = 'url(images/toilet-close.png)';
      })

    }
  } else {
    console.log("aTagClickChangeValid false");
    for (var i = 0, len = list.length; i < len; i++) {
      //aタグ要素のclickをすべて無効化する
      list.item(i).addEventListener("click", invalidEvent);

      // list.item(i).style.backgroundImage = 'url(images/toilet-open.png)';
      list.item(i).addEventListener("mouseover", function () {
        event.preventDefault();
        console.log("mouseover");
        this.style.backgroundImage = 'url(images/toilet-close.png)';
      })
      list.item(i).addEventListener("mouseenter", function () {
        event.preventDefault();
        console.log("mouseenter");
        this.style.backgroundImage = 'url(images/toilet-close.png)';
      })
      list.item(i).addEventListener("mouseout", function () {
        event.preventDefault();
        console.log("mouseout");
        this.style.backgroundImage = 'url(images/toilet-close.png)';
      })

    }

    var toilet2 = document.getElementById('toilet2');
    console.log("toilet2");
    console.log(toilet2);
    toilet2.classList.remove('hover');
  }
}

function confirmCleaningWithAjax() {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      //通信が完了した時
      if (xhr.status === 200) {

        console.log("掃除アニメーション表示する？= " + xhr.responseText);

        if (xhr.responseText == "true") {
          var obachan = document.createElement('img');
          obachan.className = "obachan01-active";
          obachan.src = "images/obachan01.gif";
          var list = document.getElementsByTagName("main");
          list[0].appendChild(obachan);

          var el = document.querySelector('.obachan01-active');
          el.addEventListener('animationend', function () {
            // animation終了時の処理
            aTagClickChangeValid(true);
          });
        } else {
          aTagClickChangeValid(true);
        }

      } else {
        //通信が失敗した時
      }
    } else {
      //通信が完了する前
    }
  };

  //サーバーとの非同期通信を開始
  xhr.open("GET", "_ajax.php?request=confirmCleaningWithAjax", true);
  xhr.send(null);
}

$(function () {
  console.log("hello");
  var num = 0;

  $(".room-wall").on("click", function (e) {
    console.log("screen=" + e.screenX + "," + e.screenY);
    console.log("page=" + e.pageX + "," + e.pageY);
    console.log("client=" + e.clientX + "," + e.clientY);
    console.log("offset=" + e.offsetX + "," + e.offsetY);
    console.log("scrollLeft()=" + $(".room-wall").scrollLeft());

    //スマホ用に縦スクロール対応する
    var offsetY = e.offsetY + $(".room-wall").scrollTop();
    //room-wallは横スクロール対応しているため、scrollLeftを足している。（縦スクロールは対応していない。）
    var offsetX = e.offsetX + $(".room-wall").scrollLeft();

    console.log("offsetY=" + offsetY);
    console.log("offsetX=" + offsetX);

    //一つ前の落書きフォームが残っていたら削除する落書き入力フォームを削除
    if (document.getElementById("new_todo_form_" + num) != null) {
      document.getElementById("new_todo_form_" + num).remove();
    }
    if (document.getElementById("fukidashi") != null) {
      document.getElementById("fukidashi").remove();
    }

    num++;
    $(".room-wall").append(
      '<form action="" id="new_todo_form_' +
      num +
      '"><input type="text" id="new_todo_' +
      num +
      '" style="position: absolute; top: ' +
      (Number(offsetY) - 0) +
      "px; left: " +
      Number(offsetX - 8) +
      'px;"></form>'
    );

    $(".room-wall").append(
      '<div id="fukidashi"; style="position: absolute; top: ' +
      (Number(offsetY) - 40) +
      "px; left: " +
      Number(offsetX - 8) +
      'px;">落書きできるよ〜<span></span></form>'
    );
    console.log(e);

    $("#new_todo_" + num).focus();

    document
      .getElementById("new_todo_form_" + num)
      .addEventListener("submit", function (event) {
        event.preventDefault();
        console.log("addEventListener");
        var roomNum = Number(
          event.target.action.match(/...php/)[0].substr(0, 2)
        );
        console.log(roomNum);

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
              y: offsetY,
              room: roomNum
            }
          })
          // Ajaxリクエストが成功した時発動
          .done(data => {
            console.log("ajax done");
            console.log(data);

            //入力された落書きをp要素で追加。（勉強がてら、一部で生javasriptで書いている。設計上の意味はない。）
            var p = document.createElement("p");
            p.style =
              "position: absolute; top: " +
              offsetY +
              "px; left: " +
              offsetX +
              "px;";
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
            document.getElementById("new_todo_form_" + num).remove();
            document.getElementById("fukidashi").remove();
          });

        //画面のリフレッシュを防ぐためにreturn falseする
        return false;
      });
  });
});