<?php

require_once (__DIR__ . "/../fxn/redirects.php");

if (!isset($_SESSION['username']) || (time() - $_SESSION['last_login_timestamp']) > 1500 || !isset($_SESSION['user_id'])) {
    redirectToHomePage();
} else {

    $_SESSION['last_login_timestamp'] = time();

    define("CSS_FILE", __DIR__ . "/css_files.html");
    define("PATH_TO_HEADER", __DIR__ . "/header.html");
    define("PATH_TO_SIDEBAR", __DIR__ . "/sidebar.html");
    ?>

    <?php include CSS_FILE; ?>

    <body>

        <?php include PATH_TO_HEADER; ?>

        <div class="container-fluid">
            <div class="row">

                <?php include PATH_TO_SIDEBAR; ?>

                <!-- Start of Main Section -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                    <!-- Page Heading -->
                    <div class="d-flex align-items-center justify-content-between pt-3 pb-1">
                        <a class="btn text-primary mb-2" onclick="goBack()">
                            <i class="fas fa-arrow-left"></i> Back
                            to previous page</a>
                    </div>

                    <span id="header_content"></span>

                    <div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-3 border-bottom">
                        <h1 class="h2" id="page_header"></h1>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#add_vehicle_modal" id="add_vehicle_btn">
                        </button>
                    </div>

                    <span id="sub_content"></span>

                    <!-- Alert  -->
                    <span id="alert"></span>
                    <!-- End of alert. -->

                    <!-- <div class="card shadow mb-3"> -->

                    <!-- <div class="card-body"> -->
                    <!-- Vehicle Datatables. -->
                    <div class="table-responsive">
                        <?php include PATH_TO_DATATABLES; ?>
                    </div>
                    <!-- </div> -->
                    <!-- End of Vehicle Datatables. -->
                    <!-- </div> -->
                </main>
                <!-- End of Main Section. -->

                <?php include PATH_TO_ADD_MODAL; ?>
                <span id="additional_content"></span>
            </div>
        </div>

        <script src="/dist/js/main.min.js"></script>

        <!-- END OF THE PAGE GOES HERE -->
    <?php } ?>