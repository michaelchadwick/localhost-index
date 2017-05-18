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
  echo "\t<h2>Localhost Projects</h2>";
  if (sizeof($files) > 0) {
    echo "\n\t\t<dl>\n";
    $i = 0;
    foreach ($files as $file) {
      $id = "dir_" . $i;
      $link = "http://" . $file;
      if ($useIframe) {
        $html = "\t\t\t<dt class='dir'><a alt='$link' title='$link' id='$id' onclick='load_url(\"" . $link . "\", this.id)'>$file</a></dt>\n";
      } else {
        $html = "\t\t\t<dt class='dir'><a alt='$link' title='$link' href='$link'>$file</a></dt>\n";
      }
      echo $html;
      $i++;
    }

    echo "\t\t</dl>\n";
  } else {
    echo "<article><p>No projects found.</p></article>";
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
 * @param boolean $useFilter Should we use a process filter?
 */
function make_port_links($useIframe = false, $checkPorts = false, $usePortFilter = false) {
  if ($checkPorts) {
    $filter = $usePortFilter ? " | grep 'httpd\|vpnkit\|java\|nc'" : "";
    $ports = explode("\n", shell_exec("lsof -i -n -P" . $filter . " | grep LISTEN | egrep -o -E ':[0-9]{2,5}' | cut -f2- -d: | sort -n | uniq"));

    // Make <dt>s if there are any ports
    echo "\t\t<h2>Localhost Web Ports</h2>";
    if ($ports) {
      echo "\n\t\t\t<dl>\n";
      $i = 0;
      foreach ($ports as $port) {
        if ($port != "" && $port != $_SERVER['SERVER_PORT']) {
          $id = "port_" . $i;
          $link = "http://localhost:" . $port;
          if ($useIframe) {
            $html = "\t\t\t\t<dt class='port'><a alt='$link' title='$link' id='$id' onclick='load_url(\"" . $link . "\", this.id)'>localhost:$port</a></dt>\n";
          } else {
            $html = "\t\t\t\t<dt class='port'><a alt='$link' title='$link' id='" . $id . "' href='$link'>localhost:$port</a></dt>\n";
          }
          echo $html;
          $i++;
        }
      }
      echo "\t\t\t</dl>\n";
    } else {
      echo "<article><p>No web ports found.</p></article>";
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
