<?php

require __DIR__ . "/../../fxn/redirects.php";

if (!isset($_SESSION['username']) || (time() - $_SESSION['last_login_timestamp']) > 1500 || !isset($_SESSION['user_id'])) {
    redirectToHomePage();
} else {

    $_SESSION['last_login_timestamp'] = time();

    define("CSS_FILE", __DIR__ . "/../../_partials/css_files.html");
    define("PATH_TO_HEADER", __DIR__ . "/../../_partials/header.html");
    define("PATH_TO_SIDEBAR", __DIR__ . "/../../_partials/sidebar.html");

    define("PATH_TO_DATATABLE", __DIR__ . "/../resources/vehicle_driver_table.html");

    include CSS_FILE;
    ?>

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

                    <div class="d-flex justify-content-between align-items-center pt-2 pb-2">
                        <h6 class="h6 text-secondary">Manage Driver and Conductors</h6>
                    </div>

                    <div class="d-flex justify-content-between align-items-center ">
                        <h1 class="h2" id="page_header"></h1>
                        <h1 class="h4" id="status_of_driver"></h1>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h6 text-secondary" id="drivers_license_no"></h1>
                        <h1 class="h6 text-secondary" id="date_of_creation"></h1>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h1 class="h6 text-secondary" id="phone_number"></h1>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" id="edit_vehicle_btn"
                            data-bs-target="#edit_vehicle_modal">Edit Driver
                        </button>
                    </div>

                    <!-- Alert  -->
                    <span id="alert"></span>
                    <!-- End of alert. -->

                    <div class="card mb-3">
                        <!-- Vehicle Datatables. -->
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>Vehicle Assignment Table.</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php include PATH_TO_DATATABLE; ?>
                            </div>
                        </div>
                        <!-- End of Vehicle Datatables. -->
                    </div>
                </main>
                <!-- End of Main Section. -->
            </div>
        </div>

        <script src="/dist/js/main.min.js"></script>
        <script src="/dist/js/vehicle_drivers/driver_details.min.js"></script>
        <!-- END OF THE PAGE GOES HERE -->

    </body>
<?php } ?>