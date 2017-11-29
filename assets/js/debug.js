var consoleHolder = console

function debug (bool) {
  if (!bool) {
    consoleHolder = console
    window.console = {}
    window.console.log = function () {}
  } else {
    window.console = consoleHolder
  }
}

// set this to true for console.log() calls to work
debug(true)
