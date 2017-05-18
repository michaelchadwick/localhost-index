var consoleHolder = console;
function debug(bool) {
  if(!bool) {
    consoleHolder = console;
    console = {};
    console.log = function(){};
  } else {
    console = consoleHolder;
  }
}
debug(false);

var i = 0;
var position = "";
var mousestatus = "";
var clickevent = "";
var sidebar = "";
var main = "";

function updateSidebarDisplay(id) {
  var dt = document.getElementById(id);
  var links = document.querySelectorAll("dt a");

  for (var i = 0; i<links.length; i++) {
    links[i].classList.remove("selected");
  }

  dt.className += " selected";
}

// load sites into iframe
function load_url(url, id) {
  var placeholder = document.createElement('div');
  placeholder.className = 'placeholder';
  placeholder.textContent = 'LOADING...';
  document.getElementById('main').appendChild(placeholder);
  var iframe = document.getElementById("site_contents");
  iframe.style.setProperty('display', 'none');
  placeholder.parentNode.insertBefore(iframe, placeholder.nextSibling);

  iframe.src = url;

  iframe.addEventListener('load', function() {
    if (placeholder.parentNode)
      placeholder.parentNode.removeChild(placeholder);
    iframe.style.removeProperty('display');
  });

  updateSidebarDisplay(id);
}

// pay attention to dragbar
function dragBarMouseDown(e) {
  e.preventDefault();
  mousestatus = "mousedown" + i++;
  window.addEventListener("mousemove", windowMouseMove, false);
  console.log('registered mousemove event');
  console.log('leaving mouseDown');
}

// update mouse position
function windowMouseMove(e) {
  position = e.pageX + ', ' + e.pageY;
  sidebar = document.getElementById("sidebar").style.width = e.pageX + 2 + "px";
  main = document.getElementById("main").style.left = e.pageX + 2 + "px";
  console.log('sidebar width', sidebar);
  console.log('main left', main);
  console.log('mouse position', position);
}

// check for mouseup
function windowMouseUp(e) {
  clickevent = 'in another mouseUp event' + i++;
  console.log('clickevent', clickevent);
  window.removeEventListener("mousemove", windowMouseMove, false);
  console.log('unregistered mousemove event');
}

// entrypoint function
const init = () => {
  console.log('dragbar init');
  window.addEventListener("mouseup", windowMouseUp, false);
  var dragbar = document.getElementById("dragbar");
  dragbar.addEventListener("mousedown", dragBarMouseDown, false);
  dragbar.style.setProperty('cursor', 'col-resize');
}

// onload, do our bootstrap
//window.onload = init;
