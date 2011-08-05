var previous;
function toggle_source(id, fncname, filename) {
  current = $("#" + id);

  if (current.css("display") === "none") {
    current.html('<img src="images/loader.gif" alt="loading...">');
    $.ajax({
      type: "GET",
      dataType: "text",
      url: "getsrc.php",
      data: "fnc="+fncname+"&file="+filename,
      success: function(msg){
        if (msg != 'false') {
          current.html('<pre>' + msg + '</pre>');
        }
      }
    });
    if (previous !== undefined) {
      previous.hide();
    }
  }
  current.toggle();
  previous = current;
}

var fnc;
var file;
var obj;
function show_statusboard(id, fncname, filename) {
  fnc   = fncname;
  file  = filename;
  obj   = id;
  var s = $('#statusboard');

  offset = id.offset();
  s.css("top", offset.top + "px");
  s.css("display", 'block');
}

function set_status(status) {
  if (fnc !== undefined && file != undefined) {
    $.ajax({
      type: "GET",
      dataType: "text",
      url: "setstatus.php",
      data: "fnc="+fnc+"&file="+file+"&status="+status,
      success: function(msg){
        if (msg == 'true') {
          obj.attr('src', "images/"+status+".png");
          var done = parseInt($('#status_done').html());
          if (status == 'done') {
            $('#status_done').html(done+1);
          } else if (status == 'not_done') {
            $('#status_done').html(done-1);
          }
        }
        $("#statusboard").toggle();
      }
    });
  }

}
