<?php header('X-Frame-Options: GOFORIT'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Localhost Index</title>
  <link rel="stylesheet" type="text/css" href="index.css">
  <script type="text/javascript" src="index.js"></script>
</head>
<body>
  <?php
  $useIframe = (isset($_GET['iframe']) && $_GET['iframe'] == 1) ? true : false;
  $usePortCheck = (isset($_GET['portcheck']) && $_GET['portcheck'] == 0) ? false : true;
  $usePortFilter = (isset($_GET['portfilter']) && $_GET['portfilter'] == 0) ? false : true;
  ?>
  
  <header <?php if ($useIframe) echo 'style="position: absolute"'; ?>>
    <h1><a href="http://localhost">Localhost Index</a></h1>
  </header>

  <?php
    if ($useIframe) {
  ?>
  <aside id="sidebar">
    <div id="dragbar"></div>
  <?php
    }

    include("funcs.php");
    make_dir_links($useIframe);
    make_port_links($useIframe, $usePortCheck, $usePortFilter);

    if ($useIframe) {
  ?>
  </aside>

  <div id="main">
    <iframe id="site_contents" frameborder="0" src=""></iframe>
  </div>
  <?php
    }
  ?>

  <footer>
    <?php if ($useIframe) { ?>
    <span>[<a href="?iframe=0">standard version</a>]</span>
    <?php } else { ?>
    <span>[<a href="?iframe=1">iframe version</a>]</span>
    <?php } ?>
  </footer>

</body>
</html>
