var lastid;
function toggle_source(id) {
  el = document.getElementById(id).style.display;
  if (el === 'none' || el === '') {
    if (lastid !== undefined) {
      document.getElementById(lastid).style.display = 'none';
    }
    document.getElementById(id).style.display = 'block';
  } else {
    document.getElementById(id).style.display = 'none';
  }
  lastid = id;
}
