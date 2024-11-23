<?php

require_once __DIR__ . "/../../fxn/redirects.php";

if (!isset($_SESSION['username']) || (time() - $_SESSION['last_login_timestamp']) > 1500 || !isset($_SESSION['user_id'])) {
    redirectToHomePage();
} else {

    $_SESSION['last_login_timestamp'] = time();

    define("CSS_FILE", __DIR__ . "/../../_partials/css_files.html");
    define("PATH_TO_HEADER", __DIR__ . "/../../_partials/header.html");
    define("PATH_TO_SIDEBAR", __DIR__ . "/../../_partials/sidebar.html");

    define("PATH_TO_DATATABLE", __DIR__ . "/../resources/view/category_view_datatables.html");

    define("PATH_TO_EDIT_MODAL", __DIR__ . "/../resources/view/edit_category_modal.html");
    define("PATH_TO_DELETE_A_VEHICLE_CATEGORY", __DIR__ . "/../resources/view/delete_category_modal.html");

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
                        <h6 class="h6 text-secondary">Manage Vehicle Category</h6>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h1 class="h2" id="page_header"></h1>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#edit_category_modal">Edit Category</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="to_delete_a_category()">Delete
                                Category</button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h1 class="h4" id="status_of_vehicle"></h1>

                        <h1 class="h6">Date of Creation: <span class="h6" id="date_of_creation"></span></h1>
                    </div>

                    <span id="alert"></span>

                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"
                                aria-selected="true">All Vehicles in Category</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                            tabindex="0">

                            <!-- <canvas id="revenue_against_expense_chart" width="60" height="35"></canvas>

                            <hr class="my-3"> -->

                            <div class="alert alert-primary d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                                    <use xlink:href="#info-fill" />
                                </svg>
                                <div>
                                    The table below shows all the vehicle in the above category. Click on a category to view
                                    more details about it.
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <div>All Vehicles</div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <?php include PATH_TO_DATATABLE; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </main>
                <!-- End of Main Section. -->

                <?php include PATH_TO_EDIT_MODAL; ?>
                <?php include PATH_TO_DELETE_A_VEHICLE_CATEGORY; ?>
            </div>
        </div>

        <script src="/dist/js/main.min.js"></script>
        <script src="/dist/js/categories/category_view.js"></script>
        <!-- END OF THE PAGE GOES HERE -->

    </body>
<?php } ?>