<html>
<head>
  <title>Localhost Index</title>
  <link rel="stylesheet" type="text/css" href="index.css">
  <script type="text/javascript">
    function load_url(url) {
      document.getElementById("site_contents").src=url;
    }
  </script>
</head>
<body>
  <div id="wrap">
    <h1><a href="http://localhost">Localhost Index</a></h1>

    <div id="main">
      <?php
        if (isset($_GET['iframe'])) {
          $useIframe = true;
          ?>
        <aside id="menu">
          <?php
        } else {
          $useIframe = false;
        }

        include("funcs.php");
        make_dir_links($useIframe);
        make_port_links($useIframe, true);

        if ($useIframe) {
          ?>
      </aside>
      <section>
        <iframe id="site_contents" frameborder="0" src=""></iframe>
      </section>
      <?php
        }
      ?>
    </div><!-- end main -->
  </div><!-- end wrap -->

  <footer id="mode_switcher">
    <?php if ($useIframe) { ?>
    <a href="?iframe=0">standard version</a>
    <?php } else { ?>
    <a href="?iframe=1">iframe version</a>
    <?php } ?>
  </footer>

</body>
</html>
