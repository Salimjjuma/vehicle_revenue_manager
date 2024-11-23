<?php

    include __DIR__."/index.php";

    $date = htmlentities($_GET['date']);
    $vehicle_id = htmlentities($_GET['vehicle_id']);

    echo json_encode(to_get_the_revenue_of_a_vehicle($date, $vehicle_id));