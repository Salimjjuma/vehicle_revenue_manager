<?php

    include __DIR__."/index.php";

    $chart_of_acc_id = htmlentities($_POST['charts_of_account']);
    $amount = htmlentities($_POST['amount']);
    $vehicle_id = htmlentities($_POST['vehicle_id']);
    $check_type = htmlentities($_POST['transaction_type']);
    $date = htmlentities($_POST['date']);
    
    echo json_encode(to_add_a_transcation($chart_of_acc_id, $amount, $vehicle_id, $check_type, $date));
