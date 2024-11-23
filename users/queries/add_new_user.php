<?php

    include __DIR__."/index.php";

    $username = htmlentities($_POST['username']);
    $first_name = htmlentities($_POST['first_name']);
    $second_name = htmlentities($_POST['second_name']);
    $last_name = htmlentities($_POST['last_name']);
    $email_address = htmlentities($_POST['email_address']);
    $password = htmlentities($_POST['password']);

    echo json_encode(add_user_to_database($username, $first_name, $second_name, $last_name, $email_address, $password));