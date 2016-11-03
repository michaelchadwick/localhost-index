<html>
<head>
  <title>Localhost Projects</title>
  <style type="text/css">
    body {
      font-family: Consolas, monospace;
      font-size: 1em;
      margin: 0;
      padding: 0;
    }
    h1 {
      background: #333;
      color: #eee;
      display: block;
      font-size: 2em;
      line-height: 1em;
      margin: 0;
      padding: 8px 4px 2px 8px;
      text-transform: uppercase;
    }
    #mode_switcher {
      background: #444;
      padding: 5px 10px;
    }
    #mode_switcher a {
      color: #eee;
    }
    dl {
      margin: 0;
      padding: 0;
      width: 100%;
    }
    dt a {
      background: #296a86;
      border-bottom: 3px solid #111;
      color: #f2f2f2;
      display: block;
      font-size: 2em;
      padding: 12px 10px 10px;
      text-decoration: none;
      text-transform: uppercase;
    }
    dt a:hover {
      background: #b3d3e0;
      color: #242424;
    }
    @media (min-width: 768px) {
      h1 {
        font-size: 3em;
        line-height: 1.2em;
        padding: 12px 10px 10px;
      }
    }
  </style>
</head>
<body>

  <h1>Localhost Projects</h1>
  <div id="mode_switcher"><a href="index_menu.php">iframe version</a></div>

  <?php
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
      echo "<dl>";

      foreach ($files as $file) {
        echo "<dt><a href='http://$file'>$file</a></dt>";
      }

      echo "</dt>";
    } else {
      echo "No projects found.";
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
  ?>

</body>
</html>
