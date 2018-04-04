// implement a bar you can drag to change the sidebar width

var i = 0
var position = ''
// var mousestatus = ''
var clickevent = ''
var sidebar = ''
var main = ''

// pay attention to dragbar
function dragBarMouseDown (e) {
  e.preventDefault()
  // var mouseStatus = 'mousedown' + i++
  window.addEventListener('mousemove', windowMouseMove, false)
  // console.log('registered mousemove event')
  // console.log('leaving mouseDown')
}

// update mouse position
function windowMouseMove (e) {
  position = e.pageX + ', ' + e.pageY
  sidebar = document.getElementById('sidebar').style.width = e.pageX + 2 + 'px'
  main = document.getElementById('main').style.left = e.pageX + 2 + 'px'
  // console.log('sidebar width', sidebar)
  // console.log('main left', main)
  // console.log('mouse position', position)
}

// check for mouseup
function windowMouseUp (e) {
  clickevent = 'in another mouseUp event' + i++
  // console.log('clickevent', clickevent)
  window.removeEventListener('mousemove', windowMouseMove, false)
  // console.log('unregistered mousemove event')
}

// entrypoint function
const init = () => {
  // console.log('dragbar init')
  window.addEventListener('mouseup', windowMouseUp, false)
  var dragbar = document.getElementById('dragbar')
  dragbar.addEventListener('mousedown', dragBarMouseDown, false)
  dragbar.style.setProperty('cursor', 'col-resize')
}

// onload, do our bootstrap
window.onload = init
