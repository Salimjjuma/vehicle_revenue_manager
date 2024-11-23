<?php

require_once (__DIR__ . "/fxn/redirects.php");

if (!isset($_SESSION['username']) || (time() - $_SESSION['last_login_timestamp']) > 1500 || !isset($_SESSION['user_id'])) {
  redirectToHomePage();
} else {

  $_SESSION['last_login_timestamp'] = time();

  include "./_partials/css_files.html";

  define("PATH_TO_CONTENT", __DIR__ . "/dashboard/resources/mainContent.html")

    ?>

  <body>

    <?php include "./_partials/header.html"; ?>

    <div class="container-fluid">
      <div class="row">

        <?php include "./_partials/sidebar.html"; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

          <?php include PATH_TO_CONTENT ?>

        </main>
      </div>
    </div>

    <script src="./dist/js/main.min.js"></script>
    <script src="./dist/js/dashboard/dashboard.min.js"></script>


  </body>

  </html>

<?php } ?>