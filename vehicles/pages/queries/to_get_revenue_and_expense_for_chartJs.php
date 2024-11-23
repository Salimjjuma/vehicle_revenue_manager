<?php

    include __DIR__."/index.php";

    $vehicle_id = htmlentities($_GET['vehicle_id']);
    echo json_encode(to_get_revenue_and_expense_for_chartsJs($vehicle_id));