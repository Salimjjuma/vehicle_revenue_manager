<?php

require "../../fxn/redirects.php";


if (!isset($_SESSION['username']) || (time() - $_SESSION['last_login_timestamp']) > 1500 || !isset($_SESSION['user_id'])) {
    redirectToHomePage();
} else {

    $_SESSION['last_login_timestamp'] = time();

    define("CSS_FILE", __DIR__ . "/../../_partials/css_files.html");
    define("PATH_TO_HEADER", __DIR__ . "/../../_partials/header.html");
    define("PATH_TO_SIDEBAR", __DIR__ . "/../../_partials/sidebar.html");
    define("PATH_TO_DATATABLE", __DIR__ . "/./resources/view_vehicle_datatables.html");
    define("PATH_TO_ADD_MODAL", __DIR__ . "/./resources/add_transaction_modal.html");
    define("PATH_TO_PRINT_DATE_FILTER", __DIR__ . "/./resources/date_filter_to_print.html");
    define("PATH_TO_EDIT_MODAL", __DIR__ . "/./resources/edit_vehicle_modal.html");
    define("PATH_TO_LEASE_VEHICLE_TO_DRIVER_MODAL", __DIR__ . "/./resources/lease_Vehicle_Modal.html");

    define("PATH_TO_DRIVER_CONDUCTOR_TABLE", __DIR__ . "/./resources/driver_and_conductor_table.html");
    define("PATH_TO_STOP_LEASE_MODAL", __DIR__ . "/./resources/stop_lease_modal.html");

    // DELETE PATH
    define("PATH_TO_DELETE_A_VEHICLE", __DIR__ . "/./resources/deleteVehicleModal.html");

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
                        <h6 class="h6 text-secondary"> Manage Vehicle</h6>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h1 class="h2" id="page_header"></h1>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                id="edit_vehicle_btn" data-bs-target="#edit_vehicle_modal">Edit Vehicle
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteVehicle()">
                                Delete Vehicle
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h6" id="category_of_vehicle"></h1>
                        <h1 class="h6" id="registration_number"></h1>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h1 class="h6" id="date_of_creation"></h1>
                        <h1 class="h6" id="brand_of_vehicle"></h1>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2">
                        <h1 class="h4" id="status_of_vehicle"></h1>
                        <h1 class="h6" id="vehicle_owner_"></h1>
                    </div>

                    <span id="alert"></span>

                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"
                                aria-selected="true">Revenue and Expenses</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane"
                                aria-selected="false">Drivers and Conductors</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                            tabindex="0">

                            <div class="d-flex justify-content-between align-items-center">
                                <h1 class="h6"></h1>
                                <button class="btn btn-sm btn-warning" id="filterRevenueAndExpenseBtn">
                                    <svg class="bi">
                                        <use xlink:href="#filter" />
                                    </svg>
                                    Filter Results
                                </button>
                            </div>

                            <hr>

                            <canvas id="revenue_against_expense_chart" width="60" height="35"></canvas>

                            <hr class="my-3">

                            <div class="alert alert-primary d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                                    <use xlink:href="#info-fill" />
                                </svg>
                                <div>
                                    Table representation of the revenue and expense of all the date for the vehicle.
                                </div>
                            </div>

                            <div class="card mb-3">
                                <!-- Vehicle Datatables. -->
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>Total Revenue and Expense.</div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#add_vehicle_modal" id="add_vehicle_btn">
                                            Add Daily Transcation
                                        </button>

                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#date_filter_to_print">
                                            Print Report
                                        </button>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <?php include PATH_TO_DATATABLE; ?>
                                    </div>
                                </div>
                                <!-- End of Vehicle Datatables. -->
                            </div>
                        </div>

                        <div class="tab-pane fade mb-2" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                            tabindex="0">

                            <div class="alert alert-primary d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                                    <use xlink:href="#info-fill" />
                                </svg>
                                <div>
                                    Table representing the driver and conductors assigned to this vehicle.
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="card mb-3">
                                <!-- Driver Vehicle Assignment Datatables. -->
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>Vehicle Driver Assignment</div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#add_vehicle_driver_modal">
                                            Assign vehicle to driver
                                        </button>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <?php include PATH_TO_DRIVER_CONDUCTOR_TABLE; ?>
                                    </div>
                                </div>
                                <!-- End of Driver Vehicle Assignment Datatables. -->
                            </div>

                        </div>
                    </div>

                </main>
                <!-- End of Main Section. -->

                <?php include PATH_TO_ADD_MODAL; ?>
                <?php include PATH_TO_PRINT_DATE_FILTER; ?>
                <?php include PATH_TO_EDIT_MODAL; ?>
                <?php include PATH_TO_LEASE_VEHICLE_TO_DRIVER_MODAL; ?>
                <?php include PATH_TO_STOP_LEASE_MODAL; ?>
                <?php include PATH_TO_DELETE_A_VEHICLE; ?>
            </div>
        </div>

        <script src="/dist/js/main.min.js"></script>
        <script src="/dist/js/vehicles/view_vehicle.min.js"></script>
        <script src="/dist/js/vehicles/viewDriverAndConductorForVehicle.min.js"></script>
        <!-- END OF THE PAGE GOES HERE -->

    </body>
<?php } ?>