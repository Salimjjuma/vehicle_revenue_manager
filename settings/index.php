<?php require_once(__DIR__ . "/../fxn/redirects.php");

if (!isset($_SESSION['username']) || (time() - $_SESSION['last_login_timestamp']) > 1500 || !isset($_SESSION['user_id'])) {
    redirectToHomePage();
} else {

    $_SESSION['last_login_timestamp'] = time();

    define("CSS_FILE", __DIR__ . "/../_partials/css_files.html");
    define("PATH_TO_HEADER", __DIR__ . "/../_partials/header.html");
    define("PATH_TO_SIDEBAR", __DIR__ . "/../_partials/sidebar.html");
    ?>

    <?php include CSS_FILE; ?>

    <body>

        <?php include PATH_TO_HEADER; ?>

        <div class="container-fluid">
            <div class="row">
                <?php include PATH_TO_SIDEBAR; ?>

                <!-- Main Content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2>Settings</h2>
                    </div>

                    <div class="accordion" id="settingsAccordion">

                        <!-- Profile Settings -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingProfile">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseProfile" aria-expanded="true" aria-controls="collapseProfile">
                                    Profile Settings
                                </button>
                            </h2>
                            <div id="collapseProfile" class="accordion-collapse collapse" aria-labelledby="headingProfile"
                                data-bs-parent="#settingsAccordion">
                                <div class="accordion-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" placeholder="Enter your name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email"
                                                placeholder="Enter your email">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Backup -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingBackup">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseBackup" aria-expanded="false" aria-controls="collapseBackup">
                                    Backup
                                </button>
                            </h2>
                            <div id="collapseBackup" class="accordion-collapse collapse" aria-labelledby="headingBackup"
                                data-bs-parent="#settingsAccordion">
                                <div class="accordion-body">
                                    <form id="backupForm" method="POST" action="queries/create_local_backup.php">
                                        <button type="submit" class="btn btn-primary">Perform Backup</button>
                                    </form>
                                    <div id="backupMessage" class="mt-3"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAccount">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseAccount" aria-expanded="false" aria-controls="collapseAccount">
                                    Account Settings
                                </button>
                            </h2>
                            <div id="collapseAccount" class="accordion-collapse collapse" aria-labelledby="headingAccount"
                                data-bs-parent="#settingsAccordion">
                                <div class="accordion-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="password"
                                                placeholder="Enter a new password">
                                        </div>
                                        <div class="mb-3">
                                            <label for="confirm-password" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirm-password"
                                                placeholder="Confirm your new password">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </main>

            </div>
        </div>

        <script src="../dist/js/main.min.js"></script>

        <script>

            const backupForm = document.getElementById('backupForm');
            const backupMessage = document.getElementById('backupMessage');

            backupForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                backupMessage.textContent = 'Creating backup...';

                const formData = new FormData(backupForm);

                const response = await fetch('queries/create_local_backup', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                if (result.success) {
                    backupMessage.innerHTML = `<div class="alert alert-success">
                                                                    ${result.message}
                                                                  </div>`;
                } else {
                    backupMessage.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
                }
            });

        </script>
    </body>

    </html>

<?php } ?>