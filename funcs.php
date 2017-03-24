<?php
/**
 * Make Directory Links
 *
 * Check the current directory, amassing a list of links to make from subdirectories.
 * Then it uses that list to make some HTML links and write it to the browser.
 *
 * @param boolean $useIframe Should we use an iframe to display link?
 */
function make_dir_links($useIframe = false) {
  $dir = opendir(".");
  $files = array();
  $lhignore = array(".", "..");
  $lhignore_filename = ".lhignore";
  $lhignore_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . "{$lhignore_filename}";

  // Append directories in the custom .lhignore file.
  if (file_exists($lhignore_path)) {
    $fp = fopen($lhignore_path, "r") or die("Unable to open custom .lhignore\n");

    $custom_lhignore = explode(PHP_EOL, fread($fp, filesize($lhignore_path)));
    $lhignore = array_merge($lhignore, $custom_lhignore);

    fclose($fp);
  } else {
    echo "no .lhignore";
  }

  // Read all the subdirectories in the current directory.
  while (($file=readdir($dir)) !== false) {
    if (passes_lhignore($file, $lhignore) AND is_dir($file)) {
      array_push($files, $file);
    }
  }
  closedir($dir);
  sort($files);

  // Make <dt>s if there are any subdirectories.
  if (sizeof($files) > 0) {
    echo "<h2>Localhost Projects</h2>";
    echo "<dl>";
    $i = 0;
    foreach ($files as $file) {
      $id = "dir_" . $i;
      if ($useIframe) {
        $html = "<dt><a id='" . $id . "' onclick='load_url(\"http://" . $file . "\", this.id)'>$file</a></dt>";
      } else {
        $html = "<dt><a href='http://$file'>$file</a></dt>";
      }
      echo $html;
      $i++;
    }

    echo "</dl>";
  } else {
    echo "No projects found.";
  }
}

/**
 * Make Port Links
 *
 * Runs `lsof` on your local machine, looking for open TCP ports from likely suspects.
 * It then uses that info to make some HTML links and echo it to the browser.
 *
 * @param boolean $useIframe Should we use an iframe to display link?
 * @param boolean $checkPorts Should we check ports at all?
 */
function make_port_links($useIframe = false, $checkPorts = false) {
  if ($checkPorts) {
    $ports = explode("\n", shell_exec("lsof -i -n -P | grep 'httpd\|vpnkit\|java\|nc' | grep LISTEN | egrep -o -E ':[0-9]{2,5}' | cut -f2- -d: | sort -n | uniq"));

    // Make <dt>s if there are any ports
    if ($ports) {
      echo "<h2>Localhost Web Ports</h2>";
      echo "<dl>";
      $i = 0;
      foreach ($ports as $port) {
        if ($port != "") {
          $id = "port_" . $i;
          if ($useIframe) {
            $html = "<dt><a id='" . $id . "' onclick='load_url(\"http://localhost:" . $port . "\", this.id)'>localhost:$port</a></dt>";
          } else {
            $html = "<dt><a id='" . $id . "' href='http://localhost:$port'>localhost:$port</a></dt>";
          }
          echo $html;
          $i++;
        }
      }
      echo "</dl>";
    }
  }
}

/**
 * Passes lhignore
 *
 * Checks a subdirectory to make sure it passes the black list, if there is one
 *
 * @param string $name Domain name to check
 * @param array $lhignore Array of domains to check against
 *
 * @return boolean $passes Whether the domain name passes or not.
 */
function passes_lhignore($name, $lhignore) {
  $passes = true;

  foreach ($lhignore as $bl) {
    if ($name == $bl) {
      $passes = false;
    }
  }

  return $passes;
}
?>
