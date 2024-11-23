<?php

require __DIR__ . "/../fxn/redirects.php";

if (!isset($_SESSION['username']) || (time() - $_SESSION['last_login_timestamp']) > 1500 || !isset($_SESSION['user_id'])) {
    redirectToHomePage();
} else {

    define("CSS_FILE", __DIR__ . "/../_partials/css_files.html");
    define("PATH_TO_HEADER", __DIR__ . "/../_partials/header.html");
    define("PATH_TO_SIDEBAR", __DIR__ . "/../_partials/sidebar.html");


    define("PATH_TO_DRIVER_DATATABLES", __DIR__ . "/resources/drivers_table.html");
    define("PATH_TO_CONDUCTORS_DATATABLES", __DIR__ . "/resources/conductors_table.html");

    define("PATH_TO_ADD_DRIVER_MODAL", __DIR__ . "/resources/add_driver_modal.html");
    define("PATH_TO_ADD_CONDUCTOR_MODAL", __DIR__ . "/resources/add_conductor_modal.html");

    include CSS_FILE;

    ?>

    <body>
        <?php include PATH_TO_HEADER; ?>

        <div class="container-fluid">
            <div class="row">
                <?php include PATH_TO_SIDEBAR; ?>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <!-- Page Heading -->
                    <div class="d-flex align-items-center justify-content-between pt-3 pb-1">
                        <a class="btn text-primary mb-2" onclick="goBack()">
                            <i class="fas fa-arrow-left"></i> Back to previous page</a>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 pb-2">
                        <h6 class="h6 text-secondary"> Manage Drivers and Conductors</h6>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 pb-2">
                        <h1 class="h2" id="page__header"></h1>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal"
                                data-bs-target="#add_conductor_modal" id="add_conductor_btn">
                            </button>

                            <button type="button" class="btn btn-primary " data-bs-toggle="modal"
                                data-bs-target="#add_vehicle_modal" id="add_vehicle_btn">
                            </button>
                        </div>
                    </div>

                    <span id="alert"></span>

                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"
                                aria-selected="true">Manage
                                Drivers</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane"
                                Z aria-selected="false">Manage Conductors</button>
                        </li>
                    </ul>

                    <div class="tab-content mb-3" id="myTabContent">
                        <!-- Drivers Tab pane -->
                        <div class="tab-pane fade show active " id="home-tab-pane" role="tabpanel"
                            aria-labelledby="home-tab" tabindex="0">

                            <?php include PATH_TO_DRIVER_DATATABLES; ?>

                        </div>

                        <!-- Conductors Tab Pane -->
                        <div class="tab-pane fade " id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                            tabindex="0">

                            <?php include PATH_TO_CONDUCTORS_DATATABLES ?>

                        </div>
                    </div>

                </main>
            </div>
        </div>

        <?php include PATH_TO_ADD_DRIVER_MODAL; ?>
        <?php include PATH_TO_ADD_CONDUCTOR_MODAL; ?>

    </body>

    <script src="/dist/js/main.min.js"></script>
    <script src="/dist/js/vehicle_drivers/vehicle_drivers.min.js"></script>

    </body>

    </html>
<?php } ?>