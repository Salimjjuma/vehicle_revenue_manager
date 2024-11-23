<?php

require_once __DIR__ . "/_partials/check_session.php";

require_once __DIR__ . "/fxn/config.php";
require_once __DIR__ . "/fxn/authenticate.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $password = htmlentities($_POST['password']);
    $email_address = htmlentities($_POST['email']);

    $data = authenticateDuringLogon($email_address, $password);

}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<title id="title"></title>

<link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

<link href="/dist/css/main.min.css" rel="stylesheet">
<link href="src/css/signin.css" rel="stylesheet">
</head>

<body>

    <main class="form-signin">

        <?php if (isset($data['error_message'])) { ?>
            <div class="alert alert-danger" role="alert">
                <p><strong>
                        <?php echo $data['error_message']; ?>
                    </strong></p>
                <hr />
                <p class="mb-0">
                    <?php echo $data["error_desc"] ?>
                </p>
            </div>
        <?php } ?>

        <form method="POST" id="user_login">
            <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

            <div class="form-floating">
                <input type="email" class="form-control" name="email" id="floatingInput" placeholder="Email Address">
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="floatingPassword"
                    placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>



            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        </form>
    </main>

</body>

<script src="dist/js/main.min.js"></script>
<script>
    const title = $("#title").html("Vanga Vehicle || Sign In.");

    const user_login = $("#user_login").validate({
        rules: {
            email: {
                email: true,
                required: true
            },
            password: {
                required: true
            }
        },

        errorClass: "text text-danger",
    });
</script>

</html>