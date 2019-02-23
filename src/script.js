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
});
