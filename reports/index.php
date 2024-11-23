<?php

require "../fxn/redirects.php";

if (!isset($_SESSION['username']) || (time() - $_SESSION['last_login_timestamp']) > 1500 || !isset($_SESSION['user_id'])) {
    redirectToHomePage();
} else {

    $_SESSION['last_login_timestamp'] = time();

    define("CSS_FILE", __DIR__ . "/../_partials/css_files.html");
    define("PATH_TO_HEADER", __DIR__ . "/../_partials/header.html");
    define("PATH_TO_SIDEBAR", __DIR__ . "/../_partials/sidebar.html");
    // define("PATH_TO_DATATABLE", __DIR__."/./resources/view_vehicle_datatables.html");
    // define("PATH_TO_ADD_MODAL", __DIR__."/./resources/add_transaction_modal.html");

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
                        <h4 class="h4">Reports Management</h4>
                    </div>


                    <hr class="my-3">


                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 border-end p-1">
                            <div class="nav flex-column nav-pills me-5" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <button class="nav-link active" id="v-pill-individual-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pill-individual" type="button" role="tab"
                                    aria-controls="v-pills-home" aria-selected="true">Individual Vehicle Report</button>

                                <button class="nav-link" id="v-pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                                    aria-selected="true">Vehicles Report</button>

                                <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-profile" type="button" role="tab"
                                    aria-controls="v-pills-profile" aria-selected="false">Custodians / Owners
                                    Report</button>


                                <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-messages" type="button" role="tab"
                                    aria-controls="v-pills-messages" aria-selected="false">Ownership Report</button>


                                <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-settings" type="button" role="tab"
                                    aria-controls="v-pills-settings" aria-selected="false">Accounts and Financial</button>
                            </div>
                        </div>
                        <div class="p-3">

                            <div class="tab-content" id="v-pills-tabContent">

                                <div class="tab-pane fade show active" id="v-pill-individual" role="tabpanel"
                                    aria-labelledby="v-pill-individual-tab">
                                    <div class="list-group">

                                        <div class="row mb-3">
                                            <div class="col-lg-6 col-md-6">
                                                <!-- Date Filters -->
                                                <label for="startDate">Start Date:</label>
                                                <input class="form-control" type="date" id="startDate">
                                            </div>

                                            <div class="col-lg-6 col-md-6">
                                                <label for="endDate">End Date:</label>
                                                <input class="form-control" type="date" id="endDate">
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="form-group col-lg-10">
                                                <input type="text" id="searchInput" class="form-control"
                                                    placeholder="Search any vehicle here..">
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <button id="searchButton" class="btn btn-md btn-primary">Search</button>
                                            </div>
                                        </div>


                                        <!-- Results Container -->
                                        <div id="results"></div>


                                    </div>
                                </div>

                                <div class="tab-pane fade show" id="v-pills-home" role="tabpanel"
                                    aria-labelledby="v-pills-home-tab">
                                    <div class="list-group">

                                        <a href="/reports/vehicles/index.php" target="_blank"
                                            class="list-group-item list-group-item-action list-group-item-primary ">
                                            Summary Vehicle Report</a>

                                        <a href="#" class="list-group-item list-group-item-action list-group-item-primary">
                                            Detailed Vehicle Report</a>

                                    </div>

                                </div>
                                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                    aria-labelledby="v-pills-profile-tab">...</div>
                                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                    aria-labelledby="v-pills-messages-tab">...</div>
                                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                                    aria-labelledby="v-pills-settings-tab">...</div>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- End of Main Section. -->

            </div>
        </div>

        <script src="/dist/js/main.min.js"></script>
        <script>
            $(document).ready(function () {
                // Handle search button click
                $("#searchButton").click(function () {
                    performSearchAndFilter();
                });

                // Handle Enter key press in the search input
                $("#searchInput").keydown(function (event) {
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        performSearchAndFilter();
                    }
                });
            });

            function performSearchAndFilter() {
                var query = $("#searchInput").val();
                var startDate = $("#startDate").val();
                var endDate = $("#endDate").val();

                // Make an AJAX request to your server with query and date filters
                $.ajax({
                    type: "GET",
                    url: "",
                    data: {
                        query: query,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function (response) {
                        // Display the results in the "results" div
                        $("#results").html(response);
                    }
                });
            }
        </script>
        <!-- END OF THE PAGE GOES HERE -->

    <?php } ?>