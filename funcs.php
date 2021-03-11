<?php
/**
 * Make Directory Links
 *
 * Check current directory, amassing a list of links to make from subdirectories.
 * Then use that list to make HTML links and write to the browser.
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
  echo "\t<h2>~/Sites</h2>";

  if (sizeof($files) > 0) {
    echo "\n\t\t<dl class='sites-list'>\n";
    $dir_index = 0;

    foreach ($files as $file) {
      $id = "dir_" . $dir_index++;
      $link = "/" . $file;

      $html  = "\t\t\t\t";
      $html .= "<dt class='port'>";

      if ($useIframe) {
        $html .= "<a data-url='$link' alt='$link' title='$link' id='$id' data-click='false' href='#'>$file</a>";
      } else {
        $html .= "<a data-url='$link' alt='$link' title='$link' id='$id' href='$link'>$file</a>";
      }

      $html .= "</dt>\n";

      echo $html;
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
 * @param int     $maxPortNumber What's the highest port number to check?
 */
function make_port_links($useIframe = false, $checkPorts = false, $usePortFilter = false, $maxPortNumber = 99999) {
  if ($checkPorts) {
    $http_services = 'httpd\|vpnkit\|java\|nc\|node\|ng\|php\|ruby\|hugo\|zola\|docker\|com.docker\|com.docke';
    $filter = $usePortFilter ? " | grep '" . $http_services . "'" : "";
    $lsof_cmd = "lsof -i -n -P" . $filter . " | grep LISTEN | egrep -o -E ':[0-9]{2,5}' | cut -f2- -d: | sort -n | uniq";
    $ports = explode("\n", shell_exec($lsof_cmd));

    // Make <dt>s if there are any ports
    echo "\t\t<h2>Ports</h2>";

    if ($ports) {
      $PORTS_LABELS_FILENAME = 'port_labels.php';

      if (stream_resolve_include_path($PORTS_LABELS_FILENAME)) {
        include $PORTS_LABELS_FILENAME; // custom port => app name mappings
      }

      echo "\n\t\t\t<dl class='ports-list'>\n";

      $port_index = 0;

      foreach ($ports as $port) {
        if ($port != "" && $port != $_SERVER['SERVER_PORT']) {
          $id = "port_" . $port_index++;
          $link = "http://localhost:$port";
          $logo = '';
          $project = '';
          $subproject = '';
          $tech = '';
          $ignore = FALSE;
          $name = '';
          $html = '';

          if (defined('PORTS_LABELS')) {
            $labels = constant('PORTS_LABELS');

            if (array_key_exists($port, $labels)) {
              $imgdir = opendir("./assets/img/_logos");

              $project = isset($labels[$port]['project']) ? $labels[$port]['project'] : '???';
              $subproject = isset($labels[$port]['subproject']) ? $labels[$port]['subproject'] : '???';
              $tech = isset($labels[$port]['tech']) ? $labels[$port]['tech'] : '???';
              $ignore = isset($labels[$port]['ignore']) ? $labels[$port]['ignore'] : FALSE;

              while (($img = readdir($imgdir)) !== FALSE) {
                $imgName = explode('.', $img);

                if ($imgName[0] == $tech) {
                  $logo = $img;

                  break;
                }
              }

              $name = "<span class='project'>$project</span> | <span class='subproject'>$subproject</span>";
            }
          }

          if (!$ignore && (intval($port) < $maxPortNumber)) {
            $html  = "\t\t\t\t";
            $html .= "<dt class='port'>";

            if ($useIframe) { // iframe
              $html .= "<a data-url='$link' alt='$link' title='$link' id='$id' data-click='false' href='#'>Port: <span class='port'>$port</span>";

              if (!empty($project)) {
                $html .= "<br />Proj: <span class='project'>$project</span>";
              }

              if (!empty($tech)) {
                $html .= "<br />subproject: <span class='subproject'>$subproject</span>";
              }

              $html .= '</a>';
            } else { // no iframe
              if ($logo !== '') {
                $html .= "<img class='tech-logo' src='./assets/img/_logos/$logo' width='20' height='20' />";
              }

              $html .= "<a data-url='$link' alt='$link' title='$link' id='$id' href='$link'>:$port";

              if (!empty($name)) {
                $html .= " ($name)";
              }

              $html .= '</a>';
            }

            $html .= "</dt>\n";

            echo $html;
          }
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
 * Checks a subdirectory to make sure it passes the ignore list, if there is one
 *
 * @param string $name Domain name to check
 * @param array $lhignore Array of domains to check against
 *
 * @return boolean $passes Whether the domain name passes or not.
 */
function passes_lhignore($name, $lhignore) {
  foreach ($lhignore as $bl) {
    if ($name == $bl) {
      return false;
    }
  }

  return true;
}
?>
