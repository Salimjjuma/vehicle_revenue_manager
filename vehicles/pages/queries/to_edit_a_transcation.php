<?php

    require __DIR__."/index.php";

    $debit_or_credit_flag = htmlentities($_POST['debit_or_credit_flag']);
    $id_of_revenue_xpense = htmlentities($_POST['id_of_revenue_xpense']);
    $charts_of_account = htmlentities($_POST['charts_of_account']);
    $new_amount = htmlentities($_POST['new_amount']);


    echo json_encode(to_edit_a_transcation($debit_or_credit_flag, $id_of_revenue_xpense, $charts_of_account, $new_amount));