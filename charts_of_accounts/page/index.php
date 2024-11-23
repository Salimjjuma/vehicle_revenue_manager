<?php

require __DIR__ . "/../../fxn/redirects.php";


if (!isset($_SESSION['username']) || (time() - $_SESSION['last_login_timestamp']) > 1500 || !isset($_SESSION['user_id'])) {
    redirectToHomePage();
} else {

    $_SESSION['last_login_timestamp'] = time();

    define("CSS_FILE", __DIR__ . "/../../_partials/css_files.html");
    define("PATH_TO_HEADER", __DIR__ . "/../../_partials/header.html");
    define("PATH_TO_SIDEBAR", __DIR__ . "/../../_partials/sidebar.html");
    define("PATH_TO_DATATABLE_AND_CHARTJS", __DIR__ . "/resources/charts_view_datatables.html");

    include CSS_FILE;


    ?>

    <!-- 



    // list of all the vehicles in this account. use of collapsing card.

    // table to have all the transcation for the account. -->

    <body>

        <?php include PATH_TO_HEADER; ?>

        <div class="container-fluid">
            <div class="row">

                <?php include PATH_TO_SIDEBAR; ?>

                <!-- Start of Main Section -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                    <!-- header to show the name, acc type of the account, status, desc, -->
                    <div id="header-section">
                        <div class="d-flex align-items-center justify-content-between pt-3 pb-1">
                            <a class="btn text-primary mb-2" onclick="goBack()">
                                <i class="fas fa-arrow-left"></i> Back
                                to previous page</a>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 pb-2">
                            <h6 class="h6 text-secondary"> Manage Chart of Account</h6>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="edit_vehicle_btn"
                                data-bs-target="#edit_vehicle_modal">Edit Account
                            </button>
                        </div>

                        <div class="d-flex justify-content-between align-items-center ">
                            <h1 class="h2" id="page_header"></h1>
                            <h1 class="h4" id="status_of_chart_of_account"></h1>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="h6 text-secondary" id="type_of_chart_of_account"></h1>
                            <h1 class="h6 text-secondary" id="date_of_creation"></h1>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h1 class="h6 text-secondary" id="acc_desc"></h1>
                        </div>

                    </div>

                    <!-- Alert  -->
                    <span id="alert"></span>
                    <!-- End of alert. -->

                    <hr class="my-3">

                    <?php include PATH_TO_DATATABLE_AND_CHARTJS; ?>


                </main>
                <!-- End of Main Section. -->
            </div>
        </div>

        <script src="/dist/js/main.min.js"></script>
        <script src="/dist/js/charts_of_accounts/view_charts_of_accounts.min.js"></script>
        <!-- END OF THE PAGE GOES HERE -->

    <?php } ?>