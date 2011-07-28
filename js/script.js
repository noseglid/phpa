var previous;
function toggle_source(id) {
  current = $("#" + id);
  if (current.css("display") === "none" && previous !== undefined) {
    previous.css("display", "none");
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
        }
        $("#statusboard").toggle();
      }
    });
  }

}
