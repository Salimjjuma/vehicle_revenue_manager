<?php

    include __DIR__."/index.php";

    $acc_name = htmlentities($_POST['acc_name']);
    $acc_description = htmlentities($_POST['acc_description']);

    echo json_encode(to_add_an_account_to_the_db($acc_name, $acc_description));