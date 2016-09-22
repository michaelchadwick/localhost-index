# Localhost Index

Have a bunch of web projects in your ~/Sites (or other-OS equivalent)? Just need a simple way to enumerate them as links so you don't need to keep track of them as they get created/removed? OK with PHP? Localhost Index has got your back.

Just put `git clone` this project into your web project root, make sure your local web server is turned on, and go to `http://localhost` in a browser.

## Configuration

**Localhost Index** already excludes anything that isn't a directory (and the usual `.` and `..`), but it can also take a custom list of directories to exclude when enumerating. Just make a ".blacklist" file in the same directory and it will skip anything in it.
