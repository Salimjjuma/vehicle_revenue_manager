<?php

require_once __DIR__ . "/index.php";

    $first_name = htmlentities($_POST['first_name']);
    $second_name = htmlentities($_POST['second_name']);
    $last_name = htmlentities($_POST['last_name']);
    $phone_number = htmlentities($_POST['phone_number']);

    echo json_encode(to_add_a_new_owner($first_name, $second_name, $last_name, $phone_number));