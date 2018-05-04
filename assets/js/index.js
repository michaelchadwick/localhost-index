function updateSidebarSelection (id) {
  var dt = document.getElementById(id)
  var links = document.querySelectorAll('dt a')

  for (var i = 0; i < links.length; i++) {
    links[i].classList.remove('selected')
  }

  dt.className += ' selected'
}

// load sites into iframe
function loadUrl (evt) {
  evt.preventDefault();
  var url = evt.target.attributes['data-url'].value
  var id = evt.target.attributes.id.value

  var placeholder = document.createElement('div')
  placeholder.className = 'placeholder'
  placeholder.textContent = `${url.toUpperCase()}\nLOADING...`
  document.getElementById('main').appendChild(placeholder)

  var iframe = document.getElementById('site_contents')
  iframe.style.setProperty('display', 'none')
  placeholder.parentNode.insertBefore(iframe, placeholder.nextSibling)
  iframe.src = url

  iframe.addEventListener('load', function () {
    if (placeholder.parentNode) {
      placeholder.parentNode.removeChild(placeholder)
    }
    iframe.style.removeProperty('display')
  })

  updateSidebarSelection(id)
  updateIframeSrc()
}

function updateIframeSrc () {
  var iframeSrc = document.getElementById('site_contents').src
  document.getElementById('iframe_src').innerText = iframeSrc
}

function toggleKeyDebug (status, event) {
  if (status) {
    var e = event || window.event
    var ctrl = e.ctrlKey
    var alt = e.altKey
    var cmd = e.keyCode === 91
    var shift = e.shiftKey

    console.log(`keyCode: %d, shift: %s, ctrl: %s, alt: %s, cmd: %s`, e.keyCode, shift, ctrl, alt, cmd)
  }
}

function init () {
  var urls = document.querySelectorAll('dt a')

  Array.from(urls).forEach(function (a) {
    a.addEventListener('click', loadUrl, false)
  })

  document.onkeyup = function (event) {
    toggleKeyDebug(false, event)

    if (event.keyCode === 73 && event.ctrlKey && event.altKey) {
      var path = window.location.search
      if (path.indexOf('?iframe=1') >= 0) {
        path = '/'
      } else {
        path = '/?iframe=1'
      }
      window.location.href = path
    }
  }
}

window.onload = init
