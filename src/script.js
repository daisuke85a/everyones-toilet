console.log("hello");

$(".room-wall").on("click", function(e) {
    console.log("screen=" + e.screenX + "," + e.screenY);
    console.log("page=" + e.pageX + "," + e.pageY);
    console.log("client=" + e.clientX + "," + e.clientY);
    console.log("offset=" + e.offsetX + "," + e.offsetY);

    //動的にUIを追加
    // divの子要素の最後に追加
    $('.room-wall').append('<p class="add" style="position: absolute; top: ' + e.offsetY +'px; left: ' + e.offsetX + 'px;">にゃーん</p>');
  });
