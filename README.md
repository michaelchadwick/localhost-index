# Localhost Index

![Localhost Index Full Page](assets/img/localhost_index_full_page.png "Localhost Index Full Page")

Have a bunch of web projects on your local development computer, nestled in your ~/Sites (or other-OS equivalent)? Just need a simple way to enumerate them as links so you don't have to keep track of them as they get created/removed? OK with PHP? **Localhost Index** has got your back.

![Localhost Index Mobile](assets/img/localhost_index_mobile.png "Localhost Index Full Mobile")

Just `git clone` this project into your web project root, `sass assets/scss:assets/css`, make sure your local web server is turned on, and go to `http://localhost` in a browser.

_Note: if you want to use a version that loads sites into an iframe, load up `http://localhost/?iframe=1` instead (or hit Ctrl+Shift+Alt+i to toggle)._

![Localhost Index Iframe](assets/img/localhost_index_iframe.png "Localhost Index Iframe")

## Configuration

**Localhost Index** already excludes anything that isn't a directory (and the usual `.` and `..`), but it can also take a custom list of directories to exclude when enumerating. Just make a `.lhignore` file in the same directory and it will skip anything in it.

Since port numbers are not the most descriptive of things, you can copy the included `port_labels.dist.php` file to `port_labels.php` and add your own `'port_number' => 'port_label'` mappings. They will be displayed to the right of the port number in parentheses.

Also, **Localhost Index** will run an `lsof` on your system to find any open and listening TCP ports that are likely to be websites, and links them in a separate section. You can turn this off by changing `$checkPorts` to `false`.
