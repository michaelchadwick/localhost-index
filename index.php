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
      font-size: 3em;
      line-height: 1.2em;
      margin: 0;
      padding: 10px;
      text-transform: uppercase;
    }
    dl {
      margin: 0;
      padding: 0;
      width: 100%;
    }
    dt {

    }
    a {
      background: #296a86;
      border-bottom: 3px solid #111;
      color: #f2f2f2;
      display: block;
      font-size: 2em;
      padding: 10px;
      text-decoration: none;
      text-transform: uppercase;
    }
    a:hover {
      background: #b3d3e0;
      color: #242424;
    }
  </style>
</head>
<body>

  <h1>Localhost Projects</h1>

  <?php
    $dir = opendir(".");
    $files = array();

    function passes_blacklist($name) {
      $passes = true;
      $blacklist = array(
        ".",
        "..",
        "favicon.ico",
        "index.php",
        ".DS_Store",
        "aquarium-website"
      );

      foreach ($blacklist as $bl) {
        if ($name == $bl)
          $passes = false;
      }

      return $passes;
    }

    while (($file=readdir($dir)) !== false) {
      if (passes_blacklist($file))
      {
        array_push($files, $file);
      }
    }
    closedir($dir);
    sort($files);

    if (sizeof($files) > 0) {
      echo "<dl>";

      foreach ($files as $file) {
        echo "<dt><a href='http://$file'>$file</a></dt>";
      }

      echo "</dt>";
    } else {
      echo "No projects found.";
    }
  ?>

</body>
</html>
