<?php

require "../../fxn/redirects.php";

if (!isset($_SESSION['username']) || (time() - $_SESSION['last_login_timestamp']) > 1500 || !isset($_SESSION['user_id'])) {
    redirectToHomePage();
} else {

    $_SESSION['last_login_timestamp'] = time();

    define("CSS_FILE", __DIR__ . "/../../_partials/css_files.html");
    define("PATH_TO_HEADER", __DIR__ . "/../../_partials/header.html");
    define("PATH_TO_SIDEBAR", __DIR__ . "/../../_partials/sidebar.html");
    define("PATH_TO_DATATABLE", __DIR__ . "/./resources/overview_datatables.html");
    define("PATH_TO_ADD_MODAL", __DIR__ . "/./resources/edit_transcation_modal.html");

    include CSS_FILE;

    ?>


    <body>

        <?php include PATH_TO_HEADER; ?>

        <div class="container-fluid">
            <div class="row">

                <?php include PATH_TO_SIDEBAR; ?>

                <!-- Start of Main Section -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-2">

                    <!-- Page Heading -->
                    <div class="d-flex align-items-center justify-content-between pt-3 pb-1">
                        <a class="btn text-primary mb-2" onclick="goBack()">
                            <i class="fas fa-arrow-left"></i> Back to previous page</a>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 pb-2">
                        <h6 class="h6 text-secondary">Daily Revenue Expense Overview.</h6>

                        <button class="btn btn-sm btn-outline-primary" id="print_daily_revenue_report">
                            <span><i class="fas fa-print"></i></span>
                            Print Report
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center ">
                        <h1 name="date" class="h2"></h1>
                        <h1 class="h2" id="page_header"></h1>
                    </div>

                    <div class="d-flex justify-content-between align-items-center  pt-2 mb-1 border-bottom">
                        <h1 class="h6 text-secondary" id="category_of_vehicle"></h1>
                        <h1 class="h6 text-secondary" id="company_name"></h1>
                    </div>
                    <!-- End of Page Heading -->

                    <div class="alert alert-info" role="alert">

                        <p> <strong>This page contains the details of the daily revenue and expense of the vehicle.</strong>
                        </p>
                        <hr>
                        <p class="mb-0">Click Print Report to generate report.</p>
                    </div>

                    <!-- Floating Panel -->
                    <div class="row" id=floating_panels></div>
                    <!-- End of Floating Panel -->

                    <hr class="my-3">

                    <div class="alert alert-primary d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                            <use xlink:href="#info-fill" />
                        </svg>
                        <div>
                            This is a graphical representation of the revenue and expense for the
                            <span name="date"></span>.
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-xs-3">
                            <!-- <div class="card shadow">
                                <div class="card-body"> -->
                                    <canvas id="myPieChart" width="" height="100"></canvas>
                                <!-- </div>
                            </div> -->
                        </div>
                        <div class="col-md-9 col-xs-9 float-right">
                            <!-- <div class="card shadow">
                                <div class="card-body"> -->
                                    <canvas id="myBarChart" width="200" height="56"></canvas>
                                <!-- </div>
                            </div> -->

                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="alert alert-primary d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                            <use xlink:href="#info-fill" />
                        </svg>
                        <div>
                            The table below shows all the revenue generated and expenses incurred for the
                            <span name="date"></span>
                        </div>
                    </div>

                    <div class="card shadow mb-2">
                        <div class="card-body">
                            <?php include PATH_TO_DATATABLE; ?>
                        </div>
                    </div>



                </main>
                <!-- End of Main Section. -->

                <?php include PATH_TO_ADD_MODAL; ?>
            </div>
        </div>

        <script src="/dist/js/main.min.js"></script>
        <script src="/dist/js/vehicles/overview_vehicle.min.js"></script>
        <!-- END OF THE PAGE GOES HERE -->

    <?php } ?>