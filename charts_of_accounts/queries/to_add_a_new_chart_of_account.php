<?php

    include __DIR__. "/index.php";

    $acc_name = htmlentities($_POST['acc_name']);
    $acc_description = htmlentities($_POST['acc_description']);
    $acc_type = htmlentities($_POST['acc_type']);

    echo json_encode(to_add_a_new_chart_of_account($acc_name, $acc_description, $acc_type));