<?php
/**
 * Make links to all subdirectories in the current directory
 */
function make_dir_links($useIframe = false) {
  $dir = opendir(".");
  $files = array();
  $blacklist = array(".", "..");
  $blacklist_filename = ".blacklist";
  $blacklist_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . "{$blacklist_filename}";

  // Append directories in the custom .blacklist file.
  if (file_exists($blacklist_path)) {
    $fp = fopen($blacklist_path, "r") or die("Unable to open custom blacklist\n");

    $custom_blacklist = explode(PHP_EOL, fread($fp, filesize($blacklist_path)));
    $blacklist = array_merge($blacklist, $custom_blacklist);

    fclose($fp);
  } else {
    echo "no blacklist";
  }

  // Read all the subdirectories in the current directory.
  while (($file=readdir($dir)) !== false) {
    if (passes_blacklist($file, $blacklist) AND is_dir($file)) {
      array_push($files, $file);
    }
  }
  closedir($dir);
  sort($files);

  // Make <dt>s if there are any subdirectories.
  if (sizeof($files) > 0) {
    echo "<h2>Localhost Projects</h2>";
    echo "<dl>";

    foreach ($files as $file) {
      if ($useIframe) {
        $html = "<dt><a onclick='load_url(\"http://$file\")'>$file</a></dt>";
      } else {
        $html = "<dt><a href='http://$file'>$file</a></dt>";
      }
      echo $html;
    }

    echo "</dl>";
  } else {
    echo "No projects found.";
  }
}

function passes_blacklist($name, $blacklist) {
  $passes = true;

  foreach ($blacklist as $bl) {
    if ($name == $bl) {
      $passes = false;
    }
  }

  return $passes;
}

/**
 * Make links to open ports being listened to that are most likely websites
 */
function make_port_links($useIframe = false, $checkPorts = false) {
  if ($checkPorts) {
    $ports = explode("\n", shell_exec("lsof -i -n -P | grep 'httpd\|vpnkit\|java\|nc' | grep LISTEN | egrep -o -E ':[0-9]{2,5}' | cut -f2- -d: | sort -n | uniq"));

    if ($ports) {
      echo "<h2>Localhost Web Ports</h2>";
      echo "<dl>";
      foreach ($ports as $port) {
        if ($port != "") {
          if ($useIframe) {
            $html = "<dt><a onclick='load_url(\"http://localhost:$port\")'>localhost:$port</a></dt>";
          } else {
            $html = "<dt><a href='http://localhost:$port'>localhost:$port</a></dt>";
          }
          echo $html;
        }
      }
      echo "</dl>";
    }
  }
}
?>
