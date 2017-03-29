<?php header('X-Frame-Options: GOFORIT'); ?>
<html>
<head>
  <title>Localhost Index</title>
  <link rel="stylesheet" type="text/css" href="index.css">
  <script type="text/javascript">
    function load_url(url, id) {
      var dt = document.getElementById(id);
      var links = document.querySelectorAll("dt a");

      for (var i = 0; i<links.length; i++) {
        links[i].classList.remove("selected");
      }

      dt.className += " selected";
      document.getElementById("site_contents").src=url;
    }
  </script>
</head>
<body>
  <header>
    <h1><a href="http://localhost">Localhost Index</a></h1>
  </header>

  <div id="content">
    <?php
      if (isset($_GET['iframe']) && $_GET['iframe'] == 1) {
        $useIframe = true;
        ?>
      <aside id="menu">
        <?php
      } else {
        $useIframe = false;
      }

      include("funcs.php");
      make_dir_links($useIframe);
      make_port_links($useIframe, true, true);

      if ($useIframe) {
        ?>
    </aside>
    <section>
      <iframe id="site_contents" frameborder="0" src=""></iframe>
    </section>
    <?php
      }
    ?>
  </div><!-- end content -->

  <footer>
    <?php if ($useIframe) { ?>
    <span>[<a href="?iframe=0">standard version</a>]</span>
    <?php } else { ?>
    <span>[<a href="?iframe=1">iframe version</a>]</span>
    <?php } ?>
  </footer>

</body>
</html>
